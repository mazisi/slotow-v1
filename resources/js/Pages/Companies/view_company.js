import Layout from "../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Multiselect from '@vueform/multiselect';
import Banner from '../components/Banner.vue';
import { ref,onMounted } from 'vue';
import Paginate from '../../Shared/Paginate.vue';
import Task from "../Tasks/Task.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import common from '../common-js/common.js';
import TextInputComponent from '../components/input-components/TextInputComponent.vue'

export default {
 props: {
    tasks: Object,
    errors: Object,
    company: Object,
    people: Array,
    company_doc: Object,
    contrib_cert: Object,
    bee_cert: Object,
    cipc_cert: Object,
    lta_cert: Object,
    sars_cert: Object,
    success: String,
    error: String,
    message: String,
    linked_licences: Object
 },  

 computed: {
  computedProvinces() {
    return common.getProvinces();
  }
},
  
  setup (props) {
    let showMenu = false;
    let people_options = props.people;
    let show_modal = ref(true); 
    let show_file_name = ref(false);
    let file_name = ref(''); 

    const form = useForm({
            company_name: props.company.name,
            company_type: props.company.company_type,
            reg_number: props.company.reg_number,
            vat_number: props.company.vat_number,
            email_address_1: props.company.email,
            email_address_2: props.company.email1,
            email_address_3: props.company.email2,
            telephone_number_1: props.company.tel_number,
            telephone_number_2: props.company.tel_number1,
            website: props.company.website,
            business_address: props.company.business_address,
            business_address2: props.company.business_address2,
            business_address3: props.company.business_address3,
            business_province: props.company.business_province,
            business_address_postal_code: props.company.business_address_postal_code,
            postal_address: props.company.postal_address,
            postal_address2: props.company.postal_code2,
            postal_address3: props.company.postal_code3,
            postal_province: props.company.postal_province,
            postal_code: props.company.postal_code,
            company_id: props.company.id,
            status: '',
            unChecked: false,
            province: props.company.business_province,
            copy_address: true
    })

    function copyBusinessAddress(){
      if(form.copy_address){
        form.postal_address = form.business_address;
        form.postal_address2 = form.business_address2;
        form.postal_address3 = form.business_address3;
        form.postal_province = form.business_province;
        form.postal_code = form.business_address_postal_code;
      }
    }



      const documentsForm = useForm({
            document: null,
            expiry_date: null,
            doc_type: null,
            company_id: props.company.id,
      })


      const addPeopleForm = useForm({
          people: [],
        })

//Add company admin
        const addCompanyUserForm = useForm({person_id: null, company_id: props.company.id })
        function addCompanyUser(){
        addCompanyUserForm.post(`/add-company-admin`, {
        preserveScroll: true,
           onSuccess: () => { 
            this.show_modal = false;
            document.querySelector('.modal-backdrop').remove()
            notify(props.success);
            addCompanyUserForm.reset();
           },
           onError: () => { 
              error()
            },
          }) 
      } 

      const editPerson = useForm({position: null,})
        

        function getPositionValue(position){//Get position value on edit 
          editPerson.position = position;
        }

        function updatePerson(pivot_id){//Update when you adit position 
           editPerson.patch(`/update-position/${pivot_id}`, {
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

        function submitPeople(){
          addPeopleForm.post(`/add-people-to-company/${props.company.id}`, {
           preserveScroll: true,
           onSuccess: (page) => { console.log(page)
            this.show_modal = false;
            document.querySelector('.modal-backdrop').remove();
            if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
                    }
            addPeopleForm.reset();
           },
           onError: () => { 
              error()
            },
          })  
        }

      function submitDocuments(){
          documentsForm.post(`/submit-company-documents`, {
          preserveScroll: true,
          onSuccess: () => {
            this.documentsForm.reset();
            this.show_modal = false;
            this.show_file_name = false;
            document.querySelector('.modal-backdrop').remove()
            if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
                    }
           
          },
          onError: () => { 
              error()
            },
        })    
        }

      function getDocType(doc_type){
        this.documentsForm.doc_type = doc_type;
        this.show_modal = true
      }

      function deleteDocument(id){
          if(confirm('Document will be deleted permanently!! Continue??')){
            Inertia.delete(`/delete-company-document/${id}`,{
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

        function deleteCompany(company_name){
          if(confirm(company_name + ' will be deleted.. Continue??')){
            Inertia.delete(`/delete-company/${props.company.slug}`,{
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

    function submit() {//Update company details
      form.post('/update-company', {
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
 


      function unlinkPerson(full_name,id){
        if(confirm(full_name + ' will be removed from this company...Continue..??')){
          Inertia.delete(`/unlink-person/${id}`,{
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

      

      function assignActiveValue(e,status_value){
       
        const updateStatusForm = useForm({
            unChecked: null,
            status: null,
         });

        if (e.target.checked) {
              updateStatusForm.status = status_value;
              updateStatusForm.unChecked = false;
            }else if(!e.target.checked){
              updateStatusForm.unChecked = true
              updateStatusForm.status = status_value;
            }
            updateStatusForm.patch(`/update-company-active-status/${props.company.slug}`,{
              onSuccess: () => { 
                if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
                    }
             }
             })
           
      }

      function redirectToWebsite(url){
        if(url.includes('https://') || url.includes('http://')){
          window.open(url, "_blank");
        }else{
          window.open(`https://${url}`, "_blank");
        }
        
      }

      let file_has_apostrophe = ref();
      function getFileName(e){
        this.show_file_name = true;
        this.documentsForm.document = e.target.files[0];
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
              let model = 'CompanyDocument';
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

    return {
      showMenu,
      notify,
      common,
      file_name,
      file_has_apostrophe,
      getFileName,
      submit,
      addCompanyUser,
      addCompanyUserForm,
      unlinkPerson,
      assignActiveValue,
      redirectToWebsite,
      show_file_name,
      people_options,
      form,toast,
      documentsForm,
      getDocType,
      submitDocuments,
      deleteDocument,
      addPeopleForm,
      submitPeople,
      editPerson,
      getPositionValue,
      updatePerson,
      show_modal,
      copyBusinessAddress,
      deleteCompany,
      viewFile,checkingFileProgress
    }
  },

   components: {
    Layout,
    Link,
    Head,
    Multiselect,
    Banner,
    Paginate,
    Task,
    TextInputComponent
  },
  
};