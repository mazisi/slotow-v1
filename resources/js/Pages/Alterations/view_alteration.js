import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link, useForm, Head } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref,onMounted } from "vue";
import Task from "../Tasks/Task.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';


export default {
  name: "ViewAlteration",
 props: {
    errors: Object,
    alteration: Object,
    success: String,
    error: String,
    tasks: Object,
    client_quoted: Object,
    client_invoiced: Object,  //doc
    liqour_board: Object,   // secod doc
    site_map_file: Object,   //doc
    application_form: Object,
    dimensional_plans: Object,
    poa_res: Object,
    smoking_affidavict: Object,
    payment_to_liquor_board: Object,
    alteration_logded: Object,
    certification_issued: Object,
    alteration_delivered: Object


  },
  setup(props) {
      let showMenu = false;
      let show_modal = ref(true);
      let show_file_name = ref(false);
      let file_name = ref('');

    const form = useForm({
         licence_slug: props.alteration.licence.slug,
         slug: props.alteration.slug,
         client_paid_at: props.alteration.client_paid_at,
         status: [],
         unChecked: false,
         invoiced_at: props.alteration.invoiced_at,
         liquor_board_at: props.alteration.liquor_board_at, 
         date: props.alteration.logded_at,
         certification_issued_at: props.alteration.certification_issued_at,
         delivered_at: props.alteration.delivered_at     
      })

      const uploadDoc = useForm({
            document: null,
            doc_type: null,
            doc_number: null,
            stage: null,
            file_name: file_name,
            alteration_id: props.alteration.id    
          })

    function update() {
          form.patch(`/update-alteration`,{
            onStart: () => { 
              setTimeout(() => {
                toast.remove();
              }, 3000);
              toast.loading('Updating stage...');
            },
            onSuccess: () => { 
               if(props.success){
                  notify(props.success)
                }else if(props.error){
                  notify(props.error)
                }
              },
          })
        }

   

    let file_has_apostrophe = ref();
      function getFileName(e){
        this.show_file_name = true;
        this.uploadDoc.document = e.target.files[0];
        this.file_name = e.target.files[0].name;
        this.file_has_apostrophe = this.file_name.includes("'");
      }

      function submitDocument(){
            uploadDoc.post('/submit-alteration-document', {
              preserveScroll: true,
              onSuccess: () => { 
                this.uploadDoc.reset();
                this.show_file_name = false;
                this.show_modal = false;
                document.querySelector('.modal-backdrop').remove();
                 if(props.success){
                   notify(props.success)
                 }else if(props.error){
                  notify(props.error)
                 }
                
           },
            })
          }

      function deleteDocument(id){
          if(confirm('Document will be deleted...Continue ??')){
            Inertia.delete(`/delete-alteration-document/${id}`, {
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

      function getDocType(stage=null,doc_type, doc_number='') {
        uploadDoc.doc_type = doc_type;
        uploadDoc.doc_number = doc_number;
        uploadDoc.stage = stage;
        this.show_modal = true
      }

      function getDocumentType(document_path){
        if(document_path){
          return document_path.split('/')[1]
        }
        
      }
     function deleteAlteration(slug, licence_slug=props.alteration.licence.slug){
       if (confirm('Are you sure you want to delete this alteration?')) {
        Inertia.delete(`/delete-altered-licence/${slug}/${licence_slug}`)
      }
    }

    function mergeDocuments(){
      Inertia.post(`/merge-alteration-documents/${props.alteration.id}`, {
              //
      })
    }

    function updateDate(){
        form.patch(`/update-alteration-date/${props.alteration.slug}`, {
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


        function pushData(e,status_value){
          if (e.target.checked) {
                this.form.status[0] = status_value;
                this.form.unChecked = false;
              }else if(!e.target.checked){
                this.form.unChecked = true
                this.form.status[0] = status_value;
              }
              update();
        }
       

         function viewFile(model_id) {
              let model = 'AlterationDocument';
               Inertia.visit(`/view-file/${model}/${model_id}`,{
                replace: true,
                onStart: () => {                  
                  checkingFileProgress('Checking file availability...')                
              },
                
               })
         }

        function getStatus(status_param) {
          let status='Not Set';
          switch (status_param) {
            case '1':
              status = 'Client Quoted'
              break;
              case '2':
              status = 'Client Invoiced'
              break;
              case '3':
              status = 'Client Paid'
              break;
              case '4':
              status = 'Prepare Alterations Application'
              break;
              case '5':
              status = 'Payment to the Liquor Board'
              break;
              case '6':
              status = 'Alterations Lodged'
              break;
              case '7':
              status = 'Alterations Certificate Issued'
              break;
              case '8':
              status = 'Alterations Delivered'
              break;                    
            default:
              break;
          }
          return status;
        }



    return {
      form,showMenu,show_modal,updateDate,
      update,update,mergeDocuments,
      pushData,file_name,
      show_file_name,
      getFileName,
      submitDocument,
      deleteDocument,
      getDocType,toast,
      uploadDoc,getDocumentType,
      deleteAlteration,file_has_apostrophe,
      viewFile,checkingFileProgress,notify,
      getStatus
    };
  },
    
  components: {
    Layout,
    Multiselect,
    Link,
    Banner,
    Head,
    Task
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
//Status keys:
// 1. Client Quoted
//2 => Client Invoiced
//3 => Client Paid
//4 => Prepare Alterations Application
//5 => Payment to the Liquor Board
//6 => Alterations Lodged
//7 => Alterations Certificate Issued
//8 => Alterations Delivered