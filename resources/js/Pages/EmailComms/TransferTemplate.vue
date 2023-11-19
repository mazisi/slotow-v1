<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-6 col-7">
<h6 class="mb-1">Licence Info: {{ licence.licence.trading_name ? licence.licence.trading_name : '' }}</h6>
</div>

</div>
<form @submit.prevent="sendMail" class="row">

<editor
      :init="{
        plugins: 'lists link image table code help wordcount'
      }"
      v-model="mailForm.mail_body"
    />
<div class="mt-3 "><button type="submit" class="float-end btn btn-secondary">Send</button></div>
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
      transfer_slug: props.licence.slug,
    })

    
    function sendMail() {
      mailForm.post('/dispatchTransferMail', {
        onSuccess: () => {
                  if(props.success){
                  notify(props.success)
                  }else if(props.error){
                  notify(props.error)
                 }
                  mailForm.reset()
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
      toast,
      notify
    }
  },
   components: {
    Layout,
    Link,
    Banner,
    'editor':Editor
  },
  
};

</script>

