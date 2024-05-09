<template>
    <div class="flex-1 small">
      <!-- Selected Item Field  -->


      <!-- Select Item Field -->
      <Dropdown class="w-100" v-model="itemSelect" :options="options" :filter="true" :optionValue="optionValue" :optionLabel="optionLabel" placeholder="Select an item" @change="onItemChange">
        <template #footer>
          <Button icon="pi pi-plus" label="Add New Subhead" class="w-100 p-button-secondary p-button-rounded" @click="addItem" />
        </template>
      </Dropdown>

      <!-- Item Description  -->
      <div class="w-100 pt-1 small text-light" v-if="optionDescription">
        <Textarea
          v-model="itemSelect.description"
          :autoResize="true"
          placeholder="description"
          :invalid="invalidDescription"
        />
        <div v-if="invalidDescription">
          <span class="text-danger">
            Description maxlength
          </span>
        </div>
      </div>

      <Dialog v-model="showDialog" header="Add New Item">
        <div class="p-fluid">
          <div class="p-field">
            <label for="itemName">Item Name</label>
            <InputText v-model="newItemName" id="itemName" />
          </div>
        </div>
        <template #footer>
          <Button label="Cancel" class="p-button-text" @click="showDialog = false" />
          <Button label="Add" class="p-button-primary" @click="addItem" />
        </template>
      </Dialog>
    </div>
  </template>

  <script>
  import { ref, computed } from 'vue'
  import Dropdown from 'primevue/dropdown'
  import Textarea from 'primevue/textarea'
  import Button from 'primevue/button'
  import Dialog from 'primevue/dialog'
  import InputText from 'primevue/inputtext'

  export default {
    components: {
      Dropdown,
      Textarea,
      Button,
      Dialog,
      InputText
    },
    props: {
      optionValue:{
        default:null,
      },
      optionDescription:{
        default:null,
      },
      optionLabel:{
        default:null,
      },
      modelValue:{
        default:null
      },
      contentLoading: {
        type: Boolean,
        default: false,
      },
      item: {
        required: true,
      },
      index: {
        type: Number,
        default: 0,
      },
      invalid: {
        type: Boolean,
        required: false,
        default: false,
      },
      url:{
        default:''
      },
    },
    emits: ['search', 'select'],
    data() {
      return {
        itemSelect: this.modelValue,
        itemData: this.item,
        options: [],
        description: this.item?.description,
        showDialog: false,
        newItemName: '',
        newItemTriggered:false
      }
    },
    watch:{
        item:function(n,o) {
            //alert(n)
        }
    },
    methods: {
      async fetchItems(search) {
        let res = await getData(this.url+'?search='+search)
        this.options = res.data
        },
      async onItemChange() {
        this.$emit('update:modelValue', this.itemSelect)
        this.$emit('change', this.itemSelect)
      },
      async addItem() {
        Swal.fire({
            title: "Name",
            input: "text",
            inputAttributes: {
                autocapitalize: "off"
            },
            html:`<div>
                    <label for="nameCTX" class='text-left'>Name</label>
                    <input id="nameCTX" type="text" class="form-control mb-3">
                    <label for="outputCTX" class='text-left'>Output</label>
                    <input id="outputCTX" type="text" class="form-control mb-3">
                    <label for="outcomeCTX" class='text-left'>Outcome</label>
                    <input id="outcomeCTX" type="text" class="form-control mb-3">
                </div>`,
            showCancelButton: true,
            confirmButtonText: "Add",
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                try {
                    const name = document.getElementById('nameCTX').ariaValueMax;
                    const output = document.getElementById('outputCTX').ariaValueMax;
                    const outcome = document.getElementById('outcomeCTX').ariaValueMax;
                    if(name == ''){
                        Swal.showValidationMessage(`Name Field is Required `);
                        return false;
                    }

                    if(output == ''){
                        Swal.showValidationMessage(`Output is Required `);
                        return false;
                    }

                    if(outcome == ''){
                        Swal.showValidationMessage(`Outcome Field is Required `);
                        return false;
                    }

                    const response = await postData(this.url+'/create',{name, output, outcome})
                    this.fetchItems('')
            } catch (error) {
                    if(error =='undefined'){
                        return;
                    }
                        Swal.showValidationMessage(`
                            Request failed: ${error}
                        `);
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
            });

        /* this.showDialog = false */
      },
      deselectItem(index) {
        this.store.deselectItem(index)
      },
    },
    created() {
      this.fetchItems('')
    },
  }
  </script>
