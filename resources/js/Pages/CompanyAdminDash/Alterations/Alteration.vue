<script>
import Layout from "../../../Shared/Layout.vue";
import { Head,Link } from '@inertiajs/inertia-vue3';
import Banner from '../../components/Banner.vue'

export default {
name: "company-alterations",
  props: {
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String,
  },

  data() {
    return {
        form: {
         alter_date: null,
         alter_status: null
      },
      showMenu: false,
    };
  },
  components: {
    Layout,
    Link,
    Head,
    Banner
  },

  methods: {
    submit() {
      this.$inertia.post(`/submit-altered-licence/${this.licence.id}`, this.form)
    },

    
   limit(string='', limit = 35) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        }

  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
</script>
<style>
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
}
.columns{
  margin-bottom: 1rem;
}</style>

<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row gx-4">

<div class="row">
  <div class="col-lg-6 col-7">
   <h6 class="mb-1">Alterations for:  
    <Link :href="`/company/view-my-licences/${licence.slug}`">
      <span class="text-success">{{ licence.trading_name ? licence.trading_name: '' }}</span></Link></h6>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end">
  </div>
</div>

</div>
<div class="row">

<div class="mt-3 row">
<div class="col-12 col-md-6 col-xl-12 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">

<div class="table-responsive">
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alteration Date</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Complete</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody v-if="$props. licence.alterations.length > 0">
      <tr v-for="alter in licence.alterations" :key="alter.id">
       
        <td class="text-center text-sm">
          <Link :href="`/company/view-company-alteration/${alter.slug}`">
          {{ alter.created_at }}
          </Link>
        </td>
        
        <td class="text-sm text-center">
          <Link :href="`/company/view-company-alteration/${alter.slug}`">
          <i v-if="alter.status == '4'" class="fa fa-check text-info" aria-hidden="true"></i>
           <i v-else class="fa fa-times text-danger" aria-hidden="true"></i>
      </Link>
        </td>

        <td class="text-sm text-center">
        <Link :href="`/company/view-company-alteration/${alter.slug}`"><i class="fa fa-eye px-1 text-secondary" aria-hidden="true"></i></Link>
        
        </td>
      </tr>
    </tbody>

     <tbody v-else>
        <td></td>
        <td class="text-danger">No alterations found.</td>
        </tbody>
  </table>
</div>

</div>
</div>
</div>

</div>

</div>
</div>
</div>


</Layout>
</template>