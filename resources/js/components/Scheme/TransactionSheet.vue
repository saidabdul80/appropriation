<template>
  <div class="p-3 rounded-lg-only shadow-lg-only bg-white" style="height: inherit;overflow-x: auto;">
    <v-popup group="templating">
      <template #container="{ message, acceptCallback, rejectCallback }">

        <table class="table w-100 h-100"> 
          <thead>
            <tr>              
              <th>Subhead</th>
              <th>Amount (₦)</th>
              <th>Balance (₦)</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(cat, index) in sub_head_budgets" :key="index" style="font-size: 0.9em;">
              <td>{{ cat.subhead }}</td>
              <td>{{$globals.currency(cat.amount) }}</td>
              <td>{{ $globals.currency(cat.balance) }}</td>
            </tr>
          </tbody>
        </table>
      </template>
    </v-popup>
    <div>
      <button @click="$emit('switch-page', 1)" class="btn fs-9 btn-primary text-white rounded" style=""><span class="pi pi-arrow-left"></span></button>
      <h5 class="text-center text-secondary"><b>{{ selected_appropriation.name }} / {{ selected_appropriation.department }}</b>
        Transactions</h5>
      <!-- <div class="btn-group" role="group" aria-label="Basic example2">
      </div> -->
      <Divider/>
      <div class="row">
        <div class="col-md-3" >              
            <p class="inline-block mb-2 me-4"><b>Balance:</b><span>&#8358;</span> {{$globals.currency(selected_appropriation_balance) }}</p>
        </div>
        <div class="col-md-3" >
          <p class="inline-block mb-0"><b>Total Expenditure: <span>&#8358;</span></b> {{$globals.currency(total_expenditure_appropriation) }}</p>
        </div>
        <div class="col-md-3" ><a class="mb-2" style="cursor: pointer;" @click="subheadDetails($event)">More details</a></div>
      </div>
      <Divider />
    </div>
    <div style="overflow: auto;height: 45vh;">
      <table class="fs-8 table-bordered transactions-tables table table-sm table-hover" id="myTable" style="min-width:1000px;">
        <thead>
          <tr>
            <th class="fs-8 fw-bold" style="white-space: nowrap;">S/N</th>
            <th v-for="header in Object.keys($globals.dynamic_data)" class="fs-8 fw-bold" style="white-space: nowrap;">
              {{ header.replaceAll('_', ' ') }}
            </th>
            <th class="fs-8 fw-bold" style="white-space: nowrap;">Total Amount</th>
            <th>#</th>
          </tr>
        </thead>
        <tbody>
          <!-- {{ resetTotalExpenditureForAppropriation() }} -->
          <tr v-for="(appr, i) in transactions?.data">
            <td>{{ parseInt(i + 1) }} </td>
            <td v-for="key in Object.keys($globals.dynamic_data)" class="" style="white-space: nowrap;">
              <span v-if="key.includes('₦')">
                {{ $globals.currency(appr.data && appr.data[key] ? appr.data[key] : 0) }}
              </span>
              <span v-else>
                <span v-if="appr.data[key] && (appr.data[key].type || 'text') === 'number'">
                  {{ $globals.currency(appr.data[key].value || 0) }}
                </span>
                <span v-else>
                  {{ appr.data[key] ? appr.data[key].value : '' }}
                </span>
              </span>
            </td>

            <td><i style="visibility: hidden;"> {{ i == 0 ? (total_expenditure_appropriation = 0) : '' }}</i> {{
        $globals.currency(appr.amount) }} </td>
            <td class="p-0">
              <button @click="editAppropriationTransaction(appr, i)"
                class="btn btn-info d-inline-block text-white btn-sm me-1"><i class="fs-9 bi bi-pencil"></i></button>
              <button @click="deleteAppropriationTransaction(appr, i)" class="btn btn-danger btn-sm d-inline-block"><i
                  class="fs-9 bi bi-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { useConfirm } from "primevue/useconfirm";
import ConfirmPopup from 'primevue/confirmpopup';
import Divider from 'primevue/divider';

export default {
  components: {
    ConfirmPopup,
    Divider
  },
  props: {
    agency_name: {
      default: ''
    },
    fund_category: {
      default: ''
    },
    switchPageFunc: {
      type: Function,
      required: true,
    },
    selected_appropriation: {
      type: Object,
      default: () => ({ name: '', department: '' }),
    },
    transactions_table_toggle: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      selected_appropriation_balance: 0,
      transactions: { data: [] },
      confirm: useConfirm(),
      sub_head_budgets:[]
    }
  },
  computed: {
    total_expenditure_appropriation() {
      return this.transactions.data.reduce((accumulator, currentTransaction) => accumulator + currentTransaction.amount, 0)
    },
    maxColumns() {
      const max = this.transactions?.data.reduce((acc, appr) => {
        return Math.max(acc, Object.keys(appr.data).length);
      }, 0);
      return max + 3; // Adjust for static columns (index, amount, action buttons)
    }
  },
  async created() {
    await this.fetchWalletBalance()
    await this.fetchTransactions()

  },
  methods: {
    subheadDetails(event) {
      this.confirm.require({
        target: event.currentTarget,
        group: 'templating',
        message: 'Are you sure you want to proceed?',
        icon: 'pi pi-exclamation-triangle',
      });
    },
    editAppropriationTransaction(appr, i) {
      this.$emit('openDebitModal', {
        transaction: { ...appr },
        index: i
      })
      //this.markSelectedDynamicField()
    },
    async fetchTransactions() {
      let res = await postData('/get_transactions', {
        owner_id: this.selected_appropriation.id,
        owner_type: 'appropriation',
        fund_category: this.fund_category,
      }, true);

      if (res.status == 200) {
        this.transactions = res.data
        setTimeout(() => {
          this.iniTableTransaction()
        }, 100)
      } else {
        showAlert('Something went wrong');
      }

      let res2 = await getData(`sub_head_budget/appropriation/${this.selected_appropriation.id}/${this.fund_category}`);
      this.sub_head_budgets = res2?.data || [];
    },
    async fetchWalletBalance() {
      let res = await postData('/get_wallet', {
        fund_category: this.fund_category,
        owner_id: this.selected_appropriation.id,
        owner_type: 'appropriation'
      }, true)
      if (res.status == 200) {
        this.selected_appropriation_balance = res.data.balance
      }
    },
    resetTotalExpenditureForAppropriation() {
      // Implement your logic for resetting total expenditure
    },
    async deleteAppropriationTransaction(appr, i) {
      const confirmText = appr.data.Subject.value + ' of ' + appr.amount;
      const { value: confirmationText } = await Swal.fire({
        title: 'Continue Delete?',
        html: 'Please type <strong class="fs-9"><i>' + confirmText + '</i></strong> to confirm:',
        input: 'text',
        inputPlaceholder: 'Type the name to confirm',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Continue',
        cancelButtonText: 'Cancel',
        preConfirm: (text) => {
          if (text !== confirmText) {
            Swal.showValidationMessage('Confirmation text does not match.');
          }
        }
      });

      if (!confirmationText) {
        return false;
      }

      await postData('/delete_appropriation_transaction', {
        transaction_id: appr.id,
        owner_id: appr.owner_id,
        fund_category: appr.fund_category
      });
    },
    getTotalExpenditureForAppropriation(amount) {
      // Implement your logic for getting total expenditure for appropriation
    },
    iniTableTransaction(destroy = true) {
      let $this = this

      var table = $('.transactions-tables').DataTable({
        dom: 'Bfrtip',
        colReorder: true,
        initComplete: function () {
          table?.colReorder?.move(1, 2);

        },
        destroy: true,
        buttons: [{
          extend: 'print',
          exportOptions: {
            stripHtml: false
          },
          title: this.agency_name,
          customize: (win) => {
            $(win.document.body)
              .css({
                'font-size': '10pt'
              })
              .prepend(
                '<style>table{width:135%}</style><svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>'
              );

            $(win.document.body).find('.table').before(`<h1 style="text-align:center;margin:10px 0px;">${this.selected_scheme?.name} - ${this.selected_appropriation?.name}/${this.selected_appropriation?.department}</h1>`)
            $(win.document.body).find('.table').before(`<p><b>Balance:</b> ${this.$globals.currency($this.selected_appropriation_balance)}</p>`)
          }
        }],
        exportOptions: {
          stripHtml: false
        },
        pageLength: 10,
      });
    },
    switchPageFunc(page) {
      // Implement your logic for switching pages
      this.$emit('switch-page', page);
    },

  },
  mounted() {
    this.$nextTick(() => {

    })

    document.addEventListener('DOMContentLoaded', function() {
      const table = document.getElementById('myTable');
      let draggingCol = null;

      table.querySelectorAll('th').forEach(th => {
          th.draggable = true;

          th.addEventListener('dragstart', function(e) {
              draggingCol = this;
              setTimeout(() => this.classList.add('dragging'), 0);
          });

          th.addEventListener('dragover', function(e) {
              e.preventDefault();
          });

          th.addEventListener('dragenter', function(e) {
              e.preventDefault();
              if (this !== draggingCol) {
                  this.style.borderLeft = '3px solid blue';
              }
          });

          th.addEventListener('dragleave', function(e) {
              this.style.borderLeft = '';
          });

          th.addEventListener('drop', function(e) {
              if (this !== draggingCol) {
                  swapColumns(table, draggingCol.cellIndex, this.cellIndex);
                  this.style.borderLeft = '';
              }
          });

          th.addEventListener('dragend', function() {
              this.classList.remove('dragging');
          });
      });

      function swapColumns(table, fromIndex, toIndex) {
          const rows = Array.from(table.querySelectorAll('tr'));
          rows.forEach(row => {
              const cells = Array.from(row.querySelectorAll('th, td'));
              row.insertBefore(cells[fromIndex], cells[toIndex > fromIndex ? toIndex + 1 : toIndex]);
          });
      }
  });



  }
};
</script>

<!-- Add your component-specific styles here if needed -->
<style scoped>
button.btn.btn-secondary.buttons-print {
  margin-left: 18px !important;
}
</style>

<style>
  .dt-search{
    max-width: 300px;
    margin-bottom: 10px; 
    margin-right: auto;
    margin-left: auto;
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
  }

  .dt-search label{
    color: #bbb;
  }
  
  table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}
.dragging {
    opacity: 0.5;
}

</style>
