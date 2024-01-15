<template>
    <div class="modal show" id="sharehoder-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Add Appropriation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" @click="$emit('closeModal')" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div>
              <span v-for="department in departments" class="d-flex fs-9 p-2 bg-light text-dark rounded m-2 d-inline-block">
                <input class="me-1" type="checkbox" :value="department.id" v-model="selected_appropriation.department_id">{{department.short_name}}
              </span>
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Appropriation Name</label>
              <select v-model="selected_appropriation.appropriation_type_id" type="text" class="form-control" id="name">
                <option v-for="appropriation_type in appropriation_types" :value="appropriation_type.id">{{appropriation_type.name}}</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="percentage_dividend" class="form-label">Percentage Dividend</label>
              <div class="input-group mt-1" id="dividendContainer">
                <span class="input-group-text">%</span>
                <input v-model="selected_appropriation.percentage_dividend" id="percentage_dividend" type="number" step="any" class="form-control" aria-label="Amount (to the nearest dollar)">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button v-if="selected_appropriation.id === ''" type="button" @click="addAppropriation()" class="btn btn-secondary">Add</button>
            <button v-else type="button" @click="addAppropriation()" class="btn btn-primary text-white">Update</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      departments: {
        type: Array,
        default: () => [],
      },
      appropriation_types: {
        type: Object,
        default: () => [],
      },
      selected_appropriation: {
        type: Object,
        default: () => ({ id: '', department_id: '', appropriation_type_id: '', percentage_dividend: '' }),
      },
    },
    created(){        
    },
    methods: {
      addAppropriation() {
        this.$emit('resolveAppropriation', this.selected_appropriation);
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
  