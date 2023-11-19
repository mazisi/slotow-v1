<script>
import Layout from "../../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import Banner from '../../components/Banner.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref } from "vue";

export default {
  name: "Company-ViewAlteration",
 props: {
    errors: Object,
    alteration: Object,
    success: String,
    error: String,
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
         alteration_date: props.alteration.date,
         licence_slug: props.alteration.licence.slug,
         slug: props.alteration.slug,
         client_paid_at: props.alteration.client_paid_at,
         status: [],
         invoiced_at: props.alteration.invoiced_at,
         liquor_board_at: props.alteration.liquor_board_at, 
         logded_at: props.alteration.logded_at,
         certification_issued_at: props.alteration.certification_issued_at,
         delivered_at: props.alteration.delivered_at     
      })

      const uploadDoc = useForm({
            document: null,
            doc_type: null,
            doc_number: null,
            file_name: file_name,
            alteration_id: props.alteration.id    
          })

    

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
                let dismiss = document.querySelector('.modal-backdrop') 
                dismiss.remove();
                
           },
            })
          }

      function deleteDocument(id){
          if(confirm('Document will be deleted...Continue ??')){
            Inertia.delete(`/delete-alteration-document/${id}`, {
              //
            })
          }
        }

      function getDocType(doc_type, doc_number='') {
        uploadDoc.doc_type = doc_type;
        uploadDoc.doc_number = doc_number;
        this.show_modal = true
      }

      function getDocumentType(document_path){
        if(document_path){
          return document_path.split('/')[1]
        }
        
      }

    return {
      form,showMenu,show_modal,
      file_name,
      show_file_name,
      getFileName,
      submitDocument,
      deleteDocument,
      getDocType,
      uploadDoc,getDocumentType,
      file_has_apostrophe
    };
  },
    
  components: {
    Layout,
    Multiselect,
    Link,
    Banner,
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
</script>
<style>
.columns{
  margin-bottom: 1rem;
}
.active-checkbox{
  margin-top: -10px;
}
.status-heading{
  font-weight: 700;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
<Layout>
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row">
  <div class="col-lg-6 col-7">
   <h6> Alteration Info for: 
    <Link :href="`/company/view-my-licence?slug=${alteration.licence.slug}`">
      <span class="text-success">{{ alteration.licence.trading_name }}</span></Link></h6>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end">
    
  </div>
</div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="update">
<div class="row">


  <div class="col-12 columns">
    <div class=" form-switch d-flex ps-0 ms-0  is-filled">
    <input id="client-quoted" class="active-checkbox" type="checkbox" disabled value="1" :checked="alteration.status >= 1">
    <label for="client-quoted" class="form-check-label text-body text-truncate status-heading">Client Quoted</label>
    </div>
  </div>  
   
    
    <div class="col-9 columns">
    <ul class="list-group">
      <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
        <div class="avatar me-3" v-if="client_quoted !== null">
        <a :href="`${$page.props.blob_file_path}${client_quoted.path}`" target="_blank">
        <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
        </a>
        </div>
        <div class="d-flex align-items-start flex-column justify-content-center">
          <h6 class="mb-0 text-sm">Document</h6>
           <p v-if="client_quoted !== null" class="mb-0 text-xs limit-file-name">{{ client_quoted.document_name }}</p>
          <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
        </div>
        <a v-if="client_quoted !== null" @click="deleteDocument(client_quoted.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
        <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
        </a>
        <a v-else @click="getDocType('Client Quoted')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
        <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
      </li> 
    </ul>
    </div>
<hr/>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-invoiced" class="active-checkbox" type="checkbox" disabled value="2" :checked="alteration.status >= 2">
<label for="client-invoiced" class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div>  


<div class="col-9 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_invoiced !== null">
    <a :href="`${$page.props.blob_file_path}${client_invoiced.path}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="client_invoiced !== null" class="mb-0 text-xs">{{ getDocumentType(client_invoiced.path) }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="client_invoiced !== null" @click="deleteDocument(client_invoiced.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Client Invoiced')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li> 
</ul>
</div>

<hr/>

<div class="col-7 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-paid" type="checkbox" disabled value="3" :checked="alteration.status >= 3">
<label for="client-paid" class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div> 

 <div class="col-5 columns">
  <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Date</label>
    <input type="date" readonly class="form-control form-control-default" v-model="form.client_paid_at">
    </div>
     <div v-if="errors.client_paid_at" class="text-danger">{{ errors.client_paid_at }}</div>
</div>

<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="alteration-details" type="checkbox" disabled value="4" :checked="alteration.status >= 4">
<label class="form-check-label text-body text-truncate status-heading" for="alteration-details">Prepare Alterations Application</label>
</div>
</div> 


<div class="row">
<div class="col-md-6 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="application_form !== ''">
    <a v-if="application_form" :href="`${$page.props.blob_file_path}${application_form.path}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Application Form </h6>
      <p v-if="application_form" class="mb-0 text-xs limit-file-name">
        {{ application_form.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="application_form" @click="deleteDocument(application_form.path)" type="button" class="mb-0 btn btn-link pe-10 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Application Form',1)" type="button" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-10 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>


  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="dimensional_plans !== ''">
    <a v-if="dimensional_plans" :href="`${$page.props.blob_file_path}${dimensional_plans.path}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Fully Dimensional Plans</h6>
      <p v-if="dimensional_plans" class="mb-0 text-xs limit-file-name">
        {{ dimensional_plans.document_name ? dimensional_plans.document_name : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="dimensional_plans" @click="deleteDocument(dimensional_plans.id)" type="button" class="mb-0 btn btn-link pe-10 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Fully Dimensional Plans',2)" type="button" data-bs-toggle="modal" data-bs-target="#document-upload" 
    class="mb-0 btn btn-link pe-10 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>

  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="payment_to_liquor_board !== ''">
    <a v-if="payment_to_liquor_board" :href="`${$page.props.blob_file_path}${payment_to_liquor_board.path}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Payment To The Liquor Board</h6>
       <p v-if="payment_to_liquor_board" class="mb-0 text-xs limit-file-name">
        {{ payment_to_liquor_board.document_name ? payment_to_liquor_board.document_name : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="payment_to_liquor_board" @click="deleteDocument(payment_to_liquor_board.id)" type="button" class="mb-0 btn btn-link pe-10 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Payment To The Liquor Board',5)" type="button" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-10 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>
</ul>
<hr class="vertical dark" />
</div>

<div class="col-md-6 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="poa_res">
    <a v-if="poa_res.path" :href="`${$page.props.blob_file_path}${poa_res.path}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">POA & RES</h6>
      <p v-if="poa_res" class="mb-0 text-xs limit-file-name">{{ poa_res.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>

    <button  v-if="poa_res" @click="deleteDocument(poa_res.id)" type="button" class="mb-0 btn btn-link pe-10 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('POA & RES',3)" type="button" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-10 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>


  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="smoking_affidavict !== ''">
    <a v-if="smoking_affidavict" :href="`${$page.props.blob_file_path}${smoking_affidavict.path}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center" style="margin-left: -1rem">
      <h6 class="mb-0 text-sm">Smoking Affidavit</h6>
       <p v-if="smoking_affidavict" class="mb-0 text-xs limit-file-name">
        {{ smoking_affidavict.document_name ? smoking_affidavict.document_name : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="smoking_affidavict" @click="deleteDocument(smoking_affidavict.id)" type="button" class="mb-0 btn btn-link pe-10 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Smoking Affidavit',4)" type="button" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-10 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>

</ul>

</div>
</div>
<hr>


<div class="col-7 columns">
  <div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="alteration-board2" type="checkbox" disabled value="5" :checked="alteration.status >= 5">
<label class="form-check-label text-body text-truncate status-heading" for="alteration-board2">Payment to the Liquor Board</label>
</div>

<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="liqour_board !== null">
    <a :href="`${$page.props.blob_file_path}${liqour_board.path}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="liqour_board !== null" class="mb-0 text-xs limit-file-name">{{ liqour_board.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="liqour_board !== null" @click="deleteDocument(liqour_board.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Payment to the Liquor Board-2')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li> 
</ul>

</div>
<div class="col-5 columns">
  <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Date</label>
    <input type="date" readonly class="form-control form-control-default" v-model="form.liquor_board_at">
    </div>
     <div v-if="errors.liquor_board_at" class="text-danger">{{ errors.liquor_board_at }}</div>
</div>

<hr/>


<div class="col-7 columns">
  <div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="alteration-logded" type="checkbox" disabled value="6" :checked="alteration.status >= 6">
<label class="form-check-label text-body text-truncate status-heading" for="alteration-logded">Alterations Lodged</label>
</div>

<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="alteration_logded !== null">
    <a :href="`${$page.props.blob_file_path}${alteration_logded.path}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="alteration_logded !== null" class="mb-0 text-xs limit-file-name">{{ alteration_logded.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="alteration_logded !== null" @click="deleteDocument(alteration_logded.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Alterations Lodged')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li> 
</ul>

</div>
<div class="col-5 columns">
  <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Date</label>
    <input type="date" readonly class="form-control form-control-default" v-model="form.logded_at">
    </div>
     <div v-if="errors.logded_at" class="text-danger">{{ errors.logded_at }}</div>
</div>

<hr/>

<div class="col-7 columns">
  <div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="alteration-issued" type="checkbox" disabled value="7" :checked="alteration.status >= 7">
<label class="form-check-label text-body text-truncate status-heading" for="alteration-issued">Alterations Certificate Issued</label>
</div>

<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="certification_issued !== null">
    <a :href="`${$page.props.blob_file_path}${certification_issued.path}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="certification_issued !== null" class="mb-0 text-xs limit-file-name">
        {{ certification_issued.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="certification_issued !== null" @click="deleteDocument(certification_issued.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Alterations Certificate Issued')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li> 
</ul>

</div>
<div class="col-5 columns">
  <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Date</label>
    <input type="date" readonly class="form-control form-control-default" v-model="form.certification_issued_at">
    </div>
     <div v-if="errors.certification_issued_at" class="text-danger">{{ errors.certification_issued_at }}</div>
</div>

<hr/>


<div class="col-7 columns">
  <div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="alteration-issued" type="checkbox" disabled value="8" :checked="alteration.status >= 8">
<label class="form-check-label text-body text-truncate status-heading" for="alteration-issued">Alterations Delivered</label>
</div>

<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="alteration_delivered !== null">
    <a :href="`${$page.props.blob_file_path}${alteration_delivered.path}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="alteration_delivered !== null" class="mb-0 text-xs limit-file-name">{{ alteration_delivered.document_name }}</p>
      <p v-if="!alteration_delivered" class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="alteration_delivered !== null" @click="deleteDocument(alteration_delivered.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Alterations Delivered')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li> 
</ul>

</div>
<div class="col-5 columns">
  <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Date</label>
    <input type="date" readonly class="form-control form-control-default" v-model="form.delivered_at">
    </div>
     <div v-if="errors.delivered_at" class="text-danger">{{ errors.delivered_at }}</div>
</div>

<hr/>

            </div>
            </form>
              </div>
            </div>
          
          </div>
        
        </div>
        
      </div>
    </div>
  </div>


  <div v-if="show_modal" class="modal fade" id="document-upload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="submitDocument">
      <input type="hidden" v-model="uploadDoc.doc_type">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns">
        <label for="licence-doc" class="btn btn-dark w-100" href="">Select File</label>
         <input type="file" @change="getFileName"
         hidden id="licence-doc" accept=".pdf"/>
         <div v-if="errors.document" class="text-danger">{{ errors.document }}</div>
         <div v-if="file_name && show_file_name">File Selected: <span class="text-success" v-text="file_name"></span></div>
         <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">Sorry <span class="text-success">{{ file_name }}</span> cannot contain apostrophe(s).Replace apostrophes with backticks.</p>  
       </div>
       <div class="col-md-12">
          <progress v-if="uploadDoc.progress" :value="uploadDoc.progress.percentage" max="100">
         {{ uploadDoc.progress.percentage }}%
         </progress>
         </div>
         </div>   


      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" :disabled="uploadDoc.processing || file_has_apostrophe">
         <span v-if="uploadDoc.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
  </Layout>
</template>
