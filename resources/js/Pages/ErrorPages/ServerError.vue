<script>
import { Link } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';

export default {
  setup() {
    function goBack(){
      return history.go(-1);
    }
    return{
      goBack
    }
  },

 components: {
    Banner,
    Link
},
}
</script>

<template>
  <div class="container-fluid">
<Banner/>

<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="col-12">

<div class="row mb-4">
<div class="d-flex align-items-center justify-content-center vh-60">
  <div class="text-center">
      <h1 class="display-1 fw-bold">Error 500</h1>
      <p class="lead">Internal Server Error. Please contact System Administrator.</p>
      
      <Link @click="goBack" class="btn btn-primary">Go Back</Link>
  </div>
</div>

</div>
</div>
</div>

</div>
</template>