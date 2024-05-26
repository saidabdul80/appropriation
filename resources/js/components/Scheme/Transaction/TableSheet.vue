<template>

<div class="table-containerx" >

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
                    <th class="fs-8 fw-bold" style="white-space: nowrap;" data-name="outcome">
                        <button class="btn btn-white text-danger btn-sm p-1" @click="removeColumn('outcome')">
                            <span class="pi pi-times "></span>
                        </button>
                        Outcome
                    </th>
                    <th class="fs-8 fw-bold" style="white-space: nowrap;" data-name="output">
                        <button class="btn btn-white text-danger btn-sm p-1" @click="removeColumn('output')">
                            <span class="pi pi-times "></span>
                        </button>
                        Output
                    </th>
                    <th v-for="header in Object.keys(dynamicData)" :data-name="header"
                    class="fs-8 fw-bold" style="white-space: nowrap;">
                    <div class="d-flex" style="justify-content: space-between; align-items: center;">
                        <button class="btn btn-white text-danger btn-sm p-1" @click="removeColumn(header)">
                            <span class="pi pi-times "></span>
                        </button>
                        {{ header.replaceAll('_', ' ') }}
                    </div>
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
                    <td>{{ appr.outcome }}</td>
                    <td>{{ appr.output }}</td>
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
import PopUp from '../../PopUp/PopUp.vue'
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
    converCurrency: {
      type: Boolean,
      default: true
    },
    filters_date:{
        default:[{ id: 'Payment_Date', name: "Payment Date" }, { id: 'Approval_Date', name: 'Approval Date' }, { id: 'created_at', name: 'Created Date' }],
    },
    selected_appropriation:{
        default:{}
    },
    filters_grouping:{
    default:[
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
      ]},
    filters:{default: { date: null, group: null }},
  },
  data() {
    return {
      showSheet: false,
      transactions: { data: [] },
      tableKey: 0,
      dynamicData: { ...this.$globals.dynamic_data },
      deletedColumns: [],
      deletedCellsMap: new Map(),
      sub_head_budgets: [],
      selected_appropriation_income:null,
      selected_appropriation_balance:null,
    }
  },
  async created() {
    this.fund_category = this.$parent.$parent?.selected_fund_category
    await this.fetchWalletBalance()
    await this.fetchTransactions()
    this.iniTableTransaction()
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
  methods: {
    toggleSheet() {
      this.showSheet = !this.showSheet;
      if (this.showSheet) {
        setTimeout(() => {
          document.getElementById('tableContainerx')?.appendChild(document.querySelector('.table-containerx'));
        }, 300)
      } else {
        document.querySelector('.table-containerx')?.appendChild(document.querySelector('.tableContainerx'));
      }
    },
    exportToExcel() {
      this.converCurrency = false;
      const table = document.getElementById('myTable');
      const wb = utils.table_to_book(table);
      const sheetName = Object.keys(wb.Sheets)[0]; // Assuming only one sheet
      const ws = wb.Sheets[sheetName];
      writeFileXLSX(wb, "transaction_reports.xlsx");
      setTimeout(() => {
        this.converCurrency = true;
      }, 1000)
    },
    async filterTransaction() {
      await this.fetchTransactions();
    },

    editAppropriationTransaction(appr, i) {
      this.$emit('openDebitModal', {
        transaction: { ...appr },
        index: i
      })
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
        } else {
          showAlert('Something went wrong');
        }
        if (page_type === 'single') {
          let res2 = await getData(`sub_head_budget/appropriation/${this.selected_appropriation.id}/${this.fund_category}`);
          let res3 = await getData(`sub_head_budget/appropriation/${this.selected_appropriation.id}/${this.fund_category}`);
          this.sub_head_budgets = res2?.data || [];
        } else {
          this.sub_head_budgets = []
        }
        this.tableKey++;
      } catch (e) {
        console.log('tranx error: ' + e)
      }
    },
    transformData(data) {
      const transformedData = []
      data.forEach((subheadBudget, index) => {
        const subheadBudgetItemChildren = subheadBudget.subhead_budget_items.map((subheadBudgetItem, inddex) => ({
          name: subheadBudgetItem.subhead_item,
          amount: subheadBudgetItem.amount,
          balance: subheadBudgetItem.balance
        }));
        transformedData.push({
          name: subheadBudget.subhead,
          amount: subheadBudget.amount,
          balance: subheadBudget.balance,
          children: subheadBudgetItemChildren
        });
      });
      return transformedData;
    },
    async fetchWalletBalance() {
      let page_type = localStorage.getItem('page_type')
      let owner_ids = []
      if (page_type == 'single') {
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
    async deleteAppropriationTransaction(appr, i) {
      const confirmText = appr.data.Subject.value + ' of ' + appr.amount;
      const { value: confirmationText } = await Swal.fire({
        title: 'Continue Delete?',
        html: 'Please type <strong class="fs-9 text-primary"><i>' + confirmText + '</i></strong> to confirm:',
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
            Swal.showValidationMessage(
              'Name typed does not match!'
            )
          }
        }
      })

      if (confirmationText === confirmText) {
        try {
          let res = await postData('/delete_transaction', {
            transaction_id: appr.id
          });
          if (res.status == 200) {
            this.$toast.add({
              severity: 'success',
              summary: 'Success!',
              detail: 'Transaction deleted successfully',
              life: 5000
            });
            this.transactions.data.splice(i, 1);
            this.tableKey++;
            this.fetchWalletBalance()
          } else {
            showAlert('Something went wrong');
          }
        } catch (e) {
          showAlert('Something went wrong');
        }
      }
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

  }
};
</script>
