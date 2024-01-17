<template>
    <div class=" mt-3 px-4 py-2" style="height: 92.1vh;">
        <br>        
    <!-- <div class=" bg-light pageTitleCard w-100 fs-9 text-danger ps-4">::Report</div> -->
    <div class="position-relative" >
        <div class=" py-2">    
            <div class="mb-2 mt-2 row">   
                <div class="col-md-4">         
                    <div class="mb-3 input-group">
                        <select @change="schemeTriggered(1)" v-model="selected_scheme" class=" mx-1 form-control d-inline-block" style="width: 240px;">            
                            <option v-for="(scheme,i) in schemes" :value="scheme">{{scheme?.name}}</option>
                        </select>       
                    </div>                  
                </div>  
                <div class="col-md-4">
                    <div class="mb-3 input-group ">
                        <span class="input-group-text">Search:</span>
                        <input class="form-control" type="text" value="" @keyup="searchFunc($event)">
                    </div>
                </div>                
                
            </div>
            <div style="height: 76vh; overflow:auto;" class="px-3">   
                <div class="">                    
                    <div v-for="key in Object.keys(appropriations)" class="row">                        
                        <div class="col-md-4 col-lg-4 mb-4 d-flex align-items-center">
                            <div class="px-3 bg-white rounded-lg">
                                <div style="width: 100%; height:200px"><canvas style="position:relative;height:200px !important;" :id="'report_pie'+key"></canvas></div>
                                {{initPieChart('report_pie'+key,[appropriations[key].reduce((t,v)=>{return t+v[0]},0),appropriations[key].reduce((t,v)=>{return t+v[1]},0)], key)}}                           
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-8 mb-4">
                            <div class="px-3 bg-white rounded-lg">
                                <div style="visibility: hidden;font-size:2px;" class="searchKey">{{key}}</div>
                                <div style="width: 90%"><canvas :id="'report_'+key"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>         
            </div>  
        </div>
    </div>
</div>
</template>

<script>
    export default{
        props:{
            schemes:Array,
            fund_categories:Array,
        },
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
                appropriations:[],
                dynamic_data:this.$globals.dynamic_data,                
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
                                       // console.log(tooltipItem, data)                               
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

    }
</script>
<style >
    select{
        border: 1px solid #ccc;
    }
    #page-content {
        overflow-y: hidden;
    }
</style>