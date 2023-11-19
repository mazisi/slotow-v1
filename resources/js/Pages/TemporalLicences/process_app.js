import Layout from "../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import LiquorBoardRequest from "../components/LiquorBoardRequest.vue";
import Banner from '../components/Banner.vue';
import Task from "../Tasks/Task.vue";
import { ref } from 'vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
  props: {
    liqour_board_requests: Object,
    tasks: Object,
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String,
    licence_logded: Object,//doc
    scanned_app: Object,//doc
    client_invoiced: Object,//doc
    licence_issued: Object,//doc
    client_quoted: Object,//doc
    collate: Object,
    liqour_board: Object,
    licence_delivered: Object,
    company_application_form: Object,
    company_poa: Object,
    company_annexure_b: Object,
    company_annexure_c: Object,
    company_cipc: Object,
    company_id_document: Object,
    company_representations: Object,
    company_landlord_letter: Object,
    company_security_letter: Object,
    company_advert: Object,
    company_plan: Object,
    //individual
    
    individual_application_form: Object,
    power_of_attorney: Object,
    individual_annexure_b: Object,
    individual_annexure_c: Object,
    individual_representations: Object,
    individual_landlord_letter: Object,
    individual_security_letter: Object,
    individual_advert: Object,
    individual_plan: Object,
    get_person_id_document: Object
     
  },

  setup (props) {
    const year = ref(new Date().getFullYear());
    let show_modal = ref(true);  
    let show_file_name = ref(false);
    let file_name = ref('');

    const form = useForm({
      status: [],
      unChecked: false,
      client_paid_at: props.licence.client_paid_at,
      payment_to_liquor_board_at: props.licence.payment_to_liquor_board_at,
      logded_at: props.licence.logded_at,
      issued_at: props.licence.issued_at, 
      delivered_at: props.licence.delivered_at,
      liquor_licence_number: props.licence.liquor_licence_number,
      reg_number: props.licence.company ? props.licence.company.reg_number: '',
      id_number: props.licence.people ? props.licence.people.id_number: '',
      belongs_to: props.licence.belongs_to
     })

    const uploadDoc = useForm({
      document: null,
      doc_type: null,
      person_or_company: null,
      merge_number: null,
      stage: null,
      file_name: file_name,
      temp_licence_id: props.licence.id    
    })


      function submitDocument(){
            uploadDoc.post('/submit-temporal-licence-document', {
              preserveScroll: true,
              onSuccess: () => { 
                this.show_file_name = false;
                this.show_modal = false;
                document.querySelector('.modal-backdrop').remove();
                
                        if(props.success){
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
                         }

                uploadDoc.reset();
              },
            })
          }

         const mergeForm = useForm({ 
          temporal_licence_id: props.licence.id,
          company_id: props.licence.company_id,
          person_id: props.licence.people_id
          })
          function mergeDocuments(type){
            mergeForm.post(`/merge-temporal-documents/${type}`,{
              onStart: () => { 
                checkingFileProgress('Checking file availability...') 
               },
            })
          }

    function getDocType(stage=null,doc_type,person_or_company,merge_number){
      this.uploadDoc.stage = stage;
      this.uploadDoc.doc_type = doc_type;
      this.uploadDoc.person_or_company = person_or_company
      this.uploadDoc.merge_number = merge_number
      this.show_modal =true  
      this.show_file_name = true; 
    }

    function deleteDocument(id){
        if(confirm('Document will be deleted permanently...Continue ??')){
          Inertia.delete(`/delete-temporal-licence-document/${id}`, {
            onSuccess: () => { 
                        if(props.success){
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
                         }
                      },
          })
        }
      }

      function deleteTemporalLicence(){
        if(confirm('Are you sure you want to delete this temporary licence??')){
          Inertia.delete(`/delete-temporal-licence/${props.licence.slug}`, {
            //
          })
        }
      }

    function updateLicence() {
      form.patch(`/update-prepared-temp-app/${props.licence.slug}`, {
        preserveScroll: true,
        onSuccess: () => { 
                        if(props.success){
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
                         }
                      },
      })
    }

    function getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    }

    function computeDocumentDate(date_param){
        return new Date(date_param).toLocaleString().split(',')[0]
    };

    function pushData(e,status_value){
      if (e.target.checked) {
            this.form.status[0] = status_value;
            this.form.unChecked = false;
          }else if(!e.target.checked){
            this.form.unChecked = true
            this.form.status[0] = status_value;
          }
          updateLicence();
      }

      let file_has_apostrophe = ref();
      function getFileName(e){
        this.uploadDoc.document = e.target.files[0];
        this.file_name = e.target.files[0].name;
        this.file_has_apostrophe = this.file_name.includes("'");
      }

      

      function updateDate(){
        form.patch(`/update-process-app-date/${props.licence.slug}`, {
             preserveScroll: true,
             onSuccess: () => { 
                        if(props.success){
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
                         }
                      },
           }) 
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

        function checkingFileProgress(message){
          setTimeout(() => {
              toast.remove();
            }, 3000);
            toast.loading(message);
        }

       

         function viewFile(model_id, alt_model='') {
          let model = '';
          if(alt_model){
            model = alt_model;
          }else{
            model = 'TemporalLicenceDocument';
          }
             
               Inertia.visit(`/view-file/${model}/${model_id}`,{
                replace: true,
                onStart: () => {                  
                  checkingFileProgress('Checking file availability...')                
              },
                
               })
         }

    return { year,form,show_modal,
     updateLicence,file_name,show_file_name,getFileName,
     getRenewalYear, pushData,uploadDoc,
     getDocType, submitDocument,file_has_apostrophe,
     computeDocumentDate,deleteDocument,
     updateDate,
     mergeDocuments,
     mergeForm,
     deleteTemporalLicence,
     toast,viewFile,checkingFileProgress,notify
     }
  },
   components: {
    Layout,
    Link,
    Head,
    Datepicker,
    LiquorBoardRequest,
    Banner,
    Task
  },

  watch: {
    'form.start_date'() {
      var d = new Date(this.form.start_date);
      d.setDate(d.getDate() - 14);
      this.form.latest_lodgment_date = d.toLocaleString().split(' ')[0].replace(/,/g, '')
    }
  },
  
};
//The following are status keys
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Collate Temporary Licence Documents 
// 5 => Payment To The Liquor Board 
// 6 => Scanned Application
// 7 => Temporary Licence Lodged 
// 8 => Temporary Licence Issued 
// 9 => Temporary Licence Delivered