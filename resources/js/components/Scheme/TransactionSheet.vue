<template>
  <div class="p-3 rounded-lg-only shadow-lg-only bg-white" style="height: inherit;overflow: hidden;">
 <!--    <v-popup group="templating">
      <template #container="{ message, acceptCallback, rejectCallback }">
        <div class="scrollable-container" style="height: 260px; overflow-y: auto;cursor: grab;user-select: none;"
          @mousedown="startDragging" @mouseup="stopDragging" @mouseleave="stopDragging" @mousemove="scrollOnDrag">
          <div class="scrollable-content" ref="scrollContent" style="width: 100%;">
            <table class="table w-100 h-100" style="position: relative;">
              <thead style="position: sticky; top: 0; z-index: 1;" class="bg-white">
                <tr>
                  <th>Subhead</th>
                  <th>Amount (₦)</th>
                  <th>Balance (₦)</th>
                </tr>
              </thead>
              <tbody style="margin-top: 20px;">
                <tr v-for="(cat, index) in sub_head_budgets" :class="cat.amount > cat.balance ? 'text-success' : ''"
                  :key="index" style="font-size: 0.9em;">
                  <td>{{ cat.subhead }}</td>
                  <td>{{ $globals.currency(cat.amount) }}</td>
                  <td>{{ $globals.currency(cat.balance) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </template>
    </v-popup> -->

    <div>
      <div class="row w-100">
        <div class="col-md-4 col-lg-3">
          <button @click="$emit('switch-page', 1)" class="btn fs-9 btn-primary text-white rounded" style=""><span
              class="pi pi-arrow-left"></span></button>
          <button @click="reloadTransaction()" class="btn fs-9 ms-3 btn-primary text-white rounded" style=""><span
              class="pi pi-refresh"></span></button>
          <button @click="exportToExcel()" class="btn fs-9 ms-3 btn-primary text-white rounded" style=""><span
              class="pi pi-file-export"></span></button>
        </div>
        <div class="col-md-5 col-lg-6 mb-3">

          <h5 class="text-center text-secondary"><b>{{ selected_appropriation.name }} / {{
            selected_appropriation.department }}</b>
            Transactions</h5>
        </div>
        <div class="col-lg-12 row">
          <div class="col-md-8 row">
            <div class="col-md-12">
                <InlineMessage severity="info" class="p-1 w-100">
                    <span style="font-size: 0.8em;" class="ms-1"> <b>Note:</b> Please Select filter by Date along with Date Category to generate report </span>
                </InlineMessage>
            </div>
            <div class="col-md-6 mb-3">
                <Calendar v-model="filters.date" selectionMode="range" placeholder="Filter by date" :manualInput="false"
                dateFormat="yy-mm-dd" class="w-100"
                :pt="{ root: { class: 'mt-2' }, input: { style: 'padding:4px !important;' } }">
                <template #footer="{ clickCallback }">
                </template>
                </Calendar>
            </div>

          <div class="col-md-6 mb-3">
            <Dropdown :pt="{ input: { style: 'padding:4px !important;' } }" v-model="filters.date_type"
              @change="filterTransaction()" :options="filters_date" optionLabel="name" optionValue="id"
              placeholder="Date Category" class="w-100 my-2" />
          </div>
          </div>
          <div class="col-md-4">
            <Dropdown :pt="{ input: { style: 'padding:4px !important;' } }" v-model="filters.group"
              @change="filterTransaction()" :options="filters_grouping" optionLabel="name" optionValue="id"
              placeholder="Group By" class="w-100 my-2" />

          </div>
        </div>
      </div>
      <!-- <div class="btn-group" role="group" aria-label="Basic example2">
        </div> -->
      <Divider />
      <div class="row">
        <div class="col-md-3">
          <p class="inline-block mb-2 me-4"><b>Income:</b><span>&#8358;</span>
            {{ $globals.currency(selected_appropriation_income, converCurrency) }}</p>
          <p class="inline-block mb-2 me-4"><b>Balance:</b><span>&#8358;</span>
            {{ $globals.currency(selected_appropriation_balance, converCurrency) }}</p>
        </div>
        <div class="col-md-3">
          <p class="inline-block mb-0"><b>Total Expenditure: <span>&#8358;</span></b>
            {{ $globals.currency(total_expenditure_appropriation, converCurrency) }}</p>
        </div>
        <div class="col-md-3">
            <PopUp label="More details" :items="transformData(sub_head_budgets)" />
        </div>
      </div>
      <!-- <Divider /> -->
    </div>
    <div style="overflow: scroll;height: 68%" :key="tableKey">
      <table v-if="filters.group !='vote_book'" class="fs-8 table-bordered transactions-tables table table-sm table-hover" id="myTable"
        style="min-width:1000px;">
        <thead>
          <!--   <tr :class="!converCurrency?'':'d-none'">
                <th colspan="6"></th>
                <th colspan="6"><b>{{ selected_appropriation.name }} / {{ selected_appropriation.department}}</b>Transactions</th>
            </tr> -->
          <tr>
            <th class="fs-8 fw-bold" style="white-space: nowrap;">S/N</th>
            <th class="fs-8 fw-bold" style="white-space: nowrap;">Head</th>
            <th class="fs-8 fw-bold" style="white-space: nowrap;">Subhead</th>
            <th class="fs-8 fw-bold" style="white-space: nowrap;">Activity</th>
            <th v-for="header in Object.keys(dynamicData)" :data-name="header"
              class="fs-8 fw-bold position-relative" style="white-space: nowrap;">
              <div class="thoverlay" @click="removeColumn(header)">
                <span class="pi pi-times "></span>
              </div>
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
            <td>{{ appr.head }}</td>
            <td>{{ appr.subhead }}</td>
            <td>{{ appr.subhead_item }}</td>
            <td v-for="key in Object.keys(dynamicData)" class="" style="white-space: nowrap;">
              <span v-if="key.includes('₦')">
                {{ $globals.currency(appr.data && appr.data[key] ? appr.data[key] : 0, converCurrency)
                }}
              </span>
              <span v-else>
                <span v-if="appr.data[key] && (appr.data[key].type || 'text') === 'number'">
                  <span v-if="key.includes('Account')">{{ appr.data[key].value }}</span>
                  <span v-else>
                    {{ $globals.currency(appr.data[key].value || 0, converCurrency) }}
                  </span>
                </span>
                <span v-else>
                  {{ appr.data[key] ? appr.data[key].value : '' }}
                </span>
              </span>
            </td>

            <td>{{ $globals.currency(appr.amount, converCurrency) }} </td>
            <td class="p-0">
              <button @click="editAppropriationTransaction(appr, i)"
                class="btn btn-info d-inline-block text-white btn-sm me-1"><i class="fs-9 bi bi-pencil"></i></button>
              <button @click="deleteAppropriationTransaction(appr, i)" class="btn btn-danger btn-sm d-inline-block"><i
                  class="fs-9 bi bi-trash"></i></button>
            </td>
          </tr>
        </tbody>
        <tfoot class="d-none">
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td v-for="(key, index) in Object.keys(dynamicData)">
              <span v-if="index == Object.keys(dynamicData)?.length - 2">Total</span>
              <span v-if="index == Object.keys(dynamicData)?.length - 1">{{
                $globals.currency(total_expenditure_appropriation, converCurrency) }}</span>
            </td>
          </tr>

          <tr>
            <td colspan="6"></td>
            <td colspan="2" class="inline-block mb-2 me-4"><b>Income:</b></td>
            <td colspan="2"><span>&#8358;</span> {{ $globals.currency(selected_appropriation_income,
              converCurrency)
              }}</td>

          </tr>
          <tr>
            <td colspan="6"></td>
            <td colspan="2" class="inline-block mb-0"><b>Total Expenditure: </b></td>
            <td colspan="2"><span>&#8358;</span> {{
              $globals.currency(total_expenditure_appropriation, converCurrency) }}</td>
          </tr>
        </tfoot>
      </table>
      <table v-if="filters.group =='vote_book'" class="fs-8 table-bordered transactions-tables table table-sm table-hover" id="myTable">
        <thead>
          <tr>
            <th class="fs-8 fw-bold" style="white-space: nowrap;">S/N</th>
            <th class="fs-8 fw-bold" style="white-space: nowrap;">Date</th>
            <th class="fs-8 fw-bold" style="white-space: nowrap;">Particulars</th>
            <th class="fs-8 fw-bold" style="white-space: nowrap;">Payment</th>
            <th class="fs-8 fw-bold" style="white-space: nowrap;">Total</th>
            <th class="fs-8 fw-bold" style="white-space: nowrap;">Balance</th>
            <th>#</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(tra,i) in transactions?.data">
            <td>{{ i+1 }}</td>
            <td>{{ tra.payment_date }}</td>
            <td>{{ tra.name }}</td>
            <td>{{ $globals.currency( tra.amount,converCurrency ) }}</td>
            <td>{{ $globals.currency( tra.total, converCurrency) }}</td>
            <td>{{ $globals.currency( tra.balance, converCurrency) }}</td>
          </tr>
        </tbody>
        <tfoot class="d-none">
          <tr></tr>
          <tr>
            <td></td><td></td>
            <td><span>APPROPRIATION:</span></td>
            <td></td>
            <td><span >{{$globals.currency(selected_appropriation_income, converCurrency) }}</span></td>
            <td><span >{{$globals.currency(selected_appropriation_income -total_expenditure_appropriation, converCurrency) }}</span></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</template>

<script>
import ConfirmPopup from 'primevue/confirmpopup';
import Divider from 'primevue/divider';
import Calendar from 'primevue/calendar';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import { utils, writeFileXLSX } from "xlsx";

import InlineMessage from 'primevue/InlineMessage';
import TreeSelect from 'primevue/treeselect';

import PopUp from '../PopUp/PopUp.vue'

import Dropdown from 'primevue/dropdown';

export default {
  components: {
    ConfirmPopup,
    Divider,
    Calendar,
    Dropdown,
    InputGroup,
    InputGroupAddon,
    TreeSelect,
    PopUp,
    InlineMessage
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
      filters_date: [{ id: 'Payment_Date', name: "Payment Date" }, { id: 'Approval_Date', name: 'Approval Date' }, { id: 'created_at', name: 'Created Date' }],
      filters_grouping: [
        {
          name: 'Heads',
          id: 'head',
        },
        {
          name: 'Subheads',
          id: 'subhead',
        },
        {
          name: 'Heads -> Subheads',
          id: 'head&subhead',
        },
        {
          name: 'Vote Book',
          id: 'vote_book',
        }
      ],
      filters: { date: null, group:null },
      selected_appropriation_balance: 0,
      selected_appropriation_income: 0,
      transactions: { data: [] },
      tableKey:0,
      dynamicData: {...this.$globals.dynamic_data},
      sub_head_budgets: [],
      converCurrency: true,
      deletedColumns: [],
      deletedCellsMap: new Map(),
    }
  },
  computed: {
    total_expenditure_appropriation() {
      return this.transactions?.data?.reduce((accumulator, currentTransaction) => accumulator + currentTransaction.amount, 0)
    },
    maxColumns() {
      const max = this.transactions?.data?.reduce((acc, appr) => {
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

    exportToExcel() {
      this.converCurrency = false;
      const table = document.getElementById('myTable');
      const wb = utils.table_to_book(table);
      const sheetName = Object.keys(wb.Sheets)[0]; // Assuming only one sheet
      const ws = wb.Sheets[sheetName];
      /*  const lastRowIndex = ws['!ref'].split(':')[1].replace(/\D/g, '');
       ws[`C${parseInt(lastRowIndex) + 1}`] = { t: 's', v: "Balance" };
       ws[`D${parseInt(lastRowIndex) + 1}`] = { t: 'n', v: this.selected_appropriation_balance };
       console.log()
       ws[`C${parseInt(lastRowIndex) + 2}`] = { t: 's', v: "Total Expenditure" };
       ws[`D${parseInt(lastRowIndex) + 2}`] = { t: 'n', v: this.total_expenditure_appropriation };
        */
      writeFileXLSX(wb, "transaction_reports.xlsx");
      setTimeout(() => {
        this.converCurrency = true;
      }, 1000)
    },
    async filterTransaction() {
        await this.fetchWalletBalance()
      await this.fetchTransactions()
    },
    async reloadTransaction() {
        //console.log(this.dynamicData,232323)
        this.filters= { date: null, group:null }
        await this.fetchWalletBalance()
        await this.fetchTransactions()
        this.tableKey++
    },
    editAppropriationTransaction(appr, i) {
      this.$emit('openDebitModal', {
        transaction: { ...appr },
        index: i
      })
      //this.markSelectedDynamicField()
    },
    async fetchTransactions() {
      try {

        let page_type = localStorage.getItem('page_type')
        let owner_ids = []
        if (page_type == 'single') {
            owner_ids = [this.selected_appropriation.id]
        } else {
          let appropriations = JSON.parse(localStorage.getItem('appropriations'));
          owner_ids = appropriations.map(item => item.id);
        }
        let res = await postData('/get_transactions', {
          owner_id: owner_ids,
          owner_type: 'appropriation',
          fund_category: this.fund_category,
          filters: this.filters
        }, true);

        if (res.status == 200) {
          this.transactions = res.data
          setTimeout(() => {
            //this.iniTableTransaction()
          }, 100)
        } else {
          showAlert('Something went wrong');
        }

        if (page_type === 'single') {
          let res2 = await getData(`sub_head_budget/appropriation/${this.selected_appropriation.id}/${this.fund_category}`);
          let res3 = await getData(`sub_head_budget/appropriation/${this.selected_appropriation.id}/${this.fund_category}`);
          this.sub_head_budgets = res2?.data || [];
          //this.sub_head_budgets = res2?.data || [];
        } else {
          this.sub_head_budgets = []
        }
      } catch (e) {
        console.log('tranx error: ' + e)
      }
    },
    transformData(data) {
         const transformedData =[]
        data.forEach((subheadBudget,index) => {
            const subheadBudgetItemChildren = subheadBudget.subhead_budget_items.map((subheadBudgetItem,inddex) => ({
                  name: subheadBudgetItem.subhead_item,
                  amount:subheadBudgetItem.amount,
                  balance:subheadBudgetItem.balance
            }));

            transformedData.push({
                name: subheadBudget.subhead,
                amount:subheadBudget.amount,
                balance:subheadBudget.balance,
                children: subheadBudgetItemChildren
            });
        });

        return transformedData;
    },
    async fetchWalletBalance() {
      let page_type = localStorage.getItem('page_type')
      let owner_ids = []
      if (page_type == 'sigle') {
        owner_ids = [this.selected_appropriation.id]
      } else {
        let appropriations = JSON.parse(localStorage.getItem('appropriations'));
        owner_ids = appropriations.map(item => item.id);
      }
      let res = await postData('/get_wallet_detail', {
        fund_category: this.fund_category,
        owner_id: owner_ids,
        owner_type: 'appropriation'
      }, true)
      if (res.status == 200) {
        this.selected_appropriation_balance = res.data?.balance
        this.selected_appropriation_income = res.data?.income
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
    removeColumn(refname) {
      const table = document.getElementById('myTable');
      const colIndex = Array.from(table.rows[0].children).findIndex(th => th.getAttribute('data-name') === refname);

      if (colIndex !== -1) {
        for (let i = 0; i < table.rows.length; i++) {
          const cell = table.rows[i].cells[colIndex];
          table.rows[i].deleteCell(colIndex);
        }
      }
    }


  },
  mounted() {
    //document.addEventListener('keydown', this.handleUndo);
    this.$nextTick(() => {
      const table = document.getElementById('myTable');
      let draggingCol = null;

      table.querySelectorAll('th').forEach(th => {
        th.draggable = true;

        th.addEventListener('dragstart', function (e) {
          draggingCol = this;
          setTimeout(() => this.classList.add('dragging'), 0);
        });

        th.addEventListener('dragover', function (e) {
          e.preventDefault();
        });

        th.addEventListener('dragenter', function (e) {
          e.preventDefault();
          if (this !== draggingCol) {
            this.style.borderLeft = '3px solid blue';
          }
        });

        th.addEventListener('dragleave', function (e) {
          this.style.borderLeft = '';
        });

        th.addEventListener('drop', function (e) {
          if (this !== draggingCol) {
            swapColumns(table, draggingCol.cellIndex, this.cellIndex);
            this.style.borderLeft = '';
          }
        });

        th.addEventListener('dragend', function () {
          this.classList.remove('dragging');
        });

        /*   th.addEventListener('dblclick', function () {
            const colIndex = this.cellIndex;
            table.querySelectorAll('tr').forEach(row => {
              row.deleteCell(colIndex);
            });
          }); */
      });

      function swapColumns(table, fromIndex, toIndex) {
        const rows = Array.from(table.querySelectorAll('tr'));
        rows.forEach(row => {
          const cells = Array.from(row.querySelectorAll('th, td'));
          row.insertBefore(cells[fromIndex], cells[toIndex > fromIndex ? toIndex + 1 : toIndex]);
        });
      }

    })




  }
};
</script>

<!-- Add your component-specific styles here if needed -->
<style scoped>
.scrollable-content {
  width: calc(100% + 17px);
  /* Adjust for scrollbar width */
  height: 100%;
  overflow-y: auto;
}

button.btn.btn-secondary.buttons-print {
  margin-left: 18px !important;
}

.transactions-tables tbody {
  max-height: 50px;
  /* Adjust the height as needed */
  overflow-y: auto;
}

th:hover .thoverlay {
  display: flex !important;
  justify-content: center;
  align-items: center;
}

th {
  cursor: pointer;
}

.thoverlay {
  background-color: rgba(206, 19, 19, .5);
  color: white;
  position: absolute;
  top: 0px;
  right: 0px;
  width: 100%;
  height: 100%;
  display: none;
  z-index: 4;
}
</style>

<style>
.dt-search {
  max-width: 300px;
  margin-bottom: 10px;
  margin-right: auto;
  margin-left: auto;
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
}

.dt-search label {
  color: #bbb;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th,
td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.dragging {
  opacity: 0.5;
  box-shadow: 1px 2px 3px #000;
}
</style>
