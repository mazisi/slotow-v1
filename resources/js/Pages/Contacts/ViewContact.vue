<script>
import Layout from "../../Shared/Layout.vue";
import { Link,useForm, Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Banner from '../components/Banner.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
  
 props: {
    success: String,
    errors: Object,
    error: String,
    contact: Object
  },
  setup(props) {
 
   let showMenu = false;
   const form = useForm({
      first_name: props.contact.first_name,
      last_name: props.contact.last_name,
      email: props.contact.email,
      business_number: props.contact.business_phone,
      mobile_number: props.contact.mobile_phone,     
    })

    function update() {
            form.patch(`/update-individual-contact/${props.contact.id}`, {
                onSuccess: () => {
                  if(props.success){
                  notify(props.success)
                  }else if(props.error){
                  notify(props.error)
                 }
                  form.reset()
                } 
            })
      }

      function deleteContact(first_name='',last_name='') {
        if(confirm(first_name + ' ' + last_name + ' will deleted..Continue??')){
          Inertia.delete(`/delete-individual-contact/${props.contact.id}`)
        }
      }

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

    return{
      showMenu,
      form,
      update,
      deleteContact,
      toast,
      notify
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
  <Head title="View Contact" />
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row ">
       
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">Contact Info: {{ contact.first_name }} {{ contact.last_name }}</h5>
          </div>
        </div>
        <div class="mx-auto mt-3 col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0">
      <button @click="deleteContact(contact.first_name,contact.last_name)" type="button" class="btn btn-sm btn-danger float-lg-end pe-4">Delete</button>
        </div>
      </div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="update">
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
  Save
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
