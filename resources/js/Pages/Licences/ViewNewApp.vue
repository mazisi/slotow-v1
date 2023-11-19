<template>
  <Layout>
    <Head title="View New App"/>
  <div class="container-fluid">
  <Banner/>
  <div class="card card-body mx-3 mx-md-4 mt-n6">
  <div class="row">
  <div class="col-lg-9 col-9">
  <h5 class="mb-1">
    <Link class="text-success" :href="`/view-licence?slug=${licence.slug}`">{{ licence.trading_name ? licence.trading_name : '' }}
      
    </Link> - New Application - {{ licence.licence_number ? licence.licence_number : '' }}</h5>
    
  </div>
  <div class="col-lg-3 col-3 my-auto text-end">
    <div class="dropdown float-lg-end pe-4">
    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
    </a>
    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
    <li><Link :href="`/registration?slug=${licence.slug}`" class="dropdown-item border-radius-md">Registration</Link></li>
    <li><Link :href="`/renew-licence?slug=${licence.slug }`" class="dropdown-item border-radius-md">Renewals</Link></li>
    
    <li><Link :href="`/transfer-history?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Transfers</Link></li>
    
    <li><Link :href="`/nominations?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Nominations</Link></li>
    
    <li><Link :href="`/alterations?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Alterations</Link></li>
    
    <li><hr class="text-danger"></li>
    <li><button v-if="$page.props.auth.has_slowtow_admin_role" @click="deleteLicence" class="dropdown-item border-radius-md text-danger" >
    <i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</button></li>
    </ul>
    </div>
    </div>
    
  
  </div>
  <div class="row">
  <div class="mt-3 ">
  <form class="row" @submit.prevent="submit">
  
    <div class="col-12">
  <div class="card card-plain h-100">
  <div class="p-3 card-body">
  
  <div class="row" style="margin-top: -1rem;">
  <div class="col-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  <label class="form-label">Trading Name *</label>
  <input type="text" required class="form-control form-control-default" v-model="form.trading_name" >
  </div>
  <div v-if="errors.trading_name" class="text-danger">{{ errors.trading_name }}</div>
  </div>

  <div class="col-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Licence Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.licence_date" >
    </div>
    <div v-if="errors.licence_date" class="text-danger">{{ errors.licence_date }}</div>
    </div>

    <div class="col-4 columns">            
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">Address Line 1</label>
      <input type="text" class="form-control form-control-default" v-model="form.address">
      </div>
      </div>
  
  <div class="col-4 columns" v-if="$page.props.auth.has_slowtow_admin_role">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Applicant *</label>
    <select v-model="form.belongs_to" @change="selectApplicant($event)" class="form-control form-control-default" required>
    <option :value="''" disabled selected>Select Applicant</option>
    <option value="Company">Company</option>
    <option value="Person">Person</option>
    </select>
    </div>
    <div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
    </div>


    
          <div class="col-4 columns" v-if="form.belongs_to === 'Company' && $page.props.auth.has_slowtow_admin_role">
            <Multiselect
              v-model="form.company_id"
              :options="companyOptions"
              :searchable="true"
              placeholder="Search Company..."
              class="form-label"
              />              
          </div>

          <div class="col-4 columns" v-else-if="form.belongs_to === 'Person' ">
            <Multiselect
              :options="peopleOptions"
              v-model="form.person_id"
              :searchable="true"
              placeholder="Search Person..."
              class="form-label"
            />
        </div>
       

        
        <div class="col-4 columns" v-if="form.belongs_to === 'Company' && $page.props.auth.has_slowtow_user_role">
          <Multiselect
              v-model="form.company_id"
              :options="companyOptions"
              :searchable="true"
              :disabled="true"
              placeholder="Search Company..."
              class="form-label"
              />   
      </div>

      <div class="col-4 columns" v-else-if="form.belongs_to === 'Person' && $page.props.auth.has_slowtow_user_role">
        <Multiselect
          :options="peopleOptions"
          v-model="form.person_id"
          :searchable="true"
          :disabled="true"
          placeholder="Search Person..."
          class="form-label"
        />
    </div> 
 

    <div class="col-4 columns">            
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">Address Line 2</label>
      <input type="text" class="form-control form-control-default" v-model="form.address2">
      </div>
      <div v-if="errors.address2" class="text-danger">{{ errors.address2 }}</div>
    </div> 
  
    <div class="col-4 columns">
      <div class="input-group input-group-outline null is-filled ">
      <label class="form-label">Licence Number</label>
      <input type="text" class="form-control form-control-default" v-model="form.licence_number" >
      </div>
      <div v-if="errors.licence_number" class="text-danger">{{ errors.licence_number }}</div>
    </div>


   

    <div class="col-4 columns" v-if="licence.belongs_to ==='Person'">
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">ID Number</label>
      <input title="You can`t edit this field" readonly type="text" class="form-control form-control-default" v-model="form.id_or_passport" >
      </div>
    </div>

      <div class="col-4 columns" v-if="licence.belongs_to === 'Company'">
        <div class="input-group input-group-outline null is-filled">
        <label class="form-label">Reg Number</label>
        <input title="You can`t edit this field" readonly type="text" class="form-control form-control-default" v-model="reg_number" >
        </div>
      </div>

      

  


   <div class="col-4 columns" v-if="licence.status >= 15">            
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Latest Renewal</label>
    <input type="text" class="form-control form-control-default" v-model="form.latest_renewal">
    </div>
    <div v-if="errors.latest_renewal" class="text-danger">{{ errors.latest_renewal }}</div>
  </div> 

 

  <div class="col-4 columns">            
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Address Line 3</label>
    <input type="text" class="form-control form-control-default" v-model="form.address3">
    </div>
    </div> 

   <div class="col-4 columns">                  
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Licence Type</label>
    <select v-model="form.licence_type" class="form-control form-control-default" required>
      <option :value="''" disabled selected>Select Licence Type</option>
      <option v-for='licence_dropdown in licence_dropdowns' :value=licence_dropdown.id> {{ licence_dropdown.licence_type }}</option>
   </select>
    </div>
    </div>
    <div class="col-4 columns">            
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">Renewal Amount</label>
      <input type="number" class="form-control form-control-default" v-model="form.renewal_amount">
      </div>
      <div v-if="errors.renewal_amount" class="text-danger">{{ errors.renewal_amount }}</div>
    </div> 
    <div class="col-4 columns" v-if="licence.province === 'Mpumalanga'">            
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">Client Number</label>
      <input type="text" class="form-control form-control-default" v-model="form.client_number">
      </div>
      <div v-if="errors.client_number" class="text-danger">{{ errors.client_number }}</div>
    </div> 

    
    <div class="col-4 columns">                  
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">Province</label>
      <select required class="form-control form-control-default" v-model="form.province" >
      <option :value="''" disabled selected>Select Province</option>
      <option v-for="province in computedProvinces" :key="province" :value=province>{{ province }}</option>
      </select>
      </div>
      </div>

    <div class="col-4 columns">                  
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">Liquor Board Region</label>
      <select required class="form-control form-control-default" v-model="form.board_region" >
       <option :value="''" disabled selected>Select Liquor Board Region</option>
       <option v-for='board_region in computedBoardRegions' :key="board_region" :value=board_region > {{ board_region }}</option>
      </select>
      </div>
      </div>

      <div class="col-4 columns"></div>
      <div class="col-4 columns">            
        <div class="input-group input-group-outline null is-filled">
        <label class="form-label">Postal Code</label>
        <input  type="text" class="form-control form-control-default" v-model="form.postal_code">
      </div> 
       </div>
  
  </div>
  <div>
    <button :disabled="form.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary ms-2" type="submit">
    <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    <span class="visually-hidden">Loading...</span> Save</button>
  </div>
  </div>
  </div>  
 </div>
    
  </form>
  
  </div>

  <Task 
  v-if="licence.status < 15"
  :tasks="tasks" 
  :model_id="licence.id" 
  :success="success" 
  :error="error" 
  :errors="errors" 
  :model_type="'Licence'"
  />

  </div>
  </div>
  </div>
  </Layout>
  </template>
  
  <style src="@vueform/multiselect/themes/default.css"></style>
  
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
  import Multiselect from '@vueform/multiselect';
  import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
  import Banner from '../components/Banner.vue';
  import { Inertia } from '@inertiajs/inertia';
  import { toast } from 'vue3-toastify';
  import 'vue3-toastify/dist/index.css';
  import common from '../common-js/common.js';
  import { computed } from 'vue';
  import Task from "../Tasks/Task.vue";
  
  export default {
   props: {
      errors: Object,
      licence_dropdowns: Object,
      companies: Object,
      licence: Object,
      tasks: Object,
      people: Object,
      success: String,
      error: String,
    },
    
    
    setup (props) {
      let companyOptions = props.companies;
      let peopleOptions = props.people;

      const form = useForm({
            trading_name: props.licence.trading_name,
            licence_type: props.licence.licence_type_id,
            belongs_to: props.licence.belongs_to,
            address: props.licence.address,
            address2: props.licence.address2,
            address3: props.licence.address3,
            province: props.licence.province,
            client_number: props.licence.client_number,
            company:  props.licence.company !== null ? props.licence.company.name : '',
            person: props.licence.people !== null ? props.licence.people.full_name : '',
            board_region: props.licence.board_region,
            licence_number: props.licence.licence_number,
            licence_date: props.licence.licence_date,
            renewal_amount: props.licence.renewal_amount,
            postal_code: props.licence.postal_code,
            id_number: props.licence.people ? props.licence.people.id_or_passport : '',
            reg_number: props.licence.company ? props.licence.company.reg_number : '',
            company_id: props.licence.belongs_to === 'Company' ? props.licence.company.id : '',
            person_id: props.licence.belongs_to === 'Person' ? props.licence.people.id : '',
             
      })

      function submit() {
        form.patch(`/update-new-app/${props.licence.slug}`, {
          onSuccess: () => { 
              notify(props.success)
           },
          preserveScroll: true,
        })
        
      }

      function deleteLicence(){
          if(confirm('Are you sure you want to delete this licence??')){
            Inertia.delete(`/delete-licence/${props.licence.slug}`)
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

        function selectApplicant(event){
            if(form.belongs_to === 'Company'){
              form.belongs_to = event.target.value;
              form.person_id='';
            }else{
              form.belongs_to = event.target.value;
              form.company_id='';
            }

     }

        // onMounted(() => {
        //   if(props.success){
        //     notify(props.success)
        //   }else if(props.error){
        //     notify(props.error)
        //   }
        // });

        const computedProvinces = computed(() => {
          return common.getProvinces();
        })

        const computedBoardRegions = computed(() => {
          return common.getBoardRegions();
        })
      return { submit, form , toast,
                companyOptions,
                peopleOptions,
                deleteLicence, notify,
                computedProvinces, 
                computedBoardRegions,
                selectApplicant
      }
    },
     components: {
      Layout,
      Link,
      Head,
      Multiselect,
      Banner,
      Task,
      toast
    },
    
  };
  </script>
  
  