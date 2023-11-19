<script>
import Layout from "../../../Shared/Layout.vue";
import { Link } from '@inertiajs/inertia-vue3';
import Banner from '../../components/Banner.vue'

export default {
    props: {
      licence: Object,
    },

    components: {
        Layout,
        Link,
        Banner
    },


}
</script>

<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="col-12">
<div class="my-4">
<div class="px-0 pb-2">
<div class="table-responsive p-0">

  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Nomination Year </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Status </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> View </th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="licence.nominations" v-for="nom in licence.nominations" :key="nom.id">
          
        <td>
          <Link :href="`/company/view-nomination/${nom.slug}`"> <p class="text-xs font-weight-bold mb-0">{{ nom.year }}</p></Link>
         
        </td>
        <td class="align-middle text-center text-sm" style="text-align: left;">
          <Link :href="`/company/view-nomination/${nom.slug}`">
          <span class="badge badge-sm bg-gradient-success" v-if="nom.status == '1'">Client Quoted</span>
          <span v-if="nom.status == '2'" class="badge badge-sm bg-gradient-success">Client Invoiced</span>
          <span v-if="nom.status == '3'" class="badge badge-sm bg-gradient-success">Client Paid</span>
          <span v-if="nom.status == '4'" class="badge badge-sm bg-gradient-success">Payment to the Liquor Board</span>
          <span v-if="nom.status == '5'" class="badge badge-sm bg-gradient-success">Select Nominees</span>
          <span v-if="nom.status == '6'" class="badge badge-sm bg-gradient-success">Prepare Nomination Application </span>
          <span v-if="nom.status == '7'" class="badge badge-sm bg-gradient-success">Scanned Application</span>
          <span v-if="nom.status == '8'" class="badge badge-sm bg-gradient-success">Nomination Lodged </span>
          <span v-if="nom.status == '9'" class="badge badge-sm bg-gradient-success">Nomination Issued</span>
          <span v-if="nom.status == '10'" class="badge badge-sm bg-gradient-success">Nomination Delivered</span>
          </Link>
        </td>
        <td class="align-middle text-center text-sm">
          <Link :href="`/company/view-nomination/${nom.slug}`"><i class="fa fa-eye" aria-hidden="true"></i></Link>
        </td>
      </tr>
      <tr v-else>
        <td class="text-center text-danger">No nominations available.</td>
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




