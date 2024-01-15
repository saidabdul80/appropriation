<template>
    <div class="modal show" id="add-fund-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Fund {{ selected_scheme.name }}</h5>
            <button @click="$emit('closeModal')" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div v-if="selected_scheme.fund_type == 'api'" class="mb-3">
              <label for="fundDate" class="form-label">Select Fund Month and Year:</label>
              <select @change="fetchFundFunc()" v-model="fund.date" id="fundDate" class="mt-1 w-100 form-control">
                <option value=""></option>
                <option v-for="fundDate in selected_scheme.funds" :key="fundDate.fund_category" class="fs-9" :value="fundDate.fund_category">
                  {{ fundDate.fund_category }}
                </option>
              </select>
            </div>
            <div class="mb-3">
              <label for="amount" class="form-label">Amount
                <span class="fs-8" v-if="fund.amount > 1000">(<span>&#8358;</span> {{ $globals.currency(fund.amount) }})</span>
              </label>
              <input v-if="selected_scheme.fund_type == 'api'" disabled :value="$globals.currency(fund.amount)" type="text" step="any" class="form-control">
              <input v-else v-model="fund.amount" type="number" step="any" class="form-control">
            </div>
            <div class="mb-3">
              <label for="source" class="form-label">Select Source</label>
              <select v-model="fund.source_id" id="source" class="mt-1 w-100 form-control">
                <option v-for="source in selected_scheme.sources" :key="source.id" class="fs-9" :value="source.id">{{ source.name }}</option>
              </select>
            </div>
            <div v-if="selected_scheme.fund_type === 'entry'" class="mb-3">
              <label for="fundDate" class="form-label">Select Fund Month and Year:</label>
              <input v-model="fund.date" type="month" id="fundDate" class="form-control">
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <input v-model="fund.description" type="text" step="any" id="description" class="form-control">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" @click="fundNow()" class="btn btn-success text-white">Fund</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      selected_scheme: {
        type: Object,
        default: () => ({ name: '', fund_type: '', funds: [], sources: [] }),
      },
    },
    data() {
      return {
        fund: {
          date: '',
          amount: 0,
          source_id: '',
          description: '',
        },
      };
    },
    methods: {
        async fetchFundFunc() {
                if (this.fund.date == '') {
                    Swal.fire('Invalid date select')
                    return false
                }

                let res = await postData('fetch_fund', {
                    fund_category: this.fund.date,
                    scheme_id: this.selected_scheme.id
                }, true)
                if (res.status == 200) {
                    this.fund.amount = res.data
                } else {
                    Swal.fire(res.data)
                }
        },
      async fundNow() {
                let $this = this;
                
                if (this.selected_scheme?.id == '' || this.selected_scheme?.id == undefined) {
                    Swal.fire("Please select Programme") //i.e scheme
                    
                    this.$emit('closeModal')
                    return 0
                }

                if (this.fund.amount == '') {
                    Swal.fire('Amount field is required');
                    return false
                }

                if (this.fund.source_id == '') {
                    Swal.fire('Source field is required');
                    return false
                }
                if (this.fund.date == '') {
                    Swal.fire('Fund date field is required');
                    return false
                }

                let resp = await Swal.fire({title: 'Continue Funding?',text: 'this should take not more than 20 seconds',showCancelButton: true,confirmButtonText: 'Continue',cancelButtonText: 'Cancel',})
                if (!resp.isConfirmed) {
                    return false;
                };

                let res = await postData('/fund_programme', {fund_month_year: this.fund.date,amount: this.fund.amount,source_id: this.fund.source_id,description: this.fund.description,scheme_id: this.selected_scheme.id}, true);
                if (res.status == 200) {
                    this.$emit('response', res.data)                    
                   
                    this.$emit('closeModal')
                }


            }, 
    },
  };
  </script>
  
  <style scoped>
  .modal{
    display: block;
    background: #000a;
  }
    /* Add your component-specific styles here if needed */
  </style>
  