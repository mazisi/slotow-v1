import Layout from "../../Shared/Layout.vue";
  import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
  import { Inertia } from '@inertiajs/inertia';
  import '@vuepic/vue-datepicker/dist/main.css';
  import Task from "../Tasks/Task.vue";
  import { ref } from 'vue';
  import Banner from '../components/Banner.vue';
  import { toast } from 'vue3-toastify';
  import 'vue3-toastify/dist/index.css';
  
  export default {
    props: {
      tasks: Object,
      errors: Object,
      licence: Object,
      success: String,
      error: String,
      client_quoted: Object,
      gba_application_form: Object,//doc
      client_invoiced: Object,//doc
      application_forms: Object,//doc
      company_docs: Object,
      cipc_docs: Object,
      id_docs: Object,
      police_clearance: Object,
      tax_clearance: Object,
      lta_certificate: Object,
      shareholding_info: Object,
      financial_interests: Object,
      _500m_affidavict: Object,
      government_gazette: Object,
      advert_affidavict: Object,
      proof_of_occupation: Object,
      representations: Object,
      menu: Object,
      photographs: Object,
      consent_letter: Object,
      zoning_certificate: Object,
      local_authority: Object,
      mapbook_plans: Object,
      google_map_plans: Object,
      description: Object,
      site_plans: Object,
      dimensional_plans: Object,
      advert_photographs: Object,
      newspaper_adverts: Object,
      payment_to_liqour_board: Object,
      scanned_application: Object,
      application_logded: Object,
      initial_inspection_doc: Object,
      final_inspection_doc: Object,
      client_finalisation: Object,
      client_paid: Object,
      activation_fee_paid: Object,
      licence_issued_doc: Object,
      licence_delivered: Object,
      liqour_board_requests: Object
    },
  
    setup (props) {
      let show_modal = ref(true);  
      let show_file_name = ref(false);
      let file_name = ref('');
      let file_size = ref(null);
      let file_has_apostrophe = ref();
      

      const form = useForm({
        deposit_paid_at: props.licence.deposit_paid_at,
        liquor_board_at: props.licence.liquor_board_at,
        application_lodged_at: props.licence.application_lodged_at,
        initial_inspection_at: props.licence.initial_inspection_at,
        final_inspection_at: props.licence.final_inspection_at,
        activation_fee_requested_at	: props.licence.activation_fee_requested_at,
        client_paid_at: props.licence.client_paid_at,
        activation_fee_paid_at: props.licence.activation_fee_paid_at,
        licence_issued_at: props.licence.licence_issued_at,
        licence_delivered_at: props.licence.licence_delivered_at,
        status: [],
        unChecked: false
       })
  
      const boardRequests = useForm({
        body: null,
        model_id: props.licence.id,
        model_type: 'Licence'
      })

      const editBoardRequestForm = useForm({
        body: null,
        id: null,
      })

      const uploadDoc = useForm({
        document_file: null,
        doc_type: null ,
        num: null,
        stage: null,
        licence_id: props.licence.id
      })
  
      function submitBoardRequests(){
        boardRequests.post('/submit-board-request', {
          preserveScroll: true,
          onSuccess: () => { 
            if(props.success){
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
                         }
            boardRequests.body = '';
          },
        })
      }
  
      function getDocType(stage='',doc_type,num=null){
        this.uploadDoc.doc_type = doc_type;
        this.uploadDoc.stage = stage;
        this.uploadDoc.num = num 
        this.show_modal =true   
      }
  
      function deleteDocument(id){
          if(confirm('Document will be deleted...Continue ??')){
            Inertia.delete(`/delete-licence-document/${id}`, {
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
  
      function submitDocument(){
        uploadDoc.post('/upload-licence-document', {
          preserveScroll: true,
          onSuccess: () => { 
            this.show_modal = false;
            this.show_file_name = false;
            document.querySelector('.modal-backdrop').classList.remove('modal-backdrop');
                        if(props.success){
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
                         }
            this.uploadDoc.reset();
           },
        })
      }
  
      function updateRegistration() {//handles dates updates
        form.patch(`/update-new-registration/${props.licence.slug}`, {
          preserveScroll: true,
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
 

      function pushData(e,status_value){

           if (e.target.checked) {
              this.form.status[0] = status_value;
              this.form.unChecked = false;
            }else if(!e.target.checked){
              this.form.unChecked = true
              this.form.status[0] = status_value;
            }
            updateRegistration();
            
        }
        function mergeDocs(){
          Inertia.post(`/merge-licence-docs/${props.licence.id}`, {
          preserveScroll: true,
          onStart: () => {                  
                  checkingFileProgress('This operation can take a while depending on number of files...')                
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

      function computeBoardRequestDate(datetime){
        return new Date(datetime).toLocaleString()
      }
  
     
     function editBoardRequest(body, request_id){
      this.show_modal = true     
      this.editBoardRequestForm.body = body,
      this.editBoardRequestForm.id = request_id      
      }

      function updateRegistrationDate(){
        form.patch(`/update-registration-date/${props.licence.slug}`, {
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


     function updateBoardRequest(){
      editBoardRequestForm.patch('/update-board-request', {
          preserveScroll: true,
          onSuccess: () => {
            this.show_modal = false;
            document.querySelector('.modal-backdrop').remove();
            if(props.success){
                     notify(props.success)
             }else if(props.error){
                      notify(props.error)
             } 
            }
        })
     }


        function deleteRegistration(){
          form.patch(`/update-registration-date/${props.licence.slug}`, {
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
       
      function getFileName(e){
        this.file_size = e.target.files[0].size;
        this.show_file_name = true;
        this.uploadDoc.document_file = e.target.files[0];
        this.file_name = e.target.files[0].name;
        this.file_has_apostrophe = this.file_name.includes("'");
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

       

         function viewFile(model_id) {
              let model = 'LicenceDocument';
               Inertia.visit(`/view-file/${model}/${model_id}`,{
                replace: true,
                onStart: () => {                  
                  checkingFileProgress('Checking file availability...')                
              },
                
               })
         }

      return { 
        viewFile,checkingFileProgress,notify,
        form,show_modal,file_size,
        file_name,getFileName,updateRegistrationDate,
        editBoardRequestForm,
        file_has_apostrophe,
        editBoardRequest,
        updateBoardRequest,
        updateRegistration,
        pushData,uploadDoc,
        getDocType, submitDocument,
        deleteDocument,
        boardRequests,
        submitBoardRequests,
        mergeDocs,show_file_name,
        computeBoardRequestDate,
        deleteRegistration,
        toast
       }
    },
     components: {
      Layout,
      Link,
      Head,
      Task,
      Banner
    },
    
  };
  //The following are status keys
  // 1. Client Quoted
  // 2. Deposit Invoice
  // 3. Deposit Paid
  // 4. Payment to the Liquor Board
  // 5. Prepare New Application
  // 6. Scanned Application
  // 7. Application Lodged
  // 8. Initial Inspection
  // 9. Liquor Board Requests
  // 10. Final Inspection
  // 11. Activation Fee Requested
  // 12. Client Finalisation Invoice
  // 13. Client Paid
  // 14. Activation Fee Paid
  // 15. Licence Issued
  // 16. Licence Delivered 