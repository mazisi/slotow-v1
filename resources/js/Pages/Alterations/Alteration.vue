<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';
import Paginate from "../../Shared/Paginate.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
name: "Alterations",
  props: {
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    alterations: Object,
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
    Banner,
    Paginate
  },

  methods: {

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
        },

   limit(string='', limit = 35) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        },

        getStatus(status_param) {
                    let status='Not Set';
                    switch (status_param) {
                      case '1':
                        status = '<span class="badge bg-dark text-default">Client Quoted</span>'
                        break;
                        case '2':
                        status = '<span class="badge bg-info text-default">Client Invoiced</span>'
                        break;
                        case '3':
                        status = '<span class="badge bg-light text-dark">Client Paid</span>'
                        break;
                        case '4':
                        status = '<span class="badge bg-warning text-default">Prepare Alterations Application</span>'
                        break;
                        case '5':
                        status = ' <span class="badge bg-secondary text-default">Payment to the Liquor Board</span>'
                        break;
                        case '6':
                        status = '<span class="badge bg-secondary text-default">Alterations Lodged</span>'
                        break;
                        case '7':
                        status = '<span class="badge bg-secondary text-default">Alterations Certificate Issued</span>'
                        break;
                        case '8':
                        status = '<span class="badge bg-success text-default">Alterations Delivered</span>'
                        break;                    
                      default:
                        break;
                    }
                    return status;
                  },

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
  <Head title="Alterations" />
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row gx-4">

<div class="row">
  <div class="col-lg-10 col-10">
   <h6 class="mb-1">Alterations for:  
    <Link :href="`/view-licence?slug=${licence.slug}`">
      <span class="text-success">{{ licence.trading_name ? licence.trading_name: '' }}</span></Link></h6>
  </div>
  <div class="col-lg-2 col-2 my-auto text-end">
    <Link :href="`/new-alteration?slug=${licence.slug }`" class="btn btn-sm btn-secondary"> New Alteration</Link>
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
        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alteration Date</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stage</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Complete</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody v-if="alterations">
      <tr v-for="alter in alterations.data" :key="alter.id">
       
        <td class="text-sm" >
          <Link :href="`/view-alteration/${alter.slug}`" style="margin-left: 12px;">
          {{ alter.logded_at }}
          </Link>
        </td>

        <td class="text-sm text-center">
          <Link :href="`/view-alteration/${alter.slug}`">
           <div v-html="getStatus(alter.status)"></div>
           </Link>
        </td>
        
        <td class="text-sm text-center">
          <Link :href="`/view-alteration/${alter.slug}`">
          <i v-if="alter.status == '8'" class="fa fa-check text-success" aria-hidden="true"></i>
           <i v-else class="fa fa-times text-danger" aria-hidden="true"></i>
           </Link>
        </td>

        <td class="text-sm text-center">
        <Link :href="`/view-alteration/${alter.slug}`"><i class="fa fa-eye px-1 text-secondary" aria-hidden="true"></i></Link>
        
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

<Paginate
  :modelName="alterations"
  :modelType="Alterations"
  />
</div>
</div>

</div>

</div>
</div>
</div>


</Layout>
</template>