<template>
    <div class="modal show" id="debit-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel3"><b>{{ appropriation?.name }} / {{ appropriation?.department }}</b> Transactions</h5>
            <button @click="$emit('closeModal')" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <InlineMessage severity="error" v-if="!$parent?.$parent?.selected_scheme?.id" class="p-1 mb-3">
                <span style="font-size: 0.8em;" class="ms-1"> <b>Note:</b> Please Select a Programme to add a transaction </span>
            </InlineMessage>

            <div class="mb-3 input-group"  v-if="$parent?.$parent?.selected_scheme?.fund_category == 'year'">
              <span class="input-group-text">Funding Year</span>
              <select v-model="selected_fund_category" @input="monthYearTriggered($event)" class="form-control">
                <option value=""></option>
                <option v-for="month_year in fund_categories" :value="month_year" :selected="appropriation_log2.fund_category" :key="month_year">{{ month_year }}</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="forApp" class="form-label">Head Budget (Appropriation).</label>
              <Dropdown v-model="appropriation" class="w-100" :options="appropriations"   optionLabel="name" />
            </div>
            <div v-if="appropriation?.budget_location ==='subhead'" >
                <div class="mb-3">
                    <label for="forApp" class="form-label">Subhead Budget.</label>
                    <Dropdown v-model="selected_subhead" class="w-100" :options="appropriation.subheads"   optionLabel="subhead" />
                </div>

                <div class="mb-3">
                    <label for="forApp" class="form-label">Subhead Budget Activity .</label>
                    <Dropdown v-model="selected_subhead_item" class="w-100" :options="selected_subhead.subhead_budget_items"   optionLabel="subhead_item" />
                </div>
            </div>
            <div v-if="reloadDynamicData" class="mb-3 w-100 d-flex">
              <!-- <button for="name" class="btn btn-sm btn-outline">
                <span class="pi pi-filter me-1"></span>Filter
              </button>               -->
              <MultiSelect filter v-model="dynamicDataSelected" icon="pi pi-filter" display="chip" @update:model-value="dynamicDataSelectedFunc" :options="data_columns"  placeholder="Other Fields (Optional) Filter"
                :maxSelectedLabels="2" class="w-100" /><!--
                  <template #optiongroup="slotProps">
                    <div class="flex align-items-center">
                        <div>{{ slotProps.option }}</div>
                    </div>
                </template>
            </MultiSelect> -->
            <!--   <div id="checkboxesToggler" class="selectBox" @click="showCheckboxes()">
                <input type="text" :value="dynamicDataSelected.join(',').replaceAll('_',' ')" class="form-control no-event" aria-label="size 3 select example" disabled>
                <div class="overSelect no-event"></div>
              </div>
              <div v-show="expanded" id="checkboxes">
                <label v-for="key in data_columns" v-show="dynamic_data[key].show===1" :key="key">
                  <input @change="dynamicDataSelectedFunc" class="me-1" type="checkbox" :value="key" v-model="dynamicDataSelected"> {{ key.replaceAll('_', ' ') }}
                </label>
              </div> -->
            </div>
            <hr class="mt-0">
            <div v-for="key in Object.keys(appropriation_log2.data ||{})" :key="key" v-show="appropriation_log.data[key].activate===1">
              <div v-if="key !== 'Amount' && key !== 'VAT_%' && key !== 'Withholding_Tax_%' && key !== 'Stamp_Duty_%' && key !== 'Gross_Amount' && key !== 'Total_Taxes'">
                <div class="mb-3 position-relative">
                  <label for="name" class="form-label"> {{ key.replaceAll('_', ' ') }}</label>
                  <span @click="removeField(key)" class="pi fs1 close_item" :class="appropriation_log.data[key].required ===0?'pi-times':'pi-ban disabled'" ></span>
                  <input v-if="appropriation_log.data[key].type === 'number'" step="any" v-model="appropriation_log2.data[key].value" :type="appropriation_log.data[key].type" class="form-control">
                  <input v-else v-model="appropriation_log2.data[key].value" :type="appropriation_log.data[key].type" class="form-control">
                </div>
              </div>
              <div v-if="key==='Amount'">
                <div class="mb-3 ">
                  <label for="ApplogAmount" class="form-label">Actual Amount (<span class="fs-9 text-dark mb-1">{{ $globals.currency(appropriation_log.data.Amount.value) }}</span>)</label>
                  <div class="input-group position-relative mt-1 mb-1" id="ApplogAmount">
                    <span class="input-group-text">&#8358;</span>
                    <span @click="removeField(key)" class="pi fs1 close_item" :class="appropriation_log.data[key].required ===0?'pi-times':'pi-ban'" ></span>
                    <input v-model="appropriation_log2.data.Amount.value" id="percentage_dividend" type="number" step="any" class="form-control" aria-label="Amount (to the nearest dollar)">
                    <span v-if="appropriation_log.id === ''" id="schemeBalanceOver" class="input-group-text">{{availableBalance <0?'0.00': $globals.currency(availableBalance - calculatedValues.gross) }}</span>
                    <span v-else id="schemeBalanceOver" class="input-group-text">{{ $globals.currency((availableBalance + appropriation_log.total_amount) - calculatedValues.gross) }}</span>
                    <span class="d-none">{{ leftAmountApp <1?0.00:leftAmountApp }}</span>
                  </div>
                  <div id="schemeBalanceOver2" class="alert alert-danger py-0 px-1 text-center fs-9 d-none">Insufficient Balance</div>
                </div>
              </div>
              <div v-if="key === 'VAT_%'">
                <div class="mb-3 ">
                  <label for="name" class="form-label"> {{ key.replaceAll('_', ' ') }}</label>
                  <div class="input-group mt-1 position-relative" id="ApplogVat">
                    <span class="input-group-text">%</span>
                    <span @click="removeField(key)" class="pi fs1 close_item" :class="appropriation_log.data[key].required ===0?'pi-times':'pi-ban'" ></span>
                    <input v-model="appropriation_log2.data[key].value" :type="appropriation_log.data[key].type" class="form-control">
                    <span class="input-group-text">{{ $globals.currency(calculatedValues.vatCalculate) }}</span>
                  </div>
                </div>
              </div>
              <div v-if="key === 'Withholding_Tax_%'">
                <div class="mb-3 position-relative">
                  <label for="name" class="form-label"> {{ key.replaceAll('_', ' ') }}</label>
                  <div class="input-group mt-1 position-relative" id="ApplogWithholding_Tax">
                    <span class="input-group-text">%</span>
                    <span @click="removeField(key)" class="pi fs1 close_item" :class="appropriation_log.data[key].required ===0?'pi-times':'pi-ban'" ></span>
                    <input v-model="appropriation_log2.data[key].value" :type="appropriation_log.data[key].type" class="form-control">
                    <span class="input-group-text">{{ $globals.currency(calculatedValues.withholdingCalculate) }}</span>
                  </div>
                </div>
              </div>
              <div v-if="key === 'Stamp_Duty_%'">
                <div class="mb-3 position-relative">
                  <label for="name" class="form-label mb-0"> {{ key.replaceAll('_', ' ') }}</label>
                  <p class="fs-9 text-danger mb-1">Sub Total 1 (Actual Amount + VAT_% + Withh. tax): {{ $globals.currency(calculatedValues.subTotalCalculate) }}</p>
                  <div class="input-group mt-1 mb-1 position-relative" id="ApplogStampDuty">
                    <span class="input-group-text">%</span>
                    <span @click="removeField(key)" class="pi fs1 close_item" :class="appropriation_log.data[key].required ===0?'pi-times':'pi-ban disabled'" ></span>
                    <input v-model="appropriation_log2.data[key].value" :type="appropriation_log.data[key].type" class="form-control">
                    <span class="input-group-text">{{ $globals.currency(calculatedValues.stampDutyCalculate) }}</span>
                  </div>
                  <p class="fs-9 text-danger mb-1">Sub Total 2 (Actual Amount + VAT_% + Withh. + Stamp D. tax): {{ $globals.currency(calculatedValues.subTotalCalculate + calculatedValues.stampDutyCalculate) }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <p>Gross: {{ $globals.currency(calculatedValues.gross) }}</p>
            <button v-if="appropriation_log.id === ''" type="button" @click="transact" class="btn btn-secondary">Save</button>
            <button v-else type="button" @click="transact" class="btn btn-primary text-white">Update</button>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>


  import MultiSelect from 'primevue/multiselect';
  import Dropdown from 'primevue/dropdown';
  import InlineMessage from 'primevue/InlineMessage';

  export default {
    components:{
      MultiSelect,
      Dropdown,
      InlineMessage
    },
    props: {
     /*  appropriation: {
        type: Object,
        default: () => (),
      }, */
      fund_categories:{
        default:[]
      },
      selected_scheme_id:{
        default:0,
      },
      appropriation_log: {
        type: Object,
        default: () => ({ data: {}, id: '' }),
      },
    },
    data() {
      return {
        appropriation:null,
        appropriations:{
          name: '',
          department: '',
          wallet: { balance: 0 },
        },
        appropriation_log2: {
          data: {},
        },
        dynamicDataSelected:[],
        dynamic_data:this.$globals.dynamic_data,
        expanded: false,
        reloadDynamicData: true,
        selected_fund_category:'',
        selected_subhead:{},
        selected_subhead_item:{}
      };
    },
   async created(){

        if(this.appropriation_log?.id)
        {
            this.appropriation_log2 = {...this.appropriation_log}
            this.selected_fund_category = this.appropriation_log2.fund_category
            await this.requestPreparedData(this.selected_fund_category);
            this.appropriation =  this.appropriations.find(item=>item.id === this.appropriation_log2.owner_id)
            this.selected_subhead = this.appropriation?.subheads?.find(item=>item.id === this.appropriation_log2.subhead_id)
            this.selected_subhead_item =this.selected_subhead?.subhead_budget_items?.find(item=>item.id === this.appropriation_log2.subhead_item_id)
        }else{
            const fundCategories = Object.values(this.fund_categories || {});
            if(fundCategories.length >0){
              this.selected_fund_category = fundCategories[fundCategories.length-1];
              await this.requestPreparedData(this.selected_fund_category);
              this.appropriation =  this.appropriations.find(item=>item.id === this.appropriation_log2.owner_id)
            }

            this.appropriation_log.data = this.dynamic_data
            this.appropriation_log2.data = this.appropriation_log.data
        }
        // this.data_columns = Object.keys(this.dynamic_data);
        this.data_columns = Object.keys(this.dynamic_data).filter(key => this.dynamic_data[key].required === 0);
        this.data_columns = this.data_columns.filter(key => this.dynamic_data[key].show === 1);
        this.markSelectedDynamicField();
        if($parent?.$parent?.selected_scheme?.fund_category == 'month'){
            this.requestPreparedData()
        }
    },
    computed: {
        calculatedValues() {
            const vatPercentage = this.appropriation_log2.data['VAT_%']?.value / 100;
            const withholdingPercentage = this.appropriation_log2.data['Withholding_Tax_%']?.value / 100;
            const stampDutyPercentage = this.appropriation_log2.data['Stamp_Duty_%']?.value / 100;

            const vatCalculate = vatPercentage * this.appropriation_log2.data['Amount']?.value;
            const withholdingCalculate = withholdingPercentage * this.appropriation_log2.data['Amount']?.value;
            const subTotalCalculate = vatCalculate + withholdingCalculate + this.appropriation_log2.data['Amount']?.value;
            const stampDutyCalculate = stampDutyPercentage * subTotalCalculate;

            const gross = stampDutyCalculate +
                vatCalculate +
                withholdingCalculate +
                this.appropriation_log2.data['Amount'].value +
                this.appropriation_log2.data['Trx_Charges'].value;

            const totalTaxes = vatCalculate + withholdingCalculate + stampDutyCalculate;

            return {
                vatCalculate,
                withholdingCalculate,
                subTotalCalculate,
                stampDutyCalculate,
                gross,
                totalTaxes
            };
        },
      leftAmountApp() {
        return this.availableBalance - this.calculatedValues.gross;
      },
      availableBalance() {
        if (this.appropriation && this.appropriation.budget_location === 'subhead' && this.selected_subhead_item) {
            return this.selected_subhead_item.balance;
        } else if (this.appropriation && this.appropriation.wallet) {
            return this.appropriation.wallet.balance;
        }
        return 0;
      },
    },
    watch: {
      'appropriation_log2.data.Amount.value': function (newValue, oldValue) {
        // Implement your watch logic for Amount
      },
    },
    methods: {
        async monthYearTriggered(e) { //1 means from the right source
            this.selected_fund_category = e.target.value
            this.requestPreparedData(this.selected_fund_category)
        },
        async requestPreparedData(fund_category=null){
          const res =  await postData('/get_appropriations', {
                scheme_id: this.selected_scheme_id,
                fund_category: fund_category
            }, true);
            if (res?.status == 200) {
                this.$nextTick(() => {
                    this.appropriations = res.data
                })
            } else {
                showAlert('Something went wrong');
                return false;
            }
        },
        markSelectedDynamicField() {
            const newData = []
            Object.keys(this.appropriation_log.data).forEach(key => {
                if (this.appropriation_log.data[key].activate == 1 && this.appropriation_log.data[key].required === 0) {
                    newData.push(key)
                }
            });
            this.dynamicDataSelected = newData
        },
      handleMonthYearTriggered(event) {
        // Implement your handleMonthYearTriggered logic
      },
      showCheckboxes() {
        this.expanded = !this.expanded;
      },
      removeField(key){
        this.dynamicDataSelected.splice(this.dynamicDataSelected.indexOf(key),1);
        this.dynamicDataSelectedFunc(this.dynamicDataSelected)
      },
      dynamicDataSelectedFunc(e) {
             const newData = {
                ...this.appropriation_log.data
            };
            Object.keys(newData).forEach(item => {
              if(newData[item].required ===0 )         {
                newData[item].activate = 0;
              }
            });

            e.forEach(item => {
                newData[item].activate = 1;
            });
            this.appropriation_log.data = newData;
     },
     async transact() {
            let resp = await Swal.fire({
                title: 'Continue transaction?',
                text: 'this should take not more than 20 seconds',
                showCancelButton: true,
                confirmButtonText: 'Continue',
                cancelButtonText: 'Cancel',
            })

            if (!resp.isConfirmed) {
                return false;
            };

            if (this.leftAmountApp < 0 ) {
                Swal.fire('Insufficent balance')
                return false
            }
            if (this.appropriation_log2.data['Subject'].value == '') {
                Swal.fire('Subject field is required')
                return false
            }
            try{
                //this.clearAppropriationGarbages();
                this.appropriation_log2.data['VAT_₦'] = this.calculatedValues.vatCalculate
                this.appropriation_log2.data['Withholding_Tax_₦'] = this.calculatedValues.withholdingCalculate
                this.appropriation_log2.data['Stamp_Duty_₦'] = this.calculatedValues.stampDutyCalculate
                this.appropriation_log2.data['Gross_Amount'] = this.calculatedValues.gross;
                this.appropriation_log2.total_amount = this.calculatedValues.gross;

                let res = await postData('/save_appropriation_transaction', {
                    fund_category: this.selected_fund_category,
                    owner_id: this.appropriation.id,
                    owner_type: 'appropriation',
                    subhead_id: this.selected_subhead.id,
                    subhead_item_id: this.selected_subhead_item.id,
                    transaction: this.appropriation_log2
                }, false);
                $('#debit-modal').modal('hide');
            }catch(e){
                Swal.fire(e)
                console.log(e)
            }
        },

    },
    mounted(){
        try{
            $('#debit-modal').on("click", (e)=> {
                let $info = $('#checkboxes');
                if (!$info.is(e.target) && $info.has(e.target).length === 0) {
                    if (e.target.id == 'checkboxesToggler') {
                        this.expanded = true
                    } else {
                        this.expanded = false
                    }
                }
            });
        }catch(e){

        }

    }
  };
  </script>

  <style scoped>
  .disabled {
    user-select: none;
    pointer-events: none;
    cursor:not-allowed;
  }
  input:focus{
    outline: 0px !important;
    box-shadow: none !important;
  }
  .modal{
    display: block !important;
    background: #000a;
  }
  select{
    border: 1px solid #ccc;
  }
  .fs1{
    font-size: 0.7em;
  }
  .close_item:hover{
    background-color: #b05;
  }
  .close_item{
    position: absolute;
    left: -10px;
    height: 43px;
    color:white;
    display: flex;
    align-items: center;
    background-color: #c35;
    padding:3px;
    z-index: 1;
    border-radius: 5px 0px 0px 5px !important;
    cursor: pointer;
  }
  input, select{
    height: 43px;
  }
  </style>

