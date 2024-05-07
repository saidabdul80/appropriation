<template>
    <div>
      <div class="row w-100">
        <div class="col-lg-6">
          <InputText v-model="department.name" class="p-2 w-100" placeholder="Enter department name" />
        </div>
        <div class="col-lg-6">
          <InputText v-model="department.short_name" class="p-2 w-100" placeholder="Enter short name" />
        </div>
      </div>
      <div class="button-margin">
        <button v-if="!editing" @click="addDepartment" class="btn btn-primary p-2">Add Department</button>
        <button v-else @click="updateDepartment" class="btn btn-success p-2">Update Department</button>
      </div>
      <table class="table table-bordered mt-3">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>Short Name</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(dept, index) in departments" :key="dept.id">
            <td>{{ index + 1 }}</td>
            <td>{{ dept.name }}</td>
            <td>{{ dept.short_name }}</td>
            <td>
              <button @click="editDepartment(dept)" class="btn btn-white btn-outline-primary py-2 btn-sm py-21 rounded-circle">
                <span class="pi pi-pencil"></span>
              </button>
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
    props: {},
    data() {
      return {
        department: {
          name: '',
          short_name: ''
        },
        departments: [],
        editing: false,
        editingIndex: null
      };
    },
    methods: {
      async addDepartment() {
        // Check if required fields are filled
        if (!this.department.name.trim() || !this.department.short_name.trim()) {
          Swal.fire('Please enter department name and short name');
          return;
        }
        // Send department data to backend
        await postData('/departments/create', this.department);
        // Refresh department list
        this.getDepartments();
        // Clear input fields
        this.clearFields();
      },
      async updateDepartment() {
        // Check if required fields are filled
        if (!this.department.name.trim() || !this.department.short_name.trim()) {
          Swal.fire('Please enter department name and short name');
          return;
        }
        // Send updated department data to backend
        await postData(`/department/create_update`, this.department);
        // Refresh department list
        this.getDepartments();
        // Clear input fields
        this.clearFields();
        // Exit editing mode
        this.editing = false;
        this.editingIndex = null;
      },
      editDepartment(department) {
        // Set editing mode and fill input fields with department data
        this.editing = true;
        this.editingIndex = this.departments.findIndex(d => d.id === department.id);
        this.department = { ...department };
      },
      async getDepartments() {
        // Fetch departments from backend
        const res = await getData('/department');
        this.departments = res.data;
      },
      clearFields() {
        // Clear input fields
        this.department = {
          name: '',
          short_name: ''
        };
      }
    },
    async created() {
      // Load departments when component is created
      await this.getDepartments();

      this.$emit('oncompleted', true);
    }
  };
  </script>

  <style scoped>
  .button-margin {
    margin-top: 10px;
  }
  </style>
