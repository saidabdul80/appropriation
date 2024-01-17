<template>
  <div>
    <!-- //Ribbon -->  
    <div class="row mx-0 mt-3">
      <div class="col-md-6 px-0">
        <ribbon-menu
          @projectionModal="openModalProjection = true"            
          @fundModal="openModalFund= true"
          @appropriationModalRemit="openModalApprRemit= true"
          @report="openReport()"
          @openExpenditure="openExpenditureModal"
          :category_income_balance="0"          
          :permissions="permissions" :category_income="category_income"  :selected_scheme="selected_scheme" 
          />
      </div>
      <div class="col-md-6">
        <scheme-selector 
          @month-selected="monthYearTriggered" 
          :fund_categories="fund_categories" 
          @openModal="openModalScheme = true" 
          :schemes="schemes" 
          @scheme-selected="changeScheme" 
          />
         
      </div>
    </div>          
    <div class="row mx-0 mt-3">
      <div class="col-md-6 px-0">
        <nav-tab v-show="switchPage == 1" 
              @switch-page="handlePageSwitch" 
              :appropriations="selected_scheme.appropriations" 
              :selected_fund_category ="selected_fund_category"
              />
      </div>
      <div class="col-md-6 px-0">
        <summary-data 
          :permissions="permissions"
          :selected_scheme="selected_scheme"
          :selected_fund_category="selected_fund_category"
          :category_income="category_income"
          :category_income_balance="category_income_balance"
          :getCategoryIncomeBalance="getCategoryIncomeBalance"
          @openExpenditure="openExpenditureModal"
        />
      </div>
    </div>
    <div class=" pb-2 pt-3" style="height: 92.1vh; z-index: 1">
      <!-- <div class="bg-light pageTitleCard w-15 fs-9 text-danger ps-4">::{{ pageName }}</div> -->
      <div class="position-relative">
      
        <div class="py-2">
          <div class="mb-3 mx-1 row ">
          </div>
         
          <div class="" style="height: 77vh;">
          <!--   <div class="mb-3 input-group">
              <span class="input-group-text">Funding Year</span>
              <select @input="monthYearTriggered($event)" class="form-control" placeholder="Select funding year">
                <option value=""> </option>
                <option v-for="month_year in fund_categories" :value="month_year">{{ month_year }}</option>
              </select>
            </div> -->
            
            <div id="pagerContainer"  style="height:65vh">
              <div v-if="switchPage === 1" style="height: inherit">
                  <index-screen                   
                  :key="selected_scheme.id"
                  :switchPageOne="switchPageOne"
                  :selected_scheme="selected_scheme"
                  :appropriations_history="appropriations_history?.data"
                  :selected_fund_category="selected_fund_category"
                  :selected_month_appropriations="appropriations"
                  :totalAppropriationPercentageValue="totalAppropriationPercentageValue"
                  :pageSwitched="switchPage"
                  :departments="departments"
                  :appropriation_types="appropriationtypes"
                  :appropriation_data_summary="appropriation_data_summary"
                  :schemes="schemes"
                  @openTransaction="handleOpenTransaction"
                  />
              </div>
              <div v-if="switchPage === 2">
                <appropriation-history 
                  :appropriationHistories="appropriations_history"
                  :selected_scheme="selected_scheme"
                />
              </div>              
              <div v-if="switchPage === 3" class="position-relative " style="height: inherit;" id="transaction-sheet">
                <transaction-sheet      
                  :key="selected_fund_category"                               
                  :selected_appropriation="selected_transcation_appropriation"
                  :fund_category="selected_fund_category"
                  agency_name=""                  
                  @switch-page="switchPageFunc"
                  @openDebitModal="openDebitModalForTransaction"
                />
              </div>
            </div>
          </div>
        </div>

        <!-- Use proper registration for swift-button -->
        <swift-button :currentPage="switchPage" @switch-page="switchPageFunc"></swift-button>
      </div>
    </div>
    <Transition name="fade">
      <projection-modal 
        v-if="openModalProjection" 
        @category-income-balance="handleCategoryIncomeBalance"
        @appropriations="appropriations" 
        :selected_scheme="selected_scheme" 
        @closeProjection="openModalProjection = false"   
        />
    </Transition> 
    <Transition name="fade">
      <add-fund-modal
        v-if="openModalFund" 
        @response="fundAdded"
        :selected_scheme="selected_scheme" 
        @closeModal="openModalFund = false"   
      />
    </Transition>
    <Transition name="fade">
      <debit-modal
        v-if="openModalApprRemit" 
        @response="fundAdded"
        @appropriation="selected_appropriation"
        :appropriation_log="transaction"
        :selected_scheme_id="selected_scheme?.id" 
        :fund_categories="fund_categories"
        @closeModal="openModalApprRemit = false"   
      />
    </Transition>
    <Transition name="fade">
      <scheme-modal
        v-if="openModalScheme"         
        :selected_scheme="selected_scheme" 
        :fund_categories="fund_categories"
        @closeModal="openModalScheme = false"   
      />
    </Transition>
    <Transition name="fade">
      <expenditure-details-modal 
        v-if="openModalExpenditure"         
        @closeModal="openModalExpenditure= false"
        :selected_fund_category="selected_fund_category"
        :selected_scheme="selected_scheme"
        :category_income="category_income"
        :category_income_balance="category_income_balance"
      />
    </Transition>

  </div>
</template>

<script>
// Import swift-button component
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
export default {
  components: {    
    'scheme-modal':SchemeModal,
    'scheme-selector':SchemeSelector,
    'index-screen':IndexScreen,
    'ribbon-menu':RibbonMenu,
    'projection-modal':ProjectionModal,
    'add-fund-modal':AddFundModal,
    'debit-modal':DebitModal,
    'nav-tab':NavTab,
    'transaction-sheet':TransactionSheet,
    'expenditure-details-modal':ExpenditureDetailsModal,
    SummaryData,
    AppropriationHistory
  },
  data() {
    return {
      switchPageOne:'0',
      pageName:'',
      selected_scheme: null,
      switchPage: 1,       
      fund_category:'', 
      fund_categories:[],      
      appropriations:[],
      appropriationHistories:[],
      appropriations_history:[],
      selected_fund_category:'',            
      category_income:0,
      expenditure_amount:0,
      category_income_balance:0,
      openModalProjection:false,
      openModalFund:false,
      openModalApprRemit:false,
      openModalReport:false,
      openModalScheme:false,
      appropriations_projection: {},
      selected_appropriation: {
          index: '',
          id: '',
          appropriation_type_id: '',
          department_id: [],
          percentage_dividend: '',
      },      
      selected_transcation_appropriation:{},
      pageSwitched:10,
      transaction:{},
      appropriation_data_summary:{},
      openModalExpenditure:false,
    };
  },
  props: {
    permissions: {
      type: Object,
      default: {},
    },
    schemes: {
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
  computed:{ 
  },
  methods: {  
    openExpenditureModal(data){
      if(this.selected_scheme?.id =='' || this.selected_scheme?.id  == undefined){
              Swal.fire('Select a programme')
              return false
      }      
      this.category_income = data.category_income
      this.category_income_balance = data.category_income_balance
      this.openModalExpenditure=true
    },
    openReport(){
      if(this.selected_scheme?.id =='' || this.selected_scheme?.id  == undefined){
              Swal.fire('Select a programme')
              return false
      }
      window.open('/report/'+this.selected_scheme.id,'blank')
    },
    async appropriationSummaryResolver() {
        if(this.selected_fund_category == ''){
          this.appropriation_data_summary = {
            income:{},
            balance:{}
          }
          return 0;
        }
        let res = await postDataWithoutLoader('/get_amount_summary_data', {
          scheme_id: this.selected_scheme.id,
          fund_category: this.selected_fund_category,
        }, true);
        if (res.status == 200) {
          this.appropriation_data_summary = res.data;
        }   
      },
    async openDebitModalForTransaction(data){            
        this.transaction = {}
        //$("#loaderHtml").show()
        
        this.transaction = data.transaction        
        this.transaction.total_amount = data.transaction.amount
        this.transaction.index = data.index
        this.openModalApprRemit = true
      /*   setTimeout(()=>{
          $("#loaderHtml").hide() 
        },100) */
    },
    handleOpenTransaction(data){
      this.switchPageFunc(3)      
      this.transactions_table_toggle = true
      this.selected_transcation_appropriation = data.selected_appropriation      
    },
    switchPageFunc(num) {
        this.switchPage = num
        
        //localStorage.setItem('pageNum', num)
    },
    handlePageSwitch(data){
      this.switchPageOne = data;
    },
    fundAdded(res){
      let index = this.$globals.getIndexOf(this.schemes, this.selected_scheme)
      this.schemes[index] = res.scheme
      this.selected_scheme = res.scheme
      showAlert(res.msg)
    },
    ProjectionModal(){  
      this.openModalProjection = true
    },
    getSchemeIndex(scheme) {
        return this.schemes.findIndex((item) => item.id == scheme.id)
    },
    async monthYearTriggered(data) { //1 means from the right source
      //console.log(data)
      this.selected_fund_category = data.selected_month
        this.appropriationSummaryResolver();                
        this.appropriations_history = data.appropriations_histories        
        this.appropriations = data.appropriations                                                      
        setTimeout(() => {
            this.getCategoryIncome()
            this.getCategoryIncomeBalance()
        }, 10)
      },
    async changeScheme(data) {      
         this.selected_scheme = data;      
            try {
                const [fundResponse, appropriationHistoryResponse] = await Promise.all([
                    postData('/fund_month_year', {
                        scheme_id: this.selected_scheme.id
                    }, true),
                    postData('/get_appropriation_histories', {
                        owner_id: this.selected_scheme.id,
                        owner_type: 'scheme'
                    }, true),
                ]);                  
                if (fundResponse?.status === 200) {
                    this.fund_categories = fundResponse.data;             
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
        this.category_income_balance = this.appropriations.reduce((total, appropriation) => {
            return total + appropriation?.wallet.balance
        }, 0)
    },
    getCategoryIncome() {
        this.category_income = this.appropriationHistories[this.selected_fund_category]?.reduce((total, item) => {
            return total + item.amount
        }, 0);
    },
    handleMonthYearTriggered() {
      // Implement the logic for month/year change if needed
    },
  },
  created(){    
    this.selected_scheme = { name: '', balance: 0, wallet: { fund_category: '', balance: 0 }, appropriations:[] }
  },
  mounted() {
    console.log('Component mounted.');
  },
};
</script>
