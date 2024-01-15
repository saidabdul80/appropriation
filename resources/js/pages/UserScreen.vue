<template>
    <div class="background px-4 py-2" style="height: 92.1vh;">
        <div class="alert-white rounded px-2 py-2 shadow bg-white">
            <button @click="tab = 4" :disabled="tab == 4" class="btn btn-primary mx-2">Assign Role</button>
            <button @click="tab = 5" :disabled="tab == 5" class="btn btn-primary mx-2">Assign Permission</button>
        </div>
        <div class="row w-100 mb-3  mx-auto py-3 ">
            <div class="col-md-4 mx-auto px-3">
                <div class="shadow bg-white py-3 px-3 rounded" style="height: 100%;">
                <!-- Search Input -->
                <div class="input-group">
                    <input type="text" v-model="search_keyword" @keyup="fetchUsers" class="form-control" autocomplete="no" placeholder="Search">
                    <button class="input-group-text btn btn-secondary" @click="fetchUsersClick">Search</button>
                </div>

                <!-- User List -->
                <div class="my-2 border px-3">
                    <ul class="my-3 nav nav-pills flex-column mb-auto">
                    <li v-for="(user, i) in users.data" :key="user.id" @click="selectUser(user, $event)" :class="{ 'mb-2': true, 'border': true, 'p-0': true, 'nav-item': true, 'rounded': true, 'user-items': true, 'select-none': true, 'active': user.id === selected_user.id }" tabindex="-1">
                        <a class="px-2 py-1 nav-link text-dark a-item" :style="{ 'pointer-events': 'none' }">
                        <div :style="{ 'color': 'inherit' }" class="m-0 p-0" aria-describedby="emailHelp">{{ user.first_name }} {{ user.surname }}</div>
                        <div :style="{ 'color': 'inherit' }" id="emailHelp" class="m-0 p-0 form-text">{{ user.nicare_code }}</div>
                        </a>
                    </li>
                    </ul>
                </div>

                <!-- Pagination Controls -->
                <div class="d-flex justify-content-center">
                    <a :disabled="users.prev_page_url == null" :href="users.prev_page_url" class="btn btn-secondary py-1 mx-1">Prev</a>
                    <a :disabled="users.next_page_url == null" :href="users.next_page_url" class="btn btn-secondary py-1 mx-1">Next</a>
                </div>
                </div>
            </div>
            <div v-if="tab == 1" class="user-list mx-auto  col-md-8 px-3">
                <form class="row needs-validation shadow bg-white rounded p-3" novalidate>
                    <div class="mb-3 col-md-6">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" v-model="selected_user.first_name" id="first_name" required>
                        <div class="invalid-feedback">Required</div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="surname" class="form-label">Surname</label>
                        <input type="text" class="form-control" v-model="selected_user.surname" id="surname" required>
                        <div class="invalid-feedback">Required</div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" v-model="selected_user.email" id="email" required>
                        <div class="invalid-feedback">Required</div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" v-model="selected_user.gender" id="gender" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <div class="invalid-feedback">Required</div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" v-model="selected_user.phone_number" id="phone_number"
                            placeholder="">
                        <div class="invalid-feedback">Required</div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-control" v-model="selected_user.department_id" id="department_id" required>
                            <option value="">--</option>
                            <option v-for="department in departments" :value="department.id">{{ department.name }}</option>
                        </select>
                        <div class="invalid-feedback">Required</div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="unit_id" class="form-label">Unit</label>
                        <select class="form-control" v-model="selected_user.unit_id" id="unit_id" required>
                            <option value="">--</option>
                            <option v-for="unit in units" :value="unit.id">{{ unit.name }}</option>
                        </select>
                        <div class="invalid-feedback">Required</div>
                    </div>
                    <div class="mb-3 col-md-6 row">
                        <div class="mb-3 col-md-6 mx-0">
                            <label for="password" class="form-label">password</label>
                            <input type="password" class="form-control" v-model="selected_user.password" id="password"
                                placeholder="" required>
                            <div class="invalid-feedback">Required</div>
                        </div>
                        <div class="mb-3 col-md-6 mx-0">
                            <label for="password" class="form-label">Confirm password</label>
                            <input @keyup="cPassword()" type="password" class="form-control"
                                v-model="selected_user.confirm_password" id="confirm_password" placeholder="" required>
                            <div class="invalid-feedback">Required</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="user-list mx-auto  col-md-8 px-3" v-if="tab == 2">
                <div class="row needs-validation shadow bg-white rounded p-3">
                    <div class="mb-3 col-md-6 row">
                        <div class="mb-3 col-md-6 mx-0">
                            <label for="password" class="form-label">password</label>
                            <input @keyup="cPassword2()" type="password" class="form-control" v-model="password"
                                id="password2" placeholder="" required>
                            <div class="invalid-feedback">Required</div>
                        </div>
                        <div class="mb-3 col-md-6 mx-0">
                            <label for="password" class="form-label">Confirm password</label>
                            <input @keyup="cPassword2()" type="password" class="form-control" v-model="confirm_password"
                                id="confirm_password2" placeholder="" required>
                            <div class="invalid-feedback">Required</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <button @click="changePassword()" class="btn btn-success" type="button">Update Password</button>
                    </div>
                </div>
            </div>
            <div class="user-list mx-auto  col-md-8 px-3" v-if="tab == 3">
                             
            </div>
            <div style="height:inherits;" class="user-list mx-auto  col-md-8 px-3" v-if="tab == 4">
                <div style="overflow: auto;height: 72%;" class="row needs-validation shadow bg-white rounded p-3">
                    <div v-if="selected_user.id != ''" class="col-md-12 ">
                        <span v-for="role in roles" class="d-flex fs-9 p-2 bg-light text-dark rounded m-2 d-inline-block">
                            <input class="me-1" @change="assignRole(role, $event)" type="checkbox" :value="role.id"
                                v-model="role_ids"> {{ role.name.replace('_', ' ') }}
                        </span>
                    </div>
                    <div v-else class="col-md-4">
                        No User selected
                    </div>
                </div>
            </div>
            <div class="user-list mx-auto  col-md-8 px-3" v-if="tab == 5">
                <div style="overflow: auto;height: 72vh;" class="row needs-validation shadow bg-white rounded p-3">
                    <div v-if="selected_user.id != ''" class="col-md-12">
                        <span v-for="permission in permissions"
                            class="d-flex fs-9 p-2 bg-light text-dark rounded m-2 d-inline-block">
                            <input class="me-1" @change="assignPermission(permission, $event)" type="checkbox"
                                :value="permission.id" v-model="permission_ids"> {{ permission.description }}
                        </span>
                    </div>
                    <div v-else class="col-md-4">
                        No User selected
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
export default {
    props:{
        departments:{
            default:[]
        },
        def_users:{
            default:[]
        },
        permissions:{
            default:[]
        },
        roles:{
            default:[]
        },
    },
    created(){
        this.users= this.def_users
    },
    data() {
        return {
            tab: 1,
            permission_ids: [],
            role_ids: [],
            users:[],
            selected_user: {
                first_name: '',
                surname: '',
                email: '',
                gender: '',
                phone_number: '',
                department_id: '',
                unit_id: '',
                password: '',
                confirm_password: '',
                id: ''
            },
            password: '',
            confirm_password: '',
            search_keyword: '',
            paginateBy: 7,
        }
    },
    computed: {
        units() {
            $('#unit_id').val('')
            $('#unit_id').css('border', '1px solid #6d0');
            setTimeout(() => { $('#unit_id').css('border', '1px solid #ccc'); }, 2000)
            return this.departments.filter((item) => {
                return item.id == this.selected_user.department_id
            })[0]?.units
        }
    },
    methods: {
        async assignRole(role, e) {
            let type = "unassign"
            if (e.target.checked) {
                type = "assign"
            }

            let res = await postData('/assign_role', { model_id: this.selected_user.id, role: role.name, type: type }, true);
            if (res.status == 200) {
                showAlert(res.data, 'success')
            } else {
                showAlert(res.data, 'error')
            }
        },
        async assignPermission(permission, e) {
            let type = "unassign"
            if (e.target.checked) {
                type = "assign"
            }
            let res = await postData('/assign_permission', { model_id: this.selected_user.id, permission: permission.name, type: type }, true);
            if (res.status == 200) {
                showAlert(res.data, 'success')
            } else {
                showAlert(res.data, 'error')
            }
        },
        async fetchUsers(e) {

            if (event.key === "Enter") {
                let res = await postData('/get_users', { search: this.search_keyword, paginateBy: this.paginateBy }, true);
                if (res.status == 200) {
                    this.users = res.data
                } else {
                    showAlert(res.data, 'error')
                }
            }
        },
        async fetchUsersClick(e) {
            let res = await postData('/get_users', { search: this.search_keyword, paginateBy: this.paginateBy }, true);            
            if (res.status == 200) {
                this.users = res.data
            } else {
                showAlert(res.data, 'error')
            }
        },
        selectUser(user, e) {            
            this.permission_ids = user.permission_ids
            this.role_ids = user.role_ids
            this.selected_user.first_name = user.first_name
            this.selected_user.surname = user.surname
            this.selected_user.email = user.email
            this.selected_user.gender = user.gender
            this.selected_user.phone_number = user.phone_number
            this.selected_user.department_id = user.department_id
            this.selected_user.unit_id = user.unit_id
            this.selected_user.id = user.id
            $('.a-item').removeClass('text-white');
            $('.a-item').removeClass('active');
            $('.a-item').addClass('text-dark');

        },
        async changePassword() {
            if (this.selected_user.id == '') {
                Swal.fire({ title: 'Select a user', icon: 'warning' });
                return 0;
            }
            if (this.password == '') {
                $('#password2').after(validateText)
                return 0
            }
            if (this.confirm_password == '') {
                $('#confirm_password2').after(validateText)
                return 0
            }

            if (this.password != this.confirm_password) {
                this.cPassword()
                return 0
            }

            let res = await postData('/change_password', { id: this.selected_user.id, password: this.password, confirm_password: this.confirm_password }, true);
            if (res.status == 200) {
                showAlert(res.data, 'success')
            } else {
                showAlert(res.data, 'error')
            }
        },
        cPassword() {
            $('.validate-text').remove();
            if (this.selected_user.password != this.selected_user.confirm_password) {
                validateText = "<span class='text-danger validate-text'>Password not matched</span>"
                $('#confirm_password').after(validateText)
            } else {
                validateText = "<span class='text-success validate-text'>Password matched</span>"
                $('#confirm_password').after(validateText)
            }
        },
        cPassword2() {
            $('.validate-text').remove();
            if (this.password != this.confirm_password) {
                validateText = "<span class='text-danger validate-text'>Password not matched</span>"
                $('#confirm_password2').after(validateText)
            } else {
                validateText = "<span class='text-success validate-text'>Password matched</span>"
                $('#confirm_password2').after(validateText)
            }
        },
        async newUser() {
            validateText = "<span class='text-danger validate-text'>Required</span>";
            $('.validate-text').remove();
            if (this.selected_user.first_name == '') {
                $('#first_name').after(validateText)
                return 0
            }
            if (this.selected_user.surname == '') {
                $('#surname').after(validateText)
                return 0
            }
            if (this.selected_user.email == '') {
                $('#email').after(validateText)
                return 0
            }
            if (this.selected_user.gender == '') {
                $('#gender').after(validateText)
                return 0
            }
            if (this.selected_user.phone_number == '') {
                $('#phone_number').after(validateText)
                return 0
            }
            if (this.selected_user.department_id == '') {
                $('#department_id').after(validateText)
                return 0
            }
            if (this.selected_user.unit_id == '') {
                $('#unit_id').after(validateText)
                return 0
            }
            if (this.selected_user.id == '') {
                if (this.selected_user.password == '') {
                    $('#password').after(validateText)
                    return 0
                }
                if (this.selected_user.confirm_password == '') {
                    $('#confirm_password').after(validateText)
                    return 0
                }
                if (this.selected_user.password != this.selected_user.confirm_password) {
                    return 0
                }
            }

            let res = await postData('/new_update_user', this.selected_user, true);
            if (res.status == 200) {
                if (this.selected_user.id != '') {
                    this.users.data[this.selected_user_index](this.selected_user)
                } else {
                    this.users.data.unshift(this.selected_user)
                }
                showAlert(res.data, 'success')
            } else {
                showAlert(res.data, 'error')
            }
        }
    },
    mounted() {
        $(document).ready(function () {

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
            $('#toggleCards').click(function () {
                $(this).find('i').toggleClass('bi-chevron-up')
                $('.cards-container').slideToggle();
            })
                
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        alert()
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })                


        })

    }

}
</script>
<style scoped>
/* Add your custom styles for this component */
.active {
  background-color: #007bff !important; /* Add your preferred active background color */  
  color: #fff !important; /* Add your preferred active text color */
}
</style>