<template>
    <div class="flex-1 small">
      <Dropdown v-if="!show" class="w-100" v-model="itemSelect" :options="options" :filter="true" :optionValue="optionValue" :optionLabel="optionLabel" placeholder="Select an item" @change="onItemChange">
        <template #footer>
          <Button icon="pi pi-plus" label="Add New Item" class="w-100 p-button-secondary p-button-rounded" @click="showDialog = true" />
        </template>
      </Dropdown>

      <!-- Add/Edit Dialog -->
      <Dialog v-model="showDialog" :visible="showDialog" :modal="true" :closable="false" width="400px">
        <template #header>
          <div class="d-flex justify-between align-center w-100">
            <h3>{{ editMode ? 'Edit Item' : 'Add New Item' }}</h3>
            <Button v-if="!editMode" icon="pi pi-pencil" class="rounded p-button-success p-0" @click="editMode = true" />
          </div>
        </template>
        <div v-if="editMode">
          <label for="editItemSelect" class="p-d-block fw-bold w-100">Select Item to Edit</label>
          <Dropdown id="editItemSelect" class="w-100 mb-3" v-model="selectedEditItem" @change="handleChange" :options="options" :optionValue="optionValue" :optionLabel="optionLabel" placeholder="Select an item" />
        </div>
        <div>
          <label for="itemNameInput" class="p-d-block fw-bold w-100">Name</label>
          <InputText id="itemNameInput" v-model="newItemName" class="p-inputtext w-100 mb-3" />
        </div>
        <div v-if="url === 'subhead_item_name'">
          <label for="outputInput" class="p-d-block fw-bold w-100">Output</label>
          <InputText id="outputInput" v-model="newItemOutput" class="p-inputtext w-100 mb-3" />
          <label for="outcomeInput" class="p-d-block fw-bold">Outcome</label>
          <InputText id="outcomeInput" v-model="newItemOutcome" class="p-inputtext w-100 mb-3" />
        </div>
        <div class="p-d-flex p-jc-end">
            <Button v-if="show" label="Cancel" class="p-button-text" @click="$emit('onclose', true)" />
            <Button v-else label="Cancel" class="p-button-text" @click="showDialog = false" />
            <Button label="Save" class="p-button-primary" @click="saveItem" />
        </div>
      </Dialog>
    </div>
  </template>

  <script>
  import { ref } from 'vue'
  import Dropdown from 'primevue/dropdown'
  import Button from 'primevue/button'
  import Dialog from 'primevue/dialog'
  import InputText from 'primevue/inputtext'

  export default {
    components: {
      Dropdown,
      Button,
      Dialog,
      InputText
    },
    props: {
      show: {
        default: false,
      },
      optionValue: {
        default: null,
      },
      optionLabel: {
        default: null,
      },
      modelValue: {
        default: null
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
      url: {
        default: ''
      },
    },
    emits: ['search', 'select','onclose'],
    setup(props, { emit }) {
      const itemSelect = ref(props.modelValue)
      const options = ref([])
      const showDialog = ref(false)
      const newItemName = ref('')
      const newItemOutput = ref('')
      const newItemOutcome = ref('')
      const selectedEditItem = ref(null)
      const editMode = ref(false)

      if(props.show){
        showDialog.value = true
        }

      const fetchItems = async (search) => {
        let res = await getData(props.url + '?search=' + search)
        options.value = res.data
      }

      const onItemChange = () => {
        emit('update:modelValue', itemSelect.value)
        emit('change', itemSelect.value)
      }

      const handleChange = () => {
        newItemName.value = selectedEditItem.value ? selectedEditItem.value[props.optionLabel] : ''
        newItemOutput.value = selectedEditItem.value?.output || ''
        newItemOutcome.value = selectedEditItem.value?.outcome || ''
      }

      const saveItem = async () => {
        try {
          if (newItemName.value === '') {
            throw new Error('Name Field is Required')
          }

          if (props.url === 'subhead_item_name' && (newItemOutput.value === '' || newItemOutcome.value === '')) {
            throw new Error('Output and Outcome Fields are Required')
          }

          const payload = {
            name: newItemName.value,
            output: newItemOutput.value,
            outcome: newItemOutcome.value
          }
          if(editMode.value){
            payload.id = selectedEditItem.value?.id

            if(payload.id === '' || payload.id == null){
                alert('nothing to save')
                return;
            }
          }

          await postData(props.url + '/create', payload)
          fetchItems('')
          if(props.show){
            emit('onclose',true)
          }else{
              showDialog.value = false
          }
          Swal.fire('Success', 'Item added successfully', 'success')
        } catch (error) {
          Swal.fire('Error', error.message, 'error')
        }
      }

      return {
        itemSelect,
        options,
        showDialog,
        newItemName,
        newItemOutput,
        newItemOutcome,
        fetchItems,
        onItemChange,
        saveItem,
        editMode,
        handleChange,
        selectedEditItem
      }
    },
    created() {
      this.fetchItems('')
    },
  }
  </script>
