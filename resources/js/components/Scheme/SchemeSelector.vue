<template>
    <div class="mb-2 mt-2 bg-white" style="position: fixed; top: 5px; z-index: 9; padding-left: 20px">
        
      <select @input="onSchemeChange" v-model="selectedScheme" class="mx-1 form-control d-inline-block" style="width: 240px;">
        <option :value="defaultScheme">Select a Scheme</option>
        <option v-for="(scheme, index) in schemes" :key="index" :value="scheme">{{ scheme?.name }} </option>
      </select>      
      <button @click="$emit('openModal')" :class="{ 'btn-success': selectedScheme.id, 'btn-secondary disabled': !selectedScheme.id }" class="update-scheme btn fs-8 mx-1 d-inline-block">
        Manage
      </button>
    </div>
  </template>
  
  <script>  
  export default {
    props: {
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
  