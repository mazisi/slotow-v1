<template>
    <Layout>
      <Head title="Create Issue" />
    <div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
    <div class="row">
    <div class="col-lg-6 col-7">
    <h6 class="mb-1">Add New Issue</h6>
    </div>
    
    </div>
    <form @submit.prevent="submitIssue" class="row">    

      <div class="columns col-md-6 col-xl-6 col-lg-6 columns">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Priority*</label>
        <select v-model="issueForm.severity" required class="form-control form-control-default">
                <option :value="''" disabled selected>Select...</option>
                <option value="High" class="text-danger">High</option>
                <option value="Moderate" class="text-warning">Moderate</option>
                <option value="Low" class="text-dark">Low</option>
                </select>
        </div>
        <div v-if="errors.severity" class="text-danger">{{ errors.severity }}</div>
        </div>

        <div class="columns col-md-6 col-xl-6 col-lg-6 columns">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Page Url</label>
        <input type="text" v-model="issueForm.url" class="form-control form-control-default" placeholder="e.g goverify.co.za/licences">
        </div>
        <div v-if="errors.severity" class="text-danger">{{ errors.severity }}</div>
        </div>


        <div class="col-md-12 col-xl-12 col-lg-12 filters">
            <Editor
            :init="{
                plugins: 'lists link image table code help wordcount',
                placeholder: 'Describe your issue.....'
            }"
            v-model="issueForm.body"
        />
        <div v-if="errors.body" class="text-danger">{{ errors.body }}</div>
        </div>
    
    <div class="mt-3 float-end">
    <button type="submit" :style="{float: 'right'}" class="btn btn-secondary" :disabled="issueForm.processing">
    <span v-if="issueForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    Submit
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
    import { ref,computed } from "vue";
    import { usePage } from '@inertiajs/vue3';
    
    export default {
     props: {
        errors: Object,
        success: String,
        error: String,
      },
      
      
      setup (props) {
        const user = computed(() => usePage().props.value.auth.user)
        let show_modal = ref(true); 

        const issueForm = useForm({
            body: '',
            severity: '',
            url: ''
          }) 

          function submitIssue() {
            issueForm.post('/submit-issue', {
              onSuccess: () => {
                //issueForm.reset('body','severity')
              }
             })
          }

            return {
                user,
                show_modal,
                issueForm,
                submitIssue,
            }
         },
       components: {
        Layout,
        Link,
        Banner,
        Editor
      },
      
    };
    
    </script>
    