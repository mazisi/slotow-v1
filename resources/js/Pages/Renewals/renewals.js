import Layout from "../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import LiquorBoardRequest from "../components/LiquorBoardRequest.vue";
import Banner from '../components/Banner.vue';
import Task from "../Tasks/Task.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import { ref,onMounted } from 'vue';

export default {
  props: {
    liqour_board_requests: Object,
    tasks: Object,
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String,
    renewal: Object,
    client_invoiced: Object,//doc
    renewal_issued: Object,//doc
    client_quoted: Object,//doc
    renewal_doc: Object,
    liqour_board: Object
  },

  setup (props) {
    const year = ref(new Date().getFullYear());
    let show_modal = ref(true); 
    let show_file_name = ref(false); 
    let file_name = ref('');

    const form = useForm({
      year: props.renewal.date,
      licence_id: props.renewal.id,
      status: [],
      unChecked: false,
      client_paid_at: props.renewal.client_paid_at,
      payment_to_liquor_board_at: props.renewal.payment_to_liquor_board_at,
      renewal_issued_at: props.renewal.renewal_issued_at,
      renewal_delivered_at: props.renewal.renewal_delivered_at,
      renewal_id: props.renewal.id,
      client_invoiced_at: props.renewal.client_invoiced_at
     })

    const uploadDoc = useForm({
      document: null,
      doc_type: null,
      date: null,
      stage: null,
      renewal_id: props.renewal.id    
    })

   
  

    function getDocType(stage=null,doc_type){
      this.show_file_name = true;
      this.uploadDoc.doc_type = doc_type;
      this.uploadDoc.stage = stage;
      this.show_modal =true   
    }

    function deleteDocument(id){
        if(confirm('Document will be deleted...Continue ??')){
          Inertia.delete(`/delete-renewal-document/${id}`, {
            onSuccess: () => {
              if(props.success){
                    notify(props.success)
                      }else if(props.error){
                        notify(props.error)
                      }
          }
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

    function submitDocument(){
      uploadDoc.post('/submit-renewal-documents', {
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

    function updateRenewal() {
      form.patch('/update-renewal', {
        preserveScroll: true,
        onSuccess: () => { 
            if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
            }
         }
      })
    }

    function getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    }

      function deleteRenewal(){
        if(confirm('Are you sure you want to delete this licence renewal??')){
          Inertia.delete(`/delete-licence-renewal/${props.renewal.licence.slug}/${props.renewal.slug}`)
        }
      }

    

      function pushData(e,status_value){
          if (e.target.checked) {
            this.form.status[0] = status_value;
            this.form.unChecked = false;
          }else if(!e.target.checked){
            this.form.unChecked = true
            this.form.status[0] = status_value;
          }
          updateRenewal();
          }

          function updateDate(){
            form.patch(`/update-renewal-date/${props.renewal.slug}`, {
             preserveScroll: true,
             onSuccess: () => { 
               if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
                    }
           }
           }) 
          }

      let file_has_apostrophe = ref();
      function getFileName(e){
        this.uploadDoc.document = e.target.files[0];
        this.file_name = e.target.files[0].name;
        this.file_has_apostrophe = this.file_name.includes("'");
      }

      const notify = (message) => {
          if(props.success){
            toast.success(message, {
            autoClose: 2000,
          });
          props.success='';
          props.error=''
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

       

         function viewFile(model_id) {
              let model = 'RenewalDocument';
               Inertia.visit(`/view-file/${model}/${model_id}`,{
                replace: true,
                onStart: () => {                  
                  checkingFileProgress('Checking file availability...')                
              },
                
               })
         }

        //  onMounted(() => {
        //   if(props.success){
        //     notify(props.success)
        //   }else if(props.error){
        //     notify(props.error)
        //   }
        // });

    return { year,form,show_modal,getFileName, notify,
      file_name,show_file_name,file_has_apostrophe,
     updateRenewal,updateDate,
     getRenewalYear, pushData,uploadDoc,
     getDocType, submitDocument,
     deleteDocument,
     deleteRenewal,
     limit,toast,viewFile,checkingFileProgress
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
  
};

//The following are status keys
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Payment To Liquor Board
// 5 => Renewal Issued
// 6 => Renewal Delivered