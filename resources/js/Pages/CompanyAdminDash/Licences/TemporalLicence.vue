<script>
import Layout from "../../../Shared/Layout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import Banner from '../../components/Banner.vue'

export default {
  props: {
  licences: Object,
  success: String,
  error: Object,
  errors: Object
  },
  data() {
    return {
     term: '',
      active_status: '' 
    }
  },
  components: {
    Layout,
    Link,
    Banner
},
methods: {
     search(){
          this.$inertia.replace(route('my_temp_licences',{
          term: this.term,
          active_status: this.active_status
          }))
     },
      
    },
};
</script>
<style>
#with-thrashed{
  margin-left: 3px;
  margin-top: -10px;
}</style>
<template>
<Layout>
  <div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="col-12">
      <form>
     <div class="row">
       <div class="col-9">
        <div class="input-group input-group-outline null is-filled">
  <input v-model="term" @keyup="search" type="text" class="form-control form-control-default" placeholder="Search..">
   </div>
       </div>
       <div class="col-2">
        <div class="input-group input-group-outline null is-filled">

  <select @change="search" v-model="active_status" class="form-control form-control-default">
    <option :value="''" disabled selected>Filter By</option>
   <option value="All">All</option>
   <option value="Active">Active</option>
   <option value="Inactive">Inactive</option>
  </select>

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
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
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
                  <tr v-for="licence in licences" :key="licence.id">
                    <td class="text-sm" >
                      <h6 class="mb-0 text-sm" style="margin-left: 1rem;">
                    <Link :href="`/company/view-my-temp-licences/${licence.slug}`">
                    {{ licence.event_name }}
                    </Link>
                  </h6>
                      
                    </td>
                    <td>
             
                          <h6  v-if="licence.people === null" class="mb-0 text-sm">               
                          <Link :href="`/company/view-my-temp-licences/${licence.slug}`"
                            class="px-0 ">{{ licence.company.name }}
                          </Link>
                           </h6>
                           <h6 v-if="licence.company === null" class="mb-0 text-sm">
                            <Link :href="`/company/view-my-temp-licences/${licence.slug}`">
                             {{ licence.people.full_name }}
                           </Link>
                            </h6>
                    </td>
                    <td class="">
                      <h6 class="mb-0 text-sm">
                        <Link :href="`/company/view-my-temp-licences/${licence.slug}`">
                          {{ licence.start_date }}
                       </Link>
                        </h6>
                      
                    </td>
                    <td class="">
                      <h6 class="mb-0 text-sm">
                        <Link :href="`/company/view-my-temp-licences/${licence.slug}`">
                          {{ licence.liquor_licence_number }}
                       </Link>
                        </h6>
                      
                    </td>
                    <td class="text-center">
                    <Link :href="`/company/view-my-temp-licences/${licence.slug}`"><i class="fa fa-eye  " aria-hidden="true"></i></Link>
                      
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
