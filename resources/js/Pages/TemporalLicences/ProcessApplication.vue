<script src="./process_app.js"></script>
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
.document-names { width: 50%; }

.curser-pointer{ cursor: pointer;}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
<Layout>
  <Head title="Process Application"/>
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row">
  <div class="col-lg-9 col-9">
  <h6 v-if="licence.people === null">Temporary Licence For: 
      <Link :href="`/view-company/${licence.company.slug}`" class="text-success">{{ licence.company.name }}
      </Link>
  </h6>
    <h6 v-else>Temporary Licence For: <Link :href="`/view-person/${licence.people.slug}`" class="text-success">{{ licence.people.full_name }}</Link></h6>
    <p class="text-sm mb-0">Current Stage: 
      <span class="font-weight-bold ms-1" v-if="licence.status == '1'">Client Quoted</span>
     <span v-if="licence.status == '2'" class="font-weight-bold ms-1">Client Invoiced</span>
     <span v-if="licence.status == '3'" class="font-weight-bold ms-1">Client Paid</span>
     <span v-if="licence.status == '4'" class="font-weight-bold ms-1">Collate Temporary Licence Documents </span>
     <span v-if="licence.status == '5'" class="font-weight-bold ms-1">Payment To The Liquor Board</span>
     <span v-if="licence.status == '6'" class="font-weight-bold ms-1">Scanned Application</span>
     <span v-if="licence.status == '7'" class="font-weight-bold ms-1">Temporary Licence Lodged</span>
     <span v-if="licence.status == '8'" class="font-weight-bold ms-1">Temporary Licence Issued</span>
     <span v-if="licence.status == '9'" class="font-weight-bold ms-1">Temporary Licence Delivered</span>
   </p>

  </div>
  <div class="col-lg-3 col-3 my-auto text-end">
    <button v-if="$page.props.auth.has_slowtow_admin_role" 
    @click="deleteTemporalLicence" class=" border-radius-md btn-danger" style="border: none">
      <i class="fa fa-trash-o cursor-pointer" aria-hidden="true" ></i> Delete</button>
  </div>
</div>

<div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="updateLicence">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-quoted" class="active-checkbox" type="checkbox" 
:checked="licence.status >= 1"
@input="pushData($event,1)" value="1">
<label for="client-quoted" class="form-check-label text-body text-truncate status-heading">Client Quoted</label>
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
      <h6 v-if="!client_quoted" class="mb-0 text-sm">Document</h6>
      <h6 v-else-if="client_quoted" class="mb-0 text-sm limit-file-name">{{ client_quoted.document_name }}</h6>
    </div>

    <a v-if="client_quoted !== null" @click="deleteDocument(client_quoted.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType(1,'Client Quoted')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5" aria-hidden="true"></i>
    </a>
  </li>
</ul>    
<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-invoiced"  type="checkbox"
@input="pushData($event,2)" 
:checked="licence.status >= 2" value="2">
<label for="client-invoiced" class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div> 
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_invoiced !== null">
    <a :href="`${$page.props.blob_file_path}${client_invoiced.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      
      <h6 v-if="!client_invoiced" class="mb-0 text-sm">Document</h6>
      <h6 v-else-if="client_invoiced" class="mb-0 text-sm limit-file-name">{{ client_invoiced.document_name }}</h6>
    </div>

    <a v-if="client_invoiced !== null" @click="deleteDocument(client_invoiced.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType(2,'Client Invoiced')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i>
    </a>
  </li>
</ul>
<hr>

<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-paid"  type="checkbox"
@input="pushData($event,3)" 
:checked="licence.status >= 3" value="3">
<label for="client-paid" class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div> 



 <template v-if="licence.client_paid_at == null">
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.client_paid_at">
     </div>
   <div v-if="errors.client_paid_at" class="text-danger">{{ errors.client_paid_at }}</div>
 </div>
 <div class="col-md-1 columns">
  <button  @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<template v-else>
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.client_paid_at">
     </div>
   <div v-if="errors.client_paid_at" class="text-danger">{{ errors.client_paid_at }}</div>
 </div>
 <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
  <button @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="prepare" type="checkbox" 
@input="pushData($event,4)" :checked="licence.status >= 4" value="4">
<label for="prepare" class="form-check-label text-body text-truncate status-heading">Prepare Temporary Application </label>
</div>
</div> 


<div class="">

<!-- ===============   Company File Uploads ===========================-->
<div v-if="licence.people_id == null" class="d-flex row">
  <div class="col-sm-2"></div>
  
  <div class="col-sm-5">
    <button type="button" class="btn btn-outline-success document-names">Application Form </button>
     <i v-if="company_application_form === null"
      @click="getDocType(4,'Application Form','Company',1)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="company_application_form !== null" @click="deleteDocument(company_application_form.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
     <a v-if="company_application_form !== null" :href="`${$page.props.blob_file_path}${company_application_form.document}`" target="_blank">
     <i v-if="company_application_form !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br> 



     <button type="button" class="btn btn-outline-success document-names">Proof Of Payment</button>
      <a v-if="liqour_board !== null" :href="`${$page.props.blob_file_path}${liqour_board.document}`" target="_blank">
    <i class="fa fa-link h5 mx-2 curser-pointer"  aria-hidden="true"></i>
    </a>
    
    <a v-else :disabled="true">
    <i class="fa fa-link h5 mx-2"  aria-hidden="true"></i>
    </a> <br>

       <button type="button" class="btn btn-outline-success document-names">POA &amp; RES</button> 
       <i v-if="company_poa == null" @click="getDocType(4,'POA And RES','Company',3)" data-bs-toggle="modal" data-bs-target="#documents" 
         class="fa fa-upload h5 curser-pointer mx-2"></i>
         <i v-if="company_poa !== null" @click="deleteDocument(company_poa.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
     <a v-if="company_poa !== null" :href="`${$page.props.blob_file_path}${company_poa.document}`" target="_blank">
     <i v-if="company_poa !== null" class="fa fa-file-pdf h4 text-danger"></i></a><br> 

       <button type="button" class="btn btn-outline-success document-names">Annexure B & C</button>
        <i v-if="company_annexure_b == null" @click="getDocType(4,'Annexure B','Company',4)" data-bs-toggle="modal" data-bs-target="#documents" 
        class="fa fa-upload h5 mx-2 curser-pointer"></i>
         <i v-if="company_annexure_b !== null" @click="deleteDocument(company_annexure_b.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_annexure_b !== null" :href="`${$page.props.blob_file_path}${company_annexure_b.document}`" target="_blank">
        <i v-if="company_annexure_b !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
     <br> 

        <!-- <button type="button" class="btn btn-outline-success document-names">Annexure C</button>
         <i v-if="company_annexure_c == null" @click="getDocType('Annexure C','Company',5)" data-bs-toggle="modal" data-bs-target="#documents" 
         class="fa fa-upload h5 mx-2 curser-pointer"></i>
        <i v-if="company_annexure_c !== null" @click="deleteDocument(company_annexure_c.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_annexure_c !== null" :href="`${$page.props.blob_file_path}${company_annexure_c.document}`" target="_blank">
        <i v-if="company_annexure_c !== null" class="fa fa-file-pdf h4 text-danger"></i></a><br> -->

        <button type="button" class="btn btn-outline-success document-names"> CIPC Certificate</button>
         <i v-if="company_cipc == null" @click="getDocType(4,'CIPC Certificate','Company',6)" data-bs-toggle="modal" data-bs-target="#documents"
         class="fa fa-upload h5 mx-2 curser-pointer"></i>
         <i v-if="company_cipc !== null" @click="deleteDocument(company_cipc.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_cipc !== null" :href="`${$page.props.blob_file_path}${company_cipc.document}`" target="_blank">
        <i v-if="company_cipc !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
        <br> 
    <div class="col-sm-1"> </div>
  </div>
  
  <div class="col-sm-5">
  <button type="button" class="btn btn-outline-success document-names">ID Dcocument </button>
     <i v-if="company_id_document == null" @click="getDocType(4,'ID Document','Company',7)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="company_id_document !== null" @click="deleteDocument(company_id_document.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_id_document !== null" 
        :href="`${$page.props.blob_file_path}${company_id_document.document_file}`" target="_blank">
        <i v-if="company_id_document !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br> 


   <button type="button" class="btn btn-outline-success document-names">Representations</button>
  <i v-if="company_representations == null" @click="getDocType(4,'Representations','Company',8)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="company_representations !== null" @click="deleteDocument(company_representations.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_representations !== null" :href="`${$page.props.blob_file_path}${company_representations.document}`" target="_blank">
        <i v-if="company_representations !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
     <br>  


    <button type="button" class="btn btn-outline-success document-names">Landlord Letter</button>
     <i v-if="company_landlord_letter == null" @click="getDocType(4,'Landlord Letter','Company',9)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="company_landlord_letter  !== null" @click="deleteDocument(company_landlord_letter.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_landlord_letter !== null" :href="`${$page.props.blob_file_path}${company_landlord_letter.document}`" target="_blank">
        <i v-if="company_landlord_letter !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
        <br>
     <button type="button" class="btn btn-outline-success document-names">Security Letter</button>
      <i v-if="company_security_letter == null" @click="getDocType(4,'Security Letter','Company',10)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="company_security_letter  !== null" @click="deleteDocument(company_security_letter.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_security_letter !== null" :href="`${$page.props.blob_file_path}${company_security_letter.document}`" target="_blank">
        <i v-if="company_security_letter !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br>


      <button type="button" class="btn btn-outline-success document-names">Advert/Blurb</button>
      <i v-if="company_advert == null" @click="getDocType(4,'Advert/Blurb','Company',11)" 
      data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
    <i v-if="company_advert  !== null" @click="deleteDocument(company_advert.id)" 
    class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_advert !== null" :href="`${$page.props.blob_file_path}${company_advert.document}`" target="_blank">
        <i v-if="company_advert !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br>


    <button type="button" class="btn btn-outline-success document-names">Plan/Maps</button>
    <i v-if="company_plan == null" @click="getDocType(4,'Plan/Maps','Company',12)" 
      data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
    <i v-if="company_plan  !== null" @click="deleteDocument(company_plan.id)" 
    class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_plan !== null" :href="`${$page.props.blob_file_path}${company_plan.document}`" target="_blank">
        <i v-if="company_plan !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br> 
  <div class="col-sm-1"> </div>
 
 <div class="d-flex">
  <button v-if="company_application_form !== null
&& company_poa !== null
&& company_annexure_b !== null
// && company_annexure_c !== null
&& company_cipc !== null
&& company_id_document !== null
&& company_representations !== null
&& company_landlord_letter !== null
&& company_security_letter !== null
&& company_advert !== null"

@click="mergeDocuments('Company')" type="button" :disabled="mergeForm.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary" >
  <span v-if="mergeForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span class="visually-hidden">Loading...</span> Compile Application
</button>
  <a :href="`/storage/app/public/${licence.merged_document}`" 
 v-if="licence.merged_document !== null" target="_blank"  class="ms-2 btn btn-sm btn-secondary" >
  View </a>
 </div>


  </div>
  
</div>





<!-- ===============   Individual File Uploads ===========================-->
<div v-if="licence.company_id == null" class="d-flex row">
  <div class="col-sm-2"></div>
  
  <div class="col-sm-5">
    <button type="button" class="btn btn-outline-success document-names">Application Form </button>
     <i v-if="individual_application_form === null"
      @click="getDocType(4,'Application Form','Individual',1)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="individual_application_form !== null" @click="deleteDocument(individual_application_form.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
     <a v-if="individual_application_form !== null" :href="`${$page.props.blob_file_path}${individual_application_form.document}`" target="_blank">
     <i v-if="individual_application_form !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br> 



     <button type="button" class="btn btn-outline-success document-names">Proof Of Payment</button>
      <a v-if="liqour_board !== null" :href="`${$page.props.blob_file_path}${liqour_board.document}`" target="_blank">
    <i class="fa fa-link h5 mx-2 curser-pointer"  aria-hidden="true"></i>
    </a>
    
    <a v-else :disabled="true">
    <i class="fa fa-link h5 mx-2"  aria-hidden="true"></i>
    </a> <br>

       <button type="button" class="btn btn-outline-success document-names">Power Of Attorney</button> 
       <i v-if="power_of_attorney == null" @click="getDocType(4,'Power Of Attorney','Individual',3)" data-bs-toggle="modal" data-bs-target="#documents" 
         class="fa fa-upload h5 curser-pointer mx-2"></i>
         <i v-if="power_of_attorney !== null" @click="deleteDocument(power_of_attorney.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
     <a v-if="power_of_attorney !== null" :href="`${$page.props.blob_file_path}${power_of_attorney.document}`" target="_blank">
     <i v-if="power_of_attorney !== null" class="fa fa-file-pdf h4 text-danger"></i></a><br> 

       <button type="button" class="btn btn-outline-success document-names">Annexure B & C</button>
        <i v-if="individual_annexure_b == null" @click="getDocType(4,'Annexure B','Individual',4)" data-bs-toggle="modal" data-bs-target="#documents" 
        class="fa fa-upload h5 mx-2 curser-pointer"></i>
         <i v-if="individual_annexure_b !== null" @click="deleteDocument(individual_annexure_b.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_annexure_b !== null" :href="`${$page.props.blob_file_path}${individual_annexure_b.document}`" target="_blank">
        <i v-if="individual_annexure_b !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
     <br> 

        <!-- <button type="button" class="btn btn-outline-success document-names">Annexure C</button>
         <i v-if="individual_annexure_c == null" @click="getDocType('Annexure C','Individual',5)" data-bs-toggle="modal" data-bs-target="#documents" 
         class="fa fa-upload h5 mx-2 curser-pointer"></i>
        <i v-if="individual_annexure_c !== null" @click="deleteDocument(individual_annexure_c.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_annexure_c !== null" :href="`${$page.props.blob_file_path}${individual_annexure_c.document}`" target="_blank">
        <i v-if="individual_annexure_c !== null" class="fa fa-file-pdf h4 text-danger"></i></a><br>  -->

<div v-if="licence.company_id == null">
  <button type="button" class="btn btn-outline-success document-names">ID Document </button>
     <a v-if="get_person_id_document !== null" 
     :href="`${$page.props.blob_file_path}${get_person_id_document.path}`" target="_blank">
        <i  class="fa fa-link h5 mx-2" ></i></a>
    <a v-else :href="`#!`">
        <i  class="fa fa-link h5 mx-2"></i></a> <br> 

    <div class="col-sm-1"> </div>
</div>
      
  </div>
  
  <div class="col-sm-5">
     <button type="button" class="btn btn-outline-success document-names">Representations</button>
  <i v-if="individual_representations == null" @click="getDocType(4,'Representations','Individual',7)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="individual_representations !== null" @click="deleteDocument(individual_representations.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_representations !== null" :href="`${$page.props.blob_file_path}${individual_representations.document}`" target="_blank">
        <i v-if="individual_representations !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
     <br>  


    <button type="button" class="btn btn-outline-success document-names">Landlord Letter</button>
     <i v-if="individual_landlord_letter == null" @click="getDocType(4,'Landlord Letter','Individual',8)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="individual_landlord_letter  !== null" @click="deleteDocument(individual_landlord_letter.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_landlord_letter !== null" :href="`${$page.props.blob_file_path}${individual_landlord_letter.document}`" target="_blank">
        <i v-if="individual_landlord_letter !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
        <br>
     <button type="button" class="btn btn-outline-success document-names">Security Letter</button>
      <i v-if="individual_security_letter == null" @click="getDocType(4,'Security Letter','Individual',9)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="individual_security_letter  !== null" @click="deleteDocument(individual_security_letter.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_security_letter !== null" :href="`${$page.props.blob_file_path}${individual_security_letter.document}`" target="_blank">
        <i v-if="individual_security_letter !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br>


      <button type="button" class="btn btn-outline-success document-names">Advert/Blurb</button>
      <i v-if="individual_advert == null" @click="getDocType(4,'Advert/Blurb','Individual',10)" 
      data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
    <i v-if="individual_advert  !== null" @click="deleteDocument(individual_advert.id)" 
    class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_advert !== null" :href="`${$page.props.blob_file_path}${individual_advert.document}`" target="_blank">
        <i v-if="individual_advert !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br>


    <button type="button" class="btn btn-outline-success document-names">Plan/Maps</button>
    <i v-if="individual_plan == null" @click="getDocType(4,'Plan/Maps','Individual',11)" 
      data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
    <i v-if="individual_plan  !== null" @click="deleteDocument(individual_plan.id)" 
    class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_plan !== null" :href="`${$page.props.blob_file_path}${individual_plan.document}`" target="_blank">
        <i v-if="individual_plan !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br> 
  <div class="col-sm-1"> </div>
 
  <div class="d-flex">
    <button v-if="individual_application_form !== null
&& get_person_id_document !== null
&& power_of_attorney !== null
&& individual_annexure_b !== null
// && individual_annexure_c !== null
&& individual_representations !== null
&& individual_landlord_letter !== null
&& individual_security_letter !== null
&& individual_advert !== null
&& individual_plan !== null"
@click="mergeDocuments('Individual')" type="button" :disabled="mergeForm.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary" >
  <span v-if="mergeForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span class="visually-hidden">Loading...</span> Compile Application</button>
    <a :href="`/storage/app/public/${licence.merged_document}`" 
 v-if="licence.merged_document !== null" target="_blank" :style="{float: 'right'}" class="ms-2 btn btn-sm btn-secondary" >
  View </a>
  </div>
 




  </div>
</div>

</div>

<hr>

<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="payment" type="checkbox" @input="pushData($event,5)"
:checked="licence.status >= 5" value="5">
<label for="payment" class="form-check-label text-body text-truncate status-heading">Payment To The Liquor Board</label>
</div>
</div> 


<template v-if="licence.payment_to_liquor_board_at == null">
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.payment_to_liquor_board_at">
     </div>
   <div v-if="errors.payment_to_liquor_board_at" class="text-danger">{{ errors.payment_to_liquor_board_at }}</div>
 </div>
 <div class="col-md-1 columns">
  <button  @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<template v-else>
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.payment_to_liquor_board_at">
     </div>
   <div v-if="errors.payment_to_liquor_board_at" class="text-danger">{{ errors.payment_to_liquor_board_at }}</div>
 </div>
 <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
  <button @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>


<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="liqour_board !== null">
    <a :href="`${$page.props.blob_file_path}${liqour_board.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
    <h6 v-if="!liqour_board" class="mb-0 text-sm">Document</h6>
    <h6 v-else-if="liqour_board" class="mb-0 text-sm limit-file-name">{{ liqour_board.document_name }}</h6>
    </div>

    <a v-if="liqour_board !== null" @click="deleteDocument(liqour_board.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType(5,'Payment To The Liquor Board')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5" aria-hidden="true"></i>
    </a>
  </li>
</ul>
<hr/>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="scanned-application" type="checkbox" 
@input="pushData($event,6)" value="6" :checked="licence.status >= 6">
<label for="scanned-application" class="form-check-label text-body text-truncate status-heading"> Scanned Application </label>
</div>
</div> 


<div class="col-md-6" style="margin-top: -1rem;">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="scanned_app !== null">
    <a :href="`${$page.props.blob_file_path}${scanned_app.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 v-if="!scanned_app" class="mb-0 text-sm">Document</h6>
    <h6 v-else-if="scanned_app" class="mb-0 text-sm limit-file-name">{{ scanned_app.document_name }}</h6>
    </div>
    <a v-if="scanned_app !== null" @click="deleteDocument(scanned_app.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a @click="getDocType(6,'Scanned Application')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;" v-else>
    <i class="fa fa-upload h5" aria-hidden="true"></i>
    </a>
  </li>
</ul>
</div>
<hr/>
<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="logded" type="checkbox" 
@input="pushData($event,7)" :checked="licence.status >= 7" value="7">
<label for="logded" class="form-check-label text-body text-truncate status-heading"> Temporary Licence Lodged </label>
</div>
</div> 

<template v-if="licence.logded_at == null">
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.logded_at">
     </div>
   <div v-if="errors.logded_at" class="text-danger">{{ errors.logded_at }}</div>
 </div>
 <div class="col-md-1 columns">
  <button  @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<template v-else>
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.logded_at">
     </div>
   <div v-if="errors.logded_at" class="text-danger">{{ errors.logded_at }}</div>
 </div>
 <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
  <button
     @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<div class="col-md-6" style="margin-top: -1rem;">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="licence_logded !== null">
    <a :href="`${$page.props.blob_file_path}${licence_logded.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 v-if="!licence_logded" class="mb-0 text-sm">Document</h6>
    <h6 v-else-if="licence_logded" class="mb-0 text-sm limit-file-name">{{ licence_logded.document_name }}</h6>
    </div>
    <a v-if="licence_logded !== null" @click="deleteDocument(licence_logded.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a @click="getDocType(7,'Licence Lodged')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;" v-else>
    <i class="fa fa-upload h5" aria-hidden="true"></i>
    </a>
  </li>
</ul>
</div>
<hr>




<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="issued" type="checkbox"
@input="pushData($event,8)" :checked="licence.status >= 8" value="8">
<label for="issued" class="form-check-label text-body text-truncate status-heading"> Temporary Licence Issued</label>
</div>
</div>

<template v-if="licence.issued_at == null">
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.issued_at">
     </div>
   <div v-if="errors.issued_at" class="text-danger">{{ errors.issued_at }}</div>
 </div>
 <div class="col-md-1 columns">
  <button type="button" @click="updateDate" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<template v-else>
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.issued_at">
     </div>
   <div v-if="errors.issued_at" class="text-danger">{{ errors.issued_at }}</div>
 </div>
 <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
  <button @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="licence_issued !== null">
    <a :href="`${$page.props.blob_file_path}${licence_issued.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
    <h6 v-if="!licence_issued" class="mb-0 text-sm">Document</h6>
    <h6 v-else-if="licence_issued" class="mb-0 text-sm limit-file-name">{{ licence_issued.document_name }}</h6>
    </div>

    <a v-if="licence_issued !== null" @click="deleteDocument(licence_issued.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType(8,'Licence Issued')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5" aria-hidden="true"></i>
    </a>
  </li>
</ul>  
<hr>





<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="delivered" type="checkbox"
@input="pushData($event,9)" :checked="licence.status == 9" value="9">
<label for="delivered" class="form-check-label text-body text-truncate status-heading"> Temporary Licence Delivered</label>
</div>
</div>

<template v-if="licence.delivered_at == null">
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.delivered_at">
     </div>
   <div v-if="errors.delivered_at" class="text-danger">{{ errors.delivered_at }}</div>
 </div>
 <div class="col-md-1 columns">
  <button  @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<template v-else>
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.delivered_at">
     </div>
   <div v-if="errors.delivered_at" class="text-danger">{{ errors.delivered_at }}</div>
 </div>
 <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
  <button @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>


<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="licence_delivered !== null">
    <a :href="`${$page.props.blob_file_path}${licence_delivered.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
    <h6 v-if="!licence_delivered" class="mb-0 text-sm">Document</h6>
    <h6 v-else-if="licence_delivered" class="mb-0 text-sm limit-file-name">{{ licence_delivered.document_name }}</h6>
    </div>

    <a v-if="licence_delivered !== null" @click="deleteDocument(licence_delivered.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType(9,'Licence Delivered')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5" aria-hidden="true"></i>
    </a>
  </li>
</ul>  



</div>
</form>
  </div>
</div>
<hr class="vertical dark" />
</div>
      <!-- //tasks were here -->
        
</div>

</div>

<hr/>
      <!-- <LiquorBoardRequest 
      :model_type='`Temporal Licence`'
      :model_id="licence.id" 
      :liqour_board_requests="liqour_board_requests"
      /> 
<hr>-->
<Task :tasks="tasks" :model_id="licence.id" :success="success" :error="error" :errors="errors" :model_type="'Temporal Licence'"/>

</div>
</div>

<div v-if="show_modal" class="modal fade" id="documents" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="submitDocument">
      <input type="hidden" v-model="uploadDoc.doc_type">
       <input type="hidden" v-model="uploadDoc.person_or_company">
       <input type="hidden" v-model="uploadDoc.merge_number">
      <div class="modal-body">      
        <div class="row">
          

        <div class="col-md-12 columns">
        <label for="licence-doc" class="btn btn-dark w-100" href="">Select File</label>
         <input type="file" @input="getFileName"
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
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary" :disabled="uploadDoc.processing || file_has_apostrophe">
         <span v-if="uploadDoc.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
  </Layout>
</template>
