<template>
  <Layout>
    <Head title="Issues" />
  <div class="container-fluid">
  
  <Banner/>
  
  <div class="card card-body mx-3 mx-md-4 mt-n6">
  <div class="col-12">
  <div class="row">
  
  <div class="col-md-4 col-xl-4 col-lg-4 filters">
  <div class="input-group input-group-outline null is-filled">
  <select @change="search" v-model="form.status" class="form-control form-control-default">
  <option :value="''" disabled selected>Filter By Status</option>
  <option value="Resolved" class="text-success">Resolved</option>
  <option value="Pending" class="text-warning">Pending</option>
  <option value="Declined" class="text-danger">Declined</option>
  </select>
  
  </div>
  </div>
  
  
  <div class="col-md-4 col-xl-4 col-lg-4 filters">
  <div class="input-group input-group-outline null is-filled">
  <select @change="search" v-model="form.priority" class="form-control form-control-default">
  <option :value="''" disabled selected>Filter By Priority</option>
  <option value="High" class="text-danger">High</option>
  <option value="Moderate" class="text-warning">Moderate</option>
  <option value="Low" class="text-dark">Low</option>
  </select>
  </div>
  </div>
  
  
  
  </div>
  <div class="my-4">
  
  <div class="px-0 pb-2">
  <div class="table-responsive p-0">
      <table class="table align-items-center mb-0">
 <thead>
    <tr>
       <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Author </th>
       <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Company </th>
       <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Priority </th>
       <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Status </th>
       <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Issued At </th>
       <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">View</th>
    </tr>
 </thead>
 <tbody>
    <tr v-if="issues" v-for="issue in issues.data" :key="issue.id">
       <td>
          <div class="d-flex px-2 py-1">
             <Link :href="`/view-issue/${issue.slug}`">
              <img :src="`https://eu.ui-avatars.com/api/?background=random&amp;name=${issue.user.name}`" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
            </Link>
             <Link :href="`/view-issue/${issue.slug}`" class="d-flex flex-column justify-content-center">
                <h6 class="mb-0 text-sm">{{ issue.user.name }}</h6>
                <p class="text-xs text-secondary mb-0"> {{ issue.user.email }} </p>
             </Link>
          </div>
       </td>
       <td>
        <Link :href="`/view-issue/${issue.slug}`">
          <p v-if="issue.user.company > 0" class="text-xs font-weight-bold mb-0">{{ issue.user.company[0].name }}</p>
          <p v-else class="text-xs font-weight-bold mb-0">Slotow Associates</p>
        </Link>
       </td>
       <td class="align-middle text-center text-sm">
        <Link :href="`/view-issue/${issue.slug}`">
          <span v-if="issue.severity === 'High'" class="badge badge-sm bg-gradient-danger">High</span>
          <span v-else-if="issue.severity === 'Moderate'" class="badge badge-sm bg-gradient-warning">Moderate</span>
          <span v-else class="badge badge-sm bg-gradient-success">Low</span>
        </Link>
      </td>

      <td class="align-middle text-center text-sm">
        <Link :href="`/view-issue/${issue.slug}`">
          <span v-if="issue.status === 'Resolved'" class="badge badge-sm bg-gradient-success">Resolved</span>
          <span v-else-if="issue.status === 'Pending'" class="badge badge-sm bg-gradient-warning">Pending</span>
          <span v-else class="badge badge-sm bg-gradient-danger">Declined</span>
        </Link>
      </td>


       <td class="align-middle text-center">
          <span class="text-secondary text-xs font-weight-bold">{{ issue.updated_at }}</span>
        </td>
       <td class="align-middle text-center">
        <Link :href="`/view-issue/${issue.slug}`" class="text-secondary font-weight-bold text-xs"> 
          <i class="fa fa-eye h6"></i>
        </Link></td>
    </tr>
    <tr v-else class="text-center text-danger">No issues found.</tr>
   
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
  
  <style>
  .filters{
   margin-top: 10px;
  }
    .table thead th {
      padding: 0;
      }
   
    #with-thrashed{
      margin-top: 3px;
      margin-left: 3px;
    }
  </style>
  
  <script>
  import Layout from "../../Shared/Layout.vue";
  import { Link, useForm,Head } from '@inertiajs/inertia-vue3';
  import { reactive, ref, watch } from 'vue'
  import { Inertia } from '@inertiajs/inertia'
  import Banner from '../components/Banner.vue'
  
  export default {
      props: {
        issues: Object,
        success: String,
        error: String,
        errors: Object,
        all_licence_types: Object
      },
  
      components: {
          Layout,
          Link,
          Banner,
          Head
      },
  
      setup(props) {
  
      const term = ref('')
      let nextPage = ref(0);
      let prevPage =  ref(0);
      let currentPage =  reactive(props.issues.current_page);
  
      const form = useForm({
            status: '',
            month: '',
            priority: ''
          })
  function getArrowBbuttons(key){
    if(key !== 0){
      return key;
    }
    
  }
         function limit(string='', limit = 25) {
          if(string !== ''){
            if(string.length >= limit){
            return string.substring(0, limit) + '...'
          }  
            return string.substring(0, limit)
          }
          }
  
          function paginateNext(){          
            if(props.issues.current_page < props.issues.last_page){            
              nextPage = props.issues.current_page + 1;
              currentPage =  props.issues.current_page+1;
              Inertia.get('/issues', { page: nextPage }, { preserveState: true, replace: true });            
            } 
          }
  
          function paginatePrev(){         
              prevPage = props.issues.current_page - 1;
              currentPage =  props.issues.current_page-1;
              Inertia.get('/issues', { page: prevPage }, { preserveState: true, replace: true });
            
          }
  
         function search(){
            form.get(`/issues`, {
              replace: true,
              preserveState: true,
              onSuccess: () => {},
              
          })
          }
  
          watch(term, _.debounce(function (value) {
            Inertia.get('/issues', { term: value }, { preserveState: true, replace: true });
          }, 1000));
            
          return {
            limit,
            form,
            term,
            search,
            paginateNext,
            nextPage,
            prevPage,
            paginatePrev,
            currentPage,
            getArrowBbuttons
          }
      },
  
      
  }
  </script>