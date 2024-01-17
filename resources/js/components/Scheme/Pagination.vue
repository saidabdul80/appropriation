<template>
    <div>
      <ul class="paginationData">
        <li v-for="link in paginationData?.links" :key="link.label"
          :class="['page-item', { active: link.active, disabled: link.url === null }]">
          <button :disabled="link.url === null" class="page-link" @click.prevent="goToPage(link)">
            <span v-html="link.label"></span>
          </button>
        </li>
      </ul>
    </div>
  </template>
    
  <script>
  
  export default {
    name: 'PaginationData',
    props: {
      paginationData: {
        type: Object,
        required: true,
      },
      method: {
        type: String,
        default: function () {
          return "get";
        }
      },
      filters: {
        type: Object,
        default: function () {
          return {};
        }
      }
    },
    data() {
      return {};
    },
    mounted() {},
    methods: {
      async goToPage(link) {
        if (link.url) {
            if(link.active){
                return false;
            }
          let res = ''
          if (this.method == 'get') {
            res = await get(link.url);
            } else {
                res = await postData(link.url,this.filters,true);
            }          
        if(res.status == 200){
            this.$emit('onFetch', res.data);
        }
        }
      },
    },
  };
  </script>
    
  <style scoped>
  .pagination {
    display: flex;
    justify-content: center;
  }
  
  .page-item {
    display: inline-block;
    margin-right: 5px;
  }
  
  .page-item.disabled .page-link {
    opacity: 0.5;
    user-select: none;
    cursor: not-allowed;
  }
  
  .page-item.disabled .page-link:hover {
    opacity: 0.5;
    user-select: none;
    cursor: not-allowed;
  }
  
  .page-item.active .page-link {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
  }
  
  .page-link {
    padding: 6px 12px;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 3px;
  }
  
  .page-link:hover {
    background-color: #e6e6e6;
    color: #333;
    text-decoration: none;
  }
  </style>