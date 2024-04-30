<template>
    <div class="w-100 position-relative">   
        
        <Dropdown @change="monthYearTriggered()"  v-model="selected_fund_category" :options="Object.values(fund_categories)"  placeholder="Select Funding Year "
            class="w-100" style="min-width: 140px;" />
     <!--    <select  class="form-control border-0" placeholder="Select funding year">
        <option value="">Select Funding Year </option>
        <option v-for="month_year in fund_categories" :value="month_year">{{ month_year }}</option>
        </select> --> 
        <!-- <i class="fa fa-caret-down text-secondary position-absolute" style="right: 10px; top: 30%;"></i> -->
    </div>
</template>
<script>  
import Dropdown from 'primevue/dropdown';
    export default {
        components:{
            Dropdown
        },
      props: {
        selected_scheme_id:{
            type:Number,
            default: null
        },
        fund_categories: {
          type: Array,
          default: () => [],
        },
      },    
      watch:{
        'fund_categories':function (a, b){
            if(this.fund_categories?.length >0){
                this.selected_fund_category =  this.fund_categories[this.fund_categories.length-1];
                this.monthYearTriggered();
            }
        }
      },
      data(){
          return {
            selected_fund_category:''
          };
      },
      created(){
        
      },
      methods:{
        async monthYearTriggered() {
       
             //1 means from the right source
            /* this.selected_fund_category  = e.target.value; */
            if(this.selected_scheme_id){
                let res = await postData('/get_prepared_data', {
                    scheme_id: this.selected_scheme_id,
                    fund_category: this.selected_fund_category
                }, true);
                if (res?.status == 200) {                        
                    this.appropriations_history = {
                        data: []
                    }
                    this.$nextTick(() => {                        
                        this.$emit('month-selected',{
                            appropriations_history:res.data.appropriations_histories,
                            appropriations:res.data.appropriations,
                            selected_month: this.selected_fund_category
                        })                 
                    })
                } else {
                    showAlert('Something went wrong');
                    return false;
                }
            }else{
                this.$emit('update:modelValue',this.selected_fund_category)
                this.$emit('change',this.selected_fund_category)
            }
        },
      },  
    };
    </script>
    