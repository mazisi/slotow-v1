<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link,useForm, Head } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';


export default {
 props: {
   errors: Object,
    licence: Object,
    success: String,
    error: String,

  },
  setup(props) {
   let showMenu = false;
    
     let options = props.nominees;
     const form = useForm({
         licence_id: props.licence.id,
         status: [],
         alteration_date: ''
        })

    function submit() {
          form.post(`/submit-altered-licence/${props.licence.id}`, props.form)
     }

     
    return {
      submit,
      options,
      form,showMenu
    };
  },

  components: {
    Layout,
    Head,
    Multiselect,
    Link,
    Banner
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
  <Head title="Create Alteration" />
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row">
  <div class="col-lg-12 col-12">
   <h6>New Alteration for: <Link :href="`/view-licence?slug=${licence.slug}`"><span class="text-success">{{ licence.trading_name }}</span></Link></h6>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end"></div>
</div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="submit">
<div class="row">
<input type="hidden" v-model="form.slug">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-Quoted" class="active-checkbox" v-model="form.status" type="checkbox" value="1">
<label for="client-Quoted" class="form-check-label text-body text-truncate status-heading">Client Quoted</label>
</div>
</div>  
<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-Invoiced" class="active-checkbox" v-model="form.status" type="checkbox" value="2">
<label for="client-Invoiced" class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div>
<hr/>


<div class="col-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="alteration-Paid" class="active-checkbox" v-model="form.status" type="checkbox" value="3">
<label for="alteration-Paid" class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div>


<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="alteration-Application" class="active-checkbox" v-model="form.status" type="checkbox" value="4">
<label for="alteration-Application" class="form-check-label text-body text-truncate status-heading">Prepare Alterations Application</label>
</div>
</div> 

<hr/>
<div class="col-md-12 columns">
  <div class=" form-switch d-flex ps-0 ms-0  is-filled">
  <input id="alteration-Liquor" class="active-checkbox" v-model="form.status" type="checkbox" value="5">
  <label for="alteration-Liquor" class="form-check-label text-body text-truncate status-heading">Payment to the Liquor Board</label>
  </div>
  </div> 

  <hr/>
  <div class="col-md-8 columns">
    <div class=" form-switch d-flex ps-0 ms-0  is-filled">
    <input id="alteration-Lodged" class="active-checkbox" v-model="form.status" type="checkbox" value="6">
    <label for="alteration-Lodged" class="form-check-label text-body text-truncate status-heading">Alterations Lodged</label>
    </div>
 </div> 

 <div class="col-4 columns">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Date Of Application</label>
  <input type="date" class="form-control form-control-default" v-model="form.alteration_date">
  </div>
   <div v-if="errors.alteration_date" class="text-danger">{{ errors.alteration_date }}</div>
</div>

 <hr/>
    <div class="col-md-12 columns">
      <div class=" form-switch d-flex ps-0 ms-0  is-filled">
      <input id="alteration-Certificate" class="active-checkbox" v-model="form.status" type="checkbox" value="7">
      <label for="alteration-Certificate" class="form-check-label text-body text-truncate status-heading">Alterations Certificate Issued</label>
      </div>
    </div> 

<hr/>
  <div class="col-md-12 columns">
    <div class=" form-switch d-flex ps-0 ms-0  is-filled">
    <input id="alteration-Delivered" class="active-checkbox" v-model="form.status" type="checkbox" value="8">
    <label for="alteration-Delivered" class="form-check-label text-body text-truncate status-heading">Alterations Delivered</label>
    </div>
  </div> 
  <hr/>
<div>
  <button :disabled="form.processing" type="submit" class="btn btn-sm btn-secondary ms-2" :style="{float: 'right'}">
    <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    Create</button>
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
  </Layout>
</template>
