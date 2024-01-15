<template>
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <a @click="handleTabClick(0)" class="fs-8 nav-link active" id="home-tab2" data-bs-toggle="tab" href="#home2" role="tab" aria-controls="home2" aria-selected="true">
        Appropriations
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a @click="handleTabClick(1)" class="fs-8 nav-link " id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
        Appropriated Income
      </a>
    </li>
    
    <li v-if="appropriations?.length > 0" class="nav-item" role="presentation">
      <a @click="handleTabClick(2)" class="fs-8 nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
        <strong> {{ selected_fund_category == '' ? 'Yearly' : selected_fund_category }}</strong> Appropriated Income
        <!--   Approp. Current Balance -->
      </a>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    appropriations: {
      type: Array,
      default: () => [],
    },
    selected_fund_category:{
      default: '',
    },
  },
  methods: {
    handleTabClick(page) {
      this.$emit('switch-page', page);
    },
  },
};
</script>

<style scoped>
  /* Add your component-specific styles here if needed */
</style>
