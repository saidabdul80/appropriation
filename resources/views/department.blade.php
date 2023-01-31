<?php

use App\Models\Department;
$departments =  Department::orderBy('id','desc')->get();


?>
@extends('layouts/master')
@section('content')

<div class="background px-4 py-2" style="height: 92.1vh;">
    <div class="alert-white rounded px-2 py-2 shadow bg-white">
        <button @click="changeTab(1)" :disabled="tab==1" class="btn btn-primary mx-2">Department</button>
        <button @click="changeTab(2)" :disabled="tab==2" class="btn btn-primary mx-2">Units</button>
    </div>
    <div  class="row w-100 mb-3  mx-auto py-3 ">
        <div class="col-md-4 mx-auto px-3">
            <div class="shadow bg-white py-3 px-3 rounded row">            
                    <div class="mb-3 col-md-12 mx-0">
                        <label for="name" class="form-label">Name</label>
                        <input  type="text" class="form-control" v-model="selected.name" id="name" placeholder="" required>
                        <div class="invalid-feedback">Required</div>
                    </div>
                    <div class="mb-3 col-md-12 mx-0">
                        <label for="short_name" class="form-label">Short Name</label>
                        <input  type="short_name" class="form-control" v-model="selected.short_name" id="short_name" placeholder="" required>
                        <div class="invalid-feedback">Required</div>
                    </div>                
                    <div class="mb-3 col-md-12 mx-0"  v-if="type=='department'" style="max-height:270px;overflow-y:scroll;">
                        <label for="unit_ids" class="form-label">Units</label>
                        <span v-for="(unit,i) in units" class="d-flex fs-9 p-2 bg-light text-dark rounded m-2 d-inline-block">
                           <input type="checkbox" v-model="selected.unit_ids" :value="unit.id"> @{{unit.short_name}}
                        </span>                        
                    </div>
                    <div class="col-12">            
                        <button @click="saveRecord()"  class="btn btn-success" type="button">                             
                            <span v-if="type=='department'">
                                <span v-if="selected.id==''">
                                    Create New Department
                                </span>
                                <span v-else>
                                    Update Department
                                </span>
                            </span>                        
                        </button>
                    </div>          
                </div>
                
            </div>        
        <div v-show="tab ==1" class="user-list mx-auto  col-md-8 px-3">
            <button @click="newDepartment()" class="btn btn-primary mx-2">Add</button>
            <div class="row needs-validation shadow bg-white rounded p-3 position-relative" style="min-height: 400px;">
                <div>
                    <table class="table table-sm table-hover table-bordered">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>Sn.</th> 
                                <th>Name</th>
                                <th>Short name</th>                                
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(department,i) in departments">
                                <td>@{{i+1}}</td>
                                <td>@{{department.name}}</td>
                                <td>@{{department.short_name}}</td>                                
                                <td>
                                    <!-- <button class="btn btn-secondary mx-1 text-white btn-sm" @click="addUnitToDepartmentModal(department)">Add Unit</button> -->
                                    <button class="btn btn-warning mx-1 text-white btn-sm" @click="editx(department)">Edit</button>
                                    <button class="btn btn-danger mx-1 text-white btn-sm" @click="deletex(department)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>         
            </div>
        </div>
        <!-- <div class="user-list mx-auto  col-md-8 px-3" v-show="tab==2">          
        </div>
      -->
    </div>

</div>

<script>
    const {
        createApp
    } = Vue
    createApp({
        data() {
            return {
                tab:1,
                departments: <?= json_encode($departments)?>,                        
                selected:{                                       
                    short_name:'',
                    name:'',
                    id:'',
                },
                type:'department' //or unit

            }
        },
        computed: {          
        },
        methods: {      
            changeTab(x){
                if(x==1){
                    this.tab = x
                    this.type ='department'                                
                }else if(x == 2){
                    this.tab = x
                    this.type ='unit'                                
                }
                
            },         
            newDepartment(){
               this.selected = {                   
                    unit_ids:[],                    
                    short_name:'',
                    name:'',
                    id:'',
                }
                this.type = 'department'
            },                  
            addToDepartment(){

            },
            
            editx(department){
                this.type ='department'                
                this.selected.unit_ids = department.unit_ids
                this.selected.short_name = department.short_name
                this.selected.name = department.name
                this.selected.id = department.id
            },
            deletex(department){

            },               
            async saveRecord(){
                validateText = "<span class='text-danger validate-text'>Required</span>";
                $('.validate-text').remove();
                if (this.selected.name == '') {
                    $('#name').after(validateText)
                    return 0
                }                
                if (this.selected.short_name == '') {
                    $('#short_name').after(validateText)
                    return 0
                }
                let record = this.selected;
                record.type = this.type
                let res = await postData('/department/create_update',record,true);
                if(res.status == 200){
                   //call page reload
                   Swal.fire({
                    title:res.data,
                    icon:'success'
                   }).then(()=>{
                        location.reload()
                   })
                }else{
                    showAlert(res.data,'error')
                }
            }
        },
        mounted() {
            setTimeout(function() {
               $('.table').DataTable({                
                   dom: 'Bfrtip',
                   buttons: [
                       'excel',
                       'pdf'
                   ],
                   pageLength: 6,

               });
               switchPage()
           }, 4000) 

        }

    }).mount('#appIn')
    $(document).ready(function() {

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        $('#toggleCards').click(function() {
            $(this).find('i').toggleClass('bi-chevron-up')
            $('.cards-container').slideToggle();
        })

        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        alert()
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()


    })
</script>
@endsection