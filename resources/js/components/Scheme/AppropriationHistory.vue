<template>
    <div>
        <div v-for="(key,i) in Object.keys(appropriationHistories)" :id="'appro_'+i">
                            <button @click="printE('appro_'+i)" class="btn btn-sm btn-light pull-right">Print</button>
                            <p>{{key}}</p>
                            <table id="myHeader" class="table table-bordered bg-secondary text-white mb-0">
                                <tbody>
                                    <tr>
                                        <td>Source: {{appInfo(appropriationHistories[key],'source')}}</td>
                                        <td>Description: {{appInfo(appropriationHistories[key],'description')}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b> Total Appropriated (Income): <span>&#8358;</span>{{currency(appInfo(appropriationHistories[key],'amount'))}}</b>
                                        </td>
                                        <td>Programme: {{selected_scheme.name}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-sm bg-white table-bordered shadow-sm w-100" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th>S/N</th>
                                        <th>Approprations Details</th>
                                        <th>Approriations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(objs,i) in appropriationHistories[key]">
                                        <td>{{i+1}}</td>
                                        <td>
                                            <p class="mb-0"><b>Amount:</b> <span>&#8358;</span>{{ currency(objs.amount)}}</p>
                                            <p class="mb-0"><b>Appropriation Date:</b> {{objs.created_at}}</p>
                                            <p class="" v-if="selected_scheme.fund_category =='month'"><b>Funding Month:</b> {{objs.fund_month_year}}</p>
                                        </td>
                                        <td>
                                            <table class="table tabl-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Percentage Dividend</th>
                                                        <th>Amount (<span>&#8358;</span>)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(obj,i) in objs.appropriation">
                                                        <td>{{obj.name}}</td>
                                                        <td>{{obj.percentage_dividend}}</td>
                                                        <td>{{currency(obj?.amount)}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
    </div>
</template>