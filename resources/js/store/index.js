import { defineStore } from 'pinia'

export const useGlobalStore = defineStore('global', {
    state: () => ({
        schemes: [],
        selected_scheme: {},
        appropriations:[],
        selected_fund_category:"",
        fund_categories:[],
        open_debit_modal:false,
        transaction:{},
        transaction_index:'',
    }),
    getters: {
      
    },
    actions: {
     
    },
  })