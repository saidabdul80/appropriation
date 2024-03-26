<template>
    <div class="mb-5">
        <div v-for="(hist, i) in appropriation_histories.data" :id="'appro_' + i">
            <button @click="printE('appro_' + i)" class="btn btn-sm btn-light pull-right">Print</button>
            <p class="text-white fw-bold">{{ hist.fund_month_year }}, {{ hist.fund_category }}</p>
            <div class="bg-white rounded-lg-tl rounded-lg-tr shadow-lg-only px-3 pt-3">
                 <table  class="table  text-dark mb-0  ">
                        <tbody>
                            <tr>
                                <td>Source: {{ hist.source.name }}</td>
                            <td>Description: {{ hist.description }}</td>
                        </tr>
                        <tr>
                            <td>
                                <b>Total Appropriated (Income): <span>&#8358;</span>{{ $globals.currency( hist.amount) }}</b>
                            </td>
                            <td>Programme: {{ selected_scheme.name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table class="table table-sm bg-white  shadow-sm w-100 rounded-lg-only shadow-lg-only" style="min-width:1000px;">
                <thead class="bg-light">
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Percentage Dividend</th>
                        <th>Amount</th>
                       <!--  <th>Appropriations</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(objs, i) in hist.appropriation">
                        <td>{{ i + 1 }}</td>
                        <td>{{ objs.name }}</td>
                        <td>
                            {{ objs.percentage_dividend }}
                            <!-- <p class="mb-0"><b>Amount:</b> <span>&#8358;</span>{{ $globals.currency(objs.amount) }}</p>
                            <p class="mb-0"><b>Appropriation Date:</b> {{ objs.created_at }}</p>
                            <p v-if="selected_scheme.fund_category == 'month'"><b>Funding Month:</b> {{ objs.fund_month_year }}</p> -->
                        </td>
                        <td>
                            {{ $globals.currency(objs.amount) }}
                           <!--  <table class="table tabl-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Percentage Dividend</th>
                                        <th>Amount (<span>&#8358;</span>)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(obj, i) in objs.appropriation">
                                        <td>{{ obj.name }}</td>
                                        <td>{{ obj.percentage_dividend }}</td>
                                        <td>{{ currency(obj?.amount) }}</td>
                                    </tr>
                                </tbody>
                            </table> -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <pagination 
            :paginationData="appropriation_histories"
            method="post"
            :filters="{
                owner_id: selected_scheme.id,
                owner_type: 'scheme'
            }"
            @onFetch="onFetch"
        />
        <br>
        <br>
        <br>
    </div>
</template>

<script>
import Pagination from './Pagination.vue';

export default {
    props: {
        appropriationHistories: Object,
        selected_scheme: Object,
    },
    data(){
        return {
            appropriation_histories:[]
        }
    },
    created(){
        this.appropriation_histories = this.appropriationHistories
    },
    methods: {
        printE(id) {
            PrintElem(id, this.selected_scheme.name);
        },
        appInfo(obj, key) {
            if (key == 'amount') {
                return obj.appropriation.reduce((previousValue, currentValue) => currentValue.amount + previousValue, 0);
            }
            if (key == 'source') {
                return obj?.source.name;
            }
            if (key == 'description') {
                return obj?.description;
            }
        },
        onFetch(data){            
            this.appropriation_histories = data
        }
    },
    components: { Pagination }
};
</script>

<style scoped>
tr:last-child td{
    border-bottom: none !important;
}
</style>
