<template>
    <div>
      <div class="btn-group" role="group" aria-label="Basic example2">
        <button @click="$emit('switch-page',1)" class="btn fs-9 btn-primary text-white" style="">Back to Appropriation</button>
      </div>
      <h4 class="text-center"><b>{{ selected_appropriation.name }} / {{ selected_appropriation.department }}</b> Transactions</h4>
      <p class="inline-block mb-2 me-4"><b>Balance:</b><span>&#8358;</span> {{ $globals.currency(selected_appropriation_balance) }}</p>
      <p class="inline-block mb-2"><b>Total Expenditure: <span>&#8358;</span></b> {{ $globals.currency(total_expenditure_appropriation) }}</p>
      <div style="overflow: auto;" >        
        <table class="fs-8 table-bordered transactions-tables table table-sm table-hover" style="width:130%">
          <thead>
            <tr>
              <th>S/N</th>
              <th v-for="header in Object.keys($globals.dynamic_data)">
                {{ header.replaceAll('_', ' ') }}
              </th>
              <th>Total Amount</th>
              <th>#</th>
            </tr>
          </thead>
          <tbody>
            <!-- {{ resetTotalExpenditureForAppropriation() }} -->
            <tr v-for="(appr, i) in transactions?.data" >
              <td>{{ parseInt(i + 1) }} </td>
              <td v-for="key in Object.keys(appr.data)">
                <span v-if="key.includes('₦')">
                  {{ $globals.currency(appr.data[key]) }}
                </span>
                <span v-else>
                  <span v-if="appr.data[key].type =='number'">{{ $globals.currency(appr.data[key].value) }}</span>
                  <span v-else>{{ appr.data[key].value }}</span>
                </span>
              </td>
              <td>
                <i style="visibility: hidden;"> {{ i == 0 ? (total_expenditure_appropriation = 0) : '' }}</i> {{ $globals.currency(appr.amount) }} </td>
              <td>
                <button @click="editAppropriationTransaction(appr, i)" class="btn btn-info text-white btn-sm me-1"><i class="fs-9 bi bi-pencil"></i></button>
                <button @click="deleteAppropriationTransaction(appr, i)" class="btn btn-danger btn-sm"><i class="fs-9 bi bi-trash"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      agency_name:{
        default:''
      },
      fund_category:{
        default:''
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
    data(){
        return {
            selected_appropriation_balance:0,            
            transactions:{ data: [] }
        }
    },
    computed:{
        total_expenditure_appropriation(){
            return this.transactions.data.reduce((accumulator, currentTransaction) => accumulator + currentTransaction.amount, 0)
        }
    },
    async created(){                    
        await this.fetchWalletBalance()
        await this.fetchTransactions()
    },
    methods: {
        editAppropriationTransaction(appr,i){               
            this.$emit('openDebitModal',{
                transaction: {...appr},
                index:i
            })
            //this.markSelectedDynamicField()
        },
        async fetchTransactions(){
            let res = await postData('/get_transactions', {
                owner_id: this.selected_appropriation.id,
                owner_type: 'appropriation',
                fund_category: this.fund_category,
                }, true);
        
                if (res.status == 200) { 
                    this.transactions =res.data         
                    setTimeout(()=>{                        
                        this.iniTableTransaction()
                    },100)  
                } else {
                showAlert('Something went wrong');
                return false;
                }
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
        const confirmText =  appr.data.Subject.value + ' of '+ appr.amount ;
        const { value: confirmationText } = await Swal.fire({
            title: 'Continue Delete?',
            html: 'Please type <strong class="fs-9"><i>' +confirmText+'</i></strong> to confirm:',
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

        $('.transactions-tables').DataTable({
            dom: 'Bfrtip',
            destroy: true,
            buttons: [{
                extend: 'print',
                exportOptions: {
                    stripHtml: false
                },
                title: this.agency_name,
                customize: (win)=> {
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
    mounted(){
        this.$nextTick(()=>{

        })

    }
  };
  </script>
  
  <!-- Add your component-specific styles here if needed -->
  <style scoped>
    /* Add your component-specific styles here if needed */
  </style>
  
