<script>
import Layout from "../../../Shared/Layout.vue";
import { Link } from '@inertiajs/inertia-vue3';
import Banner from '../../components/Banner.vue'

export default {
    props: {
      licences: Object,
      success: String,
      error: String,
      errors: Object,
      all_licence_types: Object
    },

    components: {
        Layout,
        Link,
        Banner
    },

    data() {
        return {
          term: '',
          all: '', 
          active_status: '',
          licence_type: '',
          licence_date: '',
          province: ''
        }
    },

    methods: {
      search(){
         this.$inertia.replace(route('company_admin_licences',{
          term: this.term,
          active_status: this.active_status,
          licence_type: this.licence_type,
          licence_date: this.licence_date,
          province: this.province
          }))
        },

      limit(string='', limit = 25) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        }
    },
}
</script>

<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="col-12">
<form>
  <div class="row">
<div  class="col-md-12 col-xl-12 col-lg-12">
<div class="input-group input-group-outline null is-filled">
<i class="fa fa-search h4"></i>&nbsp;&nbsp;&nbsp;
<input v-model="term" @keyup="search" type="text" class="form-control form-control-default">
</div>
</div>

<div class="col-md-3 col-xl-3 col-lg-3 filters">
<div class="input-group input-group-outline null is-filled">
<select @change="search" v-model="active_status" class="form-control form-control-default">
<option :value="''" disabled selected>Active/Inactive Status</option>
<option value="All">All</option>
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>

</div>
</div>

<div class="col-md-3 col-xl-3 col-lg-3 filters">
<div class="input-group input-group-outline null is-filled">
<select @change="search" v-model="licence_type" class="form-control form-control-default">
<option :value="''" disabled selected>Licence Type</option>
<option v-for='licence_type in all_licence_types' :value=licence_type.id> {{ licence_type.licence_type }}</option>
</select>
</div>
</div>

<div class="col-md-3 col-xl-3 col-lg-3 filters">
<div class="input-group input-group-outline null is-filled">
<select @change="search" v-model="licence_date" class="form-control form-control-default">
<option :value="''" disabled selected>Licence Date</option>
<option value="01">January</option>
<option value="02">February</option>
<option value="03">March</option>
<option value="04">April</option>
<option value="05">May</option>
<option value="06">June</option>
<option value="07">July</option>
<option value="08">August</option>
<option value="09">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
</div>
</div>

<div class="col-md-3 col-xl-3 col-lg-3 filters">
<div class="input-group input-group-outline null is-filled">
<select @change="search" v-model="province" class="form-control form-control-default">
<option :value="''" disabled selected>Province</option>
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
</form>
<div class="my-4">

<div class="px-0 pb-2">
<div class="table-responsive p-0">
  <table class="table table-striped table-hover">
<thead>
<tr>
<th>Active</th>
<th>Trading Name</th>
<th>Licence Number</th>
<th>Licence Date</th>
<th class="text-center">Licence Type</th>
<th>Company</th>
<th>View</th>
</tr>
</thead>
<tbody>

<tr v-for="licence in licences" :key="licence.id">
<td v-if="licence.is_licence_active == '1'"><i class="fa fa-check text-success" aria-hidden="true"></i></td>
<td v-else><i class="fa fa-times text-danger" aria-hidden="true"></i></td>
<td><Link :href="`/company/view-my-licences/${licence.slug}`">{{ limit(licence.trading_name) }}</Link></td>
<td><Link :href="`/company/view-my-licences/${licence.slug}`">{{ licence.licence_number ? licence.licence_number : '' }}</Link></td>
<td><Link :href="`/company/view-my-licences/${licence.slug}`">{{ licence.licence_date }}</Link></td>
<td class="text-center"><Link :href="`/company/view-my-licences/${licence.slug}`">{{ limit(licence.licence_type.licence_type) }}</Link></td>
<td><Link :href="`/company/view-my-licences/${licence.slug}`">{{ limit(licence.company.name) }}</Link></td>
<td class="text-center">
<Link :href="`/company/view-my-licences/${licence.slug}`"><i class="fa fa-eye  " aria-hidden="true"></i></Link>

</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</Layout>
</template>

<style scoped>
 .filters{
 margin-top: 10px;
}
  .table thead th {
    padding: 0;
    }
 
    #with-thrashed{
      margin-top: 3px;
      margin-left: 3px;
    }
    
</style>

