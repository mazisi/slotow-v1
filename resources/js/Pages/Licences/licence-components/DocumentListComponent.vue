<template>
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="documentModel">
    <a v-if="documentModel" @click="viewFile(documentModel.id)" href="#!">
    <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">{{ documentTitle }}</h6>
      <p v-if="documentModel" class="mb-0 text-xs">
        {{ documentModel.document_name ? removeFilePath(documentModel.document_name) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="documentModel" @click="deleteDocument(documentModel.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType(documentTitle)" type="button" data-bs-toggle="modal" data-bs-target="#licence-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>

  <input type="" v-model="documentForm.doc_type">
  <div v-if="show_modal" class="modal fade" id="licence-docs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
       
        <!-- <input type="" v-model="documentForm.doc_type"> -->
        <div class="modal-body">      
          <div class="row">
          <div class="col-md-12 columns">
          <label :for="documentTitle" class="btn btn-dark w-100" href="">Select File</label>
           <input type="file"  @change="getFileName"
           hidden :id="documentTitle" accept=".pdf"/>
           <div v-if="errors" class="text-danger">{{ errors }}</div>
           <div v-if="file_name && show_file_name">File Selected: <span class="text-success" v-text="file_name"></span></div>
           <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">Sorry <span class="text-success">{{ file_name }}</span> cannot contain apostrophe(s).Replace apostrophes with backticks.</p>  
         </div>
         <div class="col-md-12">
            <progress v-if="documentForm.progress" :value="documentForm.progress.percentage" max="100">
           {{ documentForm.progress.percentage }}%
           </progress>
           </div>
           </div>
        </div>
    
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button @click="uploadDocument" type="button" class="btn btn-primary" :disabled="documentForm.processing || file_has_apostrophe">
           <span v-if="documentForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
           Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

export default{
  
  props: {
    licence_id: Number,
    documentModel: Object,//eg Original Licence doc
    documentTitle: String,
    success: String,
    error: String,
    errors: String
  },

  setup(props){
        let show_modal = ref(true);
        let file_name = ref(''); 
        let show_file_name = ref(false);
        let file_has_apostrophe = ref();

    let documentForm = useForm({
          document_file: null,
          licence_id: props.licence_id,
          doc_type: '',
       })
        
        function deleteDocument(id){
          if(confirm('Document will be deleted..Continue??')){
            Inertia.delete(`/delete-licence-document/${id}`,{
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

         function removeFilePath(file_name){
          if(file_name.includes('mrnlabs')){
            let getFileName = file_name.split('/');
              return  getFileName[1];
            }  
              return file_name;
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


      function getDocType(doc_type){
        this.show_modal = true
        documentForm.doc_type = doc_type;
       
      }

      function getFileName(e){
        this.show_file_name = true;
        this.documentForm.document_file = e.target.files[0];
        this.file_name = e.target.files[0].name;
        this.file_has_apostrophe = this.file_name.includes("'");
      }


      
    function uploadDocument(){
          documentForm.post(`/upload-licence-document`, {
          preserveScroll: true,
          onSuccess: () => { 
          this.show_modal = false;
          document.querySelector('.modal-backdrop').remove();

            if(props.success){
                notify(props.success)
            }else if(props.error){
                notify(props.error)
            }
         // documentForm.reset();
         },
        })    
        }

    return {
      getFileName,
      toast,
      deleteDocument,
      checkingFileProgress,
      viewFile,removeFilePath,
      getDocType,
      documentForm,
      uploadDocument,
      show_modal,
      file_name,
      show_file_name,
      file_has_apostrophe
    }

    
  }
}
</script>