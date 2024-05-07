<template>
    <div class="bg-white rounded-lg-only shadow-lg-only" style="overflow-x: auto">
      <div v-if="switchPageOne == 0" style="height: inherit">
        <div style="height: inherit; display: flex; flex-direction: column">
          <div style="height: inherit; ">
            <table class="table mtable table-lg driveInRight " style="min-width:1000px;">
              <thead>
                <tr class="fw-bold">
                  <th class="small-col" >
                    <input v-if="selected_scheme.appropriations.length > 0" type="checkbox" @click="selectAllAppropriation(selected_scheme.appropriations, $event)">
                  </th>
                  <th class="small-col">SN.</th>
                  <th>Appropriation Name</th>
                  <th>Department Code</th>
                  <th>Current Percentage ({{ totalAppropriationPercentageValue }} %)</th>
                  <th>#</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(appropriation, i) in selected_scheme.appropriations" :key="i">
                  <td class="small-col"><input type="checkbox" :value="appropriation.id" v-model="selected_appropriations_to_appropriate"></td>
                  <td class="small-col">{{ i + 1 }}</td>
                  <td>{{ appropriation.name }}</td>
                  <td>{{ appropriation.department }}</td>
                  <td>{{appropriation.percentage_dividend }}</td>
                  <td>
                    <button @click="appropriationModalUpdate(appropriation, i)" class="me-2 btn btn-sm btn-info text-white">
                      <i class="bi bi-pencil-square" style="color:inherit"></i>
                    </button>
                    <button @dblclick="removeItem(appropriation,i)" class=" btn btn-sm btn-danger text-white"><span
                        class="pi pi-times "></span> </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="p-3" style="height: 15%;">
            <div v-if="canPerformAction('appropriate')">
              <button v-if="selected_scheme?.id !== '' || selected_scheme?.id !== null" title="Run Appropriation" @click="appropriate()" class="m-0 fs-9 btn btn-success text-white d-inline-block">
                <i class="bi bi-bar-chart-steps"></i> <span class="mobile-none">Run Appropriation</span>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div  v-if="switchPageOne == 1" >
      <table class="table mtable table-lg driveInRight" style="min-width:1000px;">
        <thead class="bg-light">
          <tr>
            <th>SN.</th>
            <th>Appropriation Name</th>
            <th>Department Code</th>
            <th>Current Percentage (%)</th>
            <th>Total Appropriation Income Across Funding Years(<span>&#8358;</span>)</th>
            <th>Total Balance Across Funding Years (<span>&#8358;</span>) </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(appropriation, i) in selected_scheme.appropriations" :key="i">
            <td>{{ i + 1 }}</td>
            <td>{{ appropriation.name }}</td>
            <td>{{ appropriation.department }}</td>
            <td>{{ appropriation.percentage_dividend }}</td>
            <td>{{ $globals.currency(appropriation.total_collection) }}</td>
            <td>{{ $globals.currency(appropriation.balance) }}</td>
          </tr>
        </tbody>
      </table>
      </div>

        <div  v-if="switchPageOne == 2" >
          <table class="table mtable table-lg driveInRight " :key="selected_fund_category" style="min-width:1000px;">
            <thead class="">
              <tr>
                <th>SN.</th>
                <th>Appropriation Name</th>
                <th>Department Code</th>
                <th>Appropriation Income (<span>&#8358;</span>) </th>
                <th>Balance (<span>&#8358;</span>) </th>
                <th>
                  <button v-if="canPerformAction('report')" @click="appropriationLogPage(null, null)" class="btn me btn-sm btn-success text-white">
                    <i class="bi bi-columns-gap" style="color:inherit"></i>
                  </button>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(appropriation, i) in selected_month_appropriations" :key="i">
                <td>{{ i + 1 }}</td>
                <td>{{ appropriation.name }}</td>
                <td>{{ appropriation.department }}</td>
                <td>{{ $globals.currency(appropriation_data_summary?.income?.[appropriation.name] ?? 0) }}</td>
                <td>{{ $globals.currency(appropriation_data_summary?.balance?.[appropriation.name]??0) }} </td>
                <td>
                  <button v-if="canPerformAction('report')" @click="appropriationLogPage(appropriation, i)" class="btn me btn-sm btn-success text-white">
                    <i class="bi bi-columns-gap" style="color:inherit"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <Transition name="fade">
            <div v-if="showModal">
                <sharehoder-modal
                    :departments="departments"
                    :appropriation_types="appropriation_types"
                    :selected_appropriation="selected_appropriation"
                    :new_app_request ="newAppropriation"
                    @closeModal="closeModal()"
                    @addapp="resolveAppropriation($event)"
                />
            </div>
        </Transition>
    </div>
  </template>

  <script>
  import SharehoderModal from './Modals/SharehoderModal.vue'
  export default {
    props: {
        appropriation_types:{
        type: Object,
        default: () => [],
      },
      departments:{
        type: Array,
        default: () => [],
      },
      permissions: {
        type: Array,
        default: () => [],
      },
      switchPageOne: {
        default: 0,
      },
      appropriations_history: {
        default: [],
      },
      selected_month_appropriations: {
        default: [],
      },
      selected_scheme: {
        type: Object,
        default: () => ({ id: '', appropriations: [] }),
      },
      schemes:{
        default:[]
      },
      pageSwitched:{
        default:10
      },
      selected_fund_category: {
        default: '',
      },
      appropriation_data_summary: {
        type:Object,
        default:{}
      },
    },
    components:{
        'sharehoder-modal':SharehoderModal
    },
    data() {
      return {
        selected_appropriation: [],
        appropriationHistories: [],
        appropriations: [],
        selected_appropriations_to_appropriate:[],
        showModal:false,
        newAppropriation:false,
      };
    },
    computed: {
      totalAppropriationPercentageValue() {
        return this.selected_scheme.appropriations.reduce((total, appropriation) => total + appropriation.percentage_dividend, 0);
      },
    },
    watch: {
      selected_fund_category(newVal, oldVal) {
        this.appropriationHistoriesResolver();
      },
      selected_scheme: {
        handler(newValue, oldValue) {
          this.$nextTick(() => {
            this.initTable(4000);
          });
        },
        deep: true,
        immediate: true
      },
      switchPageOne: {
        handler(newValue, oldValue) {
          this.$nextTick(() => {
            this.initTable(0);
          });
        },
        deep: true,
        immediate: true
      },
      '$parent.openAppModal': {
        handler(newValue, oldValue) {
          if(oldValue !== undefined){
            this.showModal = newValue
            this.newAppropriation = true;
          }
        },
        deep: true,
        immediate: true
      }

    },
    methods: {
        closeModal(){
          this.showModal = false
          this.$emit('closeAppModal')
          /* output to ScheemScreen */
        },
        async resolveAppropriation(selected_appropriation) {
            try {
                this.selected_appropriation = selected_appropriation;
                if (this.selected_scheme?.id =='' || this.selected_scheme?.id == null) {
                  throw new Error("Please select Programme"); //i.e scheme
                }
                this.selected_appropriation.scheme_id = this.selected_scheme.id;
                if (this.selected_appropriation?.appropriation_type_id =='' || this.selected_appropriation?.appropriation_type_id ==null) {
                  throw new Error("Name field is required");
                }

                if (this.selected_appropriation.department_id.length < 1) {
                  throw new Error("Department field is required");
                 }

                const schemeIndex = this.$globals.getIndexOf(this.schemes, this.selected_scheme);
                const scheme = this.schemes[schemeIndex];

                if (scheme.appropriations.findIndex(app =>app.appropriation_type_id == this.selected_appropriation.appropriation_type_id ) !=-1 && this.newAppropriation) {
                        throw new Error("Appropriation Already exist");
                    }
                scheme.appropriations.push(this.selected_appropriation);

                $("#totalDividend").removeClass("bg-danger text-white");
                $(".errorMsg").remove();

                if (this.selected_appropriation.id !== "") {
                scheme.appropriations.pop();
                }

                this.selected_appropriation.scheme_id = this.selected_scheme.id;

                const res = await postData("/add_appropriation", this.selected_appropriation, true);

                if (res.status === 200) {
                    const responseData = res.data;
                    const app = res.data.appropriation
                    if (!scheme.appropriations.find(app => app.scheme_id !== this.selected_appropriation.scheme_id || app.appropriation_type_id !== this.selected_appropriation.appropriation_type_id)) {
                        scheme.appropriations.push(responseData.appropriation); // Reactive way
                    } else {
                        const index = scheme.appropriations.findIndex(app => app.id === this.selected_appropriation.id);
                        scheme.appropriations[index] = { ...scheme.appropriations[index], ...this.selected_appropriation }; // Replace with a new object
                    }

                    showAlert(responseData.msg);
                    this.selected_scheme.appropriations = scheme.appropriations.slice();
                    this.$emit('updateSchemes',[schemeIndex,scheme.appropriations]);
                    //this.initTable(500);
                    /* out to scheme screen */
                  /*   setTimeout(()=>{
                      // location.reload();
                    },2000) */
                    this.showModal = false;
                }
            } catch (error) {
                Swal.fire(error.message);
            }
            this.$emit('closeAppModal')
        },
        selectAllAppropriation(appropriations, e){
            if(e.target.checked){
                this.selected_appropriations_to_appropriate = appropriations.map((item)=>item.id)
            }else{
                this.selected_appropriations_to_appropriate =[]
            }
        },
      appropriationHistoriesResolver() {
        this.appropriationHistories = this.appropriations_history.data?.reduce(
          (grouped, obj) => ({
            ...grouped,
            [obj.fund_category]: [...(grouped[obj.fund_category] || []), obj],
          }), {}
        );
      },
      getAppropriationIncome(name) {
        let total = 0;

        if (this.selected_fund_category !== '') {
          const appropriationHistory = this.appropriationHistories[this.selected_fund_category];

          if (appropriationHistory) {
            appropriationHistory.forEach(historyItem => {
              const appropriationItem = historyItem.appropriation.find(item => {
                return item.name.toLowerCase() === name.toLowerCase();
              });

              if (appropriationItem) {
                total += appropriationItem.amount;
              }
            });
          }
        }

        return total;
      },
      canPerformAction(permission) {
        console.log(this.permissions,222)
        return this.permissions.includes(permission);
      },
      appropriationModalUpdate(appropriation, index) {
        this.showModal = true
        this.newAppropriation = false
        this.selected_appropriation = appropriation
      },
      async removeItem(app, apps, index) {
            const confirmation = await Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this item!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (confirmation.isConfirmed) {
                try {
                    await postData('/appropriation/delete/' + app?.id, {}, true);
                    apps.splice(index, 1);
                    Swal.fire('Deleted', 'Your item has been deleted.', 'success');
                } catch (error) {
                    console.error('Error deleting item:', error);
                    Swal.fire('Failed to delete item', '', 'error');
                }
            }
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
            Swal.fire({title:'Invalid Action',text:'Appropriation Dividend exceeds 100% '+ this.totalPercentage() +'% given',icon:'warning'})
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

        let res = await postData('/appropriate',{
                          scheme_id:this.selected_scheme.id,
                          fund_category: this.selected_fund_category,
                          appropriation_ids:this.selected_appropriations_to_appropriate
                        },true);
        if(res.status == 200){
            //this.selected_scheme = res.data.scheme
            this.selected_scheme.wallet.balance = 0;
            showAlert('Appropriation Completed');
            location.reload();
        }else{
            showAlert('Appropriation Failed Completed','error');
        }
    },
    initTable(timeout=1000){
      setTimeout(()=>{
          if($('.mtable').DataTable && true){
            $('.mtable').DataTable({
              scrollY: '50vh',
              destroy:true,
              searching: false,
            ordering: false,
            paging: false,
            info:false,
            scrollCollapse: true,
            initComplete: function () {
              /* this.api().table().header().remove(); */
              $('.table').css({width:'100%'})
            }
          });
        }
      },timeout)
    },

      async appropriationLogPage(appropriation, i) {

        if(appropriation === null){
          localStorage.setItem('appropriations', JSON.stringify(this.selected_scheme.appropriations));
          localStorage.setItem('page_type', 'all');
          this.$emit('openTransaction', {
              type: 'all',
              appropriations:this.selected_scheme.appropriations,
          });
          return;
        }
        localStorage.setItem('page_type', 'single');
        this.selected_appropriation = appropriation;
        localStorage.setItem('selected_appropriation', JSON.stringify(appropriation));
        localStorage.setItem('selected_appropriation_index', i);

        this.selected_appropriation.index = i;
        this.selected_appropriation_balance = appropriation.wallet?.balance;
        this.$emit('openTransaction', {
          type:'single',
          selected_appropriation: this.selected_appropriation,
        });
      },
    },
    created() {

      try {
        if(this.selected_fund_category){
            this.appropriationHistoriesResolver();
        }
      } catch (e) {
        console.log('Error:' + e);
      }
    },
    mounted(){
      //this.initTable();
    }
  };
  </script>

  <!-- Add your component-specific styles here if needed -->
  <style scoped>
    /* Add your component-specific styles here if needed */
    /* .dt-scroll-body table thead{
      display: none !important;
    } */
  </style>
