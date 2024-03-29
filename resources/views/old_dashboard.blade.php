<?php

use App\Models\Appropriation;
use App\Models\Department;
use App\Models\Scheme;
use App\Models\Wallet;
$schemes = Scheme::all();
/* $ids = Appropriation::where('scheme_id', $scheme_id)->pluck('id');
$fund_categories = Wallet::whereIn('owner_id',$ids)->where('owner_type','App\\Models\\Appropriation')->pluck('fund_category')->unique();
$dpartments = Department::all(); */
?>
<style>
    #checkboxes{top:34px;}
</style>
@extends('layouts/master')
@section('content')
<script src=""></script>

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
                departments: '',                
                selected_department:{
                    name:'',
                    id:''
                },
                selected_scheme:{},
                selected_fund_category:'',
                schemes: <?= json_encode($schemes) ?>,
                fund_categories:'',
                selected_fund_category:'',
                appropriations:[],
                dynamic_data:<?= json_encode(config('data.dynamic_data'))?>,                
                dynamicDataSelected:[],
                whatIsSelected:'',
                table_ids:[],
                calcTotalExpenditureForAppropriationLimit:0,
                category_income:0,
                expenditure_amount:0,
            }
        },
        computed: {
            
        },
        methods: {  
            timemout(ms){
                return new Promise(resolve=>setTimeout(resolve,ms))
            },
            async schemeTriggered(trigger=0){                        
                    let $this = this                                        
                    let res = await postData('/dashborad_data',{programme_id:this.selected_scheme.id},true);
                    //scheme_appropriations                    
                    if(res?.status == 200){  
                        this.appropriations = res.data.appropriations
                        setTimeout(()=>{
                            Object.keys($this.appropriations).forEach(key =>{
                                $this.initChart('report_'+key,$this.programeYearReport($this.appropriations[key]),key)                      
                            });
                        },300)                                        
                    }else{
                        showAlert('Something went wrong');
                        return false;
                    }                   
                
              /*    this.category_income = 0   
                    this.expenditure_amount = 0
                    this.category_income_balance =0       
                    this.getCategoryIncome()
                    this.getCategoryIncomeBalance()      */                         
            },
            getProgrameYearReportData(col, arr){
                let result = [];
                arr.forEach(item=>{
                    result.push(item[col])
                });
                return result
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
            programeYearReport(records){
                records = JSON.parse(JSON.stringify(records))
                //console.log(records.filter((value,i)=>),records) 
                return {
                        labels:this.getProgrameYearReportData(2,records),
                        datasets: [
                            {
                                label: 'Balance',
                                data: this.getProgrameYearReportData(0,records),
                                backgroundColor:'#28a74588',
                                borderColor:'#fff'
                            },
                            {
                                label: 'Income',
                                data: this.getProgrameYearReportData(1,records),
                                backgroundColor:'#17a2b888',
                                borderColor:'#fff'
                            }
                        ]
                    }
            },
            initChart(id,data,title){      
                console.log(title)        
                new Chart(
                    document.getElementById(id),
                    {
                    type: 'bar',
                    data: data,
                    options: { 
                        title: {
                            display: true,
                            text: title,
                            fontSize: 20
                        },                       
                        elements: {
                        bar: {
                            borderWidth: 2,
                        }
                        },
                        responsive: true,                                          
                        scales: {
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Amount',
                                    fontStyle: 'bold',
                                    fontSize: 10
                                },
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value, index, values) {
                                    if(parseInt(value) >= 1000){
                                        return '₦' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    } else {
                                        return '₦' + value;
                                    }
                                    }
                                }
                            }],
                           /*  xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Year',
                                    fontStyle: 'bold',
                                    fontSize: 20
                                }
                            }], */
                        },
                        }
                    },
                );
            },
            initPieChart(id,data,title){ 
                let $this = this
                setTimeout(() => {                                    
                    new Chart(
                        document.getElementById(id),
                        {
                        type: 'doughnut',                
                        data: {
                            labels: ['Total Balance ','Total Income '],
                            datasets: [
                                {
                                label: 'Dataset 1',
                                data:data,
                                backgroundColor: ['#28a745','#17a2b8'],
                                }
                            ]
                        },
                        options: { 
                            legend: {
                                position: 'chartArea'
                            },
                            title: {
                                display: true,
                                text:'Overall '+ title,
                                fontSize: 15
                            },                                            
                            responsive: true,                                                                
                            tooltips: {
                                callbacks: {
                                    label: function(tooltipItem, data) {     
                                        console.log(tooltipItem, data)                               
                                    return data.labels[tooltipItem.index]+": "+ $this.currency(data.datasets[0].data[tooltipItem.index]);
                                    }
                                }
                                }
                        }
                        },
                    );
                }, 200);                                      
            },
                  
            currency(amount){
                if(amount){
                    let res = new Intl.NumberFormat('NGN', { style: 'currency', currency: 'NGN' }).format(amount)
                    return res.replace("NGN",'')
                }
                return '0.00';
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