<template>
    <div class="">
        <InlineMessage severity="info" class="p-1 mb-3">
            <span style="font-size: 0.8em;" class="ms-1"> <b>Note:</b> Ensure fund already available on the selected
                Head i.e Appropriation must have been completed </span>
        </InlineMessage>
        <div class="row w-100 mx-auto">
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
                            <div style="font-size: 10px;">Programme (Unappropriated fund )</div>
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
            <div class="col-md-6 mb-2">
                <label for="name" class="form-label">Funding Year</label>
                {{ monthSelected }}
                <month-year-selector v-model="monthSelected" :fund_categories="fund_categories"
                    @change="loadAppropriations"  />
                <!-- <Dropdown v-model="selected_scheme" :options="schemes" optionLabel="name" placeholder="Select a Department"
                class="w-100 mb-3" /> -->
            </div>
            <div class="col-md-12">
                <label for="name" class="form-label mb-0">Head Name</label>
                <InputGroup>
                    <!--    <Dropdown v-model="selected_appropriation" :options="selected_scheme.appropriations"
                        optionLabel="name" optionValue="id" @change="loadSubheadBudgets()" placeholder="Select a Head"
                        class="w-100 " /> -->

                    <Dropdown @change="loadSubheadBudgets()" v-model="selected_appropriation"
                        :options="appropriations" placeholder="Select a Scheme" class="w-100 ">
                        <template #value="slotProps">
                            <div v-if="slotProps.value?.name" class="flex align-items-center" :class="(slotProps.value?.wallet.balance -getTotalAmountSubheadBudget) <0?'text-danger':''">
                                <div style="font-size: 12px;font-weight: bolder;">{{ slotProps.value.name }} <span>
                                        &#8358;{{ $globals.currency(slotProps.value?.wallet?.balance - getTotalAmountSubheadBudget) }})</span> </div>
                                <div style="font-size: 10px;">Head with Current Balance</div>
                            </div>
                            <span v-else>
                                Select a Head
                            </span>
                        </template>

                        <template #option="slotProps">                            
                            <div>{{ slotProps?.option?.name }} ({{$globals.currency(slotProps.option?.wallet.balance)}})
                            </div>
                        </template>
                    </Dropdown>

                </InputGroup>
            </div>
            <div class="col-md-12 mt-3">

                <table class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Subhead</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(cat, index) in sub_head_budgets" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>
                                <span v-if="!cat?.isEditing">{{ cat.subhead }}</span>
                                <!--<Dropdown v-else v-model="cat.subhead_id" :options="subheads" optionLabel="name"
                                    optionValue="id" placeholder="Select a subhead" class="w-100" /> -->
                                    <BaseItemSelect v-else v-model="cat.subhead_id" url="subhead" optionLabel="name"
                                    optionValue="id" />
                            </td>
                            <td>
                                <span v-if="!cat?.isEditing">{{$globals.currency(cat.amount) }}</span>
                                <InputText v-else v-model="cat.amount" class="p-1 w-100" />
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-2 col-md-5 px-2" v-if="!cat?.isEditing">
                                        <button @click="cat.isEditing = true"
                                            class="btn btn-white btn-outline-primary py-2 btn-sm py-21 rounded-circle"><span
                                                class="pi pi-pencil"></span> </button>
                                    </div>
                                    <div class="col-2 col-md-5 px-2" v-else>
                                        <button @click="saveSubheadBudget(cat)"
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
                </table>
                <Teleport to="#modalFooterConfig">
                    <Button @click="addBudget()" class="rounded rounded-right pull-left" icon="pi pi-plus" />
                </Teleport>
            </div>
        </div>
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
import { useGlobalStore } from '../../../store';
import { useMonthYearTrigger } from '../../../composable';
export default {
    components: {
        Dropdown,
        InputText,
        MonthYearSelector,
        InlineMessage,
        InputGroup,
        InputGroupAddon,
        Button,
        BaseItemSelect

    },

    data() {
        return {
            globals:useGlobalStore(),
            selected_appropriation: null,
            sub_head_budgets: [],
            appropriations:[],
            subheads: [],
            schemes: [],
            selected_scheme: null,
            fund_categories: [],
            monthSelected: null,
        }
    },
    async created() {
        this.selected_scheme = { ...this.globals.selected_scheme }
        this.schemes = [...this.globals.schemes];
        await this.getAllSubhead();
        this.$emit('oncompleted', true);
    },
    computed:{
        getTotalAmountSubheadBudget(){
            if (Array.isArray(this.sub_head_budgets)) {
                return this.sub_head_budgets.reduce((total, item) => {
                    return total + (Number(item.amount) || 0);
                }, 0);
            }
            return 0;
        }
    },
    methods: {
        async removeItem(cat,index){
            if(cat?.id){
                cat.isEditing = false;
                await postData('sub_head_budget/delete/'+cat.id)
            }
            this.sub_head_budgets.splice(index,1)
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
            if (this.selected_appropriation?.id == null || this.selected_appropriation?.id == '') {
                Swal.fire('Select a Head');
                return false;
            }
            this.sub_head_budgets.push({
                appropriation_id: this.selected_appropriation?.id,
                subhead_id: '',
                subhead: '',
                amount: '',
                fund_category: this.monthSelected,
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
       async loadAppropriations(selected_fund_category){
            const res = await useMonthYearTrigger(selected_fund_category, this.selected_scheme.id);
            this.appropriations = res?.appropriations
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
        async saveSubheadBudget(subHeadBudget) {
            if (!subHeadBudget.amount || subHeadBudget.amount === '') {
                Swal.fire("Amount is required");
                return false;
            }

            if (!subHeadBudget.fund_category || subHeadBudget.fund_category === '') {
                Swal.fire("Funding Year is required");
                return false;
            }

            if (!subHeadBudget.subhead_id || subHeadBudget.subhead_id === '') {
                Swal.fire("Subhead is required");
                return false;
            }

            subHeadBudget.isLoading = true;
            try {
                await postData('sub_head_budget/save', subHeadBudget);
                await this.loadSubheadBudgets()
                subHeadBudget.isEditing = false;  // Assume you want to stop editing on save
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
