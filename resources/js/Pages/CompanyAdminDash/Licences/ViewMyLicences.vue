<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-9 col-9">
<h6 class="mb-1">Licence Info: {{ licence.trading_name ? licence.trading_name : '' }}</h6>
</div>


<div class="col-lg-3 col-3 my-auto text-end d-none">
<div class="dropdown float-lg-end pe-4 d-none">
<a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
<i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
</a>
<ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
<li><Link :href="`/renew-licence?slug=${licence.slug }`" class="dropdown-item border-radius-md">Renewals</Link></li>

<li><Link :href="`/transfer-history?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Transfers</Link></li>

<li><Link :href="`/nominations?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Nominations</Link></li>

<li><Link :href="`/alterations?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Alterations</Link></li>

<li><hr class="text-danger"></li>
<li><button @click="deleteLicence" class="dropdown-item border-radius-md text-danger" >
<i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</button></li>
</ul>
</div>
</div>
</div>
<div class="row">
<div class="mt-3 ">
<form class="row" @submit.prevent="updateLicence">
<div class="col-6 col-md-6 col-xl-6 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">

<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Active</label>
<input id="active-checkbox" disabled type="checkbox" value="1"
@input="assignActiveValue($event.target.value)" :checked="licence.is_licence_active == '1'">
</div>
</div>

<div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Trading Name *</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.trading_name" >
</div>
<div v-if="errors.trading_name" class="text-danger">{{ errors.trading_name }}</div>
</div>

<div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label mb-4">Current Company</label>
<input type="text" disabled @focus="changeCompany" class="form-control form-control-default" v-model="form.company" >
</div>
<div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
</div>


<div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Licence Type *</label>
<select disabled v-model="form.licence_type" class="form-control form-control-default">
  <option :value="''" disabled>Select Type</option>
<option v-for='licence_dropdown in licence_dropdowns' :value=licence_dropdown.id> {{ licence_dropdown.licence_type }}</option>
</select>
</div>
<div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
</div>

<div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Date of Original Issue</label>
<input type="date" disabled class="form-control form-control-default" v-model="form.licence_date">
</div>
<div v-if="errors.licence_date" class="text-danger">{{ errors.licence_date }}</div>
</div>

<div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Licence Number</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.licence_number" >
</div>
<div v-if="errors.licence_number" class="text-danger">{{ errors.licence_number }}</div>
</div>

<div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Old Licence Number</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.old_licence_number" >
</div>
<div v-if="errors.old_licence_number" class="text-danger">{{ errors.old_licence_number }}</div>
</div>  


</div>




</div>
</div>
<!-- <hr class="vertical dark" /> -->
</div>
<div class="col-6 col-md-6 col-xl-6 position-relative">
<div class="col-12 columns invisible">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Address Line 1</label>
<input type="text" disabled class="form-control form-control-default">
</div>
</div>  



<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Address Line 1</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.address">
</div>
<div v-if="errors.address" class="text-danger">{{ errors.address }}</div>
</div>  
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Address Line 2</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.address2">
</div>
<div v-if="errors.address2" class="text-danger">{{ errors.address2 }}</div>
</div> 
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Address Line 3</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.address3">
</div>
<div v-if="errors.address3" class="text-danger">{{ errors.address3 }}</div>
</div>         
<div class="col-12 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<select disabled class="form-control form-control-default" v-model="form.province" >
<option :value="''" disabled>Select Province</option>
<option value="Eastern Cape">Eastern Cape</option>
<option value="Free State">Free State</option>
<option value="Gauteng">Gauteng</option>
<option value="KwaZulu-Natal">KwaZulu-Natal</option>
<option value="Limpopo">Limpopo</option>
<option value="Mpumalanga">Mpumalanga</option>
<option value="Northern Cape">Northern Cape</option>
<option value="North West">North West</option>
<option value="Western Cape">Western Cape</option>
</select>
</div>
<div v-if="errors.province" class="text-danger">{{ errors.province }}</div>
</div>

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Code</label>
<input  type="text" disabled class="form-control form-control-default" v-model="form.postal_code">
</div>
</div>
</div>

<h6 class="text-center">Documents</h6>
<div class="row">
<div class="col-md-6 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="original_lic !== ''">
    <a v-for="doc in original_lic" :key="doc.id" :href="`${$page.props.blob_file_path}${doc.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Original Licence</h6>
      <p v-if="original_lic.length > 0" class="mb-0 text-xs">{{ original_lic[0].document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>



  </li>


  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="duplicate_original_lic !== ''">
    <a v-for="doc in duplicate_original_lic" :key="doc.id" :href="`${$page.props.blob_file_path}${doc.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Duplicate Original</h6>
      <p v-if="duplicate_original_lic.length > 0" class="mb-0 text-xs">{{ duplicate_original_lic[0].document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
  </li>
</ul>
<hr class="vertical dark" />
</div>

<div class="col-md-6 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="original_lic_delivered !== ''">
    <a v-for="doc in original_lic_delivered" :key="doc.id" :href="`${$page.props.blob_file_path}${doc.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Original Licence Delivered</h6>
      <p v-if="original_lic_delivered.length > 0" class="mb-0 text-xs">{{ original_lic_delivered[0].document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>

    
  </li>


  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="duplicate_original_lic_delivered !== ''">
    <a v-for="doc in duplicate_original_lic_delivered" :key="doc.id" :href="`${$page.props.blob_file_path}${doc.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Duplicate Original Delivered</h6>
       <p v-if="duplicate_original_lic_delivered.length > 0" class="mb-0 text-xs">{{ duplicate_original_lic_delivered[0].document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    
  </li>
</ul>
</div>
</div>
<div>
<button type="submit" class="btn btn-secondary ms-2 d-none" :style="{float: 'right'}">Save</button>
</div>
</form>
<hr>

<!-- <div class="row">
<h6 class="text-center mb-2 ">Companies Linked To : {{ licence.trading_name }}</h6>
<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
<tr>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
Active
</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Company Name
</th>

<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Action
</th>

</tr>
</thead>
<tbody>
<tr>
<td v-if="licence.company.active !== null" class="text-sm"><i class="fa fa-check text-info" aria-hidden="true"></i></td>
<td v-else class=" text-sm text-danger"><i class="fa fa-times"></i></td>
<td class="align-middle text-sm">
<Link class="text-sm text-center align-middle" :href="`/view-company/${licence.company.slug}`"><h6 class="mb-0 ">{{ licence.company.name }}</h6></Link>
</td>
<td class="text-center">
<Link :href="`/view-company/${licence.company.slug}`"><i class="fa fa-eye" aria-hidden="true"></i></Link>
</td>
</tr>
</tbody>
</table>
</div>
<hr>

</div> -->
<!-- Notes were here -->

</div>

</div>
</div>
</div>


</Layout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
    .columns{
      margin-bottom: 1rem;
    }
    #active-checkbox{
      margin-left: 3px;
    }

</style>

<style src="@vueform/multiselect/themes/default.css"></style>

<script>
import Layout from "../../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import { ref } from 'vue';
import Banner from '../../components/Banner.vue'

export default {
 props: {
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String,
    tasks: Object,
    companies: Object,
    original_lic: Object,
    duplicate_original_lic: Object,
    original_lic_delivered: Object,
    duplicate_original_lic_delivered: Object
  },
  
  
  setup (props) {
        let showMenu = false;
        let body_max = 100;
        let options = props.companies;
        let show_current_company = ref(true);
        let change_company = ref(false);
        let show_modal = ref(true); 

    const form = useForm({
         trading_name: props.licence.trading_name,
         licence_type: props.licence.licence_type_id,
         is_licence_active: props.licence.is_licence_active,
         licence_number: props.licence.licence_number,
         old_licence_number: props.licence.old_licence_number,
         licence_date: props.licence.licence_date,
         postal_code: props.licence.postal_code,
         address: props.licence.address,
         address2: props.licence.address2,
         address3: props.licence.address3,
         province: props.licence.province,
         company: props.licence.company.name,
         company_id: props.licence.company.id,
         change_company: '',  
    })

//Now handle task creation..
       const createTask = useForm({
          body: '',
          model_type: 'Licence',
          model_id: props.licence.id,
          taskDate: new Date().getDate()+'/'+(new Date().getMonth()+1)+'/'+new Date().getFullYear()
       })
//Insert original licence 
       const originalLicenceForm = useForm({
          original_licence_doc: null,
          licence_id: props.licence.id,
          doc_type: null,
       })
 
      function getDocType(doc_type){
        this.originalLicenceForm.doc_type = doc_type;
        this.show_modal = true
      }

     function changeCompany(){
          this.show_current_company=false;
          this.change_company=true;
      }

      function updateLicence() {
         form.patch(`/update-licence/${props.licence.slug}`, {
          preserveScroll: true,
        })    
        }

        function uploadOriginalLicenceDoc(){
          originalLicenceForm.post(`/upload-licence-document`, {
          preserveScroll: true,
          onSuccess: () => { 
          this.show_modal = false;
          let dismiss =  document.querySelector('.modal-backdrop')    
          dismiss.remove();
          originalLicenceForm.reset();
         },
        })    
        }

        function deleteDocument(id){
          if(confirm('Document will be deleted permanently!! Continue??')){
            Inertia.delete(`/delete-licence-document/${id}`)
          }
        }

       function deleteLicence(){
          if(confirm('Are you sure you want to delete this licence??')){
            Inertia.delete(`/delete-licence/${props.licence.slug}`)
          }      
        }
        // store task
        function submitTask() {
          createTask.post('/submit-task', {
            onSuccess: () => form.reset('body'),
           })
        }

        function deleteTask(task_id){//delete task
          if(confirm('Are you sure??')){
            Inertia.delete(`/delete-task/${task_id}`)
          }
        }
          function checkBodyLength(){//Monitor task body length..
            if(this.createTask.body.length > this.body_max){
                this.createTask.body = this.createTask.body.substring(0,this.body_max)
            }
        }
        function assignActiveValue(checkbox_value){
          this.form.is_licence_active = checkbox_value
        }

    return {
      showMenu,
      createTask,
      body_max,
      show_modal,
      show_current_company,
      change_company,
      options,
      form,
      changeCompany,
      updateLicence,
      deleteLicence,
      submitTask,
      deleteTask,
      checkBodyLength,
      assignActiveValue,
      originalLicenceForm,
      uploadOriginalLicenceDoc,
      getDocType,
      deleteDocument
    }
  },
   components: {
    Layout,
    Link,
    Head,
    Multiselect,
    Banner
  },
  
};
</script>

