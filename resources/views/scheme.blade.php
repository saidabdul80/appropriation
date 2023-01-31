<?php

use App\Models\Department;
use App\Models\Scheme;

$schemes = Scheme::all();
$departments = Department::all();
$dyear = 2020;
$cyear = date('Y');
$years  =  array();

for ($i=$dyear; $i <= $cyear ; $i++) { 
    $years[] = $i;
}
?>
@extends('layouts/master')
@section('content')

<div class="background px-4 py-2" style="height: 92.1vh;z-index:1"> 
    <div class="position-relative" >
        <div class="mb-2 mt-2">                   
            <select @change="schemeTriggered()" v-model="selected_scheme" class=" mx-1 form-control d-inline-block" style="width: 240px;">            
                <option v-for="(scheme,i) in schemes" :value="{'index':i,wallet:scheme.wallet, 'id':scheme.id,'name':scheme.name,'appropriations':scheme.appropriations}">@{{scheme.name}}</option>
            </select>            
            <button @click="schemeModalAdd()" class="btn mx-1 btn-primary d-inline-block">New Scheme</button>
            <button v-if="selected_scheme.index !== ''" @click="schemeModalUpdate()" class="btn mx-1 btn-success d-inline-block">Update Scheme</button>
        </div>
        <div class=" bg-white px-3 rounded shadow py-2">            
            <div class="mb-3 row shadow">
                <div class="col-md-7">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button v-if="selected_scheme.index !== ''" @click="archived()" class="mb-2 fs-9 btn btn-secondary text-white d-inline-block">
                        <i class="bi bi-archive"></i>Archive</button>
                        <button v-if="selected_scheme.index !== ''" @click="appropriationModalAdd()" class="mb-2 fs-9 btn btn-secondary text-white d-inline-block">
                            <i class="bi bi-journals"></i>
                            New Approp.
                        </button>
                        <button v-if="selected_scheme.index !== ''" @click="projectionModal()" class="fs-9 btn btn-secondary text-white d-inline-block mb-2">
                            <i class="bi bi-collection"></i>
                            Projection</button>
                        <button v-if="selected_scheme.index !== ''" @click="appropriate()" class="fs-9 btn btn-secondary text-white d-inline-block mb-2">
                            <i class="bi bi-bar-chart-steps"></i> Appropriate
                        </button>
                        <button v-if="selected_scheme.index !== ''" @click="fundScheme()" class="btn fs-9 btn-secondary text-white d-inline-block mb-2">
                        <i class="bi bi-database-down"></i>Fund</button>
                    </div>
                </div>
                <div class="col-md-2">
                  Current Account: <span>&#8358;</span> @{{currency(selected_scheme?.wallet?.balance)}} 
                </div>
                <div class="col-md-3">
                  Transit Account: <span>&#8358;</span> @{{currency(selected_scheme?.wallet?.safe_balance)}} 
                </div>
            </div>
            <!-- <h5 class="py-1 px-2 mb-0 bg-secondary text-white shadow rounded">Selected Scheme: @{{selected_scheme.name}}</h5> -->
            <div style="overflow:auto; height:66vh;">            
                <table v-show="switchPage==1" class="table table-sm">
                    <thead class="bg-light">
                        <tr>
                            <th>sn.</th>
                            <th>Appropriation Name</th>
                            <th>Department Code</th>
                            <th>Percentage (%)</th>                        
                            <th>Balance (<span>&#8358;</span>) </th>                        
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(appropriation,i) in selected_scheme.appropriations">
                            <td>@{{i+1}}</td>
                            <td>@{{appropriation.name}}</td>
                            <td>@{{appropriation.department}}</td>
                            <td>@{{appropriation.percentage_dividend}}</td>                                              
                            <td>@{{currency(appropriation.wallet?.balance)}}</td>                                              
                            <td>
                                <button @click="appropriationLogPage(appropriation,i)" class="btn me btn-sm btn-success text-white">
                                    <i class="bi bi-columns-gap" style="color:inherit"></i>
                                </button>
                                <button @click="appropriationModalUpdate(appropriation,i)" class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-pencil-square" style="color:inherit"></i>
                                </button>                                
                                <button class="btn btn-sm btn-danger text-white">
                                    <i class="bi bi-archive" style="color:inherit"></i>
                                </button>                            
                            </td>                        
                        </tr>
                    </tbody>
                </table>

                <div v-show="switchPage==2">
                    <div v-for="(key,i) in Object.keys(appropriationHistories)" :id="'appro_'+i">
                        <button @click="printE('appro_'+i)" class="btn btn-sm btn-light pull-right">Print</button>
                        <p>@{{key}}</p>
                        <table class="table table-bordered bg-secondary text-white mb-0">
                            <tbody>
                                <tr>
                                    <td>Source: @{{appInfo(appropriationHistories[key],'source')}}</td>
                                    <td>Description: @{{appInfo(appropriationHistories[key],'description')}}</td>                                  
                                </tr>
                                <tr>
                                    <td>
                                        <b> Appropriated Amount (Income): @{{currency(appInfo(appropriationHistories[key],'amount'))}}</b>
                                    </td>
                                    <td>Scheme: @{{selected_scheme.name}}</td>
                                </tr>
                            </tbody>                            
                        </table>
                        <table class="table table-sm bg-white table-bordered shadow-sm w-100" style="width:100%">
                            <thead  class="bg-light">
                                <tr>
                                    <th>S/N</th>
                                    <th>Fund Used</th>
                                    <th>Approriations</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(objs,i) in appropriationHistories[key]" >
                                    <td>@{{i+1}}</td>
                                    <td>@{{objs.amount}}</td>
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
                                                    <td>@{{obj.name}}</td>                                                    
                                                    <td>@{{obj.percentage_dividend}}</td>                                                    
                                                    <td>@{{currency(obj?.amount)}}</td>                                                    
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                  <!--   <td>@{{obj.appropriation.name}}</td>
                                    <td>@{{obj.appropriation.percentage_dividend}}</td>
                                    <td>@{{currency(obj.appropriation?.amount)}}</td> -->
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-show="switchPage==3" class="position-relative" id="transaction-sheet">
                    <div class="btn-group" role="group" aria-label="Basic example2">
                        <button @click="switchPage=1" class="btn fs-9 btn-primary text-white" style="">Back to Appropriation</button>
                        <button @click="appropriationModalRemit()" class="btn fs-9 btn-primary text-white" style="">Remit Fund</button>
                    </div>                    
                    <h4 class="text-center"><b>@{{selected_appropriation.name}} / @{{selected_appropriation.department}}</b> Transactions</h4>
                    <p><b>Balance:</b>@{{currency(selected_appropriation_balance)}}</p>
                    <div style="overflow: auto;">
                        <table class="transactions-tables table table-sm table-hover" style="width:130%">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th v-for="header in Object.keys(dynamic_data)">@{{header.replaceAll('_',' ')}}</th>
                                    <th>Total Amount</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(appr,i) in appropriation_transactions?.data">
                                    <td>@{{i+1}}</td>
                                    <td v-for="key in Object.keys(appr.data)">
                                        <span v-if="appr.data[key].type =='number'">@{{currency(appr.data[key].value)}}</span>
                                        <span v-else>@{{appr.data[key].value}}</span>                                    
                                    </td>
                                    <td>@{{currency(appr.amount)}}</td>
                                    <td>
                                        <button @click="editAppropriationTransaction(appr,i)" class="btn btn-info text-white btn-sm me-1"><i class="fs-9 bi bi-pencil"></i></button>
                                        <button @click="deleteAppropriationTransaction(appr.id,i)"  class="btn btn-danger btn-sm"><i class="fs-9 bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div  class="swift swift-bottom-left">
            <div id="round-tab" class="t-menu ">
                <span style="z-index: 101; position: relative;display: flex;padding:0px;height: 40px !important;width: 40px;justify-content: center;align-items: center;">
                    <i class="bi bi-phone-flip"></i>
                </span>	
                <div class="tabsI" @click="switchPage=1"><span>Appro.</span></div>	
                <div class="tabsI" @click="switchPage=2"><span>History</span></div>		              
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="sharehoder-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Appropriation to <code class="alert alert-danger px-2 py-1 "><b>@{{selected_scheme.name}}</b></code></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <span v-for="department in departments" class="d-flex fs-9 p-2 bg-light text-dark rounded m-2 d-inline-block">
                        <input class="me-1" type="checkbox" :value="department.id" v-model="selected_appropriation.department_id"> @{{department.short_name}}
                    </span>  
                </div>
                <div  class="mb-3">
                    <label for="name" class="form-label">Appropriation Name</label>
                    <input  v-model="selected_appropriation.name" type="text" class="form-control" id="name">
                </div>
                <div  class="mb-3">
                    <label for="percentage_dividend" class="form-label">Percentage Dividend</label>
                    <div class="input-group mt-1" id="dividendContainer">
                        <span class="input-group-text">%</span>
                        <input v-model="selected_appropriation.percentage_dividend" id="percentage_dividend" type="number" step="any"  class="form-control" aria-label="Amount (to the nearest dollar)">
                        <span v-if="selected_appropriation.id === ''" id="totalDividend" class="input-group-text">@{{100-totalPercentage}}</span>
                        <span v-else id="totalDividend" class="input-group-text">@{{parseFloat(100 - totalPercentage).toFixed(2)}}</span>
                    </div>
                </div>               
            </div>
            <div class="modal-footer">
                <button v-if="selected_appropriation.id==''" type="button" @click="addAppropriation()" class="btn btn-secondary">Add</button>
                <button v-else type="button" @click="addAppropriation()" class="btn btn-primary text-white">Update</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="remit-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel3"><b> @{{selected_appropriation.name}} / @{{selected_appropriation.department}}</b> Transactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div v-if="reloadDynamicData"  class="mb-3 multiselect w-100">
                <label for="name" class="form-label">Select Required Fields</label>
                <div id="checkboxesToggler" class="selectBox" @click="showCheckboxes()">
                    <input type="text" disabled :value="dynamicDataSelected.join(',').replaceAll('_',' ')"  class="form-control no-event"  aria-label="size 3 select example">
                    <div class="overSelect no-event"></div>
                </div>
                <div v-if="expanded" id="checkboxes">
                    <label v-for="key in data_columns"  :value="" >
                        <input @change="dynamicDataSelectedFunc"  class="me-1" type="checkbox" :value="key"  v-model="dynamicDataSelected"> @{{key.replaceAll('_',' ')}}
                    </label>                    
                </div>

            </div>
                <div v-for="key in Object.keys(appropriation_log.data)" v-show="appropriation_log.data[key].activate==1">
                    <span v-if="key != 'Amount' && key != 'VAT' && key != 'Withholding_Tax' && key != 'Stamp_Duty' && key !='Gross_Amount' && key != 'Total_Taxes'">
                        <div  class="mb-3" >
                            <label for="name" class="form-label"> @{{key.replaceAll('_',' ')}}</label>
                            <input v-if="appropriation_log.data[key].type == 'number'" step="any" v-model="appropriation_log.data[key].value" :type="appropriation_log.data[key].type" class="form-control" >
                            <input v-else v-model="appropriation_log.data[key].value" :type="appropriation_log.data[key].type" class="form-control" >
                        </div>
                    </span>
                    <span v-if="key=='Amount'">                   
                        <div  class="mb-3">
                            <label for="ApplogAmount" class="form-label">Actual Amount</label>
                            <div class="input-group mt-1" id="ApplogAmount">
                                <span class="input-group-text">&#8358;</span>
                                <input v-model="appropriation_log.data.Amount.value" id="percentage_dividend" type="number" step="any"  class="form-control" aria-label="Amount (to the nearest dollar)">
                                <span v-if="appropriation_log.id === ''" id="schemeBalanceOver" class="input-group-text">@{{currency(selected_appropriation.wallet?.balance - grossCalculate)}}</span>
                                <span v-else id="schemeBalanceOver" class="input-group-text">@{{currency((selected_appropriation.wallet?.balance + appropriation_log.total_amount)- grossCalculate)}}</span>
                                <span class="d-none">@{{leftAmountApp}}</span>
                            </div>
                            <div id="schemeBalanceOver2" class="alert alert-danger py-0 px-1 text-center fs-9 d-none">Insufficent Balance</div>
                        </div> 
                    </span>
                    <span v-if="key == 'VAT'">
                        <div class="mb-3" >
                            <label for="name" class="form-label"> @{{key.replaceAll('_',' ')}}</label>                            
                            <div class="input-group mt-1" id="ApplogVat">
                                <input v-model="appropriation_log.data[key].value" :type="appropriation_log.data[key].type"  class="form-control" >
                                <span class="input-group-text">@{{currency(vatCalculate)}}</span>
                            </div>
                        </div>
                    </span>
                    <span v-if="key == 'Withholding_Tax'">
                        <div class="mb-3" >
                            <label for="name" class="form-label"> @{{key.replaceAll('_',' ')}}</label>                            
                            <div class="input-group mt-1" id="ApplogWithholding_Tax">
                                <input v-model="appropriation_log.data[key].value" :type="appropriation_log.data[key].type"  class="form-control" >
                                <span class="input-group-text">@{{currency(withholdingCalculate)}}</span>
                            </div>
                        </div>
                    </span>
                    <span v-if="key == 'Stamp_Duty'">
                        <div class="mb-3" >
                            <label for="name" class="form-label mb-0"> @{{key.replaceAll('_',' ')}}</label>                            
                            <p class="fs-9 text-danger mb-1">Sub Total (Actual Amount + VAT + Withh. tax): @{{currency(subTotalCalculate)}}</p>
                            <div class="input-group mt-1" id="ApplogStampDuty">
                                <input v-model="appropriation_log.data[key].value" :type="appropriation_log.data[key].type"  class="form-control" >
                                <span class="input-group-text">@{{currency(stampDutyCalculate)}}</span>
                            </div>
                        </div>
                    </span>
                </div>
                
            </div>
            <div class="modal-footer">
                <p>Gross: @{{currency(grossCalculate)}}</p>
                <button v-if="appropriation_log.id===''" type="button" @click="transact()" class="btn btn-secondary">Save</button>
                <button v-else type="button" @click="transact()" class="btn btn-primary text-white">Update</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="scheme-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">NEW Scheme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div  class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input  v-model="selected_scheme.name" type="text" class="form-control" id="name">
                </div>                        
            </div>
            <div class="modal-footer">
                <button v-if="selected_scheme.id==''" type="button" @click="addScheme()" class="btn btn-secondary">Add</button>
                <button v-else type="button" @click="addScheme()" class="btn btn-success text-white">Update</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="projection-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen    modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Projection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="project1234">                
                <table>
                    <tbody>
                        <tr>                          
                            <td>Total Amount Expected: @{{appropriations_projection?.amount}}</td>
                        </tr>
                    </tbody>
                </table>                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Percentage Dividend</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(appr,i) in appropriations_projection?.appropriations">
                            <td>@{{appr.name}}</td>
                            <td>@{{appr.department}}</td>
                            <td>@{{appr.percentage_dividend}}</td>
                            <td>@{{currency(appr.amount)}}</td>
                        </tr>
                    </tbody>
                </table>   
            </div>
            <div class="modal-footer">   
                <button @click="printE('project1234')" class="btn btn-sm btn-light pull-right">Print</button>            
            </div>
        </div>
    </div>
    @{{appropriationHistories}}
</div>
        <script>
            const {
                createApp
            } = Vue
            createApp({
                data() {
                    return {
                        switchPage:1,
                        schemes: <?= json_encode($schemes) ?>,
                        selected_scheme:{index:'', id:'', name:'', appropriations:[]},
                        selected_appropriation:{
                            index:'',
                            id:'',
                            name:'',
                            department_id:[],
                            percentage_dividend:'',
                        },
                        selected_appropriation_old_percentage_dividend:0,
                        appropriations_history:{data:[]},
                        appropriation_transactions:[],
                        appropriation_transactions_index:0,
                        departments:<?= json_encode($departments) ?>,
                        appropriations_projection:{},
                        
                        soptions:[
                            '',
                            'BHCPF Funding (FG)',
                            'State Counterpart Funding',
                            'Premium Sales',
                            'Formal Sector Premium',
                            'TiSHIP Premium',
                            'Other Source'
                        ],
                        years:'<? json_encode($years)?>',
                        appropriation_log:{       
                            id:'',                     
                            total_amount:0,
                            data:{
                                Amount:{value:0}
                            }
                        },//selected appropriation_transactions
                        selected_appropriation_balance:0,
                        dynamicDataSelected:[],
                        reloadDynamicData:true,
                        dynamic_data:{    
                            Subject:{activate:1,value:'',type:'text',for:''},            
                            Section_of_Work_Plan:{activate:0,value:'',type:'text',for:''},                
                            File_Name:{activate:0,value:'',type:'text',for:''},
                            File_Number:{activate:0,value:'',type:'text',for:''},
                            Page_Number:{activate:0,value:'',type:'text',for:''},                                
                            Beneficiary:{activate:0,value:'',type:'text',for:''},
                            Account_Number:{activate:0,value:0, type:'number',for:''},
                            Amount:{activate:1,value:0,type:'number',for:'tax',amount:0},
                            Payment_Date:{activate:1,value:0, type:'date',for:''},
                            Trx_Charges:{activate:1,value:0, type:'number',for:'tax',amount:0},
                            VAT:{activate:0,value:0, type:'number',for:'tax',amount:0},
                            Withholding_Tax:{activate:0,value:0, type:'number',for:'tax',amount:0},
                            Stamp_Duty:{activate:0,value:0, type:'number',for:'tax',amount:0},
                            Gross_Amount:{activate:1,value:0, type:'number',for:'tax',amount:0},
                            Total_Taxes:{activate:1,value:0, type:'number',for:'tax',amount:0},
                        },
                        expanded:false

                    }
                },
                created(){
                    this.appropriation_log.data = this.dynamic_data
                },
                computed: {
                    vatCalculate(){                        
                        return (this.appropriation_log.data['VAT'].value/100) * this.appropriation_log.data['Amount'].value
                    },
                    withholdingCalculate(){
                        return (this.appropriation_log.data['Withholding_Tax'].value/100) * this.appropriation_log.data['Amount'].value
                    },
                    subTotalCalculate(){
                        return this.vatCalculate + this.withholdingCalculate +  this.appropriation_log.data['Amount'].value
                    },
                    stampDutyCalculate(){
                        return (this.appropriation_log.data['Stamp_Duty'].value/100) * this.subTotalCalculate
                    },

                    grossCalculate(){
                        let gross = this.stampDutyCalculate + this.vatCalculate + this.withholdingCalculate +  this.appropriation_log.data['Amount'].value + this.appropriation_log.data['Trx_Charges'].value
                        this.appropriation_log.data['Gross_Amount'].value = gross;
                        this.appropriation_log.total_amount = gross
                        this.appropriation_log.data['Total_Taxes'].value = this.vatCalculate+this.withholdingCalculate +this.stampDutyCalculate;                        
                        return gross;
                    },

                    totalPercentage(){
                        if(this.selected_appropriation.id ===''){
                            return this.selected_scheme.appropriations.reduce((previous, current) => previous += current.percentage_dividend, 0)?.toFixed(2)                        
                        }else{
                            let res = this.selected_scheme.appropriations.reduce((previous, current) => previous += current.percentage_dividend, 0)?.toFixed(2) - this.selected_appropriation_old_percentage_dividend;                                                    
                            return parseFloat(res).toFixed(2)
                        }

                    },
                    appropriationHistories(){
                        return this.appropriations_history.data.reduce(
                            (grouped, obj) => ({
                            ...grouped,
                            [this.groupFn(obj)]: [...(grouped[this.groupFn(obj)] || []), obj],
                            }),
                            {}
                        );
                    },
                    data_columns(){
                        return Object.keys(this.dynamic_data);
                    },
                    sourceOptions(){
                        let option="";
                        this.soptions.forEach(item=>{
                            option += `<option class="fs-9" value="${item}">${item}</option>`
                        });
                        return option;
                    },
                    yearsOptions(){
                        let option="";
                        this.years.forEach(item=>{
                            option += `<option class="fs-9" value="${item}">${item}</option>`
                        });
                        return option;
                    },
                    leftAmountApp(){
                        let total = 0;                                          
                        let leftAm = this.selected_appropriation.wallet?.balance - this.grossCalculate
                        if(this.appropriation_log.id !== ''){                            
                             leftAm = (this.selected_appropriation.wallet?.balance + this.appropriation_log.total_amount) - this.grossCalculate
                        }
                        $("#schemeBalanceOver").removeClass('bg-danger text-white')
                        $("#schemeBalanceOver2").addClass('d-none')
                        if(leftAm < 0){
                            $("#schemeBalanceOver").addClass('bg-danger text-white')
                            $("#schemeBalanceOver2").removeClass('d-none')                            
                        }
                        return total                        
                    }
                    
                },
                methods: { 
                    appInfo(arr, key){
                        if(key == 'amount'){
                            return arr.reduce((previousValue, currentValue) => currentValue.amount + previousValue ,0);
                        }
                        if(key == 'source'){
                            return arr[0]?.source
                        }
                        if(key == 'description'){
                            return arr[0]?.description
                        }

                    },
                    groupFn(data){
                        let res = '';
                        Object.keys(data?.fund_category).forEach((key)=>{
                            res = data.fund_category[key]
                        })
                        return res;
                    },
                    async fetchWalletBalance(id,type){
                        let res = await postData('/get_wallet',{owner_id:id,owner_type:type},true)
                        if(res.status == 200){
                            this.selected_appropriation_balance = res.data.balance
                        }
                    },
                    editAppropriationTransaction(appr,i){
                        this.appropriation_log =  JSON.parse(JSON.stringify(appr))
                        this.appropriation_log.total_amount = appr.amount
                        this.appropriation_transactions_index = i
                        var myModal3 = new bootstrap.Modal(document.getElementById('remit-modal'), {})
                        myModal3.show()
                        this.markSelectedDynamicField()
                    },
                    async deleteAppropriationTransaction(appr,i){
                        let resp = await Swal.fire({
                            title: 'Continue Delete?',
                            text:'this should take not more than 20 seconds',
                            icon:'warning',
                            showCancelButton:true,
                            confirmButtonText:'Continue',
                            cancelButtonText:'Cancel',
                        })
                    
                        if(!resp.isConfirmed){
                            return false;
                        };               
                        await postData('/delete_appropriation_transaction',{id:appr.id});
                        this.fetchWalletBalance();
                    },
                    clearAppropriationGarbages(){
                        Object.keys(this.appropriation_log.data).forEach(key=>{
                            if(this.appropriation_log.data[key].activate == 0){
                                if(this.appropriation_log.data[key].type == 'number'){
                                    this.appropriation_log.data[key].value = 0
                                }else{
                                    this.appropriation_log.data[key].value =''
                                }
                            }
                        });
                    },
                    iniTableTransaction(destroy=true){                        
                        let $this = this                        
                        $('.transactions-tables').DataTable({                
                            dom: 'Bfrtip',                            
                            destroy:true,
                            buttons: [                                                              
                                {
                                    extend: 'print',
                                    exportOptions: 
                                    {
                                        stripHtml: false
                                    },
                                    customize: function ( win ) {
                                        $(win.document.body)
                                            .css( {'font-size':'10pt' })
                                            .prepend(
                                                '<style>table{width:135%}</style><svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>'
                                            );
                                        
                                        $(win.document.body).find( '.table' ).before(`<h1 style="text-align:center;margin:10px 0px;">${$this.selected_scheme?.name} - ${$this.selected_appropriation?.name}/${$this.selected_appropriation?.department}</h1>`)
                                        $(win.document.body).find( '.table' ).before(`<p><b>Balance:</b> ${$this.currency($this.selected_appropriation_balance)}</p>`)
                                    }
                                }
                            ],
                            exportOptions: {
                                stripHtml: false
                            },
                            pageLength: 10,
                        });    
                    },
                    async transact(){
                        let resp = await Swal.fire({
                            title: 'Continue transaction?',
                            text:'this should take not more than 20 seconds',
                            showCancelButton:true,
                            confirmButtonText:'Continue',
                            cancelButtonText:'Cancel',
                        })
                    
                        if(!resp.isConfirmed){
                            return false;
                        };         
                        
                        if(this.leftAmountApp<0){
                            Swal.fire('Insufficent balance')
                            return false
                        }
                        if(this.appropriation_log.data['Subject'].value == ''){
                            Swal.fire('Subject field is required')
                            return false
                        }
                         
                        this.clearAppropriationGarbages();
                        //this.appropriation_log.total_amount = this.leftAmountApp                        
                        let res = await postData('/save_appropriation_transaction',{ owner_id:this.selected_appropriation.id,owner_type:'appropriation',transaction:this.appropriation_log},true);
                            if(res.status == 200){
                                if(this.appropriation_log.id ===''){
                                    this.appropriation_transactions.data.unshift(res.data)
                                    showAlert('Transaction Successful');
                                }else{
                                    this.appropriation_transactions.data[this.appropriation_transactions_index]= res.data
                                    showAlert('Transaction Updated');
                                }
                                this.fetchWalletBalance(this.selected_appropriation.id,'appropriation');                                                            
                                $('#remit-modal').modal('hide');    
                            }else{
                                showAlert('Something went wrong','danger');
                                return false;
                            }
                    },
                    dynamicDataSelectedFunc(){
                        Object.keys(this.appropriation_log.data).forEach(item=>{
                            this.appropriation_log.data[item].activate = 0
                        });
                        this.dynamicDataSelected.forEach(item=>{                        
                            this.appropriation_log.data[item].activate = 1                            
                        });                        
                    },
                    showCheckboxes() {                                                
                        this.expanded = !this.expanded;
                    },                   
                    printE(id){
                        PrintElem(id,this.selected_scheme.name)
                    },
                    async appropriate(){
                        let resp = await Swal.fire({
                            title: 'Continue Appropriation?',
                            text:'this should take not more than 20 seconds',
                            showCancelButton:true,
                            confirmButtonText:'Continue',
                            cancelButtonText:'Cancel',
                        })
                    
                        if(!resp.isConfirmed){
                            return false;
                        };               
                         

                        if(this.selected_scheme.wallet.balance <1){
                            Swal.fire('Insufficent balance')
                            return false;
                        }

                        if(this.selected_scheme.appropriations.length <1){
                            Swal.fire('No appropriations available')
                            return false;
                        }
                        let res = await postData('/appropriate',{scheme_id:this.selected_scheme.id},true);
                        if(res.status == 200){
                            this.appropriations_history.data.unshift(res.data.appropriationHistory);
                            this.schemes[this.selected_scheme.index].wallet.balance = 0
                            this.selected_scheme.wallet.balance = 0
                            showAlert('Appropriation Completed');
                        }else{
                            showAlert('Appropriation Failed Completed','error');
                        }
                    },
                    currency(amount){
                        if(amount){
                            let res = new Intl.NumberFormat('NGN', { style: 'currency', currency: 'NGN' }).format(amount)
                            return res.replace("NGN",'')
                        }
                        return '0.00';
                    },
                    resetPageData(){
                        this.appropriation_transactions = []
                        this.switchPage = 1
                        this.appropriation_transactions_index =0
                    },
                    async schemeTriggered(){
                        if(this.selected_scheme.index !== ''){
                            let res = await postData('/get_appropriation_histories',{owner_id:this.selected_scheme.id,owner_type:'scheme'},true);
                            if(res.status == 200){
                                this.appropriations_history = res.data
                                this.resetPageData()
                            }else{
                                showAlert('Something went wrong');
                                return false;
                            }
                        }else{
                            this.appropriations_history = [];
                            this.resetPageData()
                        }
                    },
                    async fundScheme(){
                        if(this.selected_scheme.index===''){
                            Swal.fire("Please select Scheme")
                            return 0
                        } 
                        const { value: data } = await Swal.fire({
                            html: `
                            <div style="width:75%">
                            
                            <span class="form-label">Amount</span>
                            <input type="number" step="any" id="swal-input2" class="swal2-input mb-3 mt-1 w-100">
                            <span class="form-label">Select Source</span>
                            <select  id="swal-input1" class="swal2-input mb-3 mt-1 w-100">
                                ${this.sourceOptions}
                            </select>
                            <span class="form-label">Select Fund Month and Year:</span>
                            <input type="date" id="swal-year" class="swal2-input mb-3 mt-1 w-100">
                            <span class="form-label">Description</span>
                            <input type="text" step="any" id="description-i" class="swal2-input mt-1 w-100">
                            </div>
                            `,
                            focusConfirm: false,
                            preConfirm: () => {
                                let val = document.getElementById('swal-input2').value
                                let val1 = document.getElementById('swal-input1').value
                                let val2 = document.getElementById('description-i').value
                                let val3 = document.getElementById('swal-year').value
                                
                                if(val == ''){
                                    Swal.showValidationMessage(`Please Enter an Amount`)
                                }
                                if(val1 == ''){
                                    Swal.showValidationMessage(`Please Select Fund Source`)
                                }
                                if(val3 == ''){
                                    Swal.showValidationMessage(`Please Select Fund Month and Year`)
                                }
                                return {amount:val,source:val1,description:val2,fund_month_year:val3}
                            }
                        })

                        if (Object.keys(data).length>0) { 
                            let resp = await Swal.fire({
                                            title: 'Continue Funding?',
                                            text:'this should take not more than 20 seconds',
                                            showCancelButton:true,
                                            confirmButtonText:'Continue',
                                            cancelButtonText:'Cancel',
                                        })
                                    
                                        if(!resp.isConfirmed){
                                            return false;
                                        };               
                                                                
                            let res = await postData('/fund_scheme',{fund_month_year:data.fund_month_year,amount:data.amount,source:data.source,description:data.description, scheme_id:this.selected_scheme.id},true);
                            if(res.status == 200){
                                res = res.data  
                                this.selected_scheme.wallet = res.wallet;
                                this.schemes[this.selected_scheme.index]['wallet'] = res.wallet
                                showAlert(res.msg)
                            }
                        }
                    },
                    errorMessage(msg){
                        return '<div class="errorMsg alert p-1 my-2 alert-danger">'+msg+'</div>'
                    },                   
                    async addAppropriation(){      
                        if(this.selected_scheme.index===''){
                            Swal.fire("Please select Scheme")
                            return 0
                        }       

                        if(this.selected_appropriation.name===''){
                            Swal.fire("Name field is required")
                            return 0
                        }  
                        
                        if(this.selected_appropriation.department_id.length <1){
                            Swal.fire("Department field is required")
                            return 0
                        }  


                        this.schemes[this.selected_scheme.index].appropriations.push(this.selected_appropriation);
                        $("#totalDividend").removeClass('bg-danger text-white')
                        $(".errorMsg").remove()
                                                
                        if(this.totalPercentage >100 && this.selected_appropriation.id === '' ){                            
                            $("#totalDividend").addClass('bg-danger text-white')
                            this.schemes[this.selected_scheme.index].appropriations.pop()
                            $("#dividendContainer").after(this.errorMessage('Invalid Value: left accepted value is '+ (100-this.totalPercentage)))
                            return 0
                        }/* else{
                            let left = 100 + this.selected_appropriation_old_percentage_dividend - this.selected_appropriation.percentage_dividend
                            if(left > 100){
                                $("#totalDividend").addClass('bg-danger text-white')
                                this.schemes[this.selected_scheme.index].appropriations.pop()
                                $("#dividendContainer").after(this.errorMessage('Invalid Value: left accepted value is '+ (100-this.totalPercentage)))
                                return 0
                            }
                        }     */       
                        if(this.selected_appropriation.id !== ''){
                            this.schemes[this.selected_scheme.index].appropriations.pop()
                        }
                        this.selected_appropriation.scheme_id = this.selected_scheme.id;
                        let res = await postData('/add_appropriation',this.selected_appropriation,true)
                        if(res.status == 200){
                            res = res.data
                            if(this.selected_appropriation.id==''){
                                this.schemes[this.selected_scheme.index].appropriations.pop() 
                                this.schemes[this.selected_scheme.index].appropriations.push(res.appropriation)
                                showAlert(res.msg)
                                $("#sharehoder-modal").modal('hide');
                            }else{
                                showAlert(res);
                            }
                        }
                        
                    },
                    appropriationModalUpdate(appropriation,index) {                      
                        this.selected_appropriation = appropriation
                        this.selected_appropriation.index = index   
                        this.selected_appropriation_old_percentage_dividend = Number(appropriation.percentage_dividend)                    
                        var myModal = new bootstrap.Modal(document.getElementById('sharehoder-modal'), {})
                        myModal.show()
                    
                    },
                    async appropriationLogPage(appropriation,i){
                        this.selected_appropriation =appropriation
                        this.selected_appropriation.index = i
                        this.selected_appropriation_balance = appropriation.wallet?.balance
                        this.switchPage=3
                        let res = await postData('/get_transactions',{owner_id:appropriation.id,owner_type:'appropriation'},true);
                            if(res.status == 200){
                                $('.transactions-tables').DataTable().clear().destroy();
                                this.appropriation_transactions = res.data
                                let $this = this
                                setTimeout(()=>{                                    
                                    $this.iniTableTransaction()
                                },1000)
                            }else{
                                showAlert('Something went wrong');
                                return false;
                            }
                        
                    },
                    markSelectedDynamicField(){
                        this.dynamicDataSelected = []
                        Object.keys(this.appropriation_log.data).forEach(key=>{
                            if(this.appropriation_log.data[key].activate == 1){
                                this.dynamicDataSelected.push(key)
                            }
                        });
                    },
                    appropriationModalRemit(){
                        this.appropriation_log.amount = 0
                        this.appropriation_log.id = ''
                        this.appropriation_log.data = this.dynamic_data;
                        var myModal3 = new bootstrap.Modal(document.getElementById('remit-modal'), {})
                        myModal3.show()
                        this.markSelectedDynamicField()
                        //this.dynamicDataSelected = []
                       /*  let $this = this
                        this.reloadDynamicData = false
                        setTimeout(()=>{
                            $this.reloadDynamicData = true
                        },100) */
                     /*   $('#dynamicData').val(null)
                        setTimeout(function(){
                            $('#dynamicData').select2();
                        },100) */
                    },
                    async projectionModal() {                      
                        let res = await postData('/get_appropriations_projection',{scheme_id:this.selected_scheme.id},true);
                            if(res.status == 200){
                                this.appropriations_projection = res.data
                            }else{
                                showAlert('Something went wrong');
                                return false;
                            }
                        var myModal = new bootstrap.Modal(document.getElementById('projection-modal'), {})
                        myModal.show()                    
                    },
                    appropriationModalAdd(appropriation) {                      
                        this.selected_appropriation = {
                            id:'',
                            name:'',
                            department_id:[],
                            percentage_dividend:'',
                        }
                        var myModal = new bootstrap.Modal(document.getElementById('sharehoder-modal'), {})
                        myModal.show()
                    
                    },

                    async addScheme(){      

                        if(this.selected_scheme.name==''){
                            Swal.fire("Name field is required")
                            return 0
                        }      
                 

                        let res = await postData('/add_scheme',this.selected_scheme,true)
                        if(res.status == 200){
                            res = res.data
                            if(this.selected_scheme.id==''){                                
                                let index =  this.schemes.push(res.scheme) - 1
                                this.selected_scheme = res.scheme;
                                this.selected_scheme.index = index;

                                showAlert(res.msg)
                            }else{
                                this.schemes[this.selected_scheme.index] = this.selected_scheme
                                showAlert(res);
                            }
                            this.closeModal('scheme-modal')
                        }
                        
                    },
                    schemeModalUpdate() {                                              
                        var myModal = new bootstrap.Modal(document.getElementById('scheme-modal'), {})
                        myModal.show()
                    },
                    schemeModalAdd(scheme) {                      
                        this.selected_scheme = {
                            index:'',
                            id:'',
                            name:'',   
                            appropriations:[]                         
                        }
                        var myModal = new bootstrap.Modal(document.getElementById('scheme-modal'), {})
                        myModal.show()

                    },
                    closeModal(id){
                        var myModalEl = document.getElementById(id)
                        var modal = bootstrap.Modal.getInstance(myModalEl)
                        modal.hide()
                    }
                },
                mounted() {
                    let $this = this
                    setTimeout(function(){
                        $('.selectpicker2').select2();
                      //  $this.iniTableTransaction(false)
                    }, 4000)
                    $('#remit-modal').on("click", function (e) {                                                                                          
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
                var switf = new SA_SwiftMenu2({wrapper:'round-tab',radius:150,rotate: 43});
	                switf.switf();

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