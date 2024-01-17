<template>
    <div class="swift swift-bottom-left">
      <div id="round-tab" class="t-menu">
        <span
          style="z-index: 101; position: relative; display: flex; padding: 0px; height: 40px !important; width: 40px; justify-content: center; align-items: center;"        >
          <i class="bi bi-phone-flip"></i>
        </span>
        <div class="tabsI" @click="switchPageFunc(1)"><span>Appro.</span></div>
        <div class="tabsI" @click="switchPageFunc(2)"><span>History</span></div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
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
  