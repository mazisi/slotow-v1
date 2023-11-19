
  import Layout from "../../Shared/Layout.vue";
  import Multiselect from '@vueform/multiselect';
  import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
  import Banner from '../components/Banner.vue';
  import { toast } from 'vue3-toastify';
  import 'vue3-toastify/dist/index.css';
  import common from '../common-js/common.js';
  import { computed } from 'vue';
  import LiquorBoardRegionComponent from '../components/input-components/LiquorBoardRegionComponent.vue'
  import TextInputComponent from '../components/input-components/TextInputComponent.vue';
  import ProvinceSelectDropdownComponent from '../components/input-components/ProvinceSelectDropdownComponent.vue'
  import LicenceTypeDropDownComponent from '../components/input-components/LicenceTypeDropDownComponent.vue'
  
  export default {
   props: {
      errors: Object,
      licence_dropdowns: Object,
      companies: Array,
      persons: Array,
      success: String,
      error: String,
      get_reg_num_or_id_number: String
    },
    
    
    setup (props) {
      let options;   

      const form = useForm({
            trading_name: '',
            licence_type: '',
            belongs_to: '',
            reg_number: '',
            id_number: '',
            address: '',
            address2: '',
            address3: '',
            province: '',
            company: '',
            person: '',
            board_region: ''   
      })

      const idRegForm = useForm({
            person : '',
            company: ''  
      })

    
      
      function submit() {
        form.post('/submit-new-app', {
          onSuccess: () => { 
              notify(props.success)
           },
          preserveScroll: true,
        })
        
      }

      const filterForm = useForm({
        variation: '',
        id: null,
        id: form.company ? form.company : form.person
      })

      function selectApplicant(){
          if(form.belongs_to === 'Company'){
            filterForm.variation = 'Company';
            filterForm.id = form.company;
            form.person='';
          }else if(form.belongs_to === 'Person'){
            filterForm.variation = 'Person';
            filterForm.id = form.person;
            form.company='';
          }
          
          filterForm.get(`/create-new-app?id=${filterForm.id}`, {
                      onStart: () => { 
                        let mess = filterForm.variation === 'Person' ? ' ID Number' : ' Registration Number';
                        getchIdOrRegNumber(mess);
                      },
            preserveScroll: true,
            replace: true,
            preserveState: true
            })

     }
     
     function getchIdOrRegNumber(message){
          setTimeout(() => {
              toast.remove();
            }, 3000);
            toast.loading(`Fetching${message}`);
        }



        const computedProvinces = computed(() => {
          return common.getProvinces();
        })

        const computedBoardRegions = computed(() => {
          return common.getBoardRegions();
        })

      return { submit, form ,options, idRegForm, 
        selectApplicant, filterForm,toast, 
        getchIdOrRegNumber, computedProvinces,
        computedBoardRegions
      }
      
    },
    
     components: {
      Layout,
      Link,
      Head,
      Multiselect,
      Banner,
      LiquorBoardRegionComponent,
      TextInputComponent,
      ProvinceSelectDropdownComponent,
      LiquorBoardRegionComponent,
      LicenceTypeDropDownComponent
    }

  };
