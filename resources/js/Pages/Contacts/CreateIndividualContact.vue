<script>
import Layout from "../../Shared/Layout.vue";
import { Link,useForm,Head } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';

export default {
  
 props: {
    errors: Object
  },
  setup() {
 
   let showMenu = false;
   const form = useForm({
      first_name: null,
      last_name: null,
      email: null,
      business_number: null,
      mobile_number: null,     
    })

    function submit() {
            form.post('/store-individual-contact', {
                onSuccess: () => form.reset(),
            })
      }
    return{
      showMenu,
      form,
      submit
    }
  },
  components: {
    Layout,
    Link,
    Head,
    Banner
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>
<style scoped>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
<Layout>
  <Head title="Create Contact" />
<div class="container-fluid">
  <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row ">
       
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">Create Contact</h5>
          </div>
        </div>
        <div class="mx-auto mt-3 col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0">
          
        </div>
      </div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="submit">
<div class="row">         
  <div class="col-md-6 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">First Names *</label>
    <input type="text" required class="form-control form-control-default" v-model="form.first_name" >
     </div>
   <div v-if="errors.first_name" class="text-danger">{{ errors.first_name }}</div>
   </div>
  
  <div class="col-md-6 columns">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Surname *</label>
  <input type="text" required class="form-control form-control-default" v-model="form.last_name" >
  </div>
 <div v-if="errors.last_name" class="text-danger">{{ errors.last_name }}</div>
</div>


<div class="col-md-6 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email Address</label>
  <input type="email" class="form-control form-control-default" v-model="form.email" >
   </div>
     <div v-if="errors.email" class="text-danger">{{ errors.email }}</div>
</div> 

<div class="col-md-6 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Mobile Number</label>
 <input v-model="form.mobile_number" class="form-control form-control-default">
  </div>
  </div>

<div class="col-md-6 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Business Number</label>
 <input v-model="form.business_number" class="form-control form-control-default">
  </div>
  </div>

  <div><button type="submit" :disabled="form.processing" class="btn btn-sm btn-secondary ms-2" :style="{float: 'right'}">
  <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  Create
  </button></div>
            </div>
            </form>
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
      <!-- //tasks were here -->
        
        </div>
        
      </div>
    </div>
  </div>
  </Layout>
</template>
