<template>
  <div class="col-md-6 columns">
    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
        <div class="me-3" v-if="company_doc !== ''">
        <a v-if="company_doc" @click="viewFile(company_doc.id)" href="#!">
         <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
        </a>    
        </div>
    
        <div class="d-flex align-items-start flex-column justify-content-center">
          <h6 class="mb-0 text-sm">Company Documents</h6>
          <p v-if="company_doc" class="mb-0 text-xs">Name: {{ company_doc.document_name }}</p>
          <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
        </div>
    
        <button  v-if="company_doc" @click="deleteDocument(company_doc.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
        <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
        </button>
    
        <button v-else @click="getDocType('')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
        <i class="fa fa-upload" aria-hidden="true"></i>
        </button>
      </li>
    
        <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
        <div class="me-3" v-if="cipc_cert">
        <a v-if="cipc_cert" @click="viewFile(cipc_cert.id)" href="#!">
        <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
        </a>    
        </div>
    
        <div class="d-flex align-items-start flex-column justify-content-center">
          <h6 class="mb-0 text-sm">CIPC Certificate</h6>
           <p v-if="cipc_cert" class="mb-0 text-xs">Name: {{ cipc_cert.document_name }}</p>
           <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
        </div>
        <button  v-if="cipc_cert" @click="deleteDocument(cipc_cert.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
        <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
        </button>
    
        <button v-else @click="getDocType('CIPC-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
        <i class="fa fa-upload" aria-hidden="true"></i>
        </button>
      </li>
    
        <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
        <div class="me-3" v-if="bee_cert">
        <a v-if="bee_cert" @click="viewFile(bee_cert.id)" href="#!">
        <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
        </a>    
        </div>
        <div class="d-flex align-items-start flex-column justify-content-center">
          <h6 class="mb-0 text-sm">BEE Certificate</h6>
          <p v-if="bee_cert" class="mb-0 text-xs">Name: {{ bee_cert.document_name }}</p>
          <p v-if="bee_cert" class="mb-0 text-xs fst-italic">Expiry Date: {{ new Date(bee_cert.expiry_date).toLocaleString().split(',')[0] }}</p>
          <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
        </div>
    
        <button  v-if="bee_cert" @click="deleteDocument(bee_cert.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
        <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
        </button>
       
        <button v-else @click="getDocType('BEE-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
        <i class="fa fa-upload" aria-hidden="true"></i>
        </button>
      </li>
      
      </div>
</template>

<script>
  import { ref } from 'vue';

export default {

  props: {

  },

  setup(){
    let show_modal = ref(true); 
    let show_file_name = ref(false);
    let file_name = ref(''); 

    const documentForm = useForm({
            document: null,
            expiry_date: null,
            doc_type: null,
            company_id: props.company.id,
      })

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

    return{
      show_modal,
      show_file_name,
      file_name,
      documentForm,
      getDocType,
      deleteDocument,
      checkingFileProgress,
      viewFile

    }
  }
}

</script>