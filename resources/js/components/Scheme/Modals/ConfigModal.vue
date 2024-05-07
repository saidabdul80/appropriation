<template>
  <div class="" style="height: 95vh;" id="config-modal" >
    <div class="bg-white rounded w-100" >
      <div class="w-100 d-flex justify-space-between" style="justify-content: space-between;">
          <TabMenu :model="items" class="m-1 w-100">
            <template #item="{ item, props }">
                <a v-if="item.type == 'exp-item'" v-ripple v-bind="props.action" class="p-3 text-center" @click="changeTab(item.type)">
                <span class="font-bold">Subhead</span>
                <span class="pi pi-spin mx-1  pi-spinner" v-if="tabLoader == item.type"></span>
                <span class="pi mx-1 pi-arrow-right-arrow-left" v-else></span>
                <span class="font-bold">Item</span>
              </a>
              <a v-if="item.type == 'exp-dept'" v-ripple v-bind="props.action" class="p-3 text-center" @click="changeTab(item.type)">
                <span class="font-bold">Head</span>
                <span class="pi pi-spin mx-1  pi-spinner" v-if="tabLoader == item.type"></span>
                <span class="pi mx-1 pi-arrow-right-arrow-left" v-else></span>
                <span class="font-bold">Subhead</span>
              </a>
              <a v-if="item.type=='dept'" v-ripple v-bind="props.action" class="p-3 text-center" @click="changeTab(item.type)">
                <span class="font-bold">{{ item.name }}</span>
                <span class="pi pi-spin  pi-spinner" v-if="tabLoader == item.type"></span>
              </a>
            </template>
          </TabMenu>
          <div class="p-3">
            <button @click="$emit('closeModal')" type="button" class="btn-close md-close" data-bs-dismiss="modal"
              aria-label="Close"></button>
          </div>
        </div>
        <div class="modal-body mt-0 px-3" style="height: 75vh;overflow-y: auto;">
          <DepartmentComponent @oncompleted="oncompleted" v-if="selectedTab == 'dept'"/>
          <SubHead @oncompleted="oncompleted"     v-if="selectedTab == 'exp'" />
          <HeadSubHeadSync @isLoading="loader($event,'exp-dept')" @oncompleted="oncompleted"     v-if="selectedTab == 'exp-dept'"  />
          <SubHeadItemSync @isLoading="loader($event,'exp-item')" @oncompleted="oncompleted"     v-if="selectedTab == 'exp-item'"  />
        </div>
        <div class="modal-footer" id="modalFooterConfig" style="justify-content: space-between;">

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
import SubHeadItemSync from './../Config/SubHeadItemSync.vue'

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
    HeadSubHeadSync,
    SubHeadItemSync
  },
  data() {
    return {
      items: [
        { name: 'Departments', image: 'amyelsner.png', type: 'dept' },
        { name: 'Subhead', image: 'annafali.png', type: 'exp' },
        { name: '-', image: 'annafali.png', type: 'exp-dept' },
        { name: '-', image: 'annafali.png', type: 'exp-item' },


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
