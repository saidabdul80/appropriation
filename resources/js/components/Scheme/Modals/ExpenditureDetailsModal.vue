<template>
    <div class="modal show" id="expenditure-details-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog w-75 modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" id="project1235">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
              <b>{{ selected_fund_category }} {{ selected_scheme?.name }} Expenditure Details</b>
            </h5>
            <button @click="$emit('closeModal')" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered">
              <thead class="bg-light">
                <tr>
                  <th>S/N</th>
                  <th>Appropriation</th>
                  <th>Amount (<span>&#8358;</span>)</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(appr, i) in expenditure_details" :key="i">
                  <td>{{ i + 1 }}</td>
                  <td>{{ appr.name }}</td>
                  <td>{{ $globals.currency(appr.expenditure_total_amount) }}</td>
                </tr>
              </tbody>
            </table>
            <p>
              <b>Total</b>: <span>&#8358;</span>
              <span v-if="getCategoryIncomeBalance == 0">0.00</span>
              <span v-else>{{ $globals.currency(expenditure_amount) }}</span>
            </p>
          </div>
          <div class="modal-footer">
            <button @click="printE('project1235')" class="btn btn-sm btn-light pull-right">Print</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      selected_fund_category: {
        type: String,
        default: '',
      },
      selected_scheme: {
        type: Object,
        default: () => ({ name: '' }),
      },
      category_income: {
        type: Number,
        default: 0,
      },
      category_income_balance: {
        type: Number,
        default: 0,
      },
    },
    data() {
      return {
        expenditure_details: [],
        expenditure_amount: 0,
      };
    },
    created() {
      this.expenditureDetails();
    },
    methods: {
      printE(elementId) {
        // Implement your logic for printing
      },
      async expenditureDetails() {
        try {
          let res = await postData('/fetch_expenditures', {
            scheme_id: this.selected_scheme.id,
            fund_category: this.selected_fund_category,
          }, true);
          if (res?.status === 200) {
            this.expenditure_details = res.data;
            this.calculateTotalExpenditureAmount();
          } else {
            showAlert('Something went wrong');
          }
        } catch (error) {
          console.error('Error fetching expenditure details:', error);
          showAlert('Error fetching expenditure details');
        }
      },
      calculateTotalExpenditureAmount() {
        this.expenditure_amount = this.expenditure_details.reduce((total, appr) => total + appr.expenditure_total_amount, 0);
      },
    },
  };
  </script>
  
  <style scoped>
  .modal {
    display: block !important;
    background: #000a;
  }
  </style>
  