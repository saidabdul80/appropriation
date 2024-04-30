<template>
  <div :style="{ left:'-52px', bottom: '120px',position:'absolute',zIndex:'10' }" >

    <!-- <div class="swift swift-bottom-left">
      <div id="round-tab" class="t-menu">
        <span
        style="z-index: 101; position: relative; display: flex; padding: 0px; height: 40px !important; width: 40px; justify-content: center; align-items: center;"        >
        <i class="bi bi-phone-flip"></i>
      </span>
      <div class="tabsI" @click="switchPageFunc(1)"><span>Appro.</span></div>
      <div class="tabsI" @click="switchPageFunc(2)"><span>History</span></div>
    </div>
  </div> -->
  <SpeedDial showIcon="pi pi-objects-column" :model="items" mask  :tooltipOptions="{ position: 'right' }"  direction="up"  buttonClass="p-button-warning rounded-circle bottom-0" :pt="{actionIcon:{style:{textDecration:'none'}}}" />
</div>
  </template>
  
  <script>
  
import SpeedDial from 'primevue/speeddial';

  export default {
    components:{
      SpeedDial
    },
    data(){
      return {
        items: [
                {
                    label: 'Approp.',
                    icon: 'pi pi-home',
                    command: () => {
                      this.switchPageFunc(1)
                    }
                },
                {
                    label: 'History',
                    icon: 'pi pi-history',
                    command: () => {
                      this.switchPageFunc(2)
                    }
                },
              ]
      }
    },
    props: {
      currentPage: {
        type: Number,
        default: 1, // Default value for the current page
      },
    },
    methods: {
      switchPageFunc(page) {
        this.$emit('switch-page', page);
      },
    },
    mounted(){
        var switf = new SA_SwiftMenu2({
            wrapper: 'round-tab',
            radius: 150,
            rotate: 43
        });
        switf.switf(); 
    }
  };
  </script>
  
<style>
ol, ul{
  padding:0px !important;
}
.p-speeddial-action{
  text-decoration: none;
  background-color: white;
}
</style>