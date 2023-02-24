<?php

use App\Models\Appropriation;
use App\Models\Department;
use App\Models\Scheme;
use App\Models\Wallet;

$scheme_id = request('scheme_id');

$scheme = Scheme::find($scheme_id);
if(empty($scheme)){
    return abort(404);
}
$ids = Appropriation::where('scheme_id', $scheme_id)->pluck('id');
$fund_categories = Wallet::whereIn('owner_id',$ids)->where('owner_type','App\\Models\\Appropriation')->pluck('fund_category')->unique();
$dpartments = Department::all();
?>
<style>
    #checkboxes{top:34px;}
</style>
@extends('layouts/master')
@section('content')
<div class="background px-4 py-2" style="height: 92.1vh;">
    <div class=" bg-light pageTitleCard w-100 fs-9 text-danger ps-4">::Report</div>
    <div class="position-relative" >
        <div class=" bg-white px-3 rounded shadow py-2">    
            <div class="mb-2 mt-2 row">   
                <div class="col-md-4">          
                    <div class="mb-3 input-group">
                        <span class="input-group-text">Fund Month/Year</span>
                        <select  @change="monthYearTriggered(1)" v-model="selected_fund_category" class="form-control">
                            <option value=""> </option>
                            <option v-for="month_year in fund_categories">@{{month_year}}</option>
                        </select>
                    </div> 
                </div>  
                <div class="col-md-4">
                    <div class="mb-3 input-group ">
                        <span class="input-group-text">Search:</span>
                        <input class="form-control" type="text" value="" @keyup="searchFunc($event)">
                    </div>
                </div>    
               
                <div class="col-md-4">          
                    <div class="mb-3 multiselect w-100 input-group">                        
                            <span class="input-group-text w-25">Filter Column:</span>
                            <div id="checkboxesToggler" class="selectBox w-75" @click="showCheckboxes()">
                                <input type="text" disabled :value="whatIsSelected"  class="form-control no-event bg-white fs-9 py-2"  aria-label="size 3 select example">
                                <div class="overSelect no-event"></div>
                            </div>
                            <div v-show="expanded" id="checkboxes">
                                <label for=""><input @change="filterColumn($event)"  class="me-1" type="checkbox" value="0" > S/N</label>
                                <label v-for="(key,i) in Object.keys(dynamic_data)"   >
                                    <input @change="filterColumn($event)"  class="me-1" type="checkbox" :value="i+1" > @{{key.replaceAll('_',' ')}}
                                </label>                    
                                <label for=""><input @change="filterColumn($event)"  class="me-1" type="checkbox" :value="Object.keys(dynamic_data).length+1" > S/N</label>
                            </div>                        
                        <!-- <span class="input-group-text">Department</span>
                        <select @change="departmentTriggered(1)" v-model="selected_department" class="form-control">            
                            <option v-for="(department,i) in departments" :value="{name:department.name,id:department.id}">@{{department.name}}</option>
                        </select>   -->                   
                    </div>
                </div>
            </div>
            <div style="height: 70vh; overflow:auto;">            
                <div v-for="(appro,index) in transactions" :key="'t_'+index" v-memo="'t_'+index" class="border p-3 mb-4 alert alert-secondary">
                    <h4  class="text-center"><b class="searchKey">@{{appro.name}} / @{{appro.department}}</b> Transactions</h4>              
                    <!-- <div style="overflow: auto;"> -->
                    <div :script="table_ids.push('#table'+index)">
                        <table :id="'table'+index" class=" fs-8 table-bordered transactions-tables table table-sm table-hover mb-0">                       
                            <caption class="visible-no" style="caption-side: top-left" >
                            <b>@{{appro.name}} / @{{appro.department}}</b> Transactions <br>
                            </caption> 
                            <thead class="bg-light">
                                <tr>
                                    <th >S/N</th>
                                    <th v-for="header in Object.keys(dynamic_data)" >
                                        @{{header.replaceAll('_',' ')}}
                                    </th>
                                    <th >Total Amount</th>                                
                                </tr>
                            </thead>
                            <tbody >                                
                                <tr  v-for="(tran,i) in appro.transactions">
                                    <td>@{{i+1}}</td>
                                    <td v-for="key in Object.keys(tran.data)">
                                        <span v-if="!key.includes('₦')">
                                            <span v-if="tran.data[key].type =='number'">@{{currency(tran.data[key].value)}}</span>
                                            <span v-else>@{{tran.data[key].value}}</span>                                    
                                        </span>
                                        <span v-else>
                                            <span v-else>@{{currency(tran.data[key])}}</span>                                    
                                        </span>
                                    </td>
                                    <td>
                                        @{{currency(tran.amount)}}
                                        <input type="number" step="any" class="_totaExp_ d-none" :value="tran.amount" >
                                    </td>                                
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th >                                
                                        <p class="inline-block mb-0 me-4"><b>Balance:</b><span>&#8358;</span> @{{currency(appro.balance)}}</p>
                                    </th>
                                    <th v-for="k in Object.keys(dynamic_data).length-2">
                                    </th>
                                    <th>
                                        <p class="inline-block mb-0"><b>Total Expenditure: <span>&#8358;</span></b> <span class="totalExpValue"></span></p>
                                    </th>
                                </tr>
                            </tfoot>              
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('newscript')
<script>
    const {
        createApp
    } = Vue
    createApp({
        data() {
            return {
                expanded:false,                
                departments: <?= json_encode($dpartments) ?>,                
                selected_department:{
                    name:'',
                    id:''
                },
                selected_scheme:<?= json_encode($scheme) ?>,
                selected_fund_category:'',
                //schemes: ,
                fund_categories:<?= json_encode($fund_categories) ?>,
                selected_fund_category:'',
                transactions:[],
                dynamic_data:<?= json_encode(config('data.dynamic_data'))?>,                
                dynamicDataSelected:[],
                whatIsSelected:'',
                table_ids:[],
                calcTotalExpenditureForAppropriationLimit:0
            }
        },
        computed: {
            
        },
        methods: {  
            timemout(ms){
                return new Promise(resolve=>setTimeout(resolve,ms))
            },
          calcTotalExpenditureForAppropriation(){      
                let total, tabel, $this = this;                                    
                this.table_ids.forEach(id => {                    
                    total = 0;
                    table = $(id);                                    
                    table.find('._totaExp_').each(function(){                        
                        total +=  parseFloat($(this).val());
                    });                                                                                          
                    table.find('.totalExpValue').text($this.currency(total))                                        
                });                 
            },
            searchFunc(e){
                let value = e.target.value;
                $('.searchKey').each(function(){
                    if($(this).text().toLowerCase().includes(value.toLowerCase())){
                        $(this).parent().parent().show()
                    }else{
                        $(this).parent().parent().hide()
                    }
                })

            },
            whatIsSelectedFunc(){                
                this.whatIsSelected = Object.keys(this.dynamic_data).filter((key,i)=>{
                    return this.dynamicDataSelected.includes(i);
                })?.join(',').replaceAll('_',' ')
            },
            filterColumn(e){   
                let elt = $(e.target);
               // this.whatIsSelectedFunc();             
                  // Get the column API object
                  let column,table, $this=this;                   
                  $('.table').each(function(){                    
                      table = $(this).DataTable();
                      column = table.column(e.target.value);                
                      column.visible(!e.target.checked);
                  })
            },
            showCheckboxes() {                                                
                this.expanded = !this.expanded;
            },        
            currency(amount){
                if(amount){
                    let res = new Intl.NumberFormat('NGN', { style: 'currency', currency: 'NGN' }).format(amount)
                    return res.replace("NGN",'')
                }
                return '0.00';
            },
            async departmentTriggered(trigger=0){
                if(this.selected_scheme.id == ''){
                    Swal.fire('Select a programme')
                    return false
                }

                if(this.selected_fund_category == ''){
                    Swal.fire('Select a fund month year')
                    return false
                }

            },
            async monthYearTriggered(trigger=0){                             
                let res = await postData('/get_appropriation_transactions',{owner_id:this.selected_scheme.id,owner_type:'appropriation',fund_category:this.selected_fund_category},true);
                    if(res?.status == 200){
                        this.transactions = res.data;
                        let $this = this;
                        setTimeout(()=>{
                            $this.calcTotalExpenditureForAppropriation()
                            setTimeout(()=>{
                                $this.iniTableTransaction()                            
                            },500)
                        },500)                        
                    }
                //1 means from the right source
                 /*    let res = await postData('/get_prepared_data',{scheme_id:this.selected_scheme.id,fund_category:this.selected_fund_category},true);
                    if(res?.status == 200){
                        this.appropriations_history = {data:[]}                           
                        this.appropriations_history = res.data.appropriations_histories
                        this.appropriations = res.data.appropriations                                
                        this.resetPageData(trigger)
                        if(trigger == 1){
                            localStorage.setItem('selected_fund_category',this.selected_fund_category)                               
                        }
                        let $this = this
                        setTimeout(()=>{
                            $this.getCategoryIncome()
                            $this.getCategoryIncomeBalance()
                        },10)
                    }else{
                        showAlert('Something went wrong');
                        return false;
                    } */
            },
         
            departmentTriggered(trigger){

            },
            iniTableTransaction(destroy=true){                        
                        let $this = this                        
                        $(this.table_ids.join(', ')).DataTable({                
                            dom: 'Bfrtip',                            
                            destroy:true,
                            scrollY:        100,
                            scrollX:        true,
                            scrollCollapse: true,
                            paging:         false,
                            fixedHeader:           {
                                header: true,
                                footer: true
                            },
                            buttons: [                                                              
                                {
                                    extend: 'excelHtml5',
                                    exportOptions: 
                                    {
                                        columns: ':visible',
                                        stripHtml: true
                                    },
                                    messageBottom: function(){

                                    },
                                    footer:true,
                                    title:'<?=agencyName()?>',
                                   /*  customize: function ( win ) {                                                                                                                                                       
                                        $(win.document.body).find('.table').before(`<h6 style="text-align:left;margin:10px 0px;">${$(win.document.body).find( '.table' ).next().html()}</h6>`)                                    
                                        let tfoot = $(win.document.body).find('.table tfoot tr th').eq(0).html()
                                        $(win.document.body).find('.table tfoot tr').remove();
                                        $(win.document.body).find('.table').after(tfoot)                                        
                                    } */
                                }
                            ],
                            exportOptions: {
                                stripHtml: false
                            },
                            pageLength: 10,
                        });    
                    },

        },
        renderTriggered({ key, target, type }) {
                console.log('renderTriggered:', { key, target, type });
                },
        mounted() {
            let $this = this
            $('.background').on("click", function (e) {                                                                                          
                let $info = $('#checkboxes');
                if (!$info.is(e.target) && $info.has(e.target).length === 0) {                            
                    if(e.target.id =='checkboxesToggler'){
                        $this.expanded = true
                    }else{
                        $this.expanded = false
                    }
                }
            });
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



    })
</script>
@endsection