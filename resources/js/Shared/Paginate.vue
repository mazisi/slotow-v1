<template>
<nav :aria-label="modelType" class="mt-2">
  <ul class="pagination justify-content-end">
    <li class="page-item" :class="{ disabled: modelName.prev_page_url == null }">
      <Link preserve-state  :href="`${modelName.prev_page_url}`" class="page-link">Prev</Link>
    </li>
    <template v-for="(link, key) in modelName.links">
    <li class="page-item " :class="{ 'active': link.active }">
      
      <Link preserve-state class="page-link" :href="link.url" v-show="key && link.url !== null" v-html="getArrowBbuttons(key)"></Link>
    
    </li>
  </template>
    <li class="page-item" :class="{ disabled: modelName.next_page_url == null }">
      <Link preserve-state as="button" :href="`${modelName.next_page_url}`" type="button" class="page-link">Next</Link>
    </li>
  </ul>
</nav>

</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';

  export default {
    props: {
      modelName: Array,
      modelType: String
    },

    setup(props){
      // let nextPage = ref(0);
      // let prevPage =  ref(0);
      // let currentPage =  reactive(props.modelName.current_page);
    
      // function paginateNext(){
      //     if(props.modelName.current_page < props.modelName.last_page){            
      //       nextPage = props.modelName.current_page + 1;
      //       currentPage =  props.modelName.current_page+1;
      //       Inertia.get(`${props.modelName.next_page_url}`, { page: nextPage }, { preserveState: true, replace: true });            
      //     } 
      //   }

      //   function paginatePrev(){         
      //       prevPage = props.modelName.current_page - 1;
      //       currentPage =  props.modelName.current_page-1;
      //       Inertia.get(`${props.modelName.prev_page_url}`, { page: prevPage }, { preserveState: true, replace: true });
          
      //   }

        function getArrowBbuttons(key){
          if(key !== 0){
            return key;
          }
          
        }
      return{
        getArrowBbuttons
      }
      
    },
    components : {
        Link
      }
  }
  </script> 