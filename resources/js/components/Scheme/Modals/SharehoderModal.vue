<!-- Mounted on IndexScreen -->
<template>
    <div class="modal show" id="sharehoder-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Add Appropriation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" @click="$emit('closeModal')" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="name" class="form-label">Departments</label>
              <MultiSelect :options="departments"  display="chip" optionValue="id"  v-model="selected_appropriation2.department_id" optionLabel="short_name" placeholder="Departments"
                  :maxSelectedLabels="3" class="w-100" />
<!--
              <span v-for="department in departments" class="d-flex fs-9 p-2 bg-light text-dark rounded m-2 d-inline-block">
                <input class="me-1" type="checkbox" :value="department.id">{{department.short_name}}
              </span> -->
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Head Name</label>
              <Dropdown  v-model="selected_appropriation2.appropriation_type_id" :options="appropriation_types" optionLabel="name" optionValue="id" placeholder="Select Head" class="w-100 mb-3" />
              <!-- <select v-model="" type="text" class="form-control" id="name">
                <option v-for="appropriation_type in " :value="appropriation_type.id">{{appropriation_type.name}}</option>
              </select> -->
            </div>
            <div class="mb-3">
              <label for="percentage_dividend" class="form-label">Percentage Dividend</label>
              <div class="input-group mt-1" id="dividendContainer">
                <span class="input-group-text">%</span>
                <input v-model="selected_appropriation2.percentage_dividend" id="percentage_dividend" type="number" step="any" class="form-control" aria-label="Amount (to the nearest dollar)">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button v-if="selected_appropriation2.id === '' || selected_appropriation2.id == null " type="button" @click="addAppropriation()" class="btn btn-secondary">Add</button>
            <button v-else type="button" @click="addAppropriation()" class="btn btn-primary text-white">Update</button>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
  import Dropdown from 'primevue/dropdown';
  import MultiSelect from 'primevue/multiselect';

  export default {
    components:{
      Dropdown,
      MultiSelect
    },
    props: {
      new_app_request:{
        default:false,
      },
      departments: {
        type: Array,
        default: () => [],
      },
      selected_appropriation: {
        type: Object,
        default: () => ({ id: '', department_id: [], appropriation_type_id: '', percentage_dividend: '' }),
      },
    },
    data(){
      return {
        appropriation_types:[],
        selected_appropriation2:{ id: '', department_id: [], appropriation_type_id: '', percentage_dividend: '' },
      }
    },
    created(){
        if(!this.new_app_request){
            this.selected_appropriation2 = {...this.selected_appropriation};
        }
      this.getAppType()
    },
    methods: {
      async getAppType(){
        let res = await getData('get_appropriation_types');
        this.appropriation_types = res?.data
      },
      addAppropriation() {
        this.$emit('addapp', this.selected_appropriation2);
        /* out to IndexScreen */
      },
    },
  };
  </script>

  <!-- Add your component-specific styles here if needed -->
  <style scoped>
    .modal{
        display: block !important;
        background: #000a;
    }
  </style>
