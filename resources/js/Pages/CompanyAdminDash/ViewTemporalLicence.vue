<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import Banner from '../components/Banner.vue'

export default {
 name: "profile-overview",
 props: {
    errors: Object,
    licence: Object,
    people: Array
  },
  data() {
    return {
      showMenu: false,
        form: {
          slug: this.licence.slug,
          event_name: this.licence.event_name,
          start_date: this.licence.start_date,
          end_date: this.licence.end_date,
          address: this.licence.address,
          application_type: this.licence.application_type,
          liquor_licence_number: this.licence.liquor_licence_number,
          reg_number: this.licence.company ? this.licence.company.reg_number: '',
          id_number: this.licence.people ? this.licence.people.id_number: '',
          belongs_to: this.licence.belongs_to,
          company_name: this.licence.company ? this.licence.company.name : '',
          person: this.licence.people ? this.licence.people.full_name : ''
      },
    options: this.companies,
    persons: this.people,
    };
  },
  
  computed: {
    computeLogdementDate(){
      var d = new Date(this.licence.start_date);
      d.setDate(d.getDate() - 14);
      return this.form.latest_lodgment_date = d.toLocaleString().split(' ')[0].replace(/,/g, '')
    }
  },
  methods: {
    update() {
          this.$inertia.patch(`/update-temp-licence`, this.form)
        }
  },
  components: {
    Layout,
    Multiselect,
    Banner
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
}
.form-control {
  border-color: #4caf50 !important;
}
</style>

<template>
<Layout>
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row ">
       
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">View Temporary Licence</h5>
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
  <form>
<div class="row">
  
  

   <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Event Name</label>
    <input type="text" disabled class="form-control form-control-default" v-model="form.event_name" >
     </div>
   <div v-if="errors.event_name" class="text-danger">{{ errors.event_name }}</div>
   </div>
 
 <div class="col-md-4 columns">pp
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Event Start Date</label>
    <input type="date" disabled class="form-control form-control-default" v-model="form.start_date" >
     </div>
   <div v-if="errors.start_date" class="text-danger">{{ errors.start_date }}</div>
   </div>


<div class="col-md-4 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Event Ending Date</label>
  <input type="date" disabled class="form-control form-control-default" v-model="form.end_date" >
   </div>
  <div v-if="errors.end_date" class="text-danger">{{ errors.end_date }}</div>
</div>  

<div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  <label class="form-label">Latest Lodgment Date</label>
  <input type="text" disabled class="form-control form-control-default" v-model="computeLogdementDate" >
   </div>
   <div v-if="errors.latest_lodgment_date" class="text-danger">{{ errors.latest_lodgment_date }}</div>
 </div>

 <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  <label class="form-label">Licence Number</label>
  <input type="text" disabled class="form-control form-control-default" v-model="form.liquor_licence_number" >
   </div>
 <div v-if="errors.liquor_licence_number" class="text-danger">{{ errors.liquor_licence_number }}</div>
 </div>
 
 <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  
  <select class="form-control form-control-default" v-model="form.address" >
    <option :value="''" disabled selected >Event Address Region</option>
    <option value="Ekurhuleni">Ekurhuleni</option>
      <option value="Johannesburg">Johannesburg</option>
      <option value="Sedibeng">Sedibeng</option>
      <option value="Tshwane">Tshwane</option>
      <option value="West Rand">West Rand</option>
  </select>
   </div>
 <div v-if="errors.address" class="text-danger">{{ errors.address }}</div>
 </div>

 <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  
  <select disabled class="form-control form-control-default" v-model="form.application_type" >
    <option :value="''" disabled selected >Application Type</option>
    <option value="Off-Consumption">Off-Consumption</option>
    <option value="On-Consumption">On-Consumption</option>
  </select>
   </div>
 <div v-if="errors.application_type" class="text-danger">{{ errors.application_type }}</div>
 </div>


  <div v-if="form.belongs_to =='Company'" class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Company Name</label>
    <input type="text" disabled class="form-control form-control-default" v-model="form.company_name" >
     </div>
   <div v-if="errors.company_name" class="text-danger">{{ errors.company_name }}</div>
   </div>

   <div v-if="form.belongs_to =='Person'" class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Person Name</label>
    <input type="text" disabled class="form-control form-control-default" v-model="form.person" >
     </div>
   <div v-if="errors.person" class="text-danger">{{ errors.person }}</div>
   </div>

   <div class="col-md-4 columns" v-if="form.belongs_to === 'Company'">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Registration Number</label>
    <input type="text" disabled class="form-control form-control-default" v-model="form.reg_number">
     </div>
   <div v-if="errors.reg_number" class="text-danger">{{ errors.reg_number }}</div>
   </div>
  
   <div class="col-md-4 columns" v-else>
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">ID Number</label>
    <input type="text" disabled class="form-control form-control-default" v-model="form.id_number">
     </div>
   <div v-if="errors.id_number" class="text-danger">{{ errors.id_number }}</div>
   </div>

  
    
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
