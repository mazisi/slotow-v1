<template>
    <Layout>
      <Head title="View Issue" />
    <div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
    <div class="row">
    <div class="col-10">
    <h6 class="mb-1">View Issue</h6>
    </div>
    <div class="col-2 text-end">
            <div class="btn-group">
            <button type="button"  :class="{ 
               'btn-success': issue.status === 'Resolved',
               'btn-warning': issue.status === 'Pending',
               'btn-danger': issue.status === 'Declined'
                }" 
            class="btn btn-sm">
            {{ issue.status }}</button>
            <button v-if="$page.props.auth.has_company_admin_role  || $page.props.auth.has_user_admin_role" type="button" 
            :class="{ 
               'btn-success': issue.status === 'Resolved',
               'btn-warning': issue.status === 'Pending',
               'btn-danger': issue.status === 'Declined'
                }" 
            class="btn btn-sm">
            
            </button>

            <button v-if="$page.props.auth.has_slowtow_admin_role" type="button" 
            :class="{ 
               'btn-success': issue.status === 'Resolved',
               'btn-warning': issue.status === 'Pending',
               'btn-danger': issue.status === 'Declined'
                }" 
            class="btn btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-dark" v-if="$page.props.auth.has_slowtow_admin_role">
                <li><Link @click="changeStatus('Resolved')" class="dropdown-item" href="#">Resolved</Link></li>
                <li><Link @click="changeStatus('Pending')" class="dropdown-item" href="#">Pending</Link></li>
                <li><Link @click="changeStatus('Declined')" class="dropdown-item" href="#">Declined</Link></li>
                <li><hr class="dropdown-divider"></li>
                <li><Link @click="changeStatus('Deleted')" class="dropdown-item" href="#">Deleted</Link></li>
            </ul>
            </div>

    </div>
    </div>
    <form @submit.prevent="updateIssue" class="row">    

      <div class="columns col-md-4 col-xl-4 col-lg-4 columns">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Date Of Issue</label>
        <input type="text" :value="issue.updated_at" class="form-control form-control-default" disabled>
        </div>
        <div v-if="errors.severity" class="text-danger">{{ errors.severity }}</div>
        </div>

        <div class="columns col-md-4 col-xl-4 col-lg-4 columns">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Priority</label>
        <select v-model="issueForm.severity" @change="updateIssue" required class="form-control form-control-default">
                <option :value="''" disabled selected>Select...</option>
                <option value="High" class="text-danger">High</option>
                <option value="Moderate" class="text-warning">Moderate</option>
                <option value="Low" class="text-dark">Low</option>
                </select>
        </div>
        <div v-if="errors.severity" class="text-danger">{{ errors.severity }}</div>
        </div>

        <div class="columns col-md-4 col-xl-4 col-lg-4 columns">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Page Url</label>
        <input type="text" v-model="issueForm.url" required class="form-control form-control-default" placeholder="e.g goverify.co.za/licences">
        <Link @click="redirectToIssueUrl(issueForm.url)" href="#!" class="input-group-text text-primary" id="addon-wrapping">
             <i class="material-icons-round opacity-10 fs-5">open_in_new</i></Link>
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
    Save
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
    import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
    import Editor from '@tinymce/tinymce-vue';
    import Banner from '../components/Banner.vue';
    import { ref } from "vue";
    import { Inertia } from "@inertiajs/inertia";
    
    export default {
     props: {
        issue: Object,
        errors: Object,
        success: String,
        error: String,
      },
      
      
      setup (props) {
        let show_modal = ref(true); 

        const issueForm = useForm({
            body: props.issue.body,
            severity: props.issue.severity,
            url: props.issue.url
          }) 

          function updateIssue() {
            issueForm.patch(`/update-issue/${props.issue.slug}`, {
              onSuccess: () => {
                //issueForm.reset('body','severity')
              }
             })
          }

          function changeStatus(status){
              Inertia.post(`/change-issue-status/${props.issue.slug}/${status}`,{
                onSuccess: () => {
                  //
                }
                })
          }

          function redirectToIssueUrl(url){
              window.open(url, "_blank");            
            
          }
          

            return {
                show_modal,
                issueForm,
                updateIssue,
                changeStatus,
                redirectToIssueUrl
            }
         },
       components: {
        Layout,
        Link,
        Head,
        Banner,
        Editor
      },
      
    };
    
    </script>
    