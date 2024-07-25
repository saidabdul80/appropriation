<template>
  <div class="p-3 rounded-lg-only shadow-lg-only bg-white" style="height: inherit;overflow: hidden;">

      <div class="row w-100">
        <div class="col-md-4 col-lg-3">
          <button @click="$emit('switch-page', 1)" class="btn fs-9 btn-primary text-white rounded" style=""><span
              class="pi pi-arrow-left"></span></button>
          <button @click="reloadTransaction()" class="btn fs-9 ms-3 btn-primary text-white rounded" style=""><span
              class="pi pi-refresh"></span></button>
          <button @click="exportToExcel()" class="btn fs-9 ms-3 btn-primary text-white rounded" style=""><span
              class="pi pi-file-export"></span></button>
          <button @click="toggleSheet" class="btn fs-9 ms-3 btn-primary text-white rounded" style=""><span
              class="pi pi-window-maximize"></span></button>

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
      <TableSheet
        ref="trxSheet"
        :converCurrency="converCurrency"
        :filters_date="filters_date"
        :filters_grouping="filters_grouping"
        :filters="filters"
        :selected_appropriation="selected_appropriation"
        />
    <teleport to="body">
        <div v-show="showSheet">
        <div class="table-sheet-overlay" @click="toggleSheet"></div>
      <div  class="table-sheet pullUp">
        <button @click="toggleSheet" class="pi pi-times btn btn-light text-danger pull-right"></button>
        <div class="sheet-content">
          <!-- Placeholder for the table -->
          <div id="tableContainerx" style="width:100%;height:100%; overflow: auto;">
            <TableSheet
                ref="trxSheet"
                height="100%"
                :converCurrency="converCurrency"
                :filters_date="filters_date"
                :filters_grouping="filters_grouping"
                :filters="filters"
                :permissions="permissions"
                :selected_appropriation="selected_appropriation"
                />
            </div>
        </div>
      </div>
      </div>
    </teleport>
  </div>
</template>

<script>
import ConfirmPopup from 'primevue/confirmpopup';
import Divider from 'primevue/divider';
import Calendar from 'primevue/calendar';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import { utils, writeFileXLSX } from "xlsx";
import TableSheet from './Transaction/TableSheet.vue';
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
    InlineMessage,
    TableSheet
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
    permissions:{
        default:[]
    }
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
      transactions: { data: [] },

      dynamicData: {...this.$globals.dynamic_data},
      converCurrency: true,
      deletedColumns: [],
      showSheet:false,
    }
  },
  async created() {


  },
  methods: {
    toggleSheet() {
      this.showSheet = !this.showSheet;
    },

    async filterTransaction() {
       await this.$refs.trxSheet.fetchWalletBalance()
      await this.$refs.trxSheet.fetchTransactions()
    },
    async reloadTransaction() {
      this.filters = { date: null, group: null }
      await this.$refs.trxSheet.fetchTransactions();

    },

    resetTotalExpenditureForAppropriation() {
      // Implement your logic for resetting total expenditure
    },

    getTotalExpenditureForAppropriation(amount) {
      // Implement your logic for getting total expenditure for appropriation
    },

    switchPageFunc(page) {
      // Implement your logic for switching pages
      this.$emit('switch-page', page);
    },



  },

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

/* Styles for the table sheet and overlay */
.table-containerx {
  position: relative; /* Ensure it can contain the absolute positioned sheet */
  height: 100%; /* Adjust as needed */
}

.table-sheet {
  position: fixed; /* Position fixed to overlay on top of everything */
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 99;
}

.table-sheet-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 98;
}

.sheet-content {
    position: absolute; /* Position absolute to overlay on top of overlay */
    bottom:0px;
    width:100%;
    padding:20px 20px;
    height:80vh ;
    border-radius:25px 25px 0px 0px;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}
</style>
