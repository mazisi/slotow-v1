<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-6 col-7">
<h6 class="mb-1">Company Info: {{ company.name }}</h6>
</div>
<div class="col-lg-6 col-5 my-auto text-end">
<div class="dropdown float-lg-end pe-4 d-none">
<a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
<i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
</a>
<ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable" style="">
<li><Link :href="`#!`" @click="show_modal=true" data-bs-toggle="modal" data-bs-target="#add-company-admin" class="dropdown-item border-radius-md">Add Company Admin</Link></li>

<li><a class="dropdown-item border-radius-md text-danger" ><i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</a></li>
</ul>
</div>
</div>
</div>



<div class="row">
<div class="mt-3 ">
<form class="row" @submit.prevent="submit">
<input type="hidden" v-model="form.slug">
<div class="col-8 col-md-8 col-xl-8 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label ms-3 mb-0 text-body text-truncate">Active Company</label>
<input id="active-checkbox" disabled type="checkbox" :checked="form.active == '1'" 
@input="assignActiveValue($event.target.value)" value="1">
</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Company Name *</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.company_name">
</div>
<div v-if="errors.company_name" class="text-danger">{{ errors.company_name }}</div>
</div>
<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Email Address</label>
<input type="email" disabled class="form-control form-control-default" v-model="form.email_address_1" >
</div>
<div v-if="errors.email_address_1" class="text-danger">{{ errors.email_address_1 }}</div>
</div>
<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Company Type </label>
<input type="text" disabled class="form-control form-control-default" v-model="form.company_type">
</div>
<div v-if="errors.company_type" class="text-danger">{{ errors.company_type }}</div>
</div>
  
<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Email Address</label>
<input type="email" disabled class="form-control form-control-default" v-model="form.email_address_2" >
</div>
<div v-if="errors.email_address_2" class="text-danger">{{ errors.email_address_2 }}</div>
</div>
<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Registration Number</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.reg_number" >
</div>
<div v-if="errors.reg_number" class="text-danger">{{ errors.reg_number }}</div>
</div>



<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Email Address</label>
<input type="email" disabled class="form-control form-control-default" v-model="form.email_address_3" >
</div>
<div v-if="errors.email_address_3" class="text-danger">{{ errors.email_address_3 }}</div>
</div>
<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Vat Number</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.vat_number" >
</div>
<div v-if="errors.vat_number" class="text-danger">{{ errors.vat_number }}</div>
</div>


<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Phone Number</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.telephone_number_1" >
</div>
<div v-if="errors.telephone_number_1" class="text-danger">{{ errors.telephone_number_1 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Website</label>
<input type="url" class="form-control form-control-default" disabled v-model="form.website" >
</div>
<div v-if="errors.website" class="text-danger">{{ errors.website }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Phone Number</label>
<input type="text" class="form-control form-control-default" disabled v-model="form.telephone_number_2" >
</div>
<div v-if="errors.telephone_number_2" class="text-danger">{{ errors.telephone_number_2 }}</div>
</div>



<h6 class="text-center mt-5">Company Documents</h6>

<div class="col-md-6 columns">
<li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="company_doc">
    <a v-if="company_doc" :href="`${$page.props.blob_file_path}${company_doc.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center" style="margin-left: -3px;">
      <h6 class="mb-0 text-sm">Company Documents</h6>
      <p v-if="company_doc" class="mb-0 text-xs">Name: {{ company_doc.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="company_doc" @click="deleteDocument(company_doc.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Company-Document')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
    
  </li>

  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="cipc_cert">
    <a v-if="cipc_cert" :href="`${$page.props.blob_file_path}${cipc_cert.document_file}`" target="_blank">
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
    <a v-if="bee_cert" :href="`${$page.props.blob_file_path}${bee_cert.document_file}`" target="_blank">
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
<div class="col-md-6 columns">
<ul class="list-group">

<li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="lta_cert">
    <a v-if="lta_cert" :href="`${$page.props.blob_file_path}${lta_cert.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">LTA Certificate</h6>
      <p v-if="lta_cert" class="mb-0 text-xs">Name: {{ lta_cert.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="lta_cert" @click="deleteDocument(lta_cert.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>
    <button v-else @click="getDocType('LTA-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>


  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="contrib_cert">
    <a v-if="contrib_cert" :href="`${$page.props.blob_file_path}${contrib_cert.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Contribution Certificate</h6>
      <p v-if="contrib_cert" class="mb-0 text-xs">Name: {{ contrib_cert.document_name }}</p>
      <p v-if="contrib_cert" class="mb-0 text-xs fst-italic">Expiry Date: {{ new Date(contrib_cert.expiry_date).toLocaleString().split(',')[0] }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>

    <button  v-if="contrib_cert" @click="deleteDocument(contrib_cert[0].id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Contribution-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>


  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="sars_cert">
    <a v-if="sars_cert" :href="`${$page.props.blob_file_path}${sars_cert.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">SARS Certificate</h6>
      <p v-if="sars_cert" class="mb-0 text-xs">Name: {{ sars_cert.document_name }}</p>
      <p v-if="sars_cert" class="mb-0 text-xs fst-italic">Expiry Date: {{ new Date(sars_cert.expiry_date).toLocaleString().split(',')[0] }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>

    <button  v-if="sars_cert" @click="deleteDocument(sars_cert.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('SARS-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>



</ul>
</div>


</div>

</div>
</div>
<hr class="vertical dark" />
</div>

<div class="col-4 col-md-4 col-xl-4" style="margin-top: 3.4rem;">
<div class="row">
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Business Address Line 1</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.business_address" >
</div>
<div v-if="errors.business_address" disabled class="text-danger">{{ errors.business_address }}</div>
</div>  

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Business Address Line 2</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.business_address2" >
</div>
<div v-if="errors.business_address2" class="text-danger">{{ errors.business_address2 }}</div>
</div> 

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Business Address Line 3</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.business_address3" >
</div>
<div v-if="errors.business_address3" class="text-danger">{{ errors.business_address3 }}</div>
</div> 

<div class="col-6 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<input type="text" class="form-control form-control-default" disabled v-model="form.business_province" >
</div>
<div v-if="errors.business_province" class="text-danger">{{ errors.business_province }}</div>
</div>

<div class="col-6 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Code</label>
<input  type="text" disabled class="form-control form-control-default" v-model="form.business_address_postal_code">
</div>

</div>

<hr>

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Address Line 1</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.postal_address" >
</div>
<div v-if="errors.postal_address" class="text-danger">{{ errors.postal_address }}</div>
</div>  

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Address Line 2</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.postal_address2" >
</div>
<div v-if="errors.postal_address2" class="text-danger">{{ errors.postal_address2 }}</div>
</div> 
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Address Line 3</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.postal_address3" >
</div>
<div v-if="errors.postal_address3" class="text-danger">{{ errors.postal_address }}</div>
</div> 

<div class="col-6 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.postal_province" >
</div>
<div v-if="errors.postal_province" class="text-danger">{{ errors.postal_province }}</div>
</div>

<div class="col-6 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Code</label>
<input  type="text" disabled class="form-control form-control-default" v-model="form.postal_code">
</div>

</div>
</div>
</div>
<div>
<button type="submit" class="btn btn-secondary ms-2 d-none" :style="{float: 'right'}">Save</button>
</div>
</form>
<hr>
</div>
</div>

<div class="row">
<div class="col-sm-12 col-lg-4"></div>
<div class="col-sm-12 col-lg-4">
<h6 class="text-center">Licences Linked To : {{ company.name }}</h6>
</div>


<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
  <tr>
    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
    Trading Name
    </th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    Licence Number
    </th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    Licence Date
    </th>

    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    View
    </th>
    
  </tr>
</thead>
<tbody>
  <tr v-for="licence in company.licences" :key="licence.id">
    <td>
      <div class="d-flex px-2">
    
    <div class="d-flex flex-column">
      <Link :href="`/company/view-my-licences/${licence.slug }`"><h6 class="mb-0 text-sm">{{ limit(licence.trading_name)  }}</h6></Link>                          
    </div>
      </div>
    </td>
    <td class="text-center">{{ licence.licence_number ? licence.licence_number : ''}}</td>
    <td class="text-center">{{ licence.licence_date }}</td>
    <td class="text-center">
    <Link :href="`/company/view-my-licences/${licence.slug }`" class="mx-2 ms-2 justify-content-center">
    <i class="fa fa-eye"></i></Link>
    </td>
    
  </tr>
  
  
</tbody>
</table>
</div>
<hr>
</div>

<!-- <div class="row">
<div class="col-sm-12 col-lg-4"></div>
<div class="col-sm-12 col-lg-4">
<h6 class="text-center">People Linked To : {{ company.name }}</h6>
</div>
<div class="col-sm-12 col-lg-4 text-end">
<button @click="show_modal = true" data-bs-toggle="modal" data-bs-target="#add-people" type="button" class="btn btn-sm btn-secondary ms-2 float-end justify-content-center">Add</button></div>

<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
  <tr>
    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
    Full Name
    </th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    Position
    </th>
  
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    Action
    </th>
    
  </tr>
</thead>
<tbody>
  <tr v-for="person in company.people" :key="person.id">
    <td>
      <div class="d-flex px-2">
    
    <div class="d-flex flex-column">
      <Link :href="`/view-consultant/${person.slug}`"><h6 class="mb-0 text-sm">{{ person.full_name }}</h6></Link>                          
    </div>
      </div>
    </td>
    <td class="mx-8">
    <div class="input-group input-group-outline null mx-5 is-filled">
    <div class="mb-3 mx-10">
  <input :value="person.pivot.position" @input="getPositionValue($event.target.value)"
  name="position" class="form-control form-control-sm form-control-default" id="formFileSm" type="text">
</div>
</div>
</td>
    <td class="text-end ">
    <div class="d-flex float-end ">
    <a @click="updatePerson(person.pivot.id)" type="button" 
    class="mx-2 ms-2 btn btn-sm btn-secondary justify-content-center">Update</a>
    <a @click="unlinkPerson(person.full_name, person.pivot.id )" type="button" 
    class=" ms-2 btn btn-sm btn-danger justify-content-center">Unlink</a>
    </div>
    </td>
    
  </tr>
  
  
</tbody>
</table>
</div>
<hr>
<h6 class="text-center text-decoration-underline">Notes</h6>
</div> -->


<div v-if="show_modal" class="modal" id="company-docs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="submitDocuments">
      <input type="hidden" v-model="documentsForm.doc_type">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns" v-if="documentsForm.doc_type == 'CIPC-Certificate' 
                                          || documentsForm.doc_type == 'BEE-Certificate'
                                          || documentsForm.doc_type == 'SARS-Certificate'">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Expiry Date</label>
        <input type="date" required class="form-control form-control-default" 
         v-model="documentsForm.expiry_date" >
        </div>
        <div v-if="errors.expiry_date" class="text-danger">{{ errors.expiry_date }}</div>
        </div>

        <div class="col-md-12 columns">
        <label for="licence-doc" class="btn btn-dark w-100" href="#!">Select File</label>
         <input type="file" @change="getFileName"
         hidden id="licence-doc" accept=".pdf"/>
         <div v-if="errors.document" class="text-danger">{{ errors.document }}</div>
         <div v-if="file_name && show_file_name">File Selected: <span class="text-success" v-text="file_name"></span></div>
         <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">Sorry <span class="text-success">{{ file_name }}</span> cannot contain apostrophe(s).Replace apostrophes with backtick.</p>  
        </div>
       <div class="col-md-12">
          <progress v-if="documentsForm.progress" :value="documentsForm.progress.percentage" max="100">
         {{ documentsForm.progress.percentage }}%
         </progress>
         </div>
         </div>   
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" :disabled="documentsForm.processing || file_has_apostrophe">
         <span v-if="documentsForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>


</div>
</div>




</Layout>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<style>
  #active-checkbox{
    margin-top: 3px;
    margin-left: 3px;
  }
  .columns{
      margin-bottom: 1rem;
    }



</style>

<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Banner from '../components/Banner.vue';
import { ref } from 'vue'

export default {
 props: {
    tasks: Object,
    sars_cert: Object,
    errors: Object,
    company: Object,
    people: Array,
    company_doc: Object,
    contrib_cert: Object,
    bee_cert: Object,
    cipc_cert: Object,
    lta_cert: Object,
    success: String,
    error: String,
    message: String
 },  
  
  setup (props) {
    let showMenu = false;
    let body_max = 100;
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
            active: props.company.active,
            province: props.company.business_province 
    })

      const createTask = useForm({
          body: '',
          taskDate: null,
          model_type: 'Company',
          model_id: props.company.id,
      })

      const documentsForm = useForm({
            document: null,
            expiry_date: null,
            doc_type: null,
            company_id: props.company.id,
            company_name: props.company.name,
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
            let dismiss =  document.querySelector('.modal-backdrop') 
            dismiss.remove();
            addCompanyUserForm.reset();
           },
          }) 
      } 

      const editPerson = useForm({position: null,})
        

        function getPositionValue(position){//Get position value on edit 
          editPerson.position = position
        }

        function updatePerson(pivot_id){//Update when you adit position 
           editPerson.patch(`/update-position/${pivot_id}`, {
           preserveScroll: true,
           onSuccess: () => {
            //         
           },
          })  
        }

        function submitPeople(){
          addPeopleForm.post(`/add-people-to-company/${props.company.id}`, {
           preserveScroll: true,
           onSuccess: (page) => { 
            this.show_modal = false;
          document.querySelector('.modal-backdrop').remove();
            addPeopleForm.reset();
           },
          })  
        }

      function submitDocuments(){
          documentsForm.post(`/company/submit-company-documents`, {
          preserveScroll: true,
          onSuccess: () => {
            documentsForm.reset();
           this.show_modal = false;
           this.show_file_name = false;
           document.querySelector('.modal-backdrop').remove();
          },
        })    
        }

      function getDocType(doc_type){
        this.documentsForm.doc_type = doc_type;
        this.show_modal = true
      }

      function deleteDocument(id){
          if(confirm('Document will be deleted permanently!! Continue??')){
            Inertia.delete(`/delete-company-document/${id}`)
          }
        }

    function submit() {//Update company details
      form.post('/update-company', {
        preserveScroll: true,
      })
      
    }
            // store task
      function submitTask() {
        createTask.post('/submit-task',{
          ///do something
        })
        createTask.reset()
      }

      function deleteTask(task_id){
        if(confirm('Are you sure??')){
          createTask.delete(`/delete-task/${task_id}`,{
          ///do something
        })
        }
      }

      function checkBodyLength(){//Monitor task body length..
          if(this.createTask.body.length > this.body_max){
              this.createTask.body = this.createTask.body.substring(0,this.body_max)
          }
      }

      function unlinkPerson(full_name,id){
        if(confirm(full_name + ' will be removed from this company...Continue..??')){
          createTask.delete(`/unlink-person/${id}`,{
          ///do something
        })
        }
      }

      

      function assignActiveValue(event){
        this.form.active = event
      }

      function redirectToWebsite(url){
        if(url.includes('https://') || url.includes('http://')){
          window.open(url, "_blank");
        }else{
          window.open('https://' + url, "_blank");
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


        let file_has_apostrophe = ref();
      function getFileName(e){
        this.show_file_name = true;
        this.documentsForm.document = e.target.files[0];
        this.file_name = e.target.files[0].name;
        this.file_has_apostrophe = this.file_name.includes("'");
      }

    return {
      limit,
      file_has_apostrophe,
      submit,getFileName,
      submitTask,
      deleteTask,
      checkBodyLength,
      addCompanyUser,
      addCompanyUserForm,
      unlinkPerson,
      assignActiveValue,
      redirectToWebsite,
      body_max,
      createTask,
      people_options,
      form,
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
      show_file_name,
      file_name, 
    }
  },

   components: {
    Layout,
    Link,
    Head,
    Banner
  },
  
};
</script>

