<template>
    <div class="w-100">
        <!-- ["add_edit_delete_task","add_remove_member",
        "request_approval","mark_completed","view_task_report",
        "update_report","e_s","fund_scheme","projection","appropriate","new_appropriationm",
        "appr_income","appr_current_balance","income","balance","expenditure","general._app._history","debit_fund","report","appro_history"] -->
        <div class="  p-0 d-flex">
            <div class="" role="group" aria-label="Basic example">
                <button v-if="canPerformAction('new_appropriation')" title="New Appropriation"
                    @click="$emit('showAddModal')" class="m-0 fs-9 btn rounded-sm mx-2 color-primary d-inline-block">
                    <i class="bi bi-journals"></i><span class="mobile-none">New Approp.</span>
                </button>
                <button v-if="canPerformAction('projection')" title="Projection" @click="$emit('projectionModal')"
                    class="m-0 fs-9 btn rounded-sm mx-2 color-primary d-inline-block">
                    <i class="bi bi-collection"></i><span class="mobile-none">Projection</span>
                </button>
                <button v-if="canPerformAction('fund_scheme')" title="Fund Programme" @click="$emit('fundModal')"
                    class="m-0 btn fs-9 rounded-sm mx-2 color-primary d-inline-block">
                    <i class="bi bi-database-down"></i><span class="mobile-none">Credit</span>
                </button>
                <button v-if="canPerformAction('debit_fund')" @click="$emit('appropriationModalRemit')" title="Debit Programme"
                    class="m-0 btn fs-9 rounded-sm mx-2 color-primary d-inline-block">
                    <i class="bi bi-database-down"></i><span class="mobile-none">Debit</span>
                </button>
                <button v-if="canPerformAction('report')" title="view report" @click="$emit('report')"
                    class="m-0 btn fs-9 rounded-sm mx-2 color-primary d-inline-block">
                    <i class="bi bi-inboxes"></i><span class="mobile-none">Report</span>
                </button>
            </div>
        </div>
        <!-- <div class="col-lg-3 pb-2 text-white">
            <p v-if="canPerformAction('income')" class="m-0 fs-9">
                <b>{{ selected_scheme.name }}</b> {{ selected_fund_category }} Income: <span>&#8358;</span> <span
                    v-show="selected_fund_category == ''">{{ $globals.currency(selected_scheme.total_collection) }}</span> <span
                    v-show="selected_fund_category != ''"> {{ $globals.currency(category_income) }} </span>
            </p>
            <p class="m-0 fs-9">Available Income for {{ selected_scheme.wallet?.fund_category }} Approp.: <span>&#8358;</span>
                {{ $globals.currency(category_income) }} </p>
        </div>

        <div class="col-lg-3 pb-2 text-white">
            <p v-if="canPerformAction('balance')" class="m-0 fs-9"><b>{{ selected_scheme.name }}</b> Balance:
                <span>&#8358;</span> <span v-if="selected_fund_category == ''">{{ $globals.currency(selected_scheme.balance) }}</span>
                <span v-show="selected_fund_category != ''">{{ $globals.currency(category_income_balance) }} </span></p>
            <p v-if="canPerformAction('expenditure')" href="#" class="m-0 fs-9 ">
                <span class="display-inline-block me-2">
                    Expenditure: <span>&#8358;</span>
                    <span v-if="getCategoryIncomeBalance == 0">0.00</span>
                    <span v-else>{{ $globals.currency(category_income - category_income_balance) }} </span>
                </span>
                <a @click="$emit('openExpenditure',{category_income:category_income||0,category_income_balance:category_income_balance||0})" class="d-inline-block fs-9" href="#">View Details </a>
            </p>
        </div> -->
    </div>
</template>
  
<script>
export default {
    props: {
        permissions: {
            type: Array,
            default: () => [],
        },
        selected_scheme: {
            type: Object,
            default: () => ({ name: '', balance: 0, wallet: { fund_category: '', balance: 0 } }),
        },
        category_income_balance:{
            
        },
        category_income:{
            default:0
        },
        selected_fund_category: {
            type: String,
            default: '',
        },
        appropriation_data_summary:{}
    },
    computed:{
        category_income(){
            return Object.values(this.appropriation_data_summary.income||{}).reduce((acc, amount) => acc + amount, 0)
        },
        category_income_balance(){
            return Object.values(this.appropriation_data_summary.balance||{}).reduce((acc, amount) => acc + amount, 0)
        }
    },
    methods: {       
        canPerformAction(permission) {
            return this.permissions.includes(permission);
        },

        appropriationModalAdd() {
            // Implement your method for handling the 'New Appropriation' button click
        },

        // Define other methods used in your component
    },
};
</script>
  
<style scoped>
.mx-2:first-child{
    margin-left:0px !important ;
}
/* Add your component-specific styles here if needed */</style>
  