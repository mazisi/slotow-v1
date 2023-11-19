import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import Banner from '../components/Banner.vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm, Head } from '@inertiajs/inertia-vue3';
import { ref, computed } from 'vue';
import { toast } from 'vue3-toastify';
import common from '../common-js/common.js';
import 'vue3-toastify/dist/index.css';

export default {
 name: "ViewTemporalLicence",
 props: {
    errors: Object,
    success: String,
    licence: Object,
    people: Array
  },
  setup(props) {
   
      let showMenu = ref(false);
      let options = props.companies;
      let persons = props.people;
      const form = useForm({
          slug: props.licence.slug,
          event_name: props.licence.event_name,
          start_date: props.licence.start_date,
          end_date: props.licence.end_date,
          address: props.licence.address,
          application_type: props.licence.application_type,
          liquor_licence_number: props.licence.liquor_licence_number,
          reg_number: props.licence.company ? props.licence.company.reg_number: '',
          id_or_passport: props.licence.people ? props.licence.people.id_or_passport: '',
          belongs_to: props.licence.belongs_to,
          company_name: props.licence.company ? props.licence.company.name : '',
          person: props.licence.people ? props.licence.people.full_name : '',
          latest_lodgment_date: props.licence.latest_lodgment_date
      })
    
  
    function computeLogdementDate(){
      var d = new Date(props.licence.start_date);
      d.setDate(d.getDate() - 14);
      return form.latest_lodgment_date = d.toLocaleString().split(' ')[0].replace(/,/g, '')
    }

 
    function update() {
         form.patch(`/update-temp-licence`, {
          onSuccess: () => { 
            if(props.success){
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
                         }
         },
          })
        }

        function deleteTempLicence(){
          if(confirm('Are you sure you want to delete this temporary licence??')){
            Inertia.delete(`/delete-temporal-licence/${this.licence.slug}`)
          }      
        }

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

        const computedBoardRegions = computed(() => {
          return common.getBoardRegions();
        })

    return {
      showMenu,
      computedBoardRegions,
      options,
      persons,
      form,notify,
      computeLogdementDate,
      update,
      deleteTempLicence
    }
  },
  
  computed: {

    computedBoardRegions() {
          return common.getBoardRegions();
        }
  },

  components: {
    Layout,
    Multiselect,
    Banner,
    Head
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};