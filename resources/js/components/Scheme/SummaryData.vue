<template>
    <div class="row position-relative mx-0">
        <div class="col-lg-6 pb-2 text-white">
            <p v-if="canPerformAction('income')" class="m-0 fs-9">
                <b>{{ selected_scheme?.name }}</b> {{ selected_fund_category }} Income: <span>&#8358;</span>
                 <span v-show="selected_fund_category == ''">{{ $globals.currency(selected_scheme?.total_collection) }}</span>
                 <span v-show="selected_fund_category != ''"> {{ $globals.currency(fund?.total_collection) }} </span>
            </p>
            <p class="m-0 fs-9">Total Income Across the years.: <span>&#8358;</span>
                {{ $globals.currency(selected_scheme?.balance) }} </p>
        </div>

        <div class="col-lg-6 pb-2 text-white">
            <p v-if="canPerformAction('balance')" class="m-0 fs-9"><b>{{ selected_scheme?.name }}</b> Balance:
                <span>&#8358;</span> <span v-if="selected_fund_category == ''">{{ $globals.currency(selected_scheme?.balance) }}</span>
                <span v-show="selected_fund_category != ''">{{ $globals.currency(fund?.balance) }} </span></p>
            <p v-if="canPerformAction('expenditure')" href="#" class="m-0 fs-9 ">
                <span class="display-inline-block me-2">
                    Expenditure: <span>&#8358;</span>
                    <span v-if="getCategoryIncomeBalance == 0">0.00</span>
                    <span v-else>{{ $globals.currency(fund?.total_collection - fund?.balance) }} </span>
                </span>
                <a @click="$emit('openExpenditure',{category_income:fund?.total_collection||0,category_income_balance:fund?.balance||0})" class="d-inline-block fs-8 underline text-white" href="#">View Details
                    <i class="fa fa-caret-right mt-1"></i>
                </a>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        permissions: {
            type: Array,
            default: () => [],
        },
        selected_scheme: Object,
        selected_fund_category: String,
        getCategoryIncomeBalance: Number,
    },
    data(){
        return {
            fund:{}
        }
    },
    watch:{
        'selected_fund_category':function (n,o){
            this.fund = this.selected_scheme.fund_categories?.[this.selected_fund_category]?.[0]
        }
    },
    methods: {
        canPerformAction(permission) {
            return this.permissions.includes(permission);
        },
        openExpenditureDetails() {
            this.$emit('openExpenditure', {
                category_income: this.category_income || 0,
                category_income_balance: this.category_income_balance || 0,
            });
        },
    },
};
</script>
