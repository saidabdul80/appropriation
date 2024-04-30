<template>
    <div>
        <div class="row w-100 mb-3">
            <div class="col-lg-3" v-if="add_cat_enabled">
                <InputText v-model="cat_name" class="p-2 w-100" placeholder="Enter new category name" />
            </div>
            <div class="col-lg-3">
                <button v-if="!add_cat_enabled" @click="enableAddCat()" class="btn btn-primary p-2">Add New</button>
                <button v-else @click="addCat()" class="btn btn-success p-2">Add Category
                    <span v-if="adding_cat" class="pi pi-spin pi-spinner"></span>
                </button>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(cat, index) in subheads" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>
                        <span v-if="!cat?.isEditing">{{ cat.name }}</span>
                        <InputText v-else v-model="cat.name" class="p-2 w-100"/>                    
                    </td>
                    <td >
                        <div class="row">
                            <div class="col-2 col-md-3" v-if="!cat?.isEditing">
                                <button  @click="cat.isEditing = true" class="btn btn-white btn-outline-primary py-2 btn-sm py-21 rounded-circle"><span class="pi pi-pencil"></span> </button>                                                
                            </div>
                            <div class="col-2 col-md-3" v-else> 
                                <button   @click="updateCat(cat)" class="btn btn-white btn-outline-success btn-sm py-21 rounded-circle"><span class="pi pi-check"></span> </button>                        
                            </div>
                            <div class="col-2 col-md-3" v-if="cat?.isEditing">
                                <button  @click="cat.isEditing = false" class="btn btn-white btn-outline-primary py-2 btn-sm py-21 rounded-circle"><span class="pi pi-times "></span> </button>                        
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import InputText from 'primevue/inputtext';

export default {
    components: {
        InputText
    },
    data() {
        return {
            adding_cat: false,
            subheads: [],
            add_cat_enabled: false,
            cat_name: ''
        };
    },
    methods: {
        async getExpCats() {
            try {
                let res = await getData('subhead');
                this.subheads = res.data;
            } catch (error) {
                console.error('Failed to fetch categories:', error);
                Swal.fire("Failed to load categories");
            }
        },
        enableAddCat() {
            this.add_cat_enabled = true;
        },
        async addCat() {
            if (this.cat_name.trim() === '') {
                Swal.fire("Enter category name");
                return false;
            }
            this.adding_cat = true;
            try {
                await postData('subhead/create', { name: this.cat_name });
                await this.getExpCats();            
            } catch (error) {
                console.error('Failed to add category:', error);
                Swal.fire("Failed to add category");
            } finally {
                this.add_cat_enabled = false;
                this.adding_cat = false;
            }
        },
        async updateCat(cat) {
            if (cat.name.trim() === '') {
                Swal.fire("Enter category name");
                return false;
            }           
            cat.isLoading = true 
            await postData('subhead/update/'+cat.id, { name: cat.name });
            await this.getExpCats();                        
        }    
    },
    
    async created() {
        await this.getExpCats();
        this.$emit('oncompleted',true)
    }
};
</script>

<style scoped>
.button-margin {
    margin-top: 10px;
}
.py-21{
    padding: 8px;
    display: flex;
}
</style>
