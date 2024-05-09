<template>
    <div class="">
      <!--   <InlineMessage severity="info" class="p-1 mb-3">
            <span style="font-size: 0.8em;" class="ms-1"> <b>Note:</b> Ensure fund already available on the selected
                Head i.e Appropriation must have been completed </span>
        </InlineMessage> -->
        <div class="row w-100 mx-auto text-dark">
            <div class="col-md-6 mb-2">
                <label for="name" class="form-label">Programmes</label>
                <!--  <Dropdown v-model="selected_scheme" @change="fetchFundYear()" :options="schemes" optionLabel="name"
                    placeholder="Select a Programme" class="w-100 mb-3" /> -->
                <Dropdown @change="fetchFundYear()" v-model="selectedScheme" :options="schemes"
                    placeholder="Select a Scheme" class="w-100 ">
                    <template #value="slotProps">
                        <div v-if="slotProps.value?.name" class="flex align-items-center">
                            <div style="font-size: 12px;font-weight: bolder;">{{ slotProps.value.name }} <span>
                                    (&#8358;{{ $globals.currency(slotProps.value?.wallet?.balance) }})</span> </div>
                            <div style="font-size: 10px;">Programme (Current Programme fund )</div>
                        </div>
                        <span v-else>
                            Select Programme
                        </span>
                    </template>

                    <template #option="slotProps">
                        <div>{{ slotProps?.option?.name }} ({{ $globals.currency(slotProps.option?.wallet?.balance) }})
                        </div>
                    </template>
                </Dropdown>
            </div>
            <div class="col-md-6 mb-2 text-dark">
                <label for="name" class="form-label">Funding Year</label>
                {{ monthSelected }}
                <month-year-selector v-model="monthSelected" :fund_categories="fund_categories"
                    @change="loadSubheadBudgets" />
                <!-- <Dropdown v-model="selected_scheme" :options="schemes" optionLabel="name" placeholder="Select a Department"
                class="w-100 mb-3" /> -->
            </div>
            <div class="col-md-6">
                <label for="name" class="form-label mb-0">Head Name</label>
                <Dropdown @change="loadSubheadBudgets()" v-model="selected_appropriation"
                    :options="selected_scheme.appropriations" placeholder="Select a Scheme" class="w-100 ">
                    <template #value="slotProps">
                        <div v-if="slotProps.value?.name" class="flex align-items-center" :class="(slotProps.value?.main_balance) <0?'text-danger':''">
                            <div style="font-size: 12px;font-weight: bolder;">{{ slotProps.value.name }} <span>
                                    (&#8358;{{ $globals.currency(slotProps.value?.main_balance) }})</span> </div>
                            <div style="font-size: 10px;">Head with Current Balance</div>
                        </div>
                        <span v-else>
                            Select a Head
                        </span>
                    </template>

                    <template #option="slotProps">
                        <div>{{ slotProps?.option?.name }} ({{$globals.currency(slotProps.option?.main_balance)}})
                        </div>
                    </template>
                </Dropdown>
            </div>
            <div class="col-md-6 text-dark">
                <label for="name" class="form-label mb-0">Subhead Budget Name</label>
                <Dropdown @change="loadSubheadBudgetItems()" v-model="selected_sub_head_budget"
                        :options="sub_head_budgets" placeholder="Select a Budget" class="w-100">
                    <template #value="{ value }">
                        <div v-if="value?.subhead" class="flex align-items-center" :class="(value?.amount - getTotalAmountSubheadBudget) < 0 ? 'text-danger' : ''">
                            <div style="font-size: 12px;font-weight: bolder;">{{ value.subhead }}
                                <span v-if="value.subhead_id === selected_sub_head_budget?.subhead_id">
                                    (&#8358;{{ $globals.currency(value?.amount - getTotalAmountSubheadBudget) }})
                                </span>
                                <span v-else>
                                    (&#8358;{{ $globals.currency(value?.amount) }})
                                </span>
                            </div>
                            <div style="font-size: 10px;">Subhead Budget Current Balance</div>
                        </div>
                        <span v-else>
                            Select a Subhead Budget
                        </span>
                    </template>

                    <template #option="{ option }">
                        <div v-if="option?.subhead" class="d-flex">
                            <span class="me-2">{{ option?.subhead }}  ({{$globals.currency(option?.amount)}})</span>
                        </div>
                    </template>
                </Dropdown>
            </div>
            <!--SUBHEAD BUDGET ITEMS  -->
            <div class="col-md-12 mt-3" >
                <DataTable v-model:expandedRows="expandedRows" v-model:editingRows="editingRows"  editMode="row"  @row-edit-save="saveSubheadBudgetItem" :value="sub_head_budget_items" dataKey="id" ref="dt">
                        <template #header>
                            <div style="text-align: left">
                                <Button class="btn btn-success" @click="exportCSV('dt')">Export</Button>
                            </div>
                        </template>
                        <Column expander style="width: 5rem" />
                        <Column header="#">
                            <template #body="slotProps">
                                {{ slotProps.index +1 }}
                            </template>
                        </Column>
                        <Column field="subhead_item" header="Activity Name">
                            <template #body="{ data, field }">{{ data[field] }}</template>
                            <template #editor="{ data, field }">
                                <BaseItemSelect v-model="data['item_name_id']" url="subhead_item_name" optionLabel="name"
                                    optionValue="id" />
                            </template>
                        </Column>
                        <Column field="amount" header="Budget" >
                            <template #body="{ data, field }">{{ $globals.currency(data[field]) }}</template>
                            <template #editor="{data, field}">
                                <InputText v-model="data[field]" class="p-1 w-100" />
                            </template>
                        </Column>
                        <Column field="balance" header="Balance" :body="$globals.currency">
                            <template #body="{ data, field }">{{ $globals.currency(data[field]) }}</template>
                        </Column>
                        <Column :rowEditor="true" style="width: 10%; min-width: 8rem" bodyStyle="text-align:center"></Column>
                        <Column>
                            <template #body="slotProps">
                                <Button icon="pi pi-reply" title="Make Virement" class="p-1 p-button- rounded p-button-success" @click="promptVirement(slotProps.data)"></Button>
                            </template>
                        </Column>
                        <Column>
                            <template #body="slotProps">
                                <button @click="removeItem(slotProps.data,slotProps.index)"
                                            class="btn btn-white btn-outline-primary py-2 btn-sm py-21 rounded-circle"><span
                                                class="pi pi-trash "></span> </button>
                            </template>
                        </Column>
                        <Column field="output" header="Out Put" />
                        <Column field="outcome" header="Out Come" />
                        <template #expansion="slotProps">
                            <div class="border rounded-3 mb-4 w-75 mx-auto">
                                <h6 class="text-left p-3 font-bold">Virments</h6>
                                <DataTable :value="slotProps.data.virments" v-if="slotProps.data.virments.length > 0">
                                    <Column field="" header="#" :body="(_, { index }) => index + 1" />
                                    <Column field="destination" header="Destination"></Column>
                                    <Column field="amount" header="Amount" >
                                        <template #body="{ data, field }">{{ $globals.currency(data[field]) }}</template>
                                        <template #editor="{data}">
                                            <InputText v-model="data[amount]" class="p-1 w-100" />
                                        </template>
                                    </Column>
                                </DataTable>
                                <p v-else class="p-3">No Virments</p>
                            </div>
                        </template>
                    </DataTable>

             <!--    <table class="table table-bordered w-100 table-condensed">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Subhead</th>
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(cat, index) in sub_head_budget_items" :key="index" style="font-size: 14px;">
                            <td>{{ index + 1 }}</td>
                            <td>
                                <span v-if="!cat?.isEditing">{{ cat.subhead_item }}</span>
                                    <BaseItemSelect v-else v-model="cat.item_name_id" url="subhead_item_name" optionLabel="name"
                                    optionValue="id" />
                            </td>
                            <td>
                                <span v-if="!cat?.isEditing">{{$globals.currency(cat.amount) }}</span>
                                <InputText v-else v-model="cat.amount" class="p-1 w-100" />
                            </td>
                            <td>
                                <span>{{$globals.currency(cat.balance) }}</span>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-2 col-md-5 px-2" v-if="!cat?.isEditing">
                                        <button @click="promptVirement(cat)"
                                            class="btn btn-white btn-outline-primary py-2 btn-sm py-21 rounded-circle"><span
                                                class="pi pi-pencil"></span> </button>
                                    </div>
                                    <div class="col-2 col-md-5 px-2" v-else>
                                        <button @click="saveSubheadBudgetItem(cat)"
                                            class="btn btn-white btn-outline-success btn-sm py-21 rounded-circle"><span
                                                :class="cat?.isLoading ? 'pi pi-spin pi-spinner' : 'pi pi-check'"></span>
                                        </button>
                                    </div>
                                    <div class="col-2 col-md-5" v-if="cat?.isEditing">
                                        <button @click="removeItem(cat,index)"
                                            class="btn btn-white btn-outline-primary py-2 btn-sm py-21 rounded-circle"><span
                                                class="pi pi-times "></span> </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table> -->
            </div>
        </div>
        <Teleport to="#modalFooterConfig">
            <Button @click="addBudget()" class="rounded rounded-right" icon="pi pi-plus" />
        </Teleport>
        <!-- <button v-if="selected_appropriation !== null" type="button" @click="addAppropriation()"
            class="btn btn-secondary">Update</button>         -->
    </div>
</template>

<script>
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import InlineMessage from 'primevue/InlineMessage';
import Button from 'primevue/button';
import MonthYearSelector from '../MonthYearSelector.vue';
import BaseItemSelect from './BaseItemSelect.vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
export default {
    components: {
        Dropdown,
        InputText,
        MonthYearSelector,
        InlineMessage,
        InputGroup,
        InputGroupAddon,
        Button,
        BaseItemSelect,
        ColumnGroup,
        Column,
        Row,
        DataTable,

    },

    data() {
        return {
            selected_appropriation: null,
            sub_head_budgets: [],
            sub_head_budget_items:[],
            subheads: [],
            schemes: [],
            selected_scheme: null,
            fund_categories: [],
            monthSelected: null,
            selected_sub_head_budget:null,
            expandedRows:null,
            editingRows:null
        }
    },
    async created() {
        this.selected_scheme = { ...this.$parent.$parent.selected_scheme }
        this.schemes = [...this.$parent.$parent.schemes];
        await this.getAllSubhead();
        this.$emit('oncompleted', true);
    },
    computed:{
        getTotalAmountSubheadBudget(){
            if (Array.isArray(this.sub_head_budget_items)) {
                return this.sub_head_budget_items.reduce((total, item) => {
                    return total + (Number(item.amount) || 0);
                }, 0);
            }
            return 0;
        }
    },
    methods: {
        async removeItem(cat,index){
            if(cat?.id, index){
                cat.isEditing = false;
                await postData('sub_head_budget_item/delete/'+cat.id)
            }
            this.sub_head_budget_items.splice(index,1)
        },
        async fetchFundYear() {
            const fundResponse = await postData('/get_fund_categories', {
                scheme_id: this.selected_scheme.id
            }, true)
            if (fundResponse.data) {
                this.fund_categories = fundResponse.data?.map(item => item.fund_category);;
            }
        },
        addBudget() {
            if (this.selected_appropriation == null) {
                Swal.fire('Select a Subhead Budget');
                return false;
            }
            this.sub_head_budget_items.push({
                subhead_budget_id: this.selected_sub_head_budget?.id,
                item_name_id:'',
                amount: '',
                isEditing: true,
            });
        },
        async getAllSubhead() {
            try {
                let res = await getData('subhead');
                this.subheads = res.data;
            } catch (error) {
                console.error("Failed to fetch subheads", error);
                Swal.fire("Failed to fetch subheads");
            }
        },
        async loadSubheadBudgets() {
            try {
                this.$emit('isLoading', true)
                let res = await getData(`sub_head_budget/appropriation/${this.selected_appropriation?.id}/${this.monthSelected}`);
                this.sub_head_budgets = res?.data || [];
                this.$emit('isLoading', false)
            } catch (error) {
                this.$emit('isLoading', false)
                console.error("Failed to load budgets", error);
                Swal.fire("Failed to load budgets");
            }
        },
        async promptVirement(subHeadBudgetItem) {
            const { value: isVirement } = await Swal.fire({
                title: 'Virement Confirmation',
                text: 'Is this a virement?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            });
            console.log(isVirement)
            if (isVirement) {
                this.handleVirement(subHeadBudgetItem);
            } else {
               /*  // Continue with regular edit
                DomHandler.find(document.body, '.p-row-editor-init')[2].click();
 */
                subHeadBudgetItem.isEditing = true;
            }
        },

        async handleVirement(subHeadBudgetItem) {
            try {
                // Fetch all subhead budget items except the selected one


                // Filter out the current item
                const filteredItems = this.sub_head_budget_items.filter(item => item.id !== subHeadBudgetItem.id);

                // Prompt user to select destination subhead budget item
                const { value: { selectedDestination, transferAmount } } = await Swal.fire({
                title: 'Virement Confirmation',
                html:
                    `<div>
                        <label for="destination" class='text-left'>Select Destination:</label>
                        <select id="destination" class="form-control border mb-3">
                            ${filteredItems.map(item => `<option value="${item.id}">${item.subhead_item} - (${item.amount})</option>`).join('')}
                        </select>
                        <label for="amount" class='text-left'>Amount to Transfer:</label>
                        <input id="amount" type="number" class="form-control">
                    </div>`,
                focusConfirm: false,
                preConfirm: async () => {
                    const selectedDestination = document.getElementById('destination').value;
                    const transferAmount = document.getElementById('amount').value;
                    const res = await postData('sub_head_budget_item/virement', {
                        source_id: subHeadBudgetItem.id,
                        destination_id: selectedDestination,
                        amount: parseFloat(transferAmount)
                    });
                    if(res !== 'success'){
                        Swal.showValidationMessage(`Request failed`);
                    }else{

                        this.loadSubheadBudgetItems()
                        return { selectedDestination, transferAmount };
                    }
                },
                showCancelButton: true,
                didOpen: () => {
                    // Set default amount to transfer as the current amount
                    document.getElementById('amount').value = 0;
                },
                inputValidator: (value) => {
                    if (!value.selectedDestination) {
                        return 'You need to select a destination';
                    }
                    if (!value.transferAmount || value.transferAmount <= 0) {
                        return 'Please enter a valid amount to transfer';
                    }
                }
            });

        } catch (error) {
            //console.error("Failed to handle virement", error);
            Swal.fire("Failed to handle virement");
        }
        },

        async loadSubheadBudgetItems() {
            try {
                this.$emit('isLoading', true)
                let res = await getData(`sub_head_budget_item/subhead_budget/${this.selected_sub_head_budget?.id}`);
                this.sub_head_budget_items = res?.data || [];
                this.$emit('isLoading', false)
            } catch (error) {
                this.$emit('isLoading', false)
                console.error("Failed to load budgets", error);
                Swal.fire("Failed to load budgets");
            }
        },
        async saveSubheadBudgetItem(data) {
            const subHeadBudgetItem = data.newData

            if (!subHeadBudgetItem.amount || subHeadBudgetItem.amount === '') {
                Swal.fire("Amount is required");
                return false;
            }

            if (!subHeadBudgetItem.subhead_budget_id || subHeadBudgetItem.subhead_budget_id === '') {
                Swal.fire("Subhead Budget is required");
                return false;
            }
            subHeadBudgetItem.amount = parseFloat(subHeadBudgetItem.amount);
            subHeadBudgetItem.isLoading = true;
            try {
                await postData('sub_head_budget_item/save', subHeadBudgetItem);
                await this.loadSubheadBudgetItems()
                subHeadBudgetItem.isEditing = false;  // Assume you want to stop editing on save
            } catch (error) {
                console.error("Failed to save budget", error);
                Swal.fire("Failed to save budget");
            } finally {
                subHeadBudget.isLoading = false;
            }
        }
    },
};
</script>


<!-- Add your component-specific styles here if needed -->
<style scoped>
.modal {
    display: block !important;
    background: #000a;
}

.py-21 {
    padding: 8px;
    display: flex;
}
</style>
