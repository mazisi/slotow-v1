<script>
import Layout from "../../Shared/Layout.vue";
import Banner from '../components/Banner.vue'

export default {
  name: "my-company",
  props: {
    user: Object
  },
  data() {
    return {
     
    }
  },
  components: {
    Layout,
    Banner
},
};
</script>
<style>
#with-thrashed{
  margin-top: -10px;
  margin-left: 3px;
}</style>
<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="col-12">

<div class=" my-4">
<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
<tr>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Active
</th>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
Company Name
</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
View
</th>
</tr>
</thead>
<tbody>
<tr v-for="company in user.company" >
<td class="align-middle text-sm">
<i v-if="company.active == 1" class="fa fa-check text-info" aria-hidden="true"></i>

<i v-else class="fa fa-times text-danger" aria-hidden="true"></i>
</td>
<td>
<div class="d-flex px-2 py-1">

<div class="d-flex flex-column justify-content-left">
<h6 class="mb-0 text-sm">

<inertia-link
:href="`/company/view-my-company/${company.slug}`" class="px-0 nav-link font-weight-bold lh-1" >{{ company.name }}
</inertia-link>
</h6>                          
</div>
</div>
</td>
<td class="text-center">
<inertia-link :href="`/company/view-my-company/${company.slug}`"><i class="fa fa-eye  " aria-hidden="true"></i></inertia-link>

</td>

</tr>


</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
  </Layout>
</template>
