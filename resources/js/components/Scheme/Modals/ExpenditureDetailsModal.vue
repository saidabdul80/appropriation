<template>
    <div class="modal show" id="expenditure-details-modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog w-75 modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" id="project1235">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <b>{{ selected_fund_category }} {{ selected_scheme?.name }} Expenditure Details</b>
                    </h5>
                    <button @click="$emit('closeModal')" type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="border-collapse w-full ">
                        <thead class="absolute sm:relative text-gray-600 bg-gray-100">
                            <tr>
                                <th class="text-gray-600">S/N</th>
                                <th class="text-gray-600">Appropriation</th>
                                <th class="text-gray-600">Amount (<span>&#8358;</span>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(appr, i) in expenditure_details" :key="i">
                                <td class="border-t-2 border-white text-gray-600 sm:flexxx sm:inline-block">{{ i + 1 }}
                                </td>
                                <td class="border-t-2 border-white text-gray-600 sm:flexxx sm:inline-block">{{ appr.name
                                    }}</td>
                                <td class="border-t-2 border-white text-gray-600 sm:flexxx sm:inline-block">{{
                                    $globals.currency(appr.expenditure_total_amount) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>
                        <b>Total</b>: <span>&#8358;</span>
                        <!--    <span v-if="getCategoryIncomeBalance == 0">0.00</span> -->
                        <span>{{ $globals.currency(expenditure_amount) }}</span>
                    </p>
                </div>
                <div class="modal-footer">
                    <button @click="printE('project1235')" class="btn btn-sm btn-light pull-right">Print</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        selected_fund_category: {
            type: String,
            default: '',
        },
        selected_scheme: {
            type: Object,
            default: () => ({ name: '' }),
        },
        category_income: {
            type: Number,
            default: 0,
        },
        category_income_balance: {
            type: Number,
            default: 0,
        },
    },
    data() {
        return {
            expenditure_details: [],
            expenditure_amount: 0,
        };
    },
    created() {
        this.expenditureDetails();
    },
    methods: {
        printE(elementId) {
            // Implement your logic for printing
        },
        async expenditureDetails() {
            try {
                let res = await postData('/fetch_expenditures', {
                    scheme_id: this.selected_scheme.id,
                    fund_category: this.selected_fund_category,
                }, true);
                if (res?.status === 200) {
                    this.expenditure_details = res.data;
                } else {
                    showAlert('Something went wrong');
                }
                this.calculateTotalExpenditureAmount();
            } catch (error) {
                console.error('Error fetching expenditure details:', error);
                showAlert('Error fetching expenditure details');
            }
        },
        calculateTotalExpenditureAmount() {

            if (this.expenditure_details.length > 0) {
                this.expenditure_amount = this.expenditure_details.reduce((total, appr) => total + appr.expenditure_total_amount, 0);
            }
        },
    },
    computed() {

    }
};
</script>

<style scoped>
.modal {
    display: block !important;
    background: #000a;
}
</style>
