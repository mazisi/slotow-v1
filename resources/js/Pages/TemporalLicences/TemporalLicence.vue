<script>
import Layout from "../../Shared/Layout.vue";
import { Link, useForm, Head } from "@inertiajs/inertia-vue3";
import { ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Banner from '../components/Banner.vue';
import Paginate from '../../Shared/Paginate.vue';

export default {
  props: {
  licences: Object,
  success: String,
  error: Object,
  errors: Object
  },

  setup(props) {
    const term = getUrlParam() ? getUrlParam() : ref('');
    
    Inertia.on('navigate', (event) => {
      Inertia.visit(`${event.detail.page.url}`,{ preserveState: true, preserveScroll: true });
    })

    const form = useForm({
          term: term,
          active_status: ''
    })

    function search(){
      form.get(`/temp-licences`, {
        replace: true,
        preserveState: true
     })     
     

   }
   
        function limit(string='', limit=25){
          if(string){
            if(string.length >= limit){
          return string.substring(0, limit) + '...'
           }  
          return string.substring(0, limit)
          }
          
        }

       function getUrlParam(){
          const urlParams = new URLSearchParams(window.location.search);
          return urlParams.get('term');
        }

   watch(term, _.debounce(function (value) {
          Inertia.get('/temp-licences', { term: value }, { preserveState: true, replace: true });
        }, 2000));

    return {
     term,
     form,
     search,
     limit,
     getUrlParam
    }
  },
  components: {
    Layout,
    Link,
    Head,
    Inertia,
    Banner,
    Paginate
}

};
</script>
<style>
#with-thrashed{
  margin-left: 3px;
  margin-top: -10px;
}</style>
<template>
<Layout>
  <Head title="Temporary Licences"/>
  <div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="col-12">
      <form>
     <div class="row">
       <div class="col-12">
        <div class="input-group input-group-outline null is-filled">
  <input v-model="term" type="text" class="form-control form-control-default" placeholder="Search..">
   </div>
       </div>
       <div class="col-2">
        <div class="input-group input-group-outline null is-filled">

  <!-- <select @change="search" v-model="form.active_status" class="form-control form-control-default">
    <option :value="''" disabled selected>Filter By</option>
   <option value="All">All</option>
   <option value="Active">Active</option>
   <option value="Inactive">Inactive</option>
  </select> -->

   </div>
       </div>
        <div class="col-3">
       </div>
       
     </div>
     </form>
        <div class=" my-4">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                     Event Name
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                    Applicant
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                      Event Date
                      </th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Licence Number
                        </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    View
                    </th>
                    
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="licence in licences.data" :key="licence.id">
                    <td class="text-sm" >
                      <h6 class="mb-0 text-sm" style="margin-left: 1rem;">
                    <Link :href="`/view-temp-licence/${licence.slug}`">
                    {{ licence.event_name }}
                    </Link>
                  </h6>
                      
                    </td>
                    <td>
             
                          <h6  v-if="licence.people === null" class="mb-0 text-sm">               
                          <Link :href="`/view-temp-licence/${licence.slug}`"
                            class="px-0 ">{{ licence.company.name }}
                          </Link>
                           </h6>
                           <h6 v-if="licence.company === null" class="mb-0 text-sm">
                            <Link :href="`/view-temp-licence/${licence.slug}`">
                             {{ licence.people.full_name }}
                           </Link>
                            </h6>
                    </td>
                    <td class="">
                      <h6 class="mb-0 text-sm">
                        <Link :href="`/view-temp-licence/${licence.slug}`">
                          {{ licence.start_date }}
                       </Link>
                     </h6>                      
                    </td>
                    <td class="">
                      <h6 class="mb-0 text-sm">
                        <Link :href="`/view-temp-licence/${licence.slug}`">
                          {{ licence.liquor_licence_number }}
                       </Link>
                        </h6>
                      
                    </td>
                    <td class="text-center">
                    <Link :href="`/view-temp-licence/${licence.slug}`"><i class="fa fa-eye  " aria-hidden="true"></i></Link>
                      
                    </td>
                    
                  </tr>
                  
                 
                </tbody>
              </table>
            </div>
          </div>
          <Paginate
            :modelName="licences"
            :modelType="Temp-Licences"
            />
        </div>
      </div>
    </div>
  </Layout>
</template>
