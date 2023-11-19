<script>
import Layout from "../../Shared/Layout.vue";
import { ref, watch,computed, onMounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link, useForm, Head } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';
import Paginate from "../../Shared/Paginate.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import  common from '../common-js/common.js';


export default {
  name: "companies",
  props: {
    companies: Object,
    success: String,
    errors: Object,
    error: String
  },

  setup(props) {
    const [search_query, status, company_type] = getUrlParam();

    const term = search_query ? search_query : ref('');
    const form = useForm({
          term: term,
          active_status: status ? status : '',
          company_type: company_type ? company_type : '',
        })

    function search(){
          form.get(`/companies`, {
            replace: true,
            preserveState: true,
            onSuccess: () => {},            
        })
    }
        function limit(string, limit=25){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }

     
       watch(term, _.debounce(function (value) {
          Inertia.get('/companies', { 
            term: value,
            active_status: form.active_status,
            company_type: form.company_type,
            
           }, { preserveState: true, replace: true });
        }, 1000));

        const notify = (message) => {
          if(props.success){
            toast.success(message, {
            autoClose: 2000,
          });
          
          }else if(props.error){
            toast.error(message, {
            autoClose: 2000,
          });
          }
        }

        function getUrlParam(){
          const urlParams = new URLSearchParams(window.location.search);
          const search_query = urlParams.get('term');
          const status = urlParams.get('active_status');
          const company_type = urlParams.get('company_type');
          return [
            search_query,
            status,
            company_type
          ];
        }
       
        Inertia.on('navigate', (event) => {
          Inertia.visit(`${event.detail.page.url}`,{ preserveState: true, preserveScroll: true });
        })

    const computedCompanyTypes = computed(() => {
      return common.getCompanyTypes();
    })

    return {
      term,
      form,
      search,
      toast,
      limit,
      notify,
      computedCompanyTypes,
      getUrlParam
    }
  },
  components: {
    Layout,
    Link,
    Banner,
    Head,
    Paginate
},
};
</script>
<style>
#with-thrashed{
  margin-top: -10px;
  margin-left: 3px;
}
.filters{
 margin-top: 10px;
}

</style>
<template>
<Layout>
  <Head title="Companies"/>
<div class="container-fluid">
  <Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="col-12">
<div class="row">
<div  class="col-md-12 col-xl-12 col-lg-12">
<div class="input-group input-group-outline null is-filled">
<i class="fa fa-search h4"></i>&nbsp;&nbsp;&nbsp;
<input v-model="term" placeholder="Search Company" type="text" class="form-control form-control-default centered-select">
</div>
</div>

<div class="col-6 filters">
<div class="input-group input-group-outline null is-filled">
<select @change="search" v-model="form.active_status" class="form-control form-control-default centered-select">
<option :value="''" disabled selected>Active/Inactive Status</option>
<option value="All">All</option>
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>
</div>
</div>

<div class="col-6 filters">
<div class="input-group input-group-outline null is-filled">
<select @change="search" v-model="form.company_type" class="form-control form-control-default centered-select">
<option :value="''" disabled selected>Company Type</option>
<option v-for="company_type in computedCompanyTypes " :value=company_type >{{ company_type }}</option>
</select>
</div>
</div>
<div class="col-1"></div>
</div>
<div class=" my-4">
<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
<tr>
<th class="text-uppercase text-secondary w-10 text-xxs font-weight-bolder opacity-7">
Active
</th>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
Company Name
</th>

<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
Company Type
</th>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
 Registration Number
</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
View
</th>

</tr>
</thead>
<tbody>
<tr v-if="companies.data" v-for="company in companies.data" :key="company.id">
<td class="align-middle text-sm">
<i v-if="company.active == 1" data-bs-placement="top" title="Active" class="fa fa-check text-success" aria-hidden="true"></i>

<i v-else data-bs-placement="top" title="Inactive" class="fa fa-times text-danger" aria-hidden="true"></i>
</td>
<td>
<h6 class="mb-0 text-sm">
<Link data-bs-placement="top" :title="company.name"
:href="`/view-company/${company.slug}`" class="px-0 nav-link font-weight-bold lh-1" :class="color ? color : 'text-body'">
{{ limit(company.name )}}
</Link>
</h6>   
</td>

<td>
<h6 class="mb-0 text-sm">
<Link data-bs-placement="top" :title="company.company_type"
:href="`/view-company/${company.slug}`" class="px-0 nav-link font-weight-bold lh-1" :class="color ? color : 'text-body'">
{{ limit(company.company_type) }}
</Link>
</h6>   
</td>

<td>
<h6 class="mb-0 text-sm">
<Link data-bs-placement="top" :title="company.reg_number"
:href="`/view-company/${company.slug}`" class="px-0 nav-link font-weight-bold lh-1" :class="color ? color : 'text-body'">{{ company.reg_number }}
</Link>
</h6>   
</td>

<td class="text-center">
<Link data-bs-placement="top" title="View" :href="`/view-company/${company.slug}`"><i class="fa fa-eye  " aria-hidden="true"></i></Link>

</td>

</tr>
<tr v-else >
  <td></td>
  <td></td>
  <td><p class="text-danger text-center">No companies found.</p></td>
</tr>


</tbody>
</table>

<Paginate
  :modelName="companies"
  :modelType="Companies"
  />

</div>
</div>
</div>
</div>
</div>
  </Layout>
</template>
