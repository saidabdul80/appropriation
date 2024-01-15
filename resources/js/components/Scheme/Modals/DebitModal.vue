<template>
    <div class="modal show" id="debit-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel3"><b>{{ appropriation?.name }} / {{ appropriation?.department }}</b> Transactions</h5>
            <button @click="$emit('closeModal')" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3 input-group">
              <span class="input-group-text">Funding Year</span>
              <select @input="monthYearTriggered($event)" class="form-control">
                <option value=""></option>
                <option v-for="month_year in fund_categories" :value="month_year" :selected="appropriation_log2.fund_category" :key="month_year">{{ month_year }}</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="forApp" class="form-label">Select Appropriation</label>
              <select v-model="appropriation" class="form-control">
                <option v-for="ap in appropriations" :key="ap" :value="ap">{{ ap.name }}</option>
              </select>
            </div>
            <div v-if="reloadDynamicData" class="mb-3 multiselect w-100">
              <label for="name" class="form-label">Select Required Fields</label>
              <div id="checkboxesToggler" class="selectBox" @click="showCheckboxes()">
                <input type="text" :value="dynamicDataSelected.join(',').replaceAll('_',' ')" class="form-control no-event" aria-label="size 3 select example" disabled>
                <div class="overSelect no-event"></div>
              </div>
              <div v-show="expanded" id="checkboxes">
                <label v-for="key in data_columns" v-show="dynamic_data[key].show===1" :key="key">
                  <input @change="dynamicDataSelectedFunc" class="me-1" type="checkbox" :value="key" v-model="dynamicDataSelected"> {{ key.replaceAll('_', ' ') }}
                </label>
              </div>
            </div>
            <div v-for="key in Object.keys(appropriation_log2.data ||{})" :key="key" v-show="appropriation_log.data[key].activate===1">
              <div v-if="key !== 'Amount' && key !== 'VAT_%' && key !== 'Withholding_Tax_%' && key !== 'Stamp_Duty_%' && key !== 'Gross_Amount' && key !== 'Total_Taxes'">
                <div class="mb-3">
                  <label for="name" class="form-label"> {{ key.replaceAll('_', ' ') }}</label>
                  <input v-if="appropriation_log.data[key].type === 'number'" step="any" v-model="appropriation_log2.data[key].value" :type="appropriation_log.data[key].type" class="form-control">
                  <input v-else v-model="appropriation_log2.data[key].value" :type="appropriation_log.data[key].type" class="form-control">
                </div>
              </div>
              <div v-if="key==='Amount'">
                <div class="mb-3">
                  <label for="ApplogAmount" class="form-label">Actual Amount (<span class="fs-9 text-dark mb-1">{{ $globals.currency(appropriation_log.data.Amount.value) }}</span>)</label>
                  <div class="input-group mt-1 mb-1" id="ApplogAmount">
                    <span class="input-group-text">&#8358;</span>
                    <input v-model="appropriation_log2.data.Amount.value" id="percentage_dividend" type="number" step="any" class="form-control" aria-label="Amount (to the nearest dollar)">
                    <span v-if="appropriation_log.id === ''" id="schemeBalanceOver" class="input-group-text">{{ $globals.currency(appropriation?.wallet?.balance - calculatedValues.gross) }}</span>
                    <span v-else id="schemeBalanceOver" class="input-group-text">{{ $globals.currency((appropriation?.wallet?.balance + appropriation_log.total_amount) - calculatedValues.gross) }}</span>
                    <span class="d-none">{{ leftAmountApp }}</span>
                  </div>
                  <div id="schemeBalanceOver2" class="alert alert-danger py-0 px-1 text-center fs-9 d-none">Insufficient Balance</div>
                </div>
              </div>
              <div v-if="key === 'VAT_%'">
                <div class="mb-3">
                  <label for="name" class="form-label"> {{ key.replaceAll('_', ' ') }}</label>
                  <div class="input-group mt-1" id="ApplogVat">
                    <span class="input-group-text">%</span>
                    <input v-model="appropriation_log2.data[key].value" :type="appropriation_log.data[key].type" class="form-control">
                    <span class="input-group-text">{{ $globals.currency(calculatedValues.vatCalculate) }}</span>
                  </div>
                </div>
              </div>
              <div v-if="key === 'Withholding_Tax_%'">
                <div class="mb-3">
                  <label for="name" class="form-label"> {{ key.replaceAll('_', ' ') }}</label>
                  <div class="input-group mt-1" id="ApplogWithholding_Tax">
                    <span class="input-group-text">%</span>
                    <input v-model="appropriation_log2.data[key].value" :type="appropriation_log.data[key].type" class="form-control">
                    <span class="input-group-text">{{ $globals.currency(calculatedValues.withholdingCalculate) }}</span>
                  </div>
                </div>
              </div>
              <div v-if="key === 'Stamp_Duty_%'">
                <div class="mb-3">
                  <label for="name" class="form-label mb-0"> {{ key.replaceAll('_', ' ') }}</label>
                  <p class="fs-9 text-danger mb-1">Sub Total 1 (Actual Amount + VAT_% + Withh. tax): {{ $globals.currency(calculatedValues.subTotalCalculate) }}</p>
                  <div class="input-group mt-1 mb-1" id="ApplogStampDuty">
                    <span class="input-group-text">%</span>
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
  export default {
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
        selected_fund_category:''
      };
    },
   async created(){        
    
        if(this.appropriation_log?.id)
        {
            this.appropriation_log2 = this.appropriation_log
            this.selected_fund_category = this.appropriation_log2.fund_category
            await this.requestPreparedData(this.selected_fund_category);            
            this.appropriation =  this.appropriations.find(item=>item.id === this.appropriation_log2.owner_id)
        }else{            
            this.appropriation_log.data = this.dynamic_data            
            this.appropriation_log2.data = this.appropriation_log.data
        }
        this.data_columns = Object.keys(this.dynamic_data);
        this.markSelectedDynamicField();
    },
    computed: {
        calculatedValues() {
            const vatPercentage = this.appropriation_log2.data['VAT_%'].value / 100;
            const withholdingPercentage = this.appropriation_log2.data['Withholding_Tax_%'].value / 100;
            const stampDutyPercentage = this.appropriation_log2.data['Stamp_Duty_%'].value / 100;

            const vatCalculate = vatPercentage * this.appropriation_log2.data['Amount'].value;
            const withholdingCalculate = withholdingPercentage * this.appropriation_log2.data['Amount'].value;
            const subTotalCalculate = vatCalculate + withholdingCalculate + this.appropriation_log2.data['Amount'].value;
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
        return this.appropriation?.wallet?.balance - this.calculatedValues.gross;        
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
        async requestPreparedData(fund_category){
          const res =  await postData('/get_prepared_data', {
                scheme_id: this.selected_scheme_id,
                fund_category: fund_category
            }, true);
            if (res?.status == 200) {                             
                this.$nextTick(() => {                    
                    this.appropriations = res.data.appropriations                                   
                })
            } else {
                showAlert('Something went wrong');
                return false;
            }
        },
        markSelectedDynamicField() {
            const newData = []
            Object.keys(this.appropriation_log.data).forEach(key => {
                if (this.appropriation_log.data[key].activate == 1) {
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
      dynamicDataSelectedFunc() {
            const newData = {
                ...this.appropriation_log.data
            };
            Object.keys(newData).forEach(item => {
                newData[item].activate = 0;
            });

            this.dynamicDataSelected.forEach(item => {
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

            if (this.leftAmountApp < 0) {
                Swal.fire('Insufficent balance')
                return false
            }
            if (this.appropriation_log2.data['Subject'].value == '') {
                Swal.fire('Subject field is required')
                return false
            }

            //this.clearAppropriationGarbages();
            this.appropriation_log2.data['VAT_₦'] = this.calculatedValues.vatCalculate
            this.appropriation_log2.data['Withholding_Tax_₦'] = this.calculatedValues.withholdingCalculate
            this.appropriation_log2.data['Stamp_Duty_₦'] = this.calculatedValues.stampDutyCalculate
            this.appropriation_log.total_amount = this.calculatedValues.gross; 
            let res = await postData('/save_appropriation_transaction', {
                fund_category: this.selected_fund_category,
                owner_id: this.appropriation.id,
                owner_type: 'appropriation',
                transaction: this.appropriation_log2
            }, true);
            if (res.status == 200) {
                if (this.appropriation_log2.id === '') {
                    //   this.appropriation_transactions?.data?.unshift(res.data)
                    showAlert('Transaction Successful');
                    // location.reload()
                } else {
                    //   this.appropriation_transactions.data[this.appropriation_transactions_index] = res.data
                    //location.reload()
                    showAlert('Transaction Updated');
                }
                //let fund_category = this.selected_fund_category;
                //this.selected_fund_category = ''
                /* this.monthYearTriggered();
                this.fetchWalletBalance(this.appropriation.id, 'appropriation'); */
                $('#debit-modal').modal('hide');
            } else {
                showAlert('Something went wrong', 'danger');
                return false;
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
  .modal{
    display: block !important;
    background: #000a;
  }
  </style>
  