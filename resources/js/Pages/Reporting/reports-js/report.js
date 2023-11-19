import Layout from "../../../Shared/Layout.vue";
import { useForm, Link, Head} from '@inertiajs/inertia-vue3';
import Multiselect from '@vueform/multiselect';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import Banner from '../../components/Banner.vue';
import { ref, computed } from 'vue';
import  common from '../../common-js/common.js';
import stages from './stages.js'

export default {
  props: {
    licenceTypes: Object,
    companies: Object,
    new_applications: Object,
    years: Object,
    people: Object,
    emails: Object,
    success: String,
    error: String,
    errors: Object,
  },

  setup(props) {
     const months = months;
     let years = props.years;                 
     let licenceTypes = props.licenceTypes;
     let companies = props.companies;
     let people = props.people;

    const date = ref('');
   
    const form = useForm({
      variation: null,
      activeStatus: '',
      month_from: '',
      month_to: '',
      year: '',
      applicant: '',
      is_license_complete: '',
      person: [],
      company: [],
      province: [],
      selectedDates:[],
      boardRegion: [],
      licence_types: [],
      new_app_stages: [],
      renewal_stages: [],
      transfer_stages: [],
      nomination_stages: [],
      alteration_stages: [],
      temp_licence_stages: [],
      temp_licence_region: []
    })
    


    const computedBoardRegions = computed(() => {
      return common.getBoardRegions();
    })

    const computedProvinces = computed(() => {
      return common.getProvinces();
    })

    const computedRenewalStages = computed(() => {
      return stages.getRenewalStages();
    })

    const computedNewAppStages = computed(() => {
      return stages.getNewAppStages();
    })
    const computedTransferStages = computed(() => {
      return stages.getTransferStages();
    })
    const computedNominationStages = computed(() => {
      return stages.getNominationStages();
    })
    const computedAlterationStages = computed(() => {
      return stages.getAlterationStages();
    })
    const computedTempLicenceStages = computed(() => {
      return stages.getTempLicenceStages();
    })

    const computedMonths = computed(() => {
      return stages.getMonths();
    })
    

   
//toggle class active
   function addClass(event){
    const buttons = document.querySelectorAll('.w-50')
    buttons.forEach(button => {
      if(event.target !== button){
        button.classList.remove('btn-info')
        button.classList.add('btn-success')
      }      
    })
     event.target.classList.remove('btn-success')
     event.target.classList.add('btn-info')   
    }
   
   

  function getType(event,type){
     form.variation=type;
     addClass(event);
     form.reset('activeStatus','month','year','applicant','person','company',
     'province','month','selectedDates','boardRegion','licence_types','new_app_stages')
   }

   const handleDate = (modelData) => {
          date.value = modelData;
       form.selectedDates.push(date.value)
   }

   const removeDate = (index) => {
       form.selectedDates.splice(index, 1)
   }

   const exportReport = () => {
    let url =
    `/export-report?variation=${form.variation}&month_from=${form.month_from}
    &month_to=${form.month_to}&year=${form.year}&applicant=${form.applicant}
    &person=${form.person}&company=${form.company}&province=${form.province}
    &selectedDates=${form.selectedDates}&boardRegion=${form.boardRegion}
    &licence_types=${form.licence_types}&new_app_stages=${form.new_app_stages}
    &renewal_stages=${form.renewal_stages}&transfer_stages=${form.transfer_stages}
    &nomination_stages=${form.nomination_stages}&alteration_stages=${form.alteration_stages}
    &temp_licence_stages=${form.temp_licence_stages}&temp_licence_region=${form.temp_licence_region}
    &activeStatus=${form.activeStatus}&is_licence_complete=${form.is_license_complete}`
    
    window.open(url,'_blank');
     
   }

   const fetchNewAppWithStages = () => {
    if(form.variation === 'New Applications'){
      form.get(`/reports`, {
            preserveScroll: true,
            onSuccess: () => {
              //
            },
            replace: true,
            preserveState: true
            })
    } 
       
   }

   function limit(string='', limit = 25) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
   }


      function resetFilters(){
        form.activeStatus = '';
        form.month_from = '';
        form.month_to = '';
        form.year = '';
        form.applicant = '';
        form.is_license_complete = '';
        form.person = [];
        form.company = [];
        form.province = [];
        form.selectedDates = [];
        form.boardRegion = [];
        form.licence_types = [];
        form.new_app_stages = [];
        form.renewal_stages = [];
        form.transfer_stages = [];
        form.nomination_stages = [];
        form.alteration_stages = [];
        form.temp_licence_stages = [];
        form.temp_licence_region = []
      }

return{
  computedBoardRegions,
  computedProvinces,
  limit,addClass,
  getType,
  form,
  licenceTypes,
  handleDate,
  removeDate,
  exportReport,
  people,
  companies,
  fetchNewAppWithStages,
  years,
  resetFilters,
  computedRenewalStages,
  computedNewAppStages,
  computedTransferStages,
  computedNominationStages,
  computedAlterationStages,
  computedTempLicenceStages,
  computedMonths
}
},
 components: {
    Layout,
    Link,
    Head,
    Multiselect,
    Datepicker,
    Banner
},


}