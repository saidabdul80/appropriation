<template>
    <div class="w-100 position-relative">        
        <select @input="monthYearTriggered($event)" class="form-control border-0" placeholder="Select funding year">
        <option value="">Select Funding Year </option>
        <option v-for="month_year in fund_categories" :value="month_year">{{ month_year }}</option>
        </select>
        <i class="fa fa-caret-down text-secondary position-absolute" style="right: 10px; top: 30%;"></i>
    </div>
</template>
    
    <script>  
    export default {
      props: {
        selected_scheme_id:{
            type:Number
        },
        fund_categories: {
          type: Array,
          default: () => [],
        },
      },    
      data(){
          return {
            selected_fund_category:null
          };
      },
      methods:{
        async monthYearTriggered(e) { //1 means from the right source
            this.selected_fund_category  = e.target.value;
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
            },
      },  
    };
    </script>
    