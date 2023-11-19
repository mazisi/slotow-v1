<script>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import Layout from "../../Shared/Layout.vue";
import Banner from '../components/Banner.vue'


export default {
  props: {
    emails: Object
  },

  setup(props) {
   const filterForm = useForm({
    type: '',
    status: ''
   })

   function dispatchFilter(){
    filterForm.get('/emails-report', {
        replace: true,
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => { 
          //
         },
      })
   }

   function ucFirst(model_type) {
      return model_type.charAt(0).toUpperCase() + model_type.slice(1);
    }

    function limit(string='', limit = 25) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        }
    return { 
      filterForm,
      limit,
      dispatchFilter,
      ucFirst
    }
  },
  components: {
    Layout,
    Link,
    Banner
  }
}
</script>
<style>
#with-thrashed{
  margin-top: 3px;
  margin-left: 3px;
}
.active{
    color: #000;
    background-color: #fff;
    border-color: #fff;
}
</style>
<template>
<Layout>
  <div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
        <h6 class="text-capitalize ps-3">Emails Not Sent</h6>
        <div class="card-body px-0 pb-2">
          <div class="row">
         <div class="col-6 columns">                  
         <div class="input-group input-group-outline null is-filled">
         <select v-model="filterForm.type" @change="dispatchFilter" class="form-control form-control-default">
         <option :value="''" disabled selected>All</option>
         <option value="renewals">Renewals</option>
         <option value="transfers">Transfers</option>
         <option value="nominations">Nominations</option>
         <!-- <option value="5">Temporary Licences</option> -->
         
         
         </select>
         </div>         
         </div>
        <div v-if="filterForm.type === 'renewals'" class="col-6 columns">                  
         <div class="input-group input-group-outline null is-filled">
         <select @change="dispatchFilter" class="form-control form-control-default" v-model="filterForm.status">
         <option :value="''" disabled selected>Filter By Stage</option>
          <option value="Client Quoted">Client Quoted</option>
          <option value="Client Invoiced">Client Invoiced </option>
          <option value="Payment to the Liquor Board">Payment to the Liquor Board</option>
          <option value="Renewal Issued">Renewal Issued</option>
         </select>
         </div>         
         </div>

         <div v-if="filterForm.type === 'transfers'" class="col-6 columns">                  
          <div class="input-group input-group-outline null is-filled">
          <select @change="dispatchFilter" class="form-control form-control-default" v-model="filterForm.status">
          <option :value="''" disabled selected>Filter By Stage</option>
          <option value="Client Quoted">Client Quoted With Requirements </option>
          <option value="Client Invoiced">Client Invoiced </option>
          <option value="Payment To The Liquor Board">Payment To The Liquor Board</option>
          <option value="Transfer Lodged">Transfer Lodged </option>
          <option value="Activation Fee Paid">Activation Fee Paid </option>
          <option value="Transfer Issued">Transfer Issued </option>
          </select>
          </div>         
          </div>

          <div v-if="filterForm.type === 'nominations'" class="col-6 columns">                  
          <div class="input-group input-group-outline null is-filled">
          <select @change="dispatchFilter" class="form-control form-control-default" v-model="filterForm.status">
          <option :value="''" disabled selected>Filter By Stage</option>
          <option value="Client Quoted">Client Quoted</option>
          <option value="Client Invoiced">Client Invoiced </option>
          <option value="Client Paid">Client Paid </option>
          <option value="Payment To The Liquor Board">Payment To The Liquor Board</option>
          </select>
          </div>         
          </div>
         
         </div>
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Current Trading Name </th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Stage </th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Status </th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Feedback </th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Action </th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="email in emails" :key="email.id">
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div class="d-flex flex-column justify-content-center">
                        <Link :href="`/view-licence?slug=${email.parent_licence_slug}`"><h6 class="mb-0 text-sm">{{ limit(email.trading_name) }}</h6></Link>
                      </div>
                    </div>
                  </td>
                  <td>
                    <Link :href="`/view-licence?slug=${email.parent_licence_slug}`">
                      <p class="text-xs font-weight-bold mb-0">{{ ucFirst(email.model_type) }}</p></Link>
                  </td>
                  <td>
                    <Link :href="`/view-licence?slug=${email.parent_licence_slug}`"><p class="text-xs font-weight-bold mb-0">{{ email.stage }}</p></Link>
                  </td>
                  <td class="align-middle text-center text-danger text-sm"><Link :href="`/view-licence?slug=${email.parent_licence_slug}`">{{ email.feedback }}</Link></td>
                  <td class="align-middle text-center">
                    <Link :href="`/email-comms/get-mail-template/${email.model_slug}/${email.model_type}`" class="text-secondary text-xs font-weight-bold">
                    <i class="fa fa-repeat" aria-hidden="true"></i>
                    Resend</Link></td>
                </tr>
                
              </tbody>
            </table>
          </div>
        </div>
      </div>


</div>

  </Layout>
</template>
