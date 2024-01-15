<template>
      <div class="modal " style="display: block !important;" id="projection-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Projection</h5>
            <button @click="$emit('closeProjection')" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="project1234">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Department</th>
                  <th>Percentage Dividend</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(appr, i) in appropriations_projection?.appropriations" :key="i">
                  <td>{{ appr.name }}</td>
                  <td>{{ appr.department }}</td>
                  <td>{{ appr.percentage_dividend }}</td>                  
                  <td>{{$globals.currency(appr.amount)}}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button @click="printE('project1234')" class="btn btn-sm btn-light pull-right">Print</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      selected_scheme:{
        default:{}
      },
      appropriations: {
        type: Object,
        default: () => ({}),
      },
    },
    computed:{
     
    },
    data(){
        return {
            appropriations_projection:{}
        }
    },
    watch:{
        appropriations_projection(n,o){                        
            this.getCategoryIncomeBalance();
        }
    },
    async created(){
        let res = await postData('/get_appropriations_projection', {
                    scheme_id: this.selected_scheme.id
                }, true);
                console.log(res,9999)
                if (res?.status == 200) {
                    this.appropriations_projection = res.data
                } else {
                    Swal.fire('There is no projection for this programme');
                    this.$emit('closeProjection');
                    return false;
                }
    },
    methods: {
        
      currencyWithBalance(amount, balance) {
        return `${this.$global.currency(amount)} ${balance}`;
      },
      printE(id) {
        // Implement your print logic
        console.log(`Printing element with ID: ${id}`);
      }, 
      getCategoryIncomeBalance() {        
        const category_income_balance = this.appropriations.reduce((total, appropriation) => {
            return total + appropriation?.wallet.balance
        }, 0)
        this.$emit('category_income_balance', category_income_balance);
      },
    },
  };
  </script>
  
  <!-- Add your component-specific styles here if needed -->
  <style scoped>
  .modal{
    display: block;
  }
  </style>
  