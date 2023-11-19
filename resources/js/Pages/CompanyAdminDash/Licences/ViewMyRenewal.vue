<script>
import Layout from "../../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import '@vuepic/vue-datepicker/dist/main.css';
import Banner from '../../components/Banner.vue';
import { ref } from 'vue';

export default {
  props: {
    licence: Object,
    success: String,
    errors: String,
    renewal: Object,
    client_invoiced: Object,//doc
    renewal_issued: Object,//doc
    client_quoted: Object,//doc
    renewal_doc: Object,
    liqour_board: Object
  },

  setup (props) {
    const year = ref(new Date().getFullYear());

    const form = useForm({
      year: props.renewal.date,
      licence_id: props.renewal.id,
      status: [],
      client_paid_at: props.renewal.client_paid_at,
      payment_to_liquor_board_at: props.renewal.payment_to_liquor_board_at,
      renewal_issued_at: props.renewal.renewal_issued_at,
      renewal_delivered_at: props.renewal.renewal_delivered_at,
      renewal_id: props.renewal.id,
     })

  

   
    function getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    }


    function computeDocumentDate(date_param){
        return new Date(date_param).toLocaleString().split(',')[0]
    };

    return { year,form,
     getRenewalYear,
     computeDocumentDate,
     }
  },
   components: {
    Layout,
    Link,
    Head,
    Banner
  },
  
};
//The following are status keys
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Payment To Liquor Board
// 5 => Renewal Issued
// 6 => Renewal Delivered

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
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
<Layout>
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row">
  <div class="col-lg-9 col-9">
   <h6>Renewal for: {{ renewal.date  }}/{{ getRenewalYear(renewal.date)  }}
    <Link :href="`/company/view-my-licences/${renewal.licence.slug}`" class="text-success">: {{ renewal.licence.trading_name ? renewal.licence.trading_name : '' }}</Link></h6>
    <p class="text-sm mb-0">Current Stage: 
       <span class="font-weight-bold ms-1" v-if="renewal.status == '1'">Client Quoted</span>
      <span v-if="renewal.status == '2'" class="font-weight-bold ms-1">Client Invoiced</span>
      <span v-if="renewal.status == '3'" class="font-weight-bold ms-1">Client Paid</span>
      <span v-if="renewal.status == '4'" class="font-weight-bold ms-1">Payment To The Liquor Board</span>
      <span v-if="renewal.status == '5'" class="font-weight-bold ms-1">Renewal Issued</span>
      <span v-if="renewal.status == '6'" class="font-weight-bold ms-1">Renewal Delivered</span>
    </p>
   
  </div>
  <div class="col-lg-3 col-3 my-auto text-end">
    <button v-if="$page.props.auth.has_slowtow_admin_role" @click="deleteRenewal" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>
  
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
<input id="client-quoted" class="active-checkbox" type="checkbox" 
:checked="renewal.status >= '1'" readonly value="1">
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
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="client_quoted !== null" class="mb-0 text-xs limit-file-name">{{ client_quoted.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

  </li>
</ul>    
<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-invoiced"  type="checkbox" value="2"
:checked="renewal.status >= '2'">
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
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="client_invoiced !== null" class="mb-0 text-xs limit-file-name">{{ client_invoiced.document_name }}</p>
      <p v-if="client_invoiced !== null" class="mb-0 text-xs text-dark">Date:{{ computeDocumentDate(client_invoiced.date) }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

  </li>
</ul>
<hr>

<div class="col-md-5 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-paid" type="checkbox" 
 value="3" :checked="renewal.status >= '3'">
<label for="client-paid" class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div> 
<div class="col-md-1 columns"></div>
 <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.client_paid_at">
     </div>
   <div v-if="errors.client_paid_at" class="text-danger">{{ errors.client_paid_at }}</div>
   </div> 

   <hr>

<div class="col-md-5 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="payment" type="checkbox" value="4"
:checked="renewal.status >= '4'">
<label for="payment" class="form-check-label text-body text-truncate status-heading">Payment To The Liquor Board</label>
</div>
</div> 

<div class="col-md-1 columns"></div>
 <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.payment_to_liquor_board_at">
     </div>
   <div v-if="errors.payment_to_liquor_board_at" class="text-danger">{{ errors.payment_to_liquor_board_at }}</div>
   </div> 
  




<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="liqour_board !== null">
    <a :href="`${$page.props.blob_file_path}${liqour_board.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="liqour_board !== null" class="mb-0 text-xs limit-file-name">{{ liqour_board.document_name }}</p>
      <p v-if="liqour_board !== null" class="mb-0 text-xs text-dark">Date:{{ computeDocumentDate(liqour_board.date) }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

  </li>
</ul>
<hr>

<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="issued" type="checkbox" 
 value="5" :checked="renewal.status >= '5'">
<label for="issued" class="form-check-label text-body text-truncate status-heading"> Renewal Issued</label>
</div>
</div> 

<div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.renewal_issued_at">
     </div>
   <div v-if="errors.renewal_issued_at" class="text-danger">{{ errors.renewal_issued_at }}</div>
   </div>

<div class="col-md-6" style="margin-top: -1rem;">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="renewal_issued !== null">
    <a :href="`${$page.props.blob_file_path}${renewal_issued.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="renewal_issued !== null" class="mb-0 text-xs limit-file-name">{{ renewal_issued.document_name }}</p>
      <p v-if="renewal_issued !== null" class="mb-0 text-xs text-dark">Date:{{ computeDocumentDate(renewal_issued.date) }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
  </li>
</ul>
</div>
<hr>




<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="delivered" type="checkbox" value="6"
 :checked="renewal.status == '6'">
<label for="delivered" class="form-check-label text-body text-truncate status-heading"> Renewal Delivered</label>
</div>
</div>

<div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.renewal_delivered_at">
     </div>
   <div v-if="errors.renewal_delivered_at" class="text-danger">{{ errors.renewal_delivered_at }}</div>
   </div>
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="renewal_doc !== null">
    <a :href="`${$page.props.blob_file_path}${renewal_doc.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="renewal_doc !== null" class="mb-0 text-xs limit-file-name">{{ renewal_doc.document_name }}</p>
      <p v-if="renewal_doc !== null" class="mb-0 text-xs text-dark">Date:{{ computeDocumentDate(renewal_doc.date) }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

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
<hr>



</div>
</div>


  </Layout>
</template>
