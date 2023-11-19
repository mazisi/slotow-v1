<script>
import { Link, Head } from "@inertiajs/inertia-vue3";
import Layout from "../../Shared/Layout.vue";
import Banner from '../components/Banner.vue';
import Paginate from '../../Shared/Paginate.vue';

export default {
  name: "email-comms-transfers",
  props: {
    transfers: Object,
    success: String,
    error: String
  },
  data() {
    return {
      month: '',
      province: '',
      stage: '',
    }
  },
  components: {
    Layout,
    Link,
    Banner,
    Head,
    Paginate
},
methods: {
  
  limit(string='', limit = 25) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        },

     filter(){
        this.$inertia.replace(route('get_licence_transfers',{
              month: this.month,
             province: this.province,
             stage: this.stage,
          }))
        },

    getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    },

    //On navigation click get renewal data
    getLicenceRenewals(){
      this.$inertia.get('/email-comms');
    },

    //On navigation click get transfer data
    getLicenceTransfers(){
      this.$inertia.get('/email-comms/transfers');
    },
    getNominations(){
      this.$inertia.get('/email-comms/nominations');
    },
     
    getTemporaryLicences(){
      this.$inertia.get('/email-comms/temp-licences');
    },

    getAlterations(){
      this.$inertia.get('/email-comms/alterations');
    },

     getEmmails(){
      this.$inertia.get('/emails-report');
    },

    getNewApps(){
      this.$inertia.get('/email-comms/new-apps');
    },
    
    notify(message){
          if(this.success){
            toast.success(message, {
            autoClose: 2000,
          });
          
          }else if(this.error){
            toast.error(message, {
            autoClose: 2000,
          });
          }
        }
    },

    

    mounted(){ 
          if(this.success){
            notify(this.success)
          }else if(this.error){
            notify(this.error)
          }
        }
};
</script>
<style>
#with-thrashed{
  margin-top: 3px;
  margin-left: 3px;
}</style>
<template>
<Layout>
  <Head title="EmailComms - Transfers" />
  <div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
  <ul class="nav mb-3 pt-3" id="pills-tab" role="tablist">

  <li class="nav-item" role="presentation">
    <button @click="getLicenceRenewals" class="nav-link btn btn-secondary text-white " id="Renewals" data-bs-toggle="pill" data-bs-target="#renewals" 
    type="button" role="tab" aria-controls="renewals" aria-selected="true">Renewals</button>
  </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  
  <li class="nav-item" role="presentation">
    <button @click="getLicenceTransfers" class="nav-link btn btn-success text-white ml-4 active" id="Transfers" data-bs-toggle="pill" data-bs-target="#transfers" 
    type="button" role="tab" aria-controls="transfers" aria-selected="false">Transfers</button>
  </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <li class="nav-item" role="presentation">
    <button @click="getNominations" class="nav-link btn btn-secondary text-white" id="Nominations" data-bs-toggle="pill" data-bs-target="#nominations" 
    type="button" role="tab" aria-controls="nominations" aria-selected="false">Nominations</button>
  </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

  <li class="nav-item" role="presentation">
    <button @click="getAlterations" class="nav-link btn btn-secondary text-white" id="Alterations" data-bs-toggle="pill" data-bs-target="#alterations" 
    type="button" role="tab" aria-controls="alterations" aria-selected="false">Alterations</button>
  </li>

  <li class="nav-item" role="presentation">
    <button @click="getNewApps" class="nav-link btn btn-secondary text-white mx-4" id="New Applications" data-bs-toggle="pill" data-bs-target="#new-apps" 
    type="button" role="tab" aria-controls="new-apps" aria-selected="false">New Applications</button>
  </li>

  <li class="nav-item" role="presentation">
    <button @click="getTemporaryLicences" class="nav-link btn btn-secondary text-white mx-4" id="Alterations" data-bs-toggle="pill" data-bs-target="#alterations" 
    type="button" role="tab" aria-controls="alterations" aria-selected="false">Temporal Licences</button>
  </li>

  <!-- <li class="nav-item" role="presentation">
    <button @click="getEmmails" class="nav-link btn btn-secondary text-white mx-4" id="emails" data-bs-toggle="pill" data-bs-target="#emails" 
    type="button" role="tab" aria-controls="emails" aria-selected="false">Emails</button>
  </li> -->
</ul>
<div class="tab-content" id="pills-tabContent">


  <div class="tab-pane fade show active" id="renewals" role="tabpanel" aria-labelledby="Renewals">
<div class="row">
<div class="col-4 columns">                  
<div class="input-group input-group-outline null is-filled">
<select v-model="stage" @change="filter" class="form-control form-control-default">
<option :value="''" disabled selected>Filter By Stage</option>
<option value="1">Client Quoted With Requirements </option>
<option value="2">Client Invoiced </option>
<option value="5">Payment To The Liquor Board</option>
<option value="6">Transfer Lodged </option>
<option value="7">Activation Fee Paid </option>
<option value="8">Transfer Issued </option>
</select>
</div>

</div>
  <div class="col-4 columns">                  
<div class="input-group input-group-outline null is-filled">
<select v-model="month" @change="filter" class="form-control form-control-default" >
<option :value="''" disabled selected>Filter By Month</option>
<option value="1">January</option>
<option value="2">February</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
</div>

</div>

<div class="col-4 columns">                  
<div class="input-group input-group-outline null is-filled">
<select @change="filter" class="form-control form-control-default" v-model="province">
<option :value="''" disabled selected>Filter By Province</option>
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
</div>
</div>
  <h5 class="text-center">Transfers</h5>
  <div class="table-responsive p-0">
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Current Trading Name </th>
        <th class="px-0 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Licence Number</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody>
    <!-- with_invoiced_status -->
      <tr v-for="transfer in transfers.data" :key="transfer.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 data-bs-placement="top" :title="transfer.licence.trading_name" class="mb-0 text-sm">{{ limit(transfer.licence.trading_name) }}</h6>
             </div>
          </div>
        </td>
        

        <td class="text-sm">
          <Link :href="`/email-comms/get-mail-template/${transfer.slug}/transfers`">
           <h6 class="mb-0 text-sm">{{ transfer.licence.licence_number ? transfer.licence.licence_number : '' }} </h6>
          </Link>
        </td>

        <td class="align-middle text-center">
        <Link :href="`/email-comms/get-mail-template/${transfer.slug}/transfers`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <Link :href="`/view-transfered-licence/${transfer.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
      
   
    </tbody>
  </table>
</div>


  </div>
  <Paginate
  :modelName="transfers"
  :modelType="Transfers"
  />

</div>


</div>


</div>

  </Layout>
</template>
