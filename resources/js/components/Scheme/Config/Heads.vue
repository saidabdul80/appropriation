<template>
          <div class="">
   <!--          <div class="d-flex w-100 mb-3" style="justify-content: space-between;">
                <button type="button" @click="addAppropriation()" class="btn btn-primary d-flex align-items-center text-white btn-sm pull-left">
                <span class="pi pi-arrow-left me-1"></span>List</button>   

                <button type="button" @click="addAppropriation()" class="btn btn-primary text-white btn-sm pull-right">
                    <span class="pi pi-plus"></span>
                    New Head</button>           
            </div> -->
            <div class="mb-2">
              <label for="name" class="form-label">Programmes</label>
              <Dropdown  v-model="selected_scheme" :options="schemes" optionLabel="name" placeholder="Select a Department" class="w-100 mb-3" />            
            </div>
            <div class="mb-2">
                <div>
                    <label for="name" class="form-label">Departments</label>
                </div>
              <span  v-for="(department,i) in departments" @click="$refs['dept'+i][0].click()"  class=" pointer fs-9 bg-light text-dark rounded mx-2 mb-1 d-inline-block">
                <input class="me-1" type="checkbox" :value="department.id" :ref="'dept'+i" v-model="selected_appropriation.department_id">{{department.short_name}}
              </span>
            </div>
            <div class="mb-2">
              <label for="name" class="form-label">Head Name</label>
              <Dropdown  v-model="selected_appropriation.appropriation_type_id" :options="appropriation_types" optionLabel="name" optionValue="id" placeholder="Select a Department" class="w-100 mb-3 " />            
            </div>
            <div class="mb-3">
              <label for="percentage_dividend" class="form-label">Percentage Dividend</label>
              <div class="input-group mt-1" id="dividendContainer">
                <span class="input-group-text">%</span>
                <input v-model="selected_appropriation.percentage_dividend" id="percentage_dividend" type="number" step="any" class="form-control" aria-label="Amount (to the nearest dollar)">
              </div>
            </div>            
            <button v-if="selected_appropriation.id == null" type="button" @click="addAppropriation()" class="btn btn-secondary">Add</button>
            <button v-else type="button" @click="addAppropriation()" class="btn btn-primary text-white">Update</button>           
          </div>
  </template>
  
  <script>
  import Dropdown from 'primevue/dropdown';
  export default {
    components:{
        Dropdown,
    },
    props: {
    },
    data(){
      return {
        departments:[],
        selected_appropriation:{department_id:[],appropriation_type_id:''},
        appropriation_types:[],
        schemes:[],
        selected_scheme:null
      }
    },
    async created(){   
        this.selected_scheme = this.$parent.$parent.$parent.selected_scheme
        this.schemes =  this.$parent.$parent.$parent.schemes;
      await this.getDepartments()
      await this.getAppType()
      this.$emit('oncompleted',true)
    },
    methods: {
        
        async getDepartments() {
            let res = await getData('department')
            this.departments = res.data
        },
      async getAppType(){
        let res = await getData('get_appropriation_types');        
        this.appropriation_types = res?.data
      },
      async addAppropriation() {
        
            try {                   
                if (!this.selected_scheme?.id) {
                throw new Error("Please select Programme"); //i.e scheme
                }

                if (!this.selected_appropriation.appropriation_type_id) {
                throw new Error("Head Name field is required");
                }

                if (this.selected_appropriation.department_id.length < 1) {
                throw new Error("Department field is required");
                }

                const schemeIndex = this.$globals.getIndexOf(this.$parent.$parent.$parent.schemes, this.selected_scheme);
                const scheme = this.$parent.$parent.$parent.schemes[schemeIndex];

                scheme.appropriations.push(this.selected_appropriation);

             /*    $("#totalDividend").removeClass("bg-danger text-white");
                $(".errorMsg").remove(); */

                if (this.selected_appropriation.id !== "") {
                    scheme.appropriations.pop();
                }

                this.selected_appropriation.scheme_id = this.selected_scheme.id;

                const res = await postData("/add_appropriation", this.selected_appropriation, true);

                if (res.status === 200) {
                    const responseData = res.data;

                    if (this.selected_appropriation.id === "") {
                        scheme.appropriations.pop();
                        scheme.appropriations.push(responseData.appropriation);
                        showAlert(responseData.msg);
                    } else {
                        showAlert(responseData);
                    }
                    this.showModal = false;
                }
            } catch (error) {
                Swal.fire(error.message);
            }
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
  