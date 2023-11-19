<template>
<Layout>
<div class="container-fluid">
<h6 class="mb-1">
  <Link :href="`/view-temp-licence/${licence.slug}`" class="text-success">Licence Info: {{ licence.event_name ? licence.event_name : ''}}
  </Link></h6>

<Banner/>
<div class="card-body mx-4 mx-md-4 mt-n6">
<div class="row">
<div class="col-12">
</div>

</div>

<form @submit.prevent="sendMail" class="row">

<editor
      :init="{
        plugins: 'lists link image table code help wordcount'
      }"
      v-model="mailForm.mail_body"
    />
<div class="mt-3 float-end">
<button type="submit" :style="{float: 'right'}" class="btn btn-secondary" :disabled="mailForm.processing">
<span v-if="mailForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
Send
</button>
</div>

</form>

</div>

</div>

</Layout>
</template>

<style scoped>
    .columns{
      margin-bottom: 1rem;
    }
    #active-checkbox{
      margin-left: 3px;
    }

</style>

<script>
import Layout from "../../Shared/Layout.vue";
import { Link,useForm } from '@inertiajs/inertia-vue3';
import Editor from '@tinymce/tinymce-vue';
import Banner from '../components/Banner.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
 props: {
    errors: Object,
    licence: Object,
    template: String,
    success: String,
    error: String,
  },
  
  
  setup (props) {   

    const mailForm = useForm({
      mail_body: props.template,
      temp_licence_slug: props.licence.slug,
    })

    
    function sendMail() {
      mailForm.post('/dispatch-temporal-licence-mail', {
        onSuccess: () => {
          if(props.success){
                  notify(props.success)
                  }else if(props.error){
                  notify(props.error)
                 }
        }
        })
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

    return {
      sendMail,
      mailForm,
      notify
    }
  },
   components: {
    Layout,
    Link,
    Banner,
    toast,
    'editor':Editor
  },
  
};

</script>

