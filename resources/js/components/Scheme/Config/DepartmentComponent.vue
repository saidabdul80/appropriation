<template>
    <div>
        <div class="row w-100">
            <div class="col-lg-6">
                <Dropdown v-model="department_id" :options="departments" @change="handleSelectDepartment()"
                    optionLabel="name" optionValue="id" placeholder="Select a Department" class="w-100 mb-3 p-2" />
            </div>
            <div class="col-lg-3" v-if="add_unit_enabled">
                <InputText v-model="unit_name" class="p-2 w-100" placeholder="Enter new unit name" />
            </div>
            <div class="col-lg-3">
                <button v-if="!add_unit_enabled" @click="enableAddUnit()" class="btn btn-primary p-2">Add Unit</button>
                <button v-else @click="addUnit()" class="btn btn-success p-2">Add Unit <span v-if="adding_unit"
                        class="pi pi-spin pi-spinner"></span></button>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(un, i) in department_units" :key="i">
                    <td>{{ i + 1 }}</td>
                    <td>
                        <span v-if="un?.isEditing">{{ un.name }}</span>                        
                        <InputText v-else v-model="un.name" class="p-2 w-100"/>                                        
                    </td>
                    <td>
                        <span v-if="un?.isEditing">{{ un?.department_name }}</span>
                        <Dropdown v-else v-model="un.department_id" :options="departments" optionLabel="name" optionValue="id" placeholder="Select a Department" class="w-100 mb-3 p-2" />
                    </td>
                    <td>
                        <td class="row">
                        <div class="col-2 col-md-3" v-if="!un?.isEditing">
                            <button  @click="un.isEditing = true" class="btn btn-white btn-outline-primary py-2 btn-sm py-21 rounded-circle"><span class="pi pi-pencil"></span> </button>                                                
                        </div>
                        <div class="col-2 col-md-3" v-else> 
                            <button   @click="updateUnit(un)" class="btn btn-white btn-outline-success btn-sm py-21 rounded-circle"><span class="pi pi-check"></span> </button>                        
                        </div>
                        <div class="col-2 col-md-3" v-if="un?.isEditing">
                            <button  @click="un.isEditing = false" class="btn btn-white btn-outline-primary py-2 btn-sm py-21 rounded-circle"><span class="pi pi-times "></span> </button>                        
                        </div>
                    </td>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';

export default {
    components: {
        Dropdown,
        InputText
    },
    props: {
    },
    data() {
        return {
            adding_unit: false,
            departments: [],
            department_units: [],
            department_id: null,
            add_unit_enabled: false,
            adding_unit: false,
            unit_name: ''
        };
    },
    methods: {
 
        async getDepartmentUnits() {
            let res = await getData('units/department/' + this.department_id)
            this.department_units = res.data
        },

        async getDepartments() {
            let res = await getData('department')
            this.departments = res.data
        },

        handleSelectDepartment() {
            this.getDepartmentUnits();
        },
        enableAddUnit() {

            if (!this.department_id) {
                Swal.fire('Select Department')
                return false
            }
            this.add_unit_enabled = true
        },
        async addUnit() {
            if (this.unit_name == '') {
                Swal.fire("Enter Unit Name")
                return false;
            }
            this.adding_unit = true
            await postData('units/create', {
                name: this.unit_name,
                department_id: this.department_id
            });
            await this.getDepartmentUnits()
            this.add_unit_enabled = false
            this.adding_unit = false
        },
        async updateUnit(un) {
            if (un.name == '') {
                Swal.fire("Unit Name Cannot be Empty")
                return false;
            }
            un.isLoading = true
            await postData('units/update/'+un.id, un);
            await this.getDepartmentUnits()           
        },

    },
    async created(){
        await this.getDepartments()
        this.$emit('oncompleted',true)
    }
};
</script>

<style scoped>
.button-margin {
    margin-top: 10px;
}
</style>