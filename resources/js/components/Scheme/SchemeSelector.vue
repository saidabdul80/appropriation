<template>
  <div class="row mx-auto">
    <div class="col-8  ps-0 ">
      <div class="mb-2 w-100 d-flex bg-white d-inline-block rounded-sm p-0 input-group position-relative">
        <select @input="onSchemeChange" v-model="selectedScheme" class="mx-1 form-control border-0" >
          <option :value="defaultScheme">Select a Scheme</option>
          <option v-for="(scheme, index) in schemes" :key="index" :value="scheme">{{ scheme?.name }} </option>
        </select>      
        <button @click="$emit('openModal')" :class="{ 'btn-success': selectedScheme.id, 'btn-secondary disabled': !selectedScheme.id }" class="px-1 update-scheme w-25 btn fs-9 ms-1">
          <i class="fa fa-wrench"></i>          
          <span class="nav-name2">Manage</span>
        </button>
        <i class="fa fa-caret-down text-secondary position-absolute" style="right: 26%; top: 33%;"></i>
      </div>
    </div>
    <div class="col-4 " style="/* position: fixed; top: 5px; z-index: 9; padding-left: 20px */">
      <div class="mb-2  bg-white d-inline-block rounded-sm p-0">
        <month-year-selector :selected_scheme_id="selectedScheme.id" @month-selected="monthSelected" :fund_categories="fund_categories"/>
       </div>
    </div>

    </div>
  </template>
  
  <script>  
  import MonthYearSelector from './MonthYearSelector.vue';
  export default {
    components:{
      MonthYearSelector
    },
    props: {
      fund_categories:{
        type: Array,
        default: () => [],
      },
      schemes: {
        type: Array,
        default: () => [],
      },
    },    
    data(){
        return {
            defaultScheme:{ index: '', id: '', name: '', appropriations: [] },
            selectedScheme: { index: '', id: '', name: '', appropriations: [] }
        };
    },
    methods:{
        monthSelected(data){
          this.$emit('month-selected',data)
        },
        onSchemeChange(){            
            setTimeout(()=>{
                console.log(this.selectedScheme);
                this.$emit('scheme-selected', this.selectedScheme);
            },500)
        },
        onUpdateScheme(){
            if (this.selectedScheme.id) {
             this.$emit('update-scheme', this.selectedScheme);
            }
        },
        buttonText(){
            return this.selectedScheme.id ? 'Update Programme' : 'Select a Scheme first';
        }
    },  
  };
  </script>
  