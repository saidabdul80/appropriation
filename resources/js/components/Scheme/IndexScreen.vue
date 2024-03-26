<template>
    <div class="bg-white rounded-lg-only shadow-lg-only" style="overflow-x: auto">
      <div v-show="switchPageOne == 0" style="height: inherit">
        <div style="height: inherit; display: flex; flex-direction: column">
          <div style="height: 100%; ">
            <table class="table table-lg" style="min-width:1000px;">
              <thead>
                <tr class="fw-bold">
                  <th>
                    <input v-if="selected_scheme.appropriations.length > 0" type="checkbox" @click="selectAllAppropriation(selected_scheme.appropriations, $event)">
                  </th>
                  <th>SN.</th>
                  <th>Appropriation Name</th>
                  <th>Department Code</th>
                  <th>Current Percentage ({{ totalAppropriationPercentageValue }} %)</th>
                  <th>#</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(appropriation, i) in selected_scheme.appropriations" :key="i">
                  <td><input type="checkbox" :value="appropriation.id" v-model="selected_appropriations_to_appropriate"></td>
                  <td>{{ i + 1 }}</td>
                  <td>{{ appropriation.name }}</td>
                  <td>{{ appropriation.department }}</td>
                  <td>{{appropriation.percentage_dividend }}</td>
                  <td>
                    <button @click="appropriationModalUpdate(appropriation, i)" class="me-2 btn btn-sm btn-info text-white">
                      <i class="bi bi-pencil-square" style="color:inherit"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="p-3" style="height: 15%;">
            <div v-if="canPerformAction('appropriate')">
              <button v-if="selected_scheme?.id !== '' || selected_scheme?.id !== null" title="Appropriate" @click="appropriate()" class="m-0 fs-9 btn btn-secondary text-white d-inline-block">
                <i class="bi bi-bar-chart-steps"></i> <span class="mobile-none">Appropriate</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <table v-show="switchPageOne == 1" class="table table-lg" style="min-width:1000px;">
        <thead class="bg-light">
          <tr>
            <th>SN.</th>
            <th>Appropriation Name</th>
            <th>Department Code</th>
            <th>Current Percentage (%)</th>
            <th>Total Appropriation Income Across Funding Years(<span>&#8358;</span>)</th>
            <th>Total Balance Across Funding Years (<span>&#8358;</span>) </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(appropriation, i) in selected_scheme.appropriations" :key="i">
            <td>{{ i + 1 }}</td>
            <td>{{ appropriation.name }}</td>
            <td>{{ appropriation.department }}</td>
            <td>{{ appropriation.percentage_dividend }}</td>
            <td>{{ $globals.currency(appropriation.total_collection) }}</td>
            <td>{{ $globals.currency(appropriation.balance) }}</td>
          </tr>
        </tbody>
      </table>
  
      <table v-show="switchPageOne == 2" class="table table-lg" :key="selected_fund_category" style="min-width:1000px;">
        <thead class="">
          <tr>
            <th>SN.</th>
            <th>Appropriation Name</th>
            <th>Department Code</th>
            <th>Appropriation Income (<span>&#8358;</span>) </th>
            <th>Balance (<span>&#8358;</span>) </th>
            <th>#</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(appropriation, i) in selected_month_appropriations" :key="i">
            <td>{{ i + 1 }}</td>
            <td>{{ appropriation.name }}</td>
            <td>{{ appropriation.department }}</td>
            <td>{{ $globals.currency(appropriation_data_summary?.income?.[appropriation.name] ?? 0) }}</td>
            <td>{{ $globals.currency(appropriation_data_summary?.balance?.[appropriation.name]??0) }} </td>
            <td>
              <button @click="appropriationLogPage(appropriation, i)" class="btn me btn-sm btn-success text-white">
                <i class="bi bi-columns-gap" style="color:inherit"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
        <Transition name="fade">
            <div v-if="showModal">
                <sharehoder-modal             
                    :departments="departments"
                    :appropriation_types="appropriation_types"
                    :selected_appropriation="selected_appropriation"
                    @closeModal="showModal = false"
                    @resolveAppropriation="resolveAppropriation"
                />
            </div>
        </Transition>

    </div>
  </template>
  
  <script>
  import SharehoderModal from './Modals/SharehoderModal.vue'
  export default {
    props: {
        appropriation_types:{
        type: Object,
        default: () => [],
      },
      departments:{
        type: Array,
        default: () => [],
      },
      permissions: {
        type: Array,
        default: () => [],
      },
      switchPageOne: {
        default: 0,
      },
      appropriations_history: {
        default: [],
      },
      selected_month_appropriations: {
        default: [],
      },
      selected_scheme: {
        type: Object,
        default: () => ({ id: '', appropriations: [] }),
      },
      schemes:{
        default:[]
      },
      pageSwitched:{
        default:10
      },
      selected_fund_category: {
        default: '',
      },
      appropriation_data_summary: {
        type:Object,
        default:{}
      },
    },
    components:{
        'sharehoder-modal':SharehoderModal
    },
    data() {
      return {
        selected_appropriation: [],
        appropriationHistories: [],
        appropriations: [],
        selected_appropriations_to_appropriate:[],
        showModal:false,
      };
    },
    computed: {
      totalAppropriationPercentageValue() {
        return this.selected_scheme.appropriations.reduce((total, appropriation) => total + appropriation.percentage_dividend, 0);
      },
    },
    watch: {
      'selected_fund_category': function (n, o) {
        this.appropriationHistoriesResolver();    
      },               
    },
    methods: {
        async resolveAppropriation(selected_appropriation) {
            try {
                this.selected_appropriation = selected_appropriation;

                if (!this.selected_scheme) {
                throw new Error("Please select Programme"); //i.e scheme
                }

                if (!this.selected_appropriation.appropriation_type_id) {
                throw new Error("Name field is required");
                }

                if (this.selected_appropriation.department_id.length < 1) {
                throw new Error("Department field is required");
                }

                const schemeIndex = this.$globals.getIndexOf(this.schemes, this.selected_scheme);
                const scheme = this.schemes[schemeIndex];

                scheme.appropriations.push(this.selected_appropriation);

                $("#totalDividend").removeClass("bg-danger text-white");
                $(".errorMsg").remove();

                if (this.selected_appropriation.id !== "") {
                scheme.appropriations.pop();
                }

                this.selected_appropriation.scheme_id = this.selected_scheme.id;

                const res = await postData("/add_appropriation", this.selected_appropriation, true);

                if (res.status === 200) {
                    const responseData = res.data;

                    if (this.selected_appropriation.id === "") {
                        scheme.appropriations.pop();
                        scheme.appropriations.push(responseData.appropriation);
                        showAlert(responseData.msg);
                    } else {
                        showAlert(responseData);
                    }
                    this.showModal = false;
                }
            } catch (error) {
                Swal.fire(error.message);
            }
        },
        selectAllAppropriation(appropriations, e){
            if(e.target.checked){                                                   
                this.selected_appropriations_to_appropriate = appropriations.map((item)=>item.id)
            }else{
                this.selected_appropriations_to_appropriate =[]
            }
        },      
      appropriationHistoriesResolver() {
        this.appropriationHistories = this.appropriations_history?.reduce(
          (grouped, obj) => ({
            ...grouped,
            [obj.fund_category]: [...(grouped[obj.fund_category] || []), obj],
          }), {}
        );
      },
      getAppropriationIncome(name) {
        let total = 0;
  
        if (this.selected_fund_category !== '') {
          const appropriationHistory = this.appropriationHistories[this.selected_fund_category];
  
          if (appropriationHistory) {
            appropriationHistory.forEach(historyItem => {
              const appropriationItem = historyItem.appropriation.find(item => {
                return item.name.toLowerCase() === name.toLowerCase();
              });
  
              if (appropriationItem) {
                total += appropriationItem.amount;
              }
            });
          }
        }
  
        return total;
      },
      canPerformAction(permission) {
        //console.log(this.permissions,222)
        return this.permissions.includes(permission);
      },    
      appropriationModalUpdate(appropriation, index) {
        this.showModal = true 
        this.selected_appropriation = appropriation
      },
      totalPercentage(){
        if(this.selected_appropriations_to_appropriate.length >0){
            let total = 0;
            this.selected_scheme.appropriations.forEach((item) =>{
                if(this.selected_appropriations_to_appropriate.includes(item.id)){
                    total += item.percentage_dividend
                }
                })
            return total.toFixed(2)
        }else{                            
            return 0
        }
    },
      async appropriate(){
        if(this.selected_appropriations_to_appropriate.length <1){
            Swal.fire('select appropriation')
            return false
        }

        if(this.totalPercentage() != 100){
            Swal.fire('Total percentage dividend expected 100%, ' +this.totalPercentage() +'% given')
            return false
        }

        let resp = await Swal.fire({
            title: 'Continue Appropriation?',
            text:'this should take not more than 20 seconds',
            showCancelButton:true,
            confirmButtonText:'Continue',
            cancelButtonText:'Cancel',
        })
    
        if(!resp.isConfirmed){
            return false;
        };               
            

        if(this.selected_scheme.wallet.balance <1){
            Swal.fire('Insufficent balance')
            return false;
        }

        if(this.selected_scheme.appropriations.length <1){
            Swal.fire('No appropriations available')
            return false;
        }

        let res = await postData('/appropriate',{scheme_id:this.selected_scheme.id,appropriation_ids:this.selected_appropriations_to_appropriate},true);
        if(res.status == 200){
            //this.selected_scheme = res.data.scheme
            this.selected_scheme.wallet.balance = 0;                      
            showAlert('Appropriation Completed');
            location.reload();
        }else{
            showAlert('Appropriation Failed Completed','error');
        }
    },
  
      async appropriationLogPage(appropriation, i) {
        this.selected_appropriation = appropriation;
        localStorage.setItem('selected_appropriation', JSON.stringify(appropriation));
        localStorage.setItem('selected_appropriation_index', i);
  
        this.selected_appropriation.index = i;
        this.selected_appropriation_balance = appropriation.wallet?.balance;
        this.$emit('openTransaction', {
          selected_appropriation: this.selected_appropriation,    
        });        
      },
    },
    created() {                
      try {
        if(this.selected_fund_category){
            this.appropriationHistoriesResolver();            
        }
      } catch (e) {
        console.log('Error:' + e);
      }
    },
  };
  </script>
  
  <!-- Add your component-specific styles here if needed -->
  <style scoped>
    /* Add your component-specific styles here if needed */
  </style>
  