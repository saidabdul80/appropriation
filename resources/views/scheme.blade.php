<?php

use App\Models\AppropriationType;
use App\Models\Department;
use App\Models\Scheme;
$schemes = Scheme::all();
$departments = Department::all();
$appropriationTypes = AppropriationType::all();
$dyear = 2020;
$cyear = date('Y');
$years  =  array();
$logedInUser  = auth()->user();
/* for ($i=$dyear; $i <= $cyear ; $i++) { 
    $years[] = $i;
} */
?>
@extends('layouts/master')
@section('content')

<div class="background px-4 pb-2 pt-3" style="height: 92.1vh;z-index:1"> 
<div class=" bg-light pageTitleCard w-100 fs-9 text-danger ps-4">::@{{pageName}}</div>
    <div class="position-relative" >
        
        <div class="mb-2 mt-2">                   
            <select @change="schemeTriggered(1)" v-model="selected_scheme" class=" mx-1 form-control d-inline-block" style="width: 240px;">            
                <option v-for="(scheme,i) in schemes" :value="scheme">@{{scheme?.name}}</option>
            </select>            
           <!--  <button @click="schemeModalAdd()" class="btn mx-1 btn-primary d-inline-block">New Scheme</button> -->
            <button v-if="getSchemeIndex(selected_scheme) !== ''" @click="schemeModalUpdate()" class="update-scheme btn fs-8 mx-1 btn-success d-inline-block">Update Programme <!-- Scheme->Programme --></button>
        </div>
        <div class=" bg-white px-3 rounded shadow py-2">            
            <div class="mb-3 row shadow">
                <div class="col-lg-6 p-0 d-flex">
                    <div class="btn-group top-header-button" role="group" aria-label="Basic example">
                        <!-- <button v-if="getSchemeIndex(selected_scheme) !== ''" title="Archive" @click="showArchived()" class="m-0 fs-9 btn btn-secondary text-white d-inline-block">
                        <i class="bi bi-archive"></i><span class="mobile-none">Archive</span></button> -->
                        @can('new_appropriation')
                        <button v-if="getSchemeIndex(selected_scheme) !== ''" title="New Appropriation" @click="appropriationModalAdd()" class="m-0 fs-9 btn btn-secondary text-white d-inline-block">
                            <i class="bi bi-journals"></i><span class="mobile-none">New Approp.</span></button>
                        @endcan
                        @can('projection')
                        <button v-if="getSchemeIndex(selected_scheme) !== ''" title="Projection" @click="projectionModal()" class="m-0 fs-9 btn btn-secondary text-white d-inline-block">
                            <i class="bi bi-collection"></i><span class="mobile-none">Projection</span></button>
                        @endcan
                        <!-- @can('appropriate')
                        <button v-if="getSchemeIndex(selected_scheme) !== ''" title="Appropriate" @click="appropriate()" class="m-0 fs-9 btn btn-secondary text-white d-inline-block">
                            <i class="bi bi-bar-chart-steps"></i> <span class="mobile-none">Appropriate</span></button>
                        @endcan -->
                        @can('fund_scheme')
                        <button v-if="getSchemeIndex(selected_scheme) !== ''" title="Fund Programme" @click="fundModal()" class="m-0 btn fs-9 btn-secondary text-white d-inline-block">
                        <i class="bi bi-database-down"></i><span class="mobile-none">Credit</span></button>
                        @endcan                        
                        <button @click="appropriationModalRemit()" v-if="getSchemeIndex(selected_scheme) !== ''" title="Debit Programme" class="m-0 btn fs-9 btn-secondary text-white d-inline-block">                        
                        <i class="bi bi-database-down"></i><span class="mobile-none">Debit</span></button>                        
                        @can('report')
                        <button v-if="getSchemeIndex(selected_scheme) !== ''" title="Fund Programme" @click="report()" class="m-0 btn fs-9 btn-secondary text-white d-inline-block">
                        <i class="bi bi-inboxes"></i><span class="mobile-none">Report</span></button>                        
                        @endcan
                    </div>
                </div>
                <div class="col-lg-3 py-2">
                    @can('income')
                    <p class="m-0 fs-8">
                    <b>@{{selected_scheme.name}}</b> @{{selected_fund_category}} Income: <span>&#8358;</span> <span v-show="selected_fund_category == ''">@{{currency(selected_scheme.total_collection)}}</span> <span v-show="selected_fund_category != ''"> @{{currency(category_income)}} </span>
                    </p>
                    @endcan
                    <p class="m-0 fs-8">Available Income for @{{selected_scheme.wallet?.fund_category}} Approp.: <span>&#8358;</span> @{{currency(selected_scheme.wallet?.balance)}} </p>
                </div>
                <div class="col-lg-3 py-2 fs-8">
                    @can('balance')
                    <p class="m-0 fs-8"><b>@{{selected_scheme.name}}</b>  Balance: <span>&#8358;</span> <span v-if="selected_fund_category == ''">@{{currency(selected_scheme.balance)}}</span>  <span v-show="selected_fund_category!= ''">@{{currency(category_income_balance)}} </span></p>
                    @endcan
                    @can('expenditure')
                    <p href="#" class="m-0 fs-8 " ><span class="display-inline-block me-2"> Expenditure: <span>&#8358;</span> 
                        <span v-if="getCategoryIncomeBalance == 0">0.00</span> 
                        <span v-else>@{{currency(category_income - category_income_balance)}} </span>
                        </span>                        
                        <a @click="expenditureDetails()" class="d-inline-block" href="#">View Details </a>                        
                    </p>
                    @endcan
                </div>
            </div>
             <!-- <h5 class="py-1 px-2 mb-0 bg-secondary text-white shadow rounded">Selected Scheme: @{{selected_scheme.name}}</h5> -->
            <div class="mb-3 input-group">
                <span class="input-group-text">Funding Year</span>
                <select  @change="monthYearTriggered(1)" v-model="selected_fund_category" class="form-control">
                    <option value=""> </option>
                    <option v-for="month_year in fund_categories">@{{month_year}}</option>
                </select>
            </div>
            <ul  v-show="switchPage==1" class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a @click="switchPageOne=0" class="fs-8 nav-link" id="home-tab2" data-bs-toggle="tab" href="#home2" role="tab" aria-controls="home2" aria-selected="true">
                        Appropriations</a>
                </li>
                <li  class="nav-item" role="presentation">
                    <a @click="switchPageOne=1" class="fs-8 nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                        Appropriated Income </a>
                </li>
                <li v-if="appropriations?.length>0" class="nav-item" role="presentation">
                    <a @click="switchPageOne=2" class="fs-8 nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                    <strong> @{{selected_fund_category}}</strong>  Appropriated Income <!--   Approp. Current Balance --></a>
                </li>                
            </ul>
            <div id="pagerContainer" style="overflow:auto; height:54.5vh;">               
                <div  v-show="switchPage===1" style="height: inherit">                 
                    <div v-show="switchPageOne==0" style="height: inherit">
                        <div style="height: inherit;display:flex;flex-direction:column">
                            <div style="height: 85%;overflow: auto;">                    
                                <table class="table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th><input v-if="selected_scheme.appropriations.length>0"  type="checkbox" @click="selectAllAppropriation(selected_scheme.appropriations, $event)"></th>
                                            <th>SN.</th>
                                            <th>Appropriation Name</th>
                                            <th>Department Code</th>
                                            <th>Current Percentage (%)</th>                                                                                
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(appropriation,i) in selected_scheme.appropriations">
                                            <td><input type="checkbox" :value="appropriation.id" v-model="selected_appropriations_to_appropriate"></td>
                                            <td>@{{i+1}}</td>
                                            <td>@{{appropriation.name}}</td>
                                            <td>@{{appropriation.department}}</td>
                                            <td>@{{appropriation.percentage_dividend}}</td>                                                                              
                                            <td>                                    
                                                <button @click="appropriationModalUpdate(appropriation,i)" class="me-2 btn btn-sm btn-info text-white">
                                                    <i class="bi bi-pencil-square" style="color:inherit"></i>
                                                </button>                                                                                  
                                            </td>                        
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class=" bg-white p-3" style="height: 15%;">
                                @can('appropriate')
                                <button v-if="selected_scheme?.id !== ''" title="Appropriate" @click="appropriate()" class="m-0 fs-9 btn btn-secondary text-white d-inline-block">
                                    <i class="bi bi-bar-chart-steps"></i> <span class="mobile-none">Appropriate</span></button>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <table v-show="switchPageOne==1" class="table">
                        <thead class="bg-light">
                            <tr>
                                <th>SN.</th>
                                <th>Appropriation Name</th>
                                <th>Department Code</th>
                                <th>Current Percentage (%)</th>                                                                           
                                <th>Total Appropriation Income Accross Funding Years(<span>&#8358;</span>)</th>      
                                <th>Total Balance Accross Funding Years (<span>&#8358;</span>) </th>                                                      
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(appropriation,i) in selected_scheme.appropriations">
                                <td>@{{i+1}}</td>
                                <td>@{{appropriation.name}}</td>
                                <td>@{{appropriation.department}}</td>
                                <td>@{{appropriation.percentage_dividend}}</td>                                                                          
                                <td>@{{currency(appropriation.total_collection)}}</td>                                       
                                <td>@{{currency(appropriation.balance)}}</td>                                                                
                            </tr>
                        </tbody>
                    </table>
                    <table v-show="switchPageOne==2" class="table table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>SN.</th>
                                <th>Appropriation Name</th>
                                <th>Department Code</th>        
                                <th>Appropriation Income (<span>&#8358;</span>) </th>                                                                       
                                <th>Balance (<span>&#8358;</span>) </th>                                                        
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(appropriation,i) in appropriations">
                                <td>@{{i+1}}</td>
                                <td>@{{appropriation.name}}</td>
                                <td>@{{appropriation.department}}</td>       
                                <td>@{{currency(getAppropriationIncome(appropriation.name))}}</td>                                                                                                              
                                <td>@{{currency(appropriation.wallet?.balance)}}</td>                                                                                                                       
                                <td>
                                    <button @click="appropriationLogPage(appropriation,i)" class="btn me btn-sm btn-success text-white">
                                        <i class="bi bi-columns-gap" style="color:inherit"></i>
                                    </button>                                                             
                                </td>                        
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-show="switchPage===2">
                    <div v-for="(key,i) in Object.keys(appropriationHistories)" :id="'appro_'+i">
                        <button @click="printE('appro_'+i)" class="btn btn-sm btn-light pull-right">Print</button>
                        <p>@{{key}}</p>
                        <table id="myHeader" class="table table-bordered bg-secondary text-white mb-0">
                            <tbody>
                                <tr>
                                    <td>Source: @{{appInfo(appropriationHistories[key],'source')}}</td>
                                    <td>Description: @{{appInfo(appropriationHistories[key],'description')}}</td>                                  
                                </tr>
                                <tr>
                                    <td>
                                        <b> Total Appropriated (Income): <span>&#8358;</span>@{{currency(appInfo(appropriationHistories[key],'amount'))}}</b>                        
                                    </td>
                                    <td>Programme: @{{selected_scheme.name}}</td>
                                </tr>
                            </tbody>                            
                        </table>
                        <table class="table table-sm bg-white table-bordered shadow-sm w-100" style="width:100%">
                            <thead  class="bg-light">
                                <tr>
                                    <th>S/N</th>
                                    <th>Approprations Details</th>
                                    <th>Approriations</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(objs,i) in appropriationHistories[key]" >
                                    <td>@{{i+1}}</td>
                                    <td>
                                        <p class="mb-0"><b>Amount:</b> <span>&#8358;</span>@{{ currency(objs.amount)}}</p>
                                        <p class="mb-0"><b>Appropriation Date:</b> @{{objs.created_at}}</p>
                                        <p class="" v-if="selected_scheme.fund_category =='month'"><b>Funding Month:</b> @{{objs.fund_month_year}}</p>                                                                                
                                    </td>
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
                <div v-show="switchPage===3" class="position-relative p-2 border" id="transaction-sheet">
                    <div class="btn-group" role="group" aria-label="Basic example2">
                        <button @click="switchPageFunc(1)" class="btn fs-9 btn-primary text-white" style="">Back to Appropriation</button>
                 
                    </div>                    
                    <h4 class="text-center"><b>@{{selected_appropriation.name}} / @{{selected_appropriation.department}}</b> Transactions</h4>
                    <p class="inline-block mb-2 me-4"><b>Balance:</b><span>&#8358;</span> @{{currency(selected_appropriation_balance)}}</p>
                    <p class="inline-block mb-2"><b>Total Expenditure: <span>&#8358;</span></b> @{{currency(total_expenditure_appropriation)}}</p>
                    <div style="overflow: auto;" v-if="transactions_table_toggle">
                        <table class=" fs-8 table-bordered transactions-tables table table-sm table-hover" style="width:130%">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th v-for="header in Object.keys(dynamic_data)">
                                        @{{header.replaceAll('_',' ')}}
                                    </th>
                                    <th>Total Amount</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody >                                
                             <!--    @{{resetTotalExpenditureForAppropriation()}} -->
                                <tr v-for="(appr,i) in appropriation_transactions?.data" v-memo="appropriation_transactions?.data">                                    
                                    <td>@{{parseInt(i+1)}} </td>
                                    <td v-for="key in Object.keys(appr.data)">
                                        <span v-if="key.includes('₦')">
                                                @{{currency(appr.data[key])}}
                                        </span>
                                        <span v-else>
                                            <span v-if="appr.data[key].type =='number'">@{{currency(appr.data[key].value)}}</span>
                                            <span v-else>@{{appr.data[key].value}}</span>                                    
                                        </span>
                                    </td>
                                    <td><i style="visibility: hidden;"> @{{i==0?total_expenditure_appropriation=0:''}}</i> @{{currency(appr.amount)}}   @{{getTotalExpenditureForAppropriation(appr.amount)}}</td>                                
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
                <div class="tabsI" @click="switchPageFunc(1)"><span>Appro.</span></div>	
                <div class="tabsI" @click="switchPageFunc(2)"><span>History</span></div>		              
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="sharehoder-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Appropriation</h5>
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
                    <select  v-model="selected_appropriation.appropriation_type_id" type="text" class="form-control" id="name">
                        <option v-for="appropriation_type in appropriation_types" :value="appropriation_type.id">@{{appropriation_type.name}}</option>
                    </select>
                </div>
                <div  class="mb-3">
                    <label for="percentage_dividend" class="form-label">Percentage Dividend</label>
                    <div class="input-group mt-1" id="dividendContainer">
                        <span class="input-group-text">%</span>
                        <input v-model="selected_appropriation.percentage_dividend" id="percentage_dividend" type="number" step="any"  class="form-control" aria-label="Amount (to the nearest dollar)">
                        <!-- <span v-if="selected_appropriation.id === ''" id="totalDividend" class="input-group-text">@{{100-totalPercentage}}</span> -->
                        <!-- <span v-else id="totalDividend" class="input-group-text">@{{parseFloat(100 - totalPercentage).toFixed(2)}}</span> -->
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

<div class="modal fade" id="debit-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel3"><b> @{{selected_appropriation.name}} / @{{selected_appropriation.department}}</b> Transactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="mb-3 input-group">
                <span class="input-group-text">Funding Year</span>
                <select  @change="monthYearTriggered(1)" v-model="selected_fund_category" class="form-control">
                    <option value=""> </option>
                    <option v-for="month_year in fund_categories">@{{month_year}}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="forApp" class="form-label">Select Appropriation</label>
                <select v-model="selected_appropriation" class="form-control">
                    <option v-for="ap in appropriations" :value="ap">@{{ap.name}}</option>
                </select>
            </div>
            <div v-if="reloadDynamicData"  class="mb-3 multiselect w-100">
                <label for="name" class="form-label">Select Required Fields</label>
                <div id="checkboxesToggler" class="selectBox" @click="showCheckboxes()">
                    <input type="text" disabled :value="dynamicDataSelected.join(',').replaceAll('_',' ')"  class="form-control no-event"  aria-label="size 3 select example">
                    <div class="overSelect no-event"></div>
                </div>
                <div v-show="expanded" id="checkboxes">
                    <label v-for="key in data_columns" v-show="dynamic_data[key].show==1" :value="" >
                        <input @change="dynamicDataSelectedFunc"  class="me-1" type="checkbox" :value="key"  v-model="dynamicDataSelected"> @{{key.replaceAll('_',' ')}}
                    </label>                    
                </div>
            </div>
                <div v-for="key in Object.keys(appropriation_log.data)" v-show="appropriation_log.data[key].activate==1">
                    <span v-if="key != 'Amount' && key != 'VAT_%' && key != 'Withholding_Tax_%' && key != 'Stamp_Duty_%' && key !='Gross_Amount' && key != 'Total_Taxes'">
                        <div  class="mb-3" >
                            <label for="name" class="form-label"> @{{key.replaceAll('_',' ')}}</label>
                            <input v-if="appropriation_log.data[key].type == 'number'" step="any" v-model="appropriation_log.data[key].value" :type="appropriation_log.data[key].type" class="form-control" >
                            <input v-else v-model="appropriation_log.data[key].value" :type="appropriation_log.data[key].type" class="form-control" >
                        </div>
                    </span>
                    <span v-if="key=='Amount'">                   
                        <div  class="mb-3">
                            <label for="ApplogAmount" class="form-label">Actual Amount (<span class="fs-9 text-dark mb-1">@{{currency(appropriation_log.data.Amount.value)}}</span>)</label>
                            <div class="input-group mt-1 mb-1" id="ApplogAmount">
                                <span class="input-group-text">&#8358;</span>
                                <input v-model="appropriation_log.data.Amount.value" id="percentage_dividend" type="number" step="any"  class="form-control" aria-label="Amount (to the nearest dollar)">
                                <span v-if="appropriation_log.id === ''" id="schemeBalanceOver" class="input-group-text">@{{currency(selected_appropriation.wallet?.balance - grossCalculate)}}</span>
                                <span v-else id="schemeBalanceOver" class="input-group-text">@{{currency((selected_appropriation.wallet?.balance + appropriation_log.total_amount)- grossCalculate)}}</span>
                                <span class="d-none">@{{leftAmountApp}}</span>
                            </div>                            
                            <div id="schemeBalanceOver2" class="alert alert-danger py-0 px-1 text-center fs-9 d-none">Insufficent Balance</div>
                        </div> 
                    </span>
                    <span v-if="key == 'VAT_%'">
                        <div class="mb-3" >
                            <label for="name" class="form-label"> @{{key.replaceAll('_',' ')}}</label>                            
                            <div class="input-group mt-1" id="ApplogVat">
                                <span class="input-group-text">%</span>
                                <input v-model="appropriation_log.data[key].value" :type="appropriation_log.data[key].type"  class="form-control" >
                                <span class="input-group-text">@{{currency(vatCalculate)}}</span>
                            </div>
                        </div>
                    </span>
                    <span v-if="key == 'Withholding_Tax_%'">
                        <div class="mb-3" >
                            <label for="name" class="form-label"> @{{key.replaceAll('_',' ')}}</label>                            
                            <div class="input-group mt-1" id="ApplogWithholding_Tax">
                                <span class="input-group-text">%</span>
                                <input v-model="appropriation_log.data[key].value" :type="appropriation_log.data[key].type"  class="form-control" >
                                <span class="input-group-text">@{{currency(withholdingCalculate)}}</span>
                            </div>
                        </div>
                    </span>
                    <span v-if="key == 'Stamp_Duty_%'">
                        <div class="mb-3" >
                            <label for="name" class="form-label mb-0"> @{{key.replaceAll('_',' ')}}</label>                            
                            <p class="fs-9 text-danger mb-1">Sub Total 1 (Actual Amount + VAT_% + Withh. tax): @{{currency(subTotalCalculate)}}</p>
                            <div class="input-group mt-1 mb-1" id="ApplogStampDuty">
                                <span class="input-group-text">%</span>
                                <input v-model="appropriation_log.data[key].value" :type="appropriation_log.data[key].type"  class="form-control" >
                                <span class="input-group-text">@{{currency(stampDutyCalculate)}}</span>
                            </div>
                            <p class="fs-9 text-danger mb-1">Sub Total 2 (Actual Amount + VAT_% + Withh. + Stamp D. tax): @{{currency(subTotalCalculate+stampDutyCalculate)}}</p>
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
                <h5 class="modal-title" id="staticBackdropLabel">NEW Programme</h5>
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

<div class="modal fade" id="add-fund-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Fund @{{selected_scheme.name}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">   
                <div v-if="selected_scheme.fund_type == 'api'"  class="mb-3">
                    <label for="name" class="form-label">Select Fund Month and Year:</label>    
                    <select @change="fetchFundFunc()" v-model="fund.date"  id="swal-input1" class=" mt-1 w-100 form-control">                                                
                        <option value=""></option>
                        <option v-for="fundDate in selected_scheme.funds" class="fs-9" :value="fundDate.fund_category">@{{fundDate.fund_category}}</option>`                        
                    </select>                     
                </div>         
                <div  class="mb-3">
                    <label for="name" class="form-label">Amount  
                        <span class="fs-8" v-if="fund.amount>1000">(<span>&#8358;</span> @{{currency(fund.amount)}})</span>
                    </label>
                    <input v-if="selected_scheme.fund_type == 'api'"   disabled :value="currency(fund.amount)" type="text" step="any" class="form-control">
                    <input v-else v-model="fund.amount" type="number" step="any" class="form-control">
                </div>     
                <div  class="mb-3">
                    <label for="name" class="form-label">Select Source</label>                
                    <select v-model="fund.source_id"  id="swal-input1" class=" mt-1 w-100 form-control">                    
                            <option v-for="source in selected_scheme.sources" class="fs-9" :value="source.id">@{{source.name}}</option>`                        
                    </select>
                </div>              
                <div  v-if="selected_scheme.fund_type === 'entry'"  class="mb-3">
                    <label for="name" class="form-label">Select Fund Month and Year:</label>                
                    <input v-model="fund.date" type="month" id="swal-year" class=" form-control">
                </div>
                <div  class="mb-3">
                    <label for="name" class="form-label">Description</label>                
                    <input v-model="fund.description" type="text" step="any" id="description-i" class="form-control">                            
                </div>                                  
            </div>
            <div class="modal-footer">
                <button type="button" @click="fundNow()" class="btn btn-success text-white">Fund</button>
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
                            <td>@{{currency(appr.amount)}} @{{getCategoryIncomeBalance(appr.amount)}}</td>
                        </tr>
                    </tbody>
                </table>   
            </div>
            <div class="modal-footer">   
                <button @click="printE('project1234')" class="btn btn-sm btn-light pull-right">Print</button>            
            </div>
        </div>
    </div>
    
</div>
<div class="modal fade" id="expenditure-details-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog w-75   modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" id="project1235">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"> <b> @{{selected_fund_category}} @{{selected_scheme?.name}} Expenditure Details</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" >                                      
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>S/N</th>
                            <th>Appropriation</th>                            
                            <th>Amount (<span>&#8358;</span>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(appr,i) in expenditure_details">
                            <td>@{{i+1}}</td>
                            <td>@{{appr.name}}</td>
                            <td>@{{currency(appr.expenditure_total_amount)}} @{{calcTotalExpenditureAmount(appr.expenditure_total_amount)}}</td>
                        </tr>
                    </tbody>
                </table>   
                <p>
                    <b>Total</b>: <span>&#8358;</span>
                    <span v-if="getCategoryIncomeBalance == 0">0.00</span> 
                    <span v-else>@{{currency(category_income - category_income_balance)}} </span>
                </p>
            </div>
            <div class="modal-footer">   
                <button @click="printE('project1235')" class="btn btn-sm btn-light pull-right">Print</button>            
            </div>
        </div>
    </div>
    
</div>
        <script>
            const {
                createApp
            } = Vue
            createApp({
                data() {
                    return {
                        appropriation_types:<?= json_encode($appropriationTypes)?>,
                        fund:{
                            amount:0,
                            date:'',
                            description:'',
                            source_id:''
                        },
                        switchPage:1,
                        schemes: <?= json_encode($schemes) ?>,
                        selected_scheme:{index:'', id:'', name:'', appropriations:[]},
                        selected_appropriation:{
                            index:'',
                            id:'',
                            appropriation_type_id:'',
                            department_id:[],
                            percentage_dividend:'',
                        },
                        selected_appropriation_old_percentage_dividend:0,
                        appropriations_history:{                            
                            data:[]
                        },
                        appropriation_transactions:[],
                        appropriation_transactions_index:0,
                        departments:<?= json_encode($departments) ?>,
                        appropriations_projection:{},
                        scheme_appropriations:[],                       
                        years:'<?= json_encode($years)?>',
                        appropriation_log:{                                   
                            id:'dead',                     
                            total_amount:0,
                            data:{
                                Amount:{value:0}
                            }
                        },//selected appropriation_transactions
                        selected_appropriation_balance:0,
                        dynamicDataSelected:[],
                        reloadDynamicData:true,
                        dynamic_data:<?= json_encode(config('data.dynamic_data'))?>,
                        expanded:false,
                        fund_categories:[],
                        selected_fund_category:'',
                        appropriations:[],
                        switchPageOne:0,
                        expenditure_details:[],
                        category_income:0,
                        category_income_balance:0,
                        expenditure_amount:0,
                        total_expenditure_appropriation:0,
                        data_columns:[],
                        transactions_table_toggle:true,
                        logedInUser:<?= json_encode($logedInUser) ?>,
                        selected_appropriations_to_appropriate:[]

                    }
                },
                created(){
                    this.appropriation_log.data = this.dynamic_data
                    this.data_columns = Object.keys(this.dynamic_data);                    
                },
                computed: {                                                       
                    pageName(){
                        return (this.switchPage == 1)?'Home Page':'Appropriation History'
                    },
                    vatCalculate(){                        
                        return (this.appropriation_log.data['VAT_%'].value/100) * this.appropriation_log.data['Amount'].value
                    },
                    withholdingCalculate(){
                        return (this.appropriation_log.data['Withholding_Tax_%'].value/100) * this.appropriation_log.data['Amount'].value
                    },
                    subTotalCalculate(){
                        return this.vatCalculate + this.withholdingCalculate +  this.appropriation_log.data['Amount'].value
                    },
                    stampDutyCalculate(){
                        return (this.appropriation_log.data['Stamp_Duty_%'].value/100) * this.subTotalCalculate
                    },
                    grossCalculate(){
                        let gross = this.stampDutyCalculate + this.vatCalculate + this.withholdingCalculate +  this.appropriation_log.data['Amount'].value + this.appropriation_log.data['Trx_Charges'].value
                        this.appropriation_log.data['Gross_Amount'].value = gross;
                        this.appropriation_log.total_amount = gross
                        this.appropriation_log.data['Total_Taxes'].value = this.vatCalculate+this.withholdingCalculate +this.stampDutyCalculate;                        
                        return gross;
                    },                    
                    appropriationHistories(){
                        return this.appropriations_history.data?.reduce(
                            (grouped, obj) => ({
                                ...grouped,
                                [obj.fund_category]: [...(grouped[obj.fund_category] || []), obj],
                            }),
                            {}
                        );                                       
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
                    selectAllAppropriation(appropriations, e){
                        if(e.target.checked){                                                   
                            this.selected_appropriations_to_appropriate = appropriations.map((item)=>item.id)
                        }else{
                            this.selected_appropriations_to_appropriate =[]
                        }
                    },
                    async showArchived(){
                    },
                    async archive(appropriation){
                    },
                    async fetchFundFunc(){
                        if(this.fund.date == ''){
                            Swal.fire('Invalid date select')
                            return false
                        }
                        
                        let res = await postData('fetch_fund',{fund_category:this.fund.date,scheme_id:this.selected_scheme.id},true)
                        if(res.status == 200){
                            this.fund.amount = res.data
                        }else{
                            Swal.fire(res.data)
                        }
                    },
                    resetTotalExpenditureForAppropriation(){
                        this.total_expenditure_appropriation=0
                    },
                    getTotalExpenditureForAppropriation(amount){                        
                        this.total_expenditure_appropriation += amount                   
                    },
                    report(){
                        if(this.selected_scheme.id ==''){
                            Swal.fire('Select a programme')
                            return false
                        }
                        window.open('/report/'+this.selected_scheme.id,'blank')
                    },
                    calcTotalExpenditureAmount(amount){
                        this.expenditure_amount += amount
                    },
                    getCategoryIncomeBalance(){
                        this.category_income_balance = this.appropriations.reduce((total,appropriation)=>{                             
                            return total+appropriation?.wallet.balance
                        },0)
                    },
                    getCategoryIncome(){                  
                        this.category_income = this.appropriationHistories[this.selected_fund_category]?.reduce((total,item)=>{
                            return total +item.amount
                        },0);                        
                    },                    
                    async expenditureDetails(){
                        var myModal = new bootstrap.Modal(document.getElementById('expenditure-details-modal'), {})
                        myModal.show()                            
                        let res = await postData('/fetch_expenditures',{scheme_id:this.selected_scheme.id,fund_category:this.selected_fund_category},true)
                        if(res.status == 200){
                            this.expenditure_details = res.data                            
                        }else{
                            showAlert('something went wrong');
                        }
                        
                    },
                    getAppropriationIncome(name){
                        let total = 0;
                        if(this.selected_fund_category !=''){
                            this.appropriationHistories[this.selected_fund_category]?.forEach(item=>{
                                total += item.appropriation.find((item) => item.name === name )?.amount;
                            });
                        }
                            return total;
                    },
                    async directFund(){
                         const { value: fund_category }  
                            = await Swal.fire({
                                title: 'Continue Direct Funding?',                                
                                html: `<p class='mx-0 w-100'>Select Funding year</p><input type="month"  id="fund-category-swal"  class="swal2-input w-100 m-0"><p class="fs-9 text-danger">this will take few seconds</p>`,
                                showCancelButton:true,
                                confirmButtonText:'Continue',
                                cancelButtonText:'Cancel',                                                                                    
                                preConfirm: (fund_category) => {
                                    let value = document.getElementById('fund-category-swal').value                                    
                                    if (!value) {
                                        Swal.showValidationMessage('Funding Year field is required') 
                                    }
                                    return value
                                }
                            })                                
                        if(!fund_category){
                            return false;
                        };               
                                                            
                        let res = await postData('/direct_fund_scheme',{fund_month_year:this.fund.date,amount:this.fund.amount,source_id:this.fund.source_id,description:this.fund.description, scheme_id:this.selected_scheme.id},true);
                        if(res.status == 200){
                            res = res.data  
                            this.selected_scheme.wallet = res.wallet;
                            this.schemes[this.getSchemeIndex(this.selected_scheme)]['wallet'] = res.wallet
                            showAlert(res.msg)
                            $('#add-fund-modal').modal('hide')
                        }      
                    },
                    getSchemeIndex(scheme){                     
                        return this.schemes.findIndex((item) =>item.id ==scheme.id )
                    },  
                    appInfo(arr, key){                    
                        if(key == 'amount'){
                            return arr.reduce((previousValue, currentValue) => currentValue.amount + previousValue ,0);
                        }
                        if(key == 'source'){
                            return arr[0]?.source.name
                        }
                        if(key == 'description'){
                            return arr[0]?.description
                        }
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
                        var myModal3 = new bootstrap.Modal(document.getElementById('debit-modal'), {})
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
                                    title:'<?=agencyName()?>',
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
                        this.appropriation_log.data['VAT_₦'] = this.vatCalculate
                        this.appropriation_log.data['Withholding_Tax_₦'] = this.withholdingCalculate
                        this.appropriation_log.data['Stamp_Duty_₦'] = this.stampDutyCalculate
                        //this.appropriation_log.total_amount = this.leftAmountApp                        
                        let res = await postData('/save_appropriation_transaction',{fund_category:this.selected_fund_category, owner_id:this.selected_appropriation.id,owner_type:'appropriation',transaction:this.appropriation_log},true);
                            if(res.status == 200){
                                if(this.appropriation_log.id ===''){
                                    this.appropriation_transactions?.data?.unshift(res.data)
                                    showAlert('Transaction Successful');
                                   // location.reload()
                                }else{
                                    this.appropriation_transactions.data[this.appropriation_transactions_index]= res.data
                                    //location.reload()
                                    showAlert('Transaction Updated');
                                }
                                let fund_category = this.selected_fund_category;
                                //this.selected_fund_category = ''
                                this.monthYearTriggered();
                                this.fetchWalletBalance(this.selected_appropriation.id,'appropriation');                                                            
                                $('#debit-modal').modal('hide');    
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
                    totalPercentage(){
                        if(this.selected_appropriations_to_appropriate.length >0){
                            let total = 0;
                            this.selected_scheme.appropriations.forEach((item) =>{
                                if(this.selected_appropriations_to_appropriate.includes(item.id)){
                                    total += item.percentage_dividend
                                }
                                })
                            return total.toFixed(2)
                        }else{                            
                            return 0
                        }
                    },
                    async appropriate(){
                        if(this.selected_appropriations_to_appropriate.length <1){
                            Swal.fire('select appropriation')
                            return false
                        }

                        if(this.totalPercentage() != 100){
                            Swal.fire('Total percentage dividend expected 100%, ' +this.totalPercentage() +'% given')
                            return false
                        }

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

                        let res = await postData('/appropriate',{scheme_id:this.selected_scheme.id,appropriation_ids:this.selected_appropriations_to_appropriate},true);
                        if(res.status == 200){
                            //this.selected_scheme = res.data.scheme
                            this.selected_scheme.wallet.balance = 0;
                            //this.schemes[this.getSchemeIndex(this.selected_scheme)] = res.data.scheme                            
                            //this.schemeTriggered(1)
                            //this.appropriations_history.data.unshift(res.data.appropriationHistory);
                            showAlert('Appropriation Completed');
                            location.reload();
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
                    resetPageData(trigger=0){ //1 means from the right source
                        this.appropriation_transactions = []
                        /* if(trigger==1){
                        } */
                       // this.appropriations = []
                            this.switchPage = 1
                        this.appropriation_transactions_index = 0
                    },
                    async monthYearTriggered(trigger=0){//1 means from the right source
                            let res = await postData('/get_prepared_data',{scheme_id:this.selected_scheme.id,fund_category:this.selected_fund_category},true);
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
                            }
                    },
                    async schemeTriggered(trigger=0){                        
                        if(this.getSchemeIndex(this.selected_scheme) !== ''){                            
                            let res = await postData('/fund_month_year',{scheme_id:this.selected_scheme.id},true);
                            //scheme_appropriations
                            //let res = await postData('/get_appropriation_histories',{owner_id:this.selected_scheme.id,owner_type:'scheme'},true);                            
                            if(res?.status == 200){
                                //this.appropriations_history = res.data
                                this.fund_categories = res.data                                
                                if(trigger===1){
                                    localStorage.setItem('pageNum',1)
                                }
                                localStorage.setItem('expiresOn', Date.now() + 1000*60*15)
                                localStorage.setItem('selected_scheme',JSON.stringify(this.selected_scheme))
                                this.selected_fund_category = ''  
                                if(trigger==1){
                                    localStorage.removeItem('selected_fund_category')
                                }                              
                            }else{
                                showAlert('Something went wrong');
                                return false;
                            }
                        }else{
                            this.resetPageData(1)
                        }
                        if(trigger == 1){
                            this.appropriations_history = {data:[]}
                            this.appropriations = []                                
                        }
                        
                        this.category_income = 0   
                        this.expenditure_amount = 0
                        this.category_income_balance =0       
                        this.getCategoryIncome()
                        this.getCategoryIncomeBalance()                              
                    },
                    fundModal(){
                        var myModal3 = new bootstrap.Modal(document.getElementById('add-fund-modal'), {})
                        myModal3.show()
                    },
                    async fundNow(){
                        let $this = this;
                        if(this.selected_scheme === ''){
                            Swal.fire("Please select Programme")//i.e scheme
                            return 0
                        } 
                        
                        if(this.fund.amount == ''){
                            Swal.fire('Amount field is required');
                            return false
                        }

                        if(this.fund.source_id == ''){
                            Swal.fire('Source field is required');
                            return false
                        }
                        if(this.fund.date == ''){
                            Swal.fire('Fund date field is required');
                            return false
                        }

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
                                                            
                        let res = await postData('/fund_programme',{fund_month_year:this.fund.date,amount:this.fund.amount,source_id:this.fund.source_id,description:this.fund.description, scheme_id:this.selected_scheme.id},true);
                        if(res.status == 200){
                            res = res.data  
                            let index = this.getSchemeIndex(this.selected_scheme)
                            this.schemes[index]= res.scheme
                            this.selected_scheme = res.scheme                            
                            showAlert(res.msg)
                            $('#add-fund-modal').modal('hide')
                        }                        
                        
                        
                    },
                    errorMessage(msg){
                        return '<div class="errorMsg alert p-1 my-2 alert-danger">'+msg+'</div>'
                    },                   
                    async addAppropriation(){      
                        if(this.selected_scheme==''){
                            Swal.fire("Please select Programme") //i.e scheme
                            return 0
                        }       

                        if(this.selected_appropriation.appropriation_type_id===''){
                            Swal.fire("Name field is required")
                            return 0
                        }  
                        
                        if(this.selected_appropriation.department_id.length <1){
                            Swal.fire("Department field is required")
                            return 0
                        }  


                        this.schemes[this.getSchemeIndex(this.selected_scheme)].appropriations.push(this.selected_appropriation);
                        $("#totalDividend").removeClass('bg-danger text-white')
                        $(".errorMsg").remove()
                                                
                        /* if(this.totalPercentage >100 && this.selected_appropriation.id === '' ){                            
                            $("#totalDividend").addClass('bg-danger text-white')
                            this.schemes[this.getSchemeIndex(this.selected_scheme)].appropriations.pop()
                            $("#dividendContainer").after(this.errorMessage('Invalid Value: left accepted value is '+ (100-this.totalPercentage)))
                            return 0
                        } */
                        /* else{
                            let left = 100 + this.selected_appropriation_old_percentage_dividend - this.selected_appropriation.percentage_dividend
                            if(left > 100){
                                $("#totalDividend").addClass('bg-danger text-white')
                                this.schemes[this.getSchemeIndex(this.selected_scheme)].appropriations.pop()
                                $("#dividendContainer").after(this.errorMessage('Invalid Value: left accepted value is '+ (100-this.totalPercentage)))
                                return 0
                            }
                        }     */       
                        if(this.selected_appropriation.id !== ''){
                            this.schemes[this.getSchemeIndex(this.selected_scheme)].appropriations.pop()
                        }
                        this.selected_appropriation.scheme_id = this.selected_scheme.id;
                        let res = await postData('/add_appropriation',this.selected_appropriation,true)
                        if(res.status == 200){
                            res = res.data
                            if(this.selected_appropriation.id==''){
                                this.schemes[this.getSchemeIndex(this.selected_scheme)].appropriations.pop() 
                                this.schemes[this.getSchemeIndex(this.selected_scheme)].appropriations.push(res.appropriation)
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
                    switchPageFunc(num){                        
                        this.switchPage = num
                        /* if(num==1){
                            //front data
                            localStorage.removeItem('selected_appropriation')
                        } */
                        localStorage.setItem('pageNum',num)
                    },                    
                    async appropriationLogPage(appropriation,i){
                        this.selected_appropriation = appropriation
                        localStorage.setItem('selected_appropriation', JSON.stringify(appropriation))
                        localStorage.setItem('selected_appropriation_index', i)
                        
                        this.selected_appropriation.index = i
                        this.selected_appropriation_balance = appropriation.wallet?.balance
                        this.switchPageFunc(3)
                        this.transactions_table_toggle =false
                        let res = await postData('/get_transactions',{owner_id:appropriation.id,owner_type:'appropriation'},true);
                            if(res.status == 200){                                
                                /* $('.transactions-tables').DataTable().clear().destroy(); */
                                this.transactions_table_toggle =true
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
                        
                        //this.selected_appropriation = appropriation

                        this.appropriation_log.amount = 0
                        this.appropriation_log.id = ''
                        this.appropriation_log.data = this.dynamic_data;
                        var myModal3 = new bootstrap.Modal(document.getElementById('debit-modal'), {})
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

                                showAlert(res.msg)
                            }else{
                                this.schemes[this.getSchemeIndex(this.selected_scheme)] = this.selected_scheme
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
                    },
                    async gotoCurrentPageAfterReload(){
                        if(localStorage.pageNum){                          
                            if(Date.now() > localStorage.getItem('expiresOn')){
                                localStorage.clear()
                                return false;
                            }
                            let num = localStorage.getItem('pageNum');
                            this.switchPageFunc(num);
                            
                            if(localStorage?.selected_scheme){
                                this.selected_scheme = JSON.parse(localStorage.getItem('selected_scheme'))
                                await this.schemeTriggered()   
                                if(localStorage.selected_fund_category){                
                                    this.selected_fund_category = localStorage.getItem('selected_fund_category')
                                    await this.monthYearTriggered()                                                                                                
                                }
                                if(num == 3){
                                    this.appropriationLogPage(JSON.parse(localStorage.getItem('selected_appropriation')),localStorage.getItem('selected_appropriation_index'))
                                }
                            }
                            
                        }
                    },
                    inputKeyPress(){
                        alert();
                        $("#swal-input2").keypress(function(){
                        console.log($(this).prev(),$(this).val())
                        $(this).prev().html(($(this).val()).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'))
                    })
                    }
                },
               /*  renderTracked({ key, target, type }) {
                console.log('renderTracked:', { key, target, type });
                }, */
                renderTriggered({ key, target, type }) {
                console.log('renderTriggered:', { key, target, type });
                },

                mounted() {
                    let $this = this
                   // this.gotoCurrentPageAfterReload()
                    setTimeout(function(){
                        $('.selectpicker2').select2();
                      //  $this.iniTableTransaction(false)
                    }, 4000)
                    $('#debit-modal').on("click", function (e) {                                                                                          
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
/* 
                setTimeout(()=>{

                
                    // Get the header                
                    window.header = document.getElementById("myHeader");                    
                    // Get the offset position of the navbar
                    window.sticky = header.offsetTop;
                    window.pager = document.getElementById('pagerContainer');
                    // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
                    function myFunction() {                        
                        if (pager.pageYOffset > sticky) {
                            header.classList.add("sticky");
                        } else {
                            header.classList.remove("sticky");
                        }
                    }
                    pager.addEventListener("scroll", function() {myFunction()});
                },3000) */
            })
        </script>
        @endsection