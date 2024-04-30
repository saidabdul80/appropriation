<template>
    <div class="modal show" id="scheme-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered ">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Programme Details ({{ selected_scheme.name }})</h5>
            <button @click="$emit('closeModal')" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body px-0 ">
            <!-- Tabs for switching between update scheme and view transactions -->
            <ul class="nav nav-tabs">
              <li class="nav-item" @click="activeTab = 'add'">
                <a class="nav-link" :class="{ active: activeTab === 'add' }" href="#">New Scheme</a>
              </li>
              <li class="nav-item" @click="activeTab = 'update'">
                <a class="nav-link" :class="{ active: activeTab === 'update' }" href="#">Update Scheme</a>
              </li>
              <li class="nav-item" @click="activeTab = 'transactions'; fetchTransactions()" v-if="selected_scheme.id !== ''">
                <a class="nav-link" :class="{ active: activeTab === 'transactions' }" href="#">View Transactions</a>
              </li>          
            </ul>
  
            <!-- Update Scheme Form -->
            <div v-show="activeTab === 'update'" class="p-3">
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input v-model="selected_scheme.name" type="text" class="form-control" id="name">
              </div>
            </div>
  
            <!-- View Transactions Section -->
            <div v-if="selected_scheme.id !== '' && activeTab === 'transactions'" class="overflow-x pb-5">                              
              <table class="table table-sm fs-8 table-bordered ">
                <tr class="fw-bold">
                    <td>Action</td>
                    <td class="nobreak">Date Created</td>
                    <td class="nobreak">Date Updated</td>
                    <td>Amount</td>
                </tr>
                <tr v-for="(transaction,i) in transactions?.credit?.data" :key="transaction.id">
                  <td>{{ transaction.action=='undo credit'?'Deleted':'Credit' }} 
                    <span v-if="transaction?.state=='  '" class="text-danger" style="font-size: 0.9em;">(Not Appropriated)</span></td>
                  <td>{{ transaction.date_added }}</td>
                  <td>{{ transaction.date_updated}}</td>
                  <td> {{ $globals.currency(transaction.amount) }}</td>
                  <td>                    
                     <button v-if="transaction?.state==='used'" disabled class="btn btn-primary bg-primary pi pi-undo"></button>                   
                     <button @click="rollbackWithConfirmation(transaction.id)" v-else class="btn btn-primary bg-primary pi pi-undo"></button>
                   </td>
                </tr>
              </table>
              <h6 class="mt-5 fw-bold bg-danger px-3 py-2">Deleted Transactions</h6>
              <table class="table table-sm fs-8 table-bordered ">
                <tr class="fw-bold">
                    <td>Action</td>
                    <td class="nobreak">Date Created</td>
                    <td class="nobreak">Date Updated</td>
                    <td>Amount</td>
                </tr>
                <tr v-for="(transaction,i) in transactions?.undo?.data" :key="transaction.id">
                  <td>Deleted</td>
                  <td>{{ transaction.date_added }}</td>
                  <td>{{ transaction.date_updated}}</td>
                  <td> {{ $globals.currency(transaction.amount) }}</td>                 
                </tr>
              </table>
            </div>
          </div>
          <div class="modal-footer" v-if="activeTab === 'update'">
            <button v-if="selected_scheme.id === ''" type="button" @click="addScheme()" class="btn btn-secondary">Add</button>
            <button v-else type="button" @click="addScheme()" class="btn btn-success text-white">Update</button>
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
        default: () => ({ id: '', name: '' }),
      },
    },
    data() {
      return {
        transactions: [],
        activeTab: 'update', // Default to 'update' tab
      };
    },
    created() {
      // Fetch transactions for the selected scheme      
    },
    methods: {
        async rollbackWithConfirmation(transaction_id) {
            const { value: confirmationText } = await Swal.fire({
                title: 'Confirm Undo',
                html: 'Please type <strong class="fs-9"><i>CONFIRM</i></strong> to confirm:',
                input: 'text',
                inputPlaceholder: 'Type "CONFIRM" to proceed',
                inputAttributes: {
                autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Continue',
                cancelButtonText: 'Cancel',
                preConfirm: (text) => {
                if (text !== 'CONFIRM') {
                    Swal.showValidationMessage('Confirmation text does not match.');
                }
                }
            });

            if (confirmationText === 'CONFIRM') {
                await this.performRollback(transaction_id);
            } else {
                Swal.fire('Undo Cancelled', '', 'info');
            }
            },

            async performRollback(transaction_id) {
            try {
                await postData('/undo_fund_programme', { transaction_id });
                Swal.fire('Undo Successful', '', 'success').then(() => {
                location.reload();
                });
            } catch (error) {
                Swal.fire('Undo Failed', 'An error occurred during undo', 'error');
            }
        },
      addScheme() {
        // Implement your logic for adding or updating scheme
        this.$emit('add-scheme', this.selected_scheme);
      },
      async fetchTransactions() {
        // Replace this with your API call to fetch transactions
        // Example: Assume there's an API endpoint /get_transactions
        const response = await postData('/get_transactions',{owner_id:this.selected_scheme.id,'owner_type':'scheme'}, true);
        
        if(response.status == 200){            
            this.transactions =response.data;
        }
      },
    },
  };
  </script>
  
  <!-- Add your component-specific styles here if needed -->
  <style scoped>
    .modal {
      display: block !important;
      background: #000a;
    }
    .overflow-x{
        overflow-x: scroll;
    }
    .nobreak{
        white-space: nowrap;
    }
  </style>
  