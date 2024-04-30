<template>
  <div class="row mx-auto mx-sm-none w-100">
    <div class="col-sm-8 px-0 px-sm-0 px-md-2 ">
      <div class="mb-2 w-100 d-inline-block shadow p-0">

        <InputGroup>
          <Dropdown @change="onSchemeChange" v-model="selectedScheme" :options="schemes" placeholder="Select a Scheme"
            class="w-100 ">
            <template #value="slotProps">
              <div v-if="slotProps.value?.name" class="flex align-items-center">
                <div style="font-size: 12px;font-weight: bolder;">{{ slotProps.value.name }} <span>
                    (&#8358;{{ $globals.currency(slotProps.value?.wallet?.balance) }})</span> </div>
                <div style="font-size: 10px;">Programme (Current Programme fund )</div>
              </div>
              <span v-else>
                Select Programme
              </span>
            </template>

            <template #option="slotProps">
              <div>{{ slotProps?.option?.name }} ({{ $globals.currency(slotProps.option?.wallet?.balance) }})</div>
            </template>
          </Dropdown>
          <InputGroupAddon>
            <button @click="$emit('openModal')"
              :class="{ 'btn-success': selectedScheme.id, 'btn-secondary disabled': !selectedScheme.id }"
              class="update-scheme btn h-100 w-100 btn-sm">
              <i class="fa fa-wrench"></i>
              <span class="nav-name2"></span>
            </button>
          </InputGroupAddon>
        </InputGroup>
        <!--         <select @input="onSchemeChange" v-model="selectedScheme" class="mx-1 form-control border-0" >
          <option :value="defaultScheme">Select a Scheme</option>
          <option v-for="(scheme, index) in schemes" :key="index" :value="scheme">{{ scheme?.name }} </option>
        </select>      -->

        <!--   <i class="fa fa-caret-down text-secondary position-absolute" style="right: 26%; top: 33%;"></i> -->
      </div>
    </div>
    <div class="col-sm-4 px-0 px-sm-0 px-md-2" style="/* position: fixed; top: 5px; z-index: 9; padding-left: 20px */">
      <div class="mb-2  w-100  d-inline-block rounded-sm p-0">
        <month-year-selector :selected_scheme_id="selectedScheme.id" @month-selected="monthSelected"
          :fund_categories="fund_categories" />
      </div>
    </div>

  </div>
</template>

<script>
import Dropdown from 'primevue/dropdown';
import MonthYearSelector from './MonthYearSelector.vue';

import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';

export default {
  components: {
    MonthYearSelector,
    Dropdown,
    InputGroup,
    InputGroupAddon
  },
  props: {
    fund_categories: {
      type: Array,
      default: () => [],
    },
    scheme_changed:{default:0},
    schemes: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      defaultScheme: { index: '', id: '', name: '', appropriations: [] },
      selectedScheme: { index: '', id: '', name: '', appropriations: [] }
    };
  },
  watch:{
    scheme_changed: {
        handler(newValue, oldValue) {                  
          if (!this.selectedScheme?.id) return; // Early return if no selected scheme
          const foundScheme = this.schemes.find(item => item.id === this.selectedScheme.id);
          if (foundScheme) {
            this.selectedScheme = foundScheme;
          }
        }            
      },
  },
  methods: {
    monthSelected(data) {
      this.$emit('month-selected', data)
    },
    onSchemeChange() {
      setTimeout(() => {
        //console.log(this.selectedScheme);
        this.$emit('scheme-selected', this.selectedScheme);
      }, 500)
    },
    onUpdateScheme() {
      if (this.selectedScheme.id) {
        this.$emit('update-scheme', this.selectedScheme);
      }
    },
    buttonText() {
      return this.selectedScheme.id ? 'Update Programme' : 'Select a Scheme first';
    }
  },
};
</script>