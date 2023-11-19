<script>
import Layout from "../../../Shared/Layout.vue";
import { Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref } from "vue";
import Banner from '../../components/Banner.vue';

export default{
  props:{
      errors: Object,
      tasks: Object,
      nominees: Array,
      success: String,
      error: String,
      nomination: Object,
      client_quoted: Object,//doc
      client_invoiced: Object,//doc
      liquor_board: Object,//doc
      nomination_forms: Object,//doc
      proof_of_payment: Object,//doc
      attorney_doc: Object,//doc
      certified_id_doc: Object,//doc
      police_clearance_doc: Object,//doc
      latest_renewal_doc: Object,//doc
      nomination_logded: Object,//doc
      nomination_issued: Object,//doc
      nomination_delivered: Object,//doc
      scanned_app: Object,//doc,
      latest_renewal_licence_doc: Object,
  },

  setup (props) {
      let show_modal = ref(true);
      let show_file_name = ref(false);
      let file_name = ref('');

      const updateForm = useForm({
              nomination_year: props.nomination.year,
              nomination_id: props.nomination.id,
              client_paid_date: props.nomination.client_paid_date,
              nomination_lodged_at: props.nomination.nomination_lodged_at,
              nomination_issued_at: props.nomination.nomination_issued_at,
              nomination_delivered_at: props.nomination.nomination_delivered_at,
              payment_to_liquor_board_at: props.nomination.payment_to_liquor_board_at,
              status: [],      
      });

      const uploadDoc = useForm({
            document: null,
            doc_type: null,
            date: null,
            file_name: file_name,
            nomination_id: props.nomination.id    
          })


      function getDocType(doc_type) {
        uploadDoc.doc_type = doc_type
        this.show_modal = true
      }


      function submitDocument(){
            uploadDoc.post('/submit-nomination-document', {
              preserveScroll: true,
              onSuccess: () => { 
                this.show_modal = false;
                this.show_file_name = false;
                let dismiss = document.querySelector('.modal-backdrop') 
                dismiss.remove();
                uploadDoc.reset();
           },
            })
          }

      function deleteDocument(id){
          if(confirm('Document will be deleted permanently...Continue ??')){
            Inertia.delete(`/delete-nomination-document/${id}`, {
              //
            })
          }
        }

      function computeDocumentDate(date_param){
              return new Date(date_param).toLocaleString().split(',')[0]
          };





      let file_has_apostrophe = ref();
      function getFileName(e){
        this.show_file_name = true;
        this.uploadDoc.document = e.target.files[0];
        this.file_name = e.target.files[0].name;
        this.file_has_apostrophe = this.file_name.includes("'");
      }

      return{
            show_modal,file_has_apostrophe,
            computeDocumentDate,deleteDocument,submitDocument,show_file_name,
            getDocType,uploadDoc,file_name,getFileName,updateForm


      }
  },

   components: {
    Layout,
    Link,
    Banner
  },

  // 1= > Client Quoted
  // 2 => Client Invoiced
  // 3 => Client Paid
  // 4 => Payment to the Liquor Board
  // 5 => Select nominees
  // 6 => Prepare Nomination Application 
  // 7  => Scanned Application
  // 8 => Nomination Lodged 
  // 9 => Nomination Issued
  // 10 => Nomination Delivered
}
</script>
<style>
.columns{
  margin-bottom: 1rem;
}
.active-checkbox{
  margin-top: -10px;
  margin-left: 3px;
}
.status-heading{
  font-weight: 700;
}
</style>
<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-12 col-12">
<h6><Link :href="`/company/view-my-licences/?slug=${nomination.licence.slug}`" class="text-success">
  {{ nomination.licence.trading_name ? nomination.licence.trading_name : '' }}</Link> - {{ nomination.year }} </h6>
<p class="text-sm mb-0">Current Stage: 
  <span class="font-weight-bold ms-1" v-if="nomination.status == '1'">Client Quoted</span>
 <span v-if="nomination.status == '2'" class="font-weight-bold ms-1">Client Invoiced</span>
 <span v-if="nomination.status == '3'" class="font-weight-bold ms-1">Client Paid</span>
 <span v-if="nomination.status == '4'" class="font-weight-bold ms-1">Payment to the Liquor Board</span>
 <span v-if="nomination.status == '5'" class="font-weight-bold ms-1">Select Nominees</span>
 <span v-if="nomination.status == '6'" class="font-weight-bold ms-1">Prepare Nomination Application </span>
 <span v-if="nomination.status == '7'" class="font-weight-bold ms-1">Scanned Application</span>
 <span v-if="nomination.status == '8'" class="font-weight-bold ms-1">Nomination Lodged </span>
 <span v-if="nomination.status == '9'" class="font-weight-bold ms-1">Nomination Issued</span>
 <span v-if="nomination.status == '10'" class="font-weight-bold ms-1">Nomination Delivered</span>
</p>

  
</div>
<div class="col-lg-6 col-5 my-auto text-end">
 
</div>
</div>
<div class="row">
<div class="mt-3 row">
<div class="col-12 col-md-12 col-xl-12 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">
<form>
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-quoted" type="checkbox" disabled
:checked="nomination.status >= '1'" class="active-checkbox">
<label for="client-quoted" class="form-check-label text-body text-truncate status-heading">Client Quoted</label>
</div>
</div>  

<div class="col-md-12 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_quoted !== null">
    <a :href="`${$page.props.blob_file_path}${client_quoted.document}`" target="_blank">
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

<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-inv" type="checkbox" disabled
:checked="nomination.status >= '2'" class="active-checkbox">
<label for="client-inv" class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div>

<div class="col-md-12 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_invoiced !== null">
    <a :href="`${$page.props.blob_file_path}${client_invoiced.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="client_invoiced !== null" class="mb-0 text-xs limit-file-name">{{ client_invoiced.document_name }}</p>
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

<hr>
<div class="col-7 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-paid" type="checkbox" disabled
:checked="nomination.status >= '3'" class="active-checkbox">
<label for="client-paid" class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div>

 <template v-if="nomination.client_paid_date == null">
  <div class="col-5 columns mb-4">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" 
      v-model="updateForm.client_paid_date" >
    </div>
    <div v-if="errors.client_paid_date" class="text-danger">{{ errors.client_paid_date }}</div>
   </div>
   
</template>

<hr>

<div class="col-7 columns">
  <div class=" form-switch d-flex ps-0 ms-0  is-filled">
  <input id="liquor-board" type="checkbox" disabled
  :checked="nomination.status >= '4'" class="active-checkbox">
  <label for="liquor-board" class="form-check-label text-body text-truncate status-heading">Payment To The Liquor Board</label>
  </div>
  </div>

 <template v-if="nomination.payment_to_liquor_board_at == null">  

   <div class="col-5 columns mb-4">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" 
      v-model="updateForm.payment_to_liquor_board_at" >
    </div>
    <div v-if="errors.payment_to_liquor_board_at" class="text-danger">{{ errors.payment_to_liquor_board_at }}</div>
   </div>
</template>

 
<div class="col-md-12 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="liquor_board !== null">
    <a :href="`${$page.props.blob_file_path}${liquor_board.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="liquor_board !== null" class="mb-0 text-xs limit-file-name">{{ liquor_board.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
      <!-- <p v-if="liquor_board !== null" class="mb-0 text-xs">Date: {{ computeDocumentDate(liquor_board.date) }}</p> -->
    </div>
    <a v-if="liquor_board !== null" @click="deleteDocument(liquor_board.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Payment To The Liquor Board')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li> 
</ul>
</div>
<hr>

<div class="col-md-11 d-flex columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="Select nominees" type="checkbox" disabled 
:checked="nomination.status >= '5'" class="active-checkbox">
</div>

<label for="Select nominees" class="form-check-label text-body text-truncate status-heading">Nominees</label>
</div>


<div class="table-responsive mb-2">
<table class="table align-items-center mb-0">
<thead>
<tr>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Full Name And Surname
</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Date OF Birth
</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
ID Number
</th>

<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Action
</th>

</tr>
</thead>
<tbody>
<!-- <tr v-if="nomination.people" v-for="person in nomination.people" :key="person.id" >
<td>
<div class="d-flex px-2 py-1" >

<div class="d-flex flex-column justify-content-left">
<Link :href="`/view-person/${person.slug}`"><h6 class="mb-0 text-sm">{{ person.full_name }} </h6></Link>                  
</div>
</div>
</td>
<td class="text-center">{{ person.date_of_birth }}</td>
<td class="text-center">{{ person.id_number }}</td>
<td class="text-center">
<i 
 style="cursor: pointer;"
 class="material-icons-round text-danger fs-10">highlight_off</i>
<Link :href="`/view-person/${person.slug}`">
 <i 
 style="cursor: pointer;"
 class="material-icons-round text-secondary fs-10">visibility</i>
</Link>
</td>
</tr> -->


</tbody>
</table>
</div>
<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="documents-required" type="checkbox" disabled
:checked="nomination.status >= '6'" class="active-checkbox">
<label for="documents-required" class="form-check-label text-body text-truncate status-heading">Prepare Nomination Application </label>
</div>
</div>


<div class="col-md-4 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="nomination_forms !== null">
    <a :href="`${$page.props.blob_file_path}${nomination_forms.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm"> Nomination Forms </h6>
       <p v-if="nomination_forms !== null" class="mb-0 text-xs limit-file-name">{{ nomination_forms.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="nomination_forms !== null" @click="deleteDocument(nomination_forms.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Nomination Forms')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li> 
</ul>
</div>

<div class="col-md-4 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" >
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Proof Of Payment</h6>
    </div>
    <a v-if="liquor_board !== null" :href="`${$page.props.blob_file_path}${liquor_board.document}`" class="mb-0 btn btn-link ms-auto" href="javascript:;">
    <i class="fa fa-link h5 " aria-hidden="true"></i></a>
    <a v-else style="cursor: no-drop;" title="Liqour document not uploaded" class="mb-0 btn btn-link ms-auto" href="javascript:;">
      <i class="fa fa-link h5" aria-hidden="true"></i></a>
  </li>  
</ul>
</div>


<div class="col-md-4 columns">
<ul class="list-group">
   <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="attorney_doc !== null">
    <a :href="`${$page.props.blob_file_path}${attorney_doc.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Signed Power Of Attorney And Resolution </h6>
       <p v-if="attorney_doc !== null" class="mb-0 text-xs limit-file-name">{{ attorney_doc.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="attorney_doc !== null" @click="deleteDocument(attorney_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Power of Attorney')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li>  
</ul>
</div>

<div class="col-md-4 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="certified_id_doc !== null">
    <a :href="`${$page.props.blob_file_path}${certified_id_doc.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">ID Documents</h6>
       <p v-if="certified_id_doc !== null" class="mb-0 text-xs limit-file-name">{{ certified_id_doc.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="certified_id_doc !== null" @click="deleteDocument(certified_id_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('ID Document')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li>  
</ul>
</div>

<div class="col-md-4 columns">
<ul class="list-group">
   <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="police_clearance_doc !== null">
    <a :href="`${$page.props.blob_file_path}${police_clearance_doc.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Police Clearances </h6>
       <p v-if="police_clearance_doc !== null" class="mb-0 text-xs limit-file-name">{{ police_clearance_doc.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="police_clearance_doc !== null" @click="deleteDocument(police_clearance_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Police Clearances')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li>  
</ul>
</div>

<div class="col-md-4 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="latest_renewal_doc !== null">
    <a v-if="latest_renewal_doc !== null" :href="`${$page.props.blob_file_path}${latest_renewal_doc.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Latest renewal/ licence</h6>
       <p v-if="latest_renewal_doc !== null" class="mb-0 text-xs limit-file-name">{{ latest_renewal_doc.document_name }}</p>
      <!-- <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p> -->
    </div>
    <a v-if="latest_renewal_doc !== null" @click="deleteDocument(latest_renewal_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>

    <a v-if="latest_renewal_licence_doc !== null" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" 
      :href="`${$page.props.blob_file_path}${latest_renewal_licence_doc.document_file}`">
      <i class="fa fa-link h5" aria-hidden="true"></i>
    </a>

    <a v-else-if="latest_renewal_doc == null" @click="getDocType('Latest Renewal/Licence')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>

  </li>  
</ul>
</div>

<hr>


<div class="col-md-12 columns">
  <div class=" form-switch d-flex ps-0 ms-0  is-filled">
  <input id="scanned-app" type="checkbox" disabled 
  :checked="nomination.status >= '7'" class="active-checkbox">
  <label for="scanned-app" class="form-check-label text-body text-truncate status-heading">Scanned Application </label>
  </div>
  </div>
  <div class="col-md-6 columns">
    <ul class="list-group">
     <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
        <div class="avatar me-3" v-if="scanned_app !== null">
        <a :href="`${$page.props.blob_file_path}${scanned_app.document}`" target="_blank">
        <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
        </a>
        </div>
        <div class="d-flex align-items-start flex-column justify-content-center">
          <h6 class="mb-0 text-sm">Document</h6>
           <p v-if="scanned_app !== null" class="mb-0 text-xs limit-file-name">{{ scanned_app.document_name }}</p>
          <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
          <p v-if="scanned_app !== null" class="mb-0 text-xs"></p>
        </div>
        <a v-if="scanned_app !== null" @click="deleteDocument(scanned_app.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
        <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
        </a>
        <a v-else @click="getDocType('Scanned Application')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
        <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
      </li>  
    </ul>
    </div>
  <hr/>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="nomination-logded" type="checkbox" disabled
:checked="nomination.status >= '8'" class="active-checkbox">
<label for="nomination-logded" class="form-check-label text-body text-truncate status-heading">Application Lodged</label>
</div>
</div>

<div class="col-7 columns">
<ul class="list-group">
 <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="nomination_logded !== null">
    <a :href="`${$page.props.blob_file_path}${nomination_logded.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="nomination_logded !== null" class="mb-0 text-xs limit-file-name">{{ nomination_logded.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
      <p v-if="nomination_logded !== null" class="mb-0 text-xs"></p>
    </div>
    <a v-if="nomination_logded !== null" @click="deleteDocument(nomination_logded.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Nomination Lodged')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li>  
</ul>
</div>


 <template v-if="nomination.nomination_lodged_at == null">  
 <div class="col-5 columns mb-4">
  <div class="input-group input-group-outline null is-filled ">
  <label class="form-label">Date</label>
  <input type="date" class="form-control form-control-default" 
    v-model="updateForm.nomination_lodged_at" >
  </div>
  <div v-if="errors.nomination_lodged_at" class="text-danger">{{ errors.nomination_lodged_at }}</div>
 </div>
 
</template>

<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="nomination-issued" type="checkbox" disabled
:checked="nomination.status >= '9'" class="active-checkbox">
<label for="nomination-issued" class="form-check-label text-body text-truncate status-heading">Nomination Issued</label>
</div>
</div>

<div class="col-7 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="nomination_issued !== null">
    <a :href="`${$page.props.blob_file_path}${nomination_issued.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="nomination_issued !== null" class="mb-0 text-xs limit-file-name">{{ nomination_issued.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="nomination_issued !== null" @click="deleteDocument(nomination_issued.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Nomination Issued')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li>  
</ul>
</div>



 <template v-if="nomination.nomination_issued_at == null">  
  <div class="col-5 columns mb-4">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" 
      v-model="updateForm.nomination_issued_at" >
    </div>
    <div v-if="errors.nomination_issued_at" class="text-danger">{{ errors.nomination_issued_at }}</div>
   </div>
 </template>
 
<hr>




<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="nomination-delivered" type="checkbox" disabled
:checked="nomination.status >= '10'" class="active-checkbox">
<label for="nomination-delivered" class="form-check-label text-body text-truncate status-heading"> Nomination Delivered</label>
</div>
</div> 

<div class="col-7 columns">
<ul class="list-group">
 <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="nomination_delivered !== null">
    <a :href="`${$page.props.blob_file_path}${nomination_delivered.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="nomination_delivered !== null" class="mb-0 text-xs limit-file-name">{{ nomination_delivered.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
      <p v-if="nomination_delivered !== null" class="mb-0 text-xs"></p>
    </div>
    <a v-if="nomination_delivered !== null" @click="deleteDocument(nomination_delivered.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Nomination Delivered')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li>   
</ul>
</div>

 <template v-if="nomination.nomination_delivered_at == null">  
  <div class="col-5 columns mb-4">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" 
      v-model="updateForm.nomination_delivered_at" >
    </div>
    <div v-if="errors.nomination_delivered_at" class="text-danger">{{ errors.nomination_delivered_at }}</div>
   </div>
  
 </template>
 
<hr>

<div>

</div>
</div>
</form>
</div>
</div>
</div>
<!-- //tasks were here -->

</div>


</div>
</div>
</div>


<div v-if="show_modal" class="modal fade" id="document-upload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload <span class="">{{ uploadDoc.doc_type }}</span> Document</h5>
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


