<template>
  <div class="modal show" id="config-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered ">
      <div class="modal-content">
        <div class="w-100 d-flex justify-space-between" style="justify-content: space-between;">
          <TabMenu :model="items" class="m-1 w-100">
            <template #item="{ item, props }">
              <a v-if="item.type == 'exp-dept'" v-ripple v-bind="props.action" class="p-3 text-center" @click="changeTab(item.type)">
                <span class="font-bold">Head</span>
                <span class="pi pi-spin mx-1  pi-spinner" v-if="tabLoader == item.type"></span>
                <span class="pi mx-1 pi-arrow-right-arrow-left" v-else></span>
                <span class="font-bold">Subhead</span>
              </a>
              <a v-else v-ripple v-bind="props.action" class="p-3 text-center" @click="changeTab(item.type)">
                <span class="font-bold">{{ item.name }}</span>
                <span class="pi pi-spin  pi-spinner" v-if="tabLoader == item.type"></span>
              </a>
            </template>
          </TabMenu>
          <div class="p-3">
            <button @click="$emit('closeModal')" type="button" class="btn-close" data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
        </div>
        <div class="modal-body mt-0 px-3" style="min-height: 400px;">          
          <Heads @oncompleted="oncompleted" v-if="selectedTab == 'dept'"/>    
          <SubHead @oncompleted="oncompleted"     v-if="selectedTab == 'exp'" />
          <HeadSubHeadSync @isLoading="loader($event,'exp-dept')" @oncompleted="oncompleted"     v-if="selectedTab == 'exp-dept'"  />
        </div>
        <div class="modal-footer" v-if="activeTab === 'update'">
        </div>
      </div>
    </div>
  </div>
</template>

<script>


import TabMenu from 'primevue/tabmenu';

import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import DepartmentComponent from './../Config/DepartmentComponent.vue';
import SubHead from './../Config/SubHead.vue';
import Heads from './../Config/Heads.vue';
import HeadSubHeadSync from './../Config/HeadSubHeadSync.vue'
SubHead
export default {
  props: {
    selected_config: {
      type: Object,
      default: () => ({ id: '', name: '' }),
    },
  },
  components: {
    DepartmentComponent,
    SubHead,
    TabMenu,
    Dropdown,
    InputText,
    Heads,
    HeadSubHeadSync
  },
  data() {
    return {
      items: [
        { name: 'Heads', image: 'amyelsner.png', type: 'dept' },
        { name: 'Subhead', image: 'annafali.png', type: 'exp' },
        { name: '-', image: 'annafali.png', type: 'exp-dept' },

        /* { name: 'Expenditure Units', image: 'amyelsner.png', type: 'dept' }, */
        /* { name: 'Expenditure Categories', image: 'annafali.png', type: 'exp' } */
      ],      
      expenditure_categories: [],
      tabLoader: '',
      selectedTab: 'dept',
      activeTab: 'update', // Default to 'update' tab
    };
  },
  created() {        
  },
  methods: {  
    loader(e,type){
      if(e){
        this.tabLoader = type
      }else{
        this.tabLoader = ''
      }
    },
    async changeTab(type) {
      this.tabLoader = type
      this.selectedTab = type             
    },
    oncompleted(){
      this.tabLoader = ''
    }
  },
};
</script>

<!-- Add your component-specific styles here if needed -->
<style scoped>
.modal {
  display: block !important;
  background: #000a;
}

.overflow-x {
  overflow-x: scroll;
}

.nobreak {
  white-space: nowrap;
}

a {
  text-decoration: none !important;
}
</style>
<style>
  ul.p-tabmenu-nav.p-reset {
    padding: 0px !important;
    margin-bottom: 0px !important;
  }
</style>