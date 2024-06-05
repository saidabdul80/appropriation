<template>

    <div style="height: 100vh" v-if="!pageIsLoading">
      <div class="row mx-0 mt-3">
        <div class="col-md-6 px-0 mb-4 mt-4 mt-sm-0">
          <ribbon-menu
            @projectionModal="openModalProjection = true"
            @fundModal="openModalFund = true"
            @appropriationModalRemit="openTransactionModal"
            @report="openReport"
            @openExpenditure="openExpenditureModal"
            @showAddModal="openAppModalFunc"
            :category_income_balance="category_income_balance"
            :permissions="permissions"
            :category_income="category_income"
            :selected_scheme="selected_scheme"
          />
        </div>
        <div class="col-md-6 px-0">
          <scheme-selector
            @month-selected="monthYearTriggered"
            :fund_categories="fund_categories"
            @openModal="openModalScheme = true"
            :schemes="schemes"
            :scheme_changed="scheme_changed"
            @scheme-selected="changeScheme"
          />
        </div>
      </div>
      <div class="row mx-0 mt-3">
        <div class="col-md-6 px-0">
          <nav-tab
            v-show="switchPage === 1"
            @switch-page="handlePageSwitch"
            :appropriations="selected_scheme?.appropriations"
            :selected_fund_category="selected_fund_category"
          />
        </div>
        <div class="col-md-6 px-0">
          <summary-data
            :permissions="permissions"
            :selected_scheme="selected_scheme"
            :selected_fund_category="selected_fund_category"
            :category_income="category_income"
            :category_income_balance="category_income_balance"
            @openExpenditure="openExpenditureModal"
          />
        </div>
      </div>
      <div class="pb-2 pt-3" style="height: inherit; z-index: 1">
        <div class="position-relative">
          <div class="py-2">
            <div style="height: inherit;">
              <div id="pagerContainer" style="height: 65vh">
                <div v-if="switchPage === 1" style="height: inherit;" class="driveInRight">
                  <index-screen
                    :permissions="permissions"
                    :key="selected_scheme?.id + keyttoggle"
                    :switchPageOne="switchPageOne"
                    :selected_scheme="selected_scheme"
                    :appropriations_history="appropriations_history"
                    :selected_fund_category="selected_fund_category"
                    :selected_month_appropriations="appropriations"
                    :totalAppropriationPercentageValue="totalAppropriationPercentageValue"
                    :pageSwitched="switchPage"
                    :departments="departments"
                    :appropriation_types="appropriationtypes"
                    :appropriation_data_summary="appropriation_data_summary"
                    :schemes="schemes"
                    @updateSchemes="updateSchemes"
                    :openAppropriationModal="openAppModal"
                    @closeAppModal="openAppModal = false"
                    @openTransaction="handleOpenTransaction"
                    @updateParentSchemes="reloadSchemes"
                  />
                </div>
                <div v-if="switchPage === 2" style="height: inherit; min-width: 1000px; overflow-x: auto">
                  <appropriation-history
                    :appropriationHistories="appropriations_history"
                    :selected_scheme="selected_scheme"
                  />
                </div>
                <div v-if="switchPage === 3" class="position-relative" style="height: inherit; min-width: 1000px; overflow-x: auto" id="transaction-sheet">
                  <transaction-sheet
                    :key="selected_fund_category"
                    :selected_appropriation="selected_transcation_appropriation"
                    :fund_category="selected_fund_category"
                    agency_name=""
                    :permissions="permissions"
                    @switch-page="switchPageFunc"
                    @openDebitModal="openDebitModalForTransaction"
                  />
                </div>
              </div>
              <swift-button :currentPage="switchPage" @switch-page="switchPageFunc"></swift-button>
            </div>
          </div>
        </div>
      </div>
      <transition name="fade">
        <projection-modal
          v-if="openModalProjection"
          @category-income-balance="handleCategoryIncomeBalance"
          @appropriations="appropriations"
          :selected_scheme="selected_scheme"
          @closeProjection="openModalProjection = false"
        />
      </transition>
      <transition name="fade">
        <add-fund-modal
          v-if="openModalFund"
          @onComplete="fundAdded"
          :selected_scheme="selected_scheme"
          @closeModal="openModalFund = false"
        />
      </transition>
      <transition name="fade">
        <debit-modal
          v-if="openModalApprRemit"
          @response="fundAdded"
          @appropriation="selected_appropriation"
          :appropriation_log="transaction"
          :selected_scheme_id="selected_scheme?.id"
          :fund_categories="fund_categories"
          @closeModal="openModalApprRemit = false"
        />
      </transition>
      <transition name="fade">
        <scheme-modal
          v-if="openModalScheme"
          :selected_scheme="selected_scheme"
          :fund_categories="fund_categories"
          @closeModal="openModalScheme = false"
        />
      </transition>
      <transition name="fade">
        <expenditure-details-modal
          v-if="openModalExpenditure"
          @closeModal="openModalExpenditure = false"
          :selected_fund_category="selected_fund_category"
          :selected_scheme="selected_scheme"
          :category_income="category_income"
          :category_income_balance="category_income_balance"
        />
      </transition>
      <div id="modal-10" class="effect-modal md-effect-10" style="width: 80%;">
        <config-modal :permissions="permissions"  class="effect-content"  />
        </div>
        <div class="md-overlay"></div>
    </div>
</template>

  <script>
  import SchemeSelector from './../components/Scheme/SchemeSelector.vue';
  import IndexScreen from './../components/Scheme/IndexScreen.vue';
  import TransactionSheet from './../components/Scheme/TransactionSheet.vue';
  import RibbonMenu from './../components/Scheme/RibbonMenu.vue';
  import NavTab from './../components/Scheme/NavTabs.vue';
  import ProjectionModal from './../components/Scheme/Modals/ProjectionModal.vue';
  import AddFundModal from './../components/Scheme/Modals/AddFundModal.vue';
  import DebitModal from './../components/Scheme/Modals/DebitModal.vue';
  import SchemeModal from './../components/Scheme/Modals/SchemeModal.vue';
  import ExpenditureDetailsModal from './../components/Scheme/Modals/ExpenditureDetailsModal.vue';
  import SummaryData from '../components/Scheme/SummaryData.vue';
  import AppropriationHistory from '../components/Scheme/AppropriationHistory.vue';
  import ConfigModal from './../components/Scheme/Modals/ConfigModal.vue';
  import { useGlobalStore } from '../store';
  export default {
    components: {
      'scheme-modal': SchemeModal,
      'scheme-selector': SchemeSelector,
      'index-screen': IndexScreen,
      'ribbon-menu': RibbonMenu,
      'projection-modal': ProjectionModal,
      'add-fund-modal': AddFundModal,
      'debit-modal': DebitModal,
      'nav-tab': NavTab,
      'transaction-sheet': TransactionSheet,
      'expenditure-details-modal': ExpenditureDetailsModal,
      SummaryData,
      AppropriationHistory,
      ConfigModal
    },
    data() {
      return {
        globals:useGlobalStore(),
        pageIsLoading: true,
        scheme_changed: 0,
        switchPageOne: '0',
        selected_scheme: null,
        switchPage: 1,
        fund_categories: [],
        appropriations: [],
        appropriations_history: [],
        selected_fund_category: '',
        category_income: 0,
        category_income_balance: 0,
        openModalProjection: false,
        openModalFund: false,
        openModalApprRemit: false,
        openModalScheme: false,
        appropriations_projection: {},
        selected_appropriation: {
          index: '',
          id: '',
          appropriation_type_id: '',
          department_id: [],
          percentage_dividend: '',
        },
        selected_transcation_appropriation: {},
        transaction: {},
        appropriation_data_summary: {},
        openModalExpenditure: false,
        keyttoggle: 0,
        schemes: [],
        openAppModal:false
      };
    },
    props: {
      permissions: {
        type: Object,
        default: {},
      },
      appropriationtypes: {
        type: Object,
        default: [],
      },
      departments: {
        type: Array,
        default: [],
      },
      dyear: {
        default: '',
      },
      cyear: {
        default: '',
      },
    },
    watch:{
      'globals.open_debit_modal':function(n){
        if(this.globals.open_debit_modal){
          this.transaction = {};
          this.transaction = this.globals.transaction;
          this.transaction.total_amount =  this.globals.transaction.amount;
          this.transaction.index =  this.globals.transaction_index;
          this.openModalApprRemit = true;
        }
      }
    },
    methods: {
      openTransactionModal() {
        this.transaction = { id: '' };
        this.openModalApprRemit = true;
      },
      openAppModalFunc() {
        this.openAppModal = true;
      },
      openExpenditureModal(data) {
        if (!this.selected_scheme?.id) {
          Swal.fire('Select a programme');
          return false;
        }
        this.category_income = data.category_income;
        this.category_income_balance = data.category_income_balance;
        this.openModalExpenditure = true;
      },
      openReport() {
        if (!this.selected_scheme?.id) {
          Swal.fire('Select a programme');
          return false;
        }
        window.open('/report/' + this.selected_scheme.id, 'blank');
      },
      async appropriationSummaryResolver() {
        if (!this.selected_fund_category) {
          this.appropriation_data_summary = {
            income: {},
            balance: {},
          };
          return 0;
        }
        const res = await postDataWithoutLoader('/get_amount_summary_data', {
          scheme_id: this.selected_scheme.id,
          fund_category: this.selected_fund_category,
        }, true);
        if (res.status === 200) {
          this.appropriation_data_summary = res.data;
        }
      },
      async openDebitModalForTransaction(data) {
        this.transaction = {};
        this.transaction = data.transaction;
        this.transaction.total_amount = data.transaction.amount;
        this.transaction.index = data.index;
        this.openModalApprRemit = true;
      },
      updateSchemes(data) {
        this.$forceUpdate();
      },
      handleOpenTransaction(data) {
        this.switchPageFunc(3);
        this.transactions_table_toggle = true;
        this.selected_transcation_appropriation = data.selected_appropriation;
      },
      switchPageFunc(num) {
        this.switchPage = num;
      },
      handlePageSwitch(data) {
        this.switchPageOne = data;
      },
      fundAdded(res) {
        const index = this.schemes.findIndex(scheme => scheme.id === this.selected_scheme.id);
        this.schemes[index] = res.scheme;
        this.scheme_changed += 1;
        this.selected_scheme = res.scheme;
        this.changeScheme(this.selected_scheme);
        showAlert(res.msg);
      },
      ProjectionModal() {
        this.openModalProjection = true;
      },
      async monthYearTriggered(data) {
        this.selected_fund_category = data.selected_month;
        this.appropriationSummaryResolver();
        this.appropriations = data.appropriations;
        this.appropriationHistories = data.appropriations_histories
        setTimeout(() => {
          this.getCategoryIncome();
          this.getCategoryIncomeBalance();
        }, 10);
      },
      async reloadSchemes() {
        this.pageIsLoading = true;
        const res = await getData('/schemes');
        if (res?.data) {
          this.schemes = res.data;
          this.globals.schemes = this.schemes
          if (this.selected_scheme?.name != '') {
            this.selected_scheme = this.schemes.find(scheme => scheme.id === this.selected_scheme.id);
          }
        }
        this.keyttoggle += 1;
        this.pageIsLoading = false;
      },
      async changeScheme(data) {
        this.selected_scheme = data;
        try {
          const [fundResponse, appropriationHistoryResponse] = await Promise.all([
            postData('/get_fund_categories', {
              scheme_id: this.selected_scheme.id,
            }, true),
            postData('/get_appropriation_histories', {
              owner_id: this.selected_scheme.id,
              owner_type: 'scheme',
            }, true),
          ]);

          if (fundResponse?.status === 200) {
            this.fund_categories = fundResponse.data.map(item => item.fund_category);
          } else {
            showAlert('Something went wrong');
            return false;
          }
          if (appropriationHistoryResponse?.status === 200) {
            this.appropriations_history = appropriationHistoryResponse.data;
            this.appropriations = [];
          } else {
            showAlert('Something went wrong');
            return false;
          }
        } catch (error) {
          console.error(error);
          showAlert('An error occurred while fetching data.');
          return false;
        }
        this.category_income = 0;
        this.expenditure_amount = 0;
        this.category_income_balance = 0;
        this.getCategoryIncome();
        this.getCategoryIncomeBalance();
      },
      getCategoryIncomeBalance() {
      try {
        if (!this.appropriations) {
          throw new Error('Appropriations is null or undefined.');
        }
        this.category_income_balance = this.appropriations.reduce((total, appropriation) => total + (appropriation?.wallet?.balance || 0), 0);
      } catch (error) {
        console.warn('Error in getCategoryIncomeBalance:', error);
      }
    },
    getCategoryIncome() {
      try {
        if (!this.appropriationHistories || !this.selected_fund_category) {
          throw new Error('Appropriation histories or selected fund category is null or undefined.');
        }
        this.category_income = this.appropriationHistories[this.selected_fund_category]?.reduce((total, item) => total + item.amount, 0) || 0;
      } catch (error) {
        console.warn('Error in getCategoryIncome:', error);
      }
    },
      handleMonthYearTriggered() {
        // Implement the logic for month/year change if needed
      },
    },
    async created() {
     
        this.selected_scheme = { name: '', balance: 0, wallet: { fund_category: '', balance: 0 }, appropriations: [] };
        await this.reloadSchemes();
      // If alert() is not necessary, remove or replace with desired logic.

    },
    mounted() {
      console.log('Component mounted.');
    },
  };
  </script>


<style>
.p-inputtext {

    color: #334155;
    background: #ffffff;
    padding: 0.5rem 0.75rem !important;
    /* border: 1px solid #cbd5e1; */
    transition: background-color 0.2s, color 0.2s, border-color 0.2s, box-shadow 0.2s, outline-color 0.2s !important;
    appearance: none;
    border-radius: 6px;
    outline-color: transparent !important;
}
.p-multiselect-panel .p-multiselect-header {
    padding: 0.5rem 0.5rem 0 0.5rem !important;
    }

 .p-multiselect-filter-container {
    position: relative;
    flex: 1 1 auto;
    padding: 9px !important;
}
.p-multiselect-filter-icon {
    position: absolute;
    top: 35% !important;
    }

 .p-multiselect.p-multiselect-chip .p-multiselect-token {
    border-radius: 4px;
    margin-right: 0.25rem !important;
}
.p-multiselect.p-multiselect-chip .p-multiselect-token {
    padding: 0.25rem 0.75rem !important;
}
select.form-control:hover, input:hover{
  border: 1px solid #ccc !important;
}
</style>
