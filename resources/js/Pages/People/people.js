import Layout from "../../Shared/Layout.vue";
import { useForm ,Link, Head } from '@inertiajs/inertia-vue3';
import { ref, watch, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Banner from '../components/Banner.vue'
import Paginate from "@/Shared/Paginate.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
  props: {
    success: String,
    error: String,
    people: Object,
    linked_companies: Object,
    links: Array,
  },
  setup(props){

    const [search_query, active_status] = getUrlParam();

    function getUrlParam(){
      const urlParams = new URLSearchParams(window.location.search);
      const search_query = urlParams.get('term')
      const status = urlParams.get('active_status')
      return [search_query,status];
    }

    const term = search_query ? search_query : ref('');

    const form = useForm({
          term: term,
          active_status: ''
        })

    Inertia.on('navigate', (event) => {
      Inertia.visit(`${event.detail.page.url}`, { preserveState: true, preserveScroll: true });
    })

    function search(){
      form.get(`/people`, {
            replace: true,
            preserveState: true
            
        })
    }
    watch(term, _.debounce(function (value) {
          Inertia.get('/people', { term: value, active_status: form.active_status }, { preserveState: true, replace: true });
        }, 2000));


        const notify = (message) => {
          if(props.success){
            toast.success(message, {
            autoClose: 2000,
          });
          
          }else if(props.error){
            toast.error(message, {
            autoClose: 2000,
          });
          }
        }
        onMounted(() => {
          if(props.success){
            notify(props.success)
          }else if(props.error){
            notify(props.error)
          }
        });

    return{
      getUrlParam,
      term,
      search,
      form,
      notify,
      toast
    }
  },
 components: {
    Layout,
    Link,
    Head,
    useForm,
    Banner,
    Paginate
},
}