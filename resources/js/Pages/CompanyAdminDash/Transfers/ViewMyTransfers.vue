<script>
import Layout from "../../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link,useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import LiquorBoardRequest from "../../components/LiquorBoardRequest.vue";
import Banner from '../../components/Banner.vue';


export default {
 props: {
  liqour_board_requests: Object,
    errors: Object,
    view_transfer: Object,
    // companies_dropdown: Array,
    success: String,
    error: String,
    tasks: Object,
    old_transfer_forms: Object,
    current_transfer_forms: Object,
    smoking_affidavict: Object,
    old_poa_res_docs: Object,
    current_poa_res_docs: Object,
    old_shareholding: Object,
    current_shareholding: Object,
    old_cipc_certificate: Object,
    current_cipc_certificate: Object,
    company_docs: Object,
    id_docs: Object,
    police_clearance: Object,
    tax_clearance: Object,
    lta_certificate: Object,
    financial_interest: Object,
    landloard_letter: Object,
    representation: Object,
    index_page: Object,
    client_quoted: Object,
    client_invoiced: Object,
    payment_to_liquor_board: Object,
    original_licence: Object,
    transfer_logded: Object,
    activation_fee: Object,
    transfer_issued: Object,
    transfer_delivered: Object,
    latest_renewal: Object,
    scanned_application: Object
  },

  setup (props) {
    let showMenu = false;
    let show_modal = ref(true);
    let show_file_name = ref(false);
    let file_name = ref('');

    const documentsForm = useForm({
      document_type: '',
      belong_to: '',
      document_number: '',
      file_name: file_name,
      document: null
    })
    

      function submitDocuments(){
          documentsForm.post(`/submit-transfer-documents/${props.view_transfer.id}`, {
          preserveScroll: true,
          onSuccess: () => {
            documentsForm.reset();
            this.show_file_name = false;
           this.show_modal = false;
           let dismiss =  document.querySelector('.modal-backdrop')    
           dismiss.remove();
          },
        })    
        }

        function setDocType(doc_type,belong_to='',document_number=''){
          this.show_modal=true;
          documentsForm.document_type = doc_type
          documentsForm.belong_to = belong_to
          documentsForm.document_number = document_number
          this.show_file_name = false
        }



      function deleteDocument(id){
        if(confirm('Document will be deleted permanently...Continue ??')){
          Inertia.delete(`/delete-transfer-document/${id}`, {
            //
          })
        }
      }

      let file_has_apostrophe = ref();
      function getFileName(e){
        this.show_file_name = true;
        this.documentsForm.document = e.target.files[0];
        this.file_name = e.target.files[0].name;
        this.file_has_apostrophe = this.file_name.includes("'");
      }
    return {
      file_name,
      file_has_apostrophe,
      show_file_name,
      getFileName,
      submitDocuments,
      show_modal,
      documentsForm,
      setDocType,
      deleteDocument
    }
  },
  components: {
    Layout,
    Multiselect,
    Link,
    LiquorBoardRequest,
    Banner
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

//The following are status keys
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Prepare Transfer Application
// 5 => Payment To The Liquor Board
// 6 => Scanned Application
// 7 => Application Logded
// 8 => Activation Fee Paid
// 9 => Transfer Issued
// 10 => Transfer Delivered
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
.fa-upload{
  cursor: pointer;
  }
  .document-names { width: 60%; }

.curser-pointer{ cursor: pointer;}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
<Layout>
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row">
  <div class="col-lg-9 col-9">
  <h5>Transfer Info for: <Link :href="`/company/view-my-licences/${view_transfer.licence.slug}`" class="text-success">
    {{ view_transfer.licence.trading_name ? view_transfer.licence.trading_name : '' }}</Link></h5>
  <p class="text-sm mb-0">Current Stage: 
    <span class="font-weight-bold ms-1" v-if="view_transfer.status == '1'">Client Quoted</span>
   <span v-if="view_transfer.status == '2'" class="font-weight-bold ms-1">Client Invoiced</span>
   <span v-if="view_transfer.status == '3'" class="font-weight-bold ms-1">Client Paid</span>
   <span v-if="view_transfer.status == '4'" class="font-weight-bold ms-1">Prepare Transfer Application</span>
   <span v-if="view_transfer.status == '5'" class="font-weight-bold ms-1">Payment To The Liquor Board</span>
   <span v-if="view_transfer.status == '6'" class="font-weight-bold ms-1">Scanned Application</span>
   <span v-if="view_transfer.status == '7'" class="font-weight-bold ms-1">Application Logded</span>
   <span v-if="view_transfer.status == '8'" class="font-weight-bold ms-1">Activation Fee Paid</span>
   <span v-if="view_transfer.status == '9'" class="font-weight-bold ms-1">Transfer Issued</span>
   <span v-if="view_transfer.status == '10'" class="font-weight-bold ms-1">Transfer Delivered</span>
 </p>
  </div>
  <div class="col-lg-3 col-3 my-auto text-end">
    <button v-if="$page.props.auth.has_slowtow_admin_role"
     @click="deleteTransfer" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>
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
<input id="client-quoted" class="active-checkbox"
:checked="view_transfer.status >= 1" type="checkbox" disabled >
<label for="client-quoted" class="form-check-label text-body text-truncate status-heading">Client Quoted </label>
</div>
</div>
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_quoted !== null">
    <a :href="`${$page.props.blob_file_path}${client_quoted.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="client_quoted !== null" class="mb-0 text-xs limit-file-name">{{ client_quoted.document_name }}</p>
    </div>

  </li>
</ul>  

<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-invoiced" class="active-checkbox"
:checked="view_transfer.status >= 2" type="checkbox" disabled >
<label for="client-invoiced" class="form-check-label text-body text-truncate status-heading">Client Invoiced </label>
</div>
</div> 

<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_invoiced !== null">
    <a :href="`${$page.props.blob_file_path}${client_invoiced.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="client_invoiced !== null" class="mb-0 text-xs limit-file-name">{{ client_invoiced.document_name }}</p>
    </div>

    <a v-if="client_invoiced !== null" @click="deleteDocument(client_invoiced.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="setDocType('Client Invoiced')" data-bs-toggle="modal" data-bs-target="#upload-documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i>
    </a>
  </li>
</ul>  

<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-paid" class="active-checkbox"
:checked="view_transfer.status >= 3" type="checkbox" disabled>
<label for="client-paid" class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div>  
<hr>

<div class="col-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="prepare" class="active-checkbox" 
:checked="view_transfer.status >= 4" type="checkbox" disabled>
<label for="prepare" class="form-check-label text-body text-truncate status-heading">Prepare Transfer Application</label>
</div>
</div> 
 <div class="col-4 columns">
    <div v-if="view_transfer.transfered_from === 'Company'" class="input-group input-group-outline null is-filled ">
    <label class="form-label">Previous Licence Holder</label>
    <input type="text" required readonly title="You can`t change this field." class="form-control form-control-default" 
      :value="view_transfer.old_company.name" >
     </div>

     <div v-else-if="view_transfer.transfered_from === 'Person'" class="input-group input-group-outline null is-filled ">
      <label class="form-label">Previous Licence Holder</label>
      <input type="text" required readonly title="You can`t change this field." class="form-control form-control-default" 
        :value="view_transfer.old_person.full_name" >
       </div>
   </div>

   <div class="col-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="mx-6 mt-2">Transfering To</label>
     </div>
   </div>

  <div v-if="view_transfer.transfered_to === 'Company'" class="col-4 columns" >
  <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Current Licence Holder</label>
    <input :value="view_transfer.new_company.name" type="text" required 
    disabled title="You can`t change this field." class="form-control form-control-default">

    </div>
</div>

<div v-else-if="view_transfer.transfered_to === 'Person'" class="col-4 columns">
  <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Current Licence Holder</label>
    <input :value="view_transfer.new_person.full_name" type="text" required readonly title="You can`t change this field." class="form-control form-control-default">
    </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10"> 
    
    <i v-if="old_transfer_forms == null" @click="setDocType('Transfer Forms','Old Licence Holder',3)" 
    data-bs-toggle="modal" data-bs-target="#upload-documents" class="fa fa-upload h5 " aria-hidden="true"></i>
    <a v-if="old_transfer_forms !== null" target="_blank" :href="`${$page.props.blob_file_path}${old_transfer_forms.document}`">
      <i class="fa fa-file-pdf h5 mx-2 text-danger curser-pointer"></i>
      </a><i v-if="old_transfer_forms !== null" @click="deleteDocument(old_transfer_forms.id)" 
      class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
      
  </div>
  <button type="button" class=" w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Transfer Forms </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
    <i v-if="current_transfer_forms == null" @click="setDocType('Transfer Forms','Current Licence Holder',4)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
    class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
    <a v-if="current_transfer_forms !== null" target="_blank" :href="`${$page.props.blob_file_path}${current_transfer_forms.document}`">
     <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
   </a>
   <i v-if="current_transfer_forms !== null" @click="deleteDocument(current_transfer_forms.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
   </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 mb-2 active w-10"> <i class="fa fa-times-circle h5" aria-hidden="true"></i> </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Smoking Affidavit </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
    <i v-if="smoking_affidavict == null" @click="setDocType('Smoking Affidavit','Current Licence Holder',5)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
    class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
    <a v-if="smoking_affidavict !== null" target="_blank" :href="`${$page.props.blob_file_path}${smoking_affidavict.document}`">
     <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
   </a>
   <i v-if="smoking_affidavict !== null" @click="deleteDocument(smoking_affidavict.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
   </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
     <i v-if="old_poa_res_docs == null" 
     @click="setDocType('POA & RES','Old Licence Holder',6)" data-bs-toggle="modal" data-bs-target="#upload-documents"
     class="fa fa-upload curser-pointer h5" aria-hidden="true"></i>
     
     <a v-if="old_poa_res_docs !== null" target="_blank" :href="`${$page.props.blob_file_path}${old_poa_res_docs.document}`">
      <i class="fa fa-file-pdf h5 mx-2 text-danger curser-pointer"></i>
    </a>
    <i v-if="old_poa_res_docs !== null" @click="deleteDocument(old_poa_res_docs.id)" 
    class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> POA & Res </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <i v-if="current_poa_res_docs == null" @click="setDocType('POA & RES','Current Licence Holder',7)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
     class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
     <a v-if="current_poa_res_docs !== null" target="_blank" :href="`${$page.props.blob_file_path}${current_poa_res_docs.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    <i v-if="current_poa_res_docs !== null" @click="deleteDocument(current_poa_res_docs.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
    </div>
</div>


<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
     <i v-if="old_shareholding == null" 
     @click="setDocType('Shareholding','Old Licence Holder',10)" data-bs-toggle="modal" data-bs-target="#upload-documents"
     class="fa fa-upload curser-pointer h5" aria-hidden="true"></i>
     
     <a v-if="old_shareholding !== null" target="_blank" :href="`${$page.props.blob_file_path}${old_shareholding.document}`">
      <i class="fa fa-file-pdf h5 mx-2 text-danger curser-pointer"></i>
    </a>
    <i v-if="old_shareholding !== null" @click="deleteDocument(old_shareholding.id)" 
    class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Shareholding </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <i v-if="current_shareholding == null" @click="setDocType('Shareholding','Current Licence Holder',12)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
     class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
     <a v-if="current_shareholding !== null" target="_blank" :href="`${$page.props.blob_file_path}${current_shareholding.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    <i v-if="current_shareholding !== null" @click="deleteDocument(current_shareholding.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
    </div>
</div>


<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
     <i v-if="old_cipc_certificate == null" 
     @click="setDocType('CIPC Certificate','Old Licence Holder',11)" data-bs-toggle="modal" data-bs-target="#upload-documents"
     class="fa fa-upload curser-pointer h5" aria-hidden="true"></i>
     
     <a v-if="old_cipc_certificate !== null" target="_blank" :href="`${$page.props.blob_file_path}${old_cipc_certificate.document}`">
      <i class="fa fa-file-pdf h5 mx-2 text-danger curser-pointer"></i>
    </a>
    <i v-if="old_cipc_certificate !== null" @click="deleteDocument(old_cipc_certificate.id)" 
    class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> CIPC Certificate </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <i v-if="current_cipc_certificate == null" @click="setDocType('CIPC Certificate','Current Licence Holder',13)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
     class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
     <a v-if="current_cipc_certificate !== null" target="_blank" :href="`${$page.props.blob_file_path}${current_cipc_certificate.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    <i v-if="current_cipc_certificate !== null" @click="deleteDocument(current_cipc_certificate.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
    </div>
</div>


<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Company Documents </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <i v-if="company_docs == null" @click="setDocType('Company Documents','Current Licence Holder')" data-bs-toggle="modal" data-bs-target="#upload-documents" 
     class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
     <a v-if="company_docs !== null" target="_blank" :href="`${$page.props.blob_file_path}${company_docs.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    <i v-if="company_docs !== null" @click="deleteDocument(company_docs.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
    </div>
</div>



<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> ID Documents </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <i v-if="id_docs == null" @click="setDocType('ID Documents','Current Licence Holder',14)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
     class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
     <a v-if="id_docs !== null" target="_blank" :href="`${$page.props.blob_file_path}${id_docs.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    <i v-if="id_docs !== null" @click="deleteDocument(id_docs.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
    </div>
</div>



<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Police Clearances </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <i v-if="police_clearance == null" @click="setDocType('Police Clearances','Current Licence Holder',15)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
     class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
     <a v-if="police_clearance !== null" target="_blank" :href="`${$page.props.blob_file_path}${police_clearance.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    <i v-if="police_clearance !== null" @click="deleteDocument(police_clearance.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
    </div>
</div>


<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Tax Clearance </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <i v-if="tax_clearance == null" @click="setDocType('Tax Clearance','Current Licence Holder',16)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
     class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
     <a v-if="tax_clearance !== null" target="_blank" :href="`${$page.props.blob_file_path}${tax_clearance.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    <i v-if="tax_clearance !== null" @click="deleteDocument(tax_clearance.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
    </div>
</div>


<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> LTA Certificate </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <i v-if="lta_certificate == null" @click="setDocType('LTA Certificate','Current Licence Holder',17)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
     class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
     <a v-if="lta_certificate !== null" target="_blank" :href="`${$page.props.blob_file_path}${lta_certificate.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    <i v-if="lta_certificate !== null" @click="deleteDocument(lta_certificate.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
    </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Financial Interests </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <i v-if="financial_interest == null" @click="setDocType('Financial Interests','Current Licence Holder',18)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
     class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
     <a v-if="financial_interest !== null" target="_blank" :href="`${$page.props.blob_file_path}${financial_interest.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    <i v-if="financial_interest !== null" @click="deleteDocument(financial_interest.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
    </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Lease/Landlord Letter </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <i v-if="landloard_letter == null" @click="setDocType('Lease/Landlord Letter','Current Licence Holder',19)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
     class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
     <a v-if="landloard_letter !== null" target="_blank" :href="`${$page.props.blob_file_path}${landloard_letter.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    <i v-if="landloard_letter !== null" @click="deleteDocument(landloard_letter.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
    </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    <i v-if="representation == null" @click="setDocType('Representation','Old Licence Holder',8)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
     class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
     <a v-if="representation !== null" target="_blank" :href="`${$page.props.blob_file_path}${representation.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    <i v-if="representation !== null" @click="deleteDocument(representation.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Representation </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
    </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Latest Renewal/Licence </button>
  
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
      <i v-if="latest_renewal == null" @click="setDocType('Latest Renewal','Current Licence Holder',9)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
      class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
      <a v-if="latest_renewal !== null" target="_blank" :href="`${$page.props.blob_file_path}${latest_renewal.document}`">
        <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
      </a>

      <a v-else-if="original_licence !== null" target="_blank" :href="`${$page.props.blob_file_path}${original_licence.document_file}`">
        <i class="fa fa-link float-end h5 curser-pointer"></i>
      </a>
      <i v-if="latest_renewal !== null" @click="deleteDocument(latest_renewal.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
  </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    <i v-if="index_page == null" @click="setDocType('Index Page','Old Licence Holder',1)" data-bs-toggle="modal" data-bs-target="#upload-documents" 
        class="fa fa-upload h5 curser-pointer" aria-hidden="true"></i> 
        <a v-if="index_page !== null" target="_blank" :href="`${$page.props.blob_file_path}${index_page.document}`">
          <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
        </a>
        <i v-if="index_page !== null" @click="deleteDocument(index_page.id)" class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Index Page </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
    </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
   
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Proof Of Payment </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
    <a v-if="payment_to_liquor_board !== null" :href="`${$page.props.blob_file_path}${payment_to_liquor_board.document}`" target="_blank">
    <i class="fa fa-link float-end h5 "></i>
 </a>
    
    </div>
</div>

<hr>



<div class="col-8 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="liquor-b" class="active-checkbox" 
:checked="view_transfer.status >= 5" type="checkbox" disabled>
<label for="liquor-b" class="form-check-label text-body text-truncate status-heading">Payment To The Liquor Board</label>
</div>
</div> 

 
    <div class="col-4 columns mb-4">
     <div class="input-group input-group-outline null is-filled ">
     <label class="form-label">Date</label>
     <input type="date" class="form-control form-control-default" 
       v-model="view_transfer.payment_to_liquor_board_at" >
     </div>
    </div>
   

<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="payment_to_liquor_board !== null">
    <a :href="`${$page.props.blob_file_path}${payment_to_liquor_board.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="payment_to_liquor_board !== null" class="mb-0 text-xs limit-file-name">{{ payment_to_liquor_board.document_name }}</p>
    </div>

    <a v-if="payment_to_liquor_board !== null" @click="deleteDocument(payment_to_liquor_board.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="setDocType('Payment To The Liquor Board')" data-bs-toggle="modal" data-bs-target="#upload-documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i>
    </a>
  </li>
</ul>  


<hr>
<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="scanned-app" class="active-checkbox"
:checked="view_transfer.status >= 6" type="checkbox" disabled>
<label for="scanned-app" class="form-check-label text-body text-truncate status-heading">Scanned Application</label>
</div>
</div>
<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="scanned_application !== null">
    <a :href="`${$page.props.blob_file_path}${scanned_application.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="scanned_application !== null" class="mb-0 text-xs limit-file-name">{{ scanned_application.document_name }}</p>
    </div>

    <a v-if="scanned_application !== null" @click="deleteDocument(scanned_application.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="setDocType('Scanned Application')" data-bs-toggle="modal" data-bs-target="#upload-documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i>
    </a>
  </li>
</ul>  
<hr/>

<div class="col-8 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="app-logded" class="active-checkbox"
:checked="view_transfer.status >= 6" type="checkbox" disabled>
<label for="app-logded" class="form-check-label text-body text-truncate status-heading">Application Logded</label>
</div>
</div>


    <div class="col-4 columns mb-4">
     <div class="input-group input-group-outline null is-filled ">
     <label class="form-label">Date</label>
     <input type="date" class="form-control form-control-default" 
       v-model="view_transfer.lodged_at" >
     </div>
    </div>

  


<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="transfer_logded !== null">
    <a :href="`${$page.props.blob_file_path}${transfer_logded.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="transfer_logded !== null" class="mb-0 text-xs limit-file-name">{{ transfer_logded.document_name }}</p>
    </div>

    <a v-if="transfer_logded !== null" @click="deleteDocument(transfer_logded.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="setDocType('Transfer Logded')" data-bs-toggle="modal" data-bs-target="#upload-documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i>
    </a>
  </li>
</ul>  
<hr>

<div class="col-md-5 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="activation-fee" class="active-checkbox"
:checked="view_transfer.status >= 7" type="checkbox" disabled>
<label for="activation-fee" class="form-check-label text-body text-truncate status-heading">Activation Fee Paid</label>
</div>
</div>



   <template v-if="view_transfer.activation_fee_paid_at == null">  

    <div class="col-md-5 columns mb-4">
     <div class="input-group input-group-outline null is-filled ">
     <label class="form-label">Date</label>
     <input type="date" class="form-control form-control-default" 
       v-model="view_transfer.activation_fee_paid_at" >
     </div>
    </div>
   <div class="col-md-1"></div>
    <div class="col-md-1 columns">
     <button v-if="view_transfer.activation_fee_paid_at == null" 
     @click="submit" type="submit" class="btn btn-sm btn-secondary">Save</button>
    </div>
  </template>
  
  <template v-else>
    <div class="col-md-5 columns mb-4">
     <div class="input-group input-group-outline null is-filled ">
     <label class="form-label">Date</label>
     <input type="date" class="form-control form-control-default" 
       v-model="view_transfer.activation_fee_paid_at" >
     </div>
    </div>
   <div class="col-md-1"></div>
    <div class="col-md-1 columns">
     <button v-if="$page.props.auth.has_slowtow_admin_role" 
     @click="submit" type="submit" class="btn btn-sm btn-secondary">Save</button>
    </div>
  </template>

<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="activation_fee !== null">
    <a :href="`${$page.props.blob_file_path}${activation_fee.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="activation_fee !== null" class="mb-0 text-xs limit-file-name">{{ activation_fee.document_name }}</p>
    </div>

    <a v-if="activation_fee !== null" @click="deleteDocument(activation_fee.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="setDocType('Activation Fee Paid')" data-bs-toggle="modal" data-bs-target="#upload-documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i>
    </a>
  </li>
</ul>  
<hr>


<div class="col-md-5 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="transfer-issued" class="active-checkbox" 
:checked="view_transfer.status >= 8" type="checkbox" disabled>
<label for="transfer-issued" class="form-check-label text-body text-truncate status-heading">Transfer Issued</label>
</div>
</div> 


<template v-if="view_transfer.issued_at == null">  

  <div class="col-md-5 columns mb-4">
   <div class="input-group input-group-outline null is-filled ">
   <label class="form-label">Date</label>
   <input type="date" class="form-control form-control-default" 
     v-model="view_transfer.issued_at" >
   </div>
  </div>
 <div class="col-md-1"></div>
  <div class="col-md-1 columns">
   <button v-if="view_transfer.issued_at == null" 
   @click="submit" type="submit" class="btn btn-sm btn-secondary">Save</button>
  </div>
</template>

<template v-else>
  <div class="col-md-5 columns mb-4">
   <div class="input-group input-group-outline null is-filled ">
   <label class="form-label">Date</label>
   <input type="date" class="form-control form-control-default" 
     v-model="view_transfer.issued_at" >
   </div>
  </div>
 <div class="col-md-1"></div>
  <div class="col-md-1 columns">
   <button v-if="$page.props.auth.has_slowtow_admin_role" 
   @click="submit" type="submit" class="btn btn-sm btn-secondary">Save</button>
  </div>
</template>


<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="transfer_issued !== null">
    <a :href="`${$page.props.blob_file_path}${transfer_issued.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="transfer_issued !== null" class="mb-0 text-xs limit-file-name">{{ transfer_issued.document_name }}</p>
    </div>

    <a v-if="transfer_issued !== null" @click="deleteDocument(transfer_issued.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="setDocType('Transfer Issued')" data-bs-toggle="modal" data-bs-target="#upload-documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i>
    </a>
  </li>
</ul>  
<hr>

<div class="col-5 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="transfer-delivered" class="active-checkbox"
:checked="view_transfer.status >= 9" type="checkbox" disabled>
<label for="transfer-delivered" class="form-check-label text-body text-truncate status-heading">Transfer Delivered</label>
</div>
</div> 


   <template v-if="view_transfer.delivered_at == null">  

    <div class="col-md-5 columns mb-4">
     <div class="input-group input-group-outline null is-filled ">
     <label class="form-label">Date</label>
     <input type="date" class="form-control form-control-default" 
       v-model="view_transfer.delivered_at" >
     </div>
    </div>
   <div class="col-md-1"></div>
    <div class="col-md-1 columns">
     <button v-if="view_transfer.delivered_at == null" 
     @click="submit" type="submit" class="btn btn-sm btn-secondary">Save</button>
    </div>
 </template>
 
 <template v-else>
    <div class="col-md-5 columns mb-4">
     <div class="input-group input-group-outline null is-filled ">
     <label class="form-label">Date</label>
     <input type="date" class="form-control form-control-default" 
       v-model="view_transfer.delivered_at" >
     </div>
    </div>
   <div class="col-md-1"></div>
    <div class="col-md-1 columns">
     <button v-if="$page.props.auth.has_slowtow_admin_role" 
     @click="submit" type="submit" class="btn btn-sm btn-secondary">Save</button>
    </div>
 </template>

<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="transfer_delivered !== null">
    <a :href="`${$page.props.blob_file_path}${transfer_delivered.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="transfer_delivered !== null" class="mb-0 text-xs limit-file-name">{{ transfer_delivered.document_name }}</p>
    </div>

    <a v-if="transfer_delivered !== null" @click="deleteDocument(transfer_delivered.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="setDocType('Transfer Delivered')" data-bs-toggle="modal" data-bs-target="#upload-documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i>
    </a>
  </li>
</ul>  
<hr>


<!-- save -->
            </div>
            </form>
              </div>
            </div>
            
          </div>
  
        
        </div>
        
      </div>


    </div>
  </div>


<div v-if="show_modal" class="modal" id="upload-documents" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="submitDocuments">
      <input type="hidden" v-model="documentsForm.document_type">
      <input type="hidden" v-model="documentsForm.belong_to">
      <input type="hidden" v-model="documentsForm.document_number">
      
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
          <progress v-if="documentsForm.progress" :value="documentsForm.progress.percentage" max="100">
         {{ documentsForm.progress.percentage }}%
         </progress>
         </div>
         </div>   
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary" :disabled="documentsForm.processing || file_has_apostrophe">
         <span v-if="documentsForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
  </Layout>
</template>
