
<script src="./view_temp_licence.js"></script>
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
  <Head title="View Temporary Licence"/>
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
          <button v-if="$page.props.auth.has_slowtow_admin_role" 
           @click="deleteTempLicence" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>
        </div>
      </div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="update">
<div class="row">
  
  

   <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Event Name</label>
    <input type="text" class="form-control form-control-default" v-model="form.event_name" >
     </div>
   <div v-if="errors.event_name" class="text-danger">{{ errors.event_name }}</div>
   </div>
 
 <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Event Start Date</label>
    <input type="date" required class="form-control form-control-default" v-model="form.start_date" @input="computeLogdementDate">
     </div>
   <div v-if="errors.start_date" class="text-danger">{{ errors.start_date }}</div>
   </div>


<div class="col-md-4 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Event Ending Date</label>
  <input type="date" class="form-control form-control-default" v-model="form.end_date" >
   </div>
  <div v-if="errors.end_date" class="text-danger">{{ errors.end_date }}</div>
</div>  

<div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  <label class="form-label">Latest Lodgment Date</label>
  <input type="text" disabled class="form-control form-control-default" v-model="form.latest_lodgment_date" >
   </div>
   <div v-if="errors.latest_lodgment_date" class="text-danger">{{ errors.latest_lodgment_date }}</div>
 </div>

 <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  <label class="form-label">Licence Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.liquor_licence_number" >
   </div>
 <div v-if="errors.liquor_licence_number" class="text-danger">{{ errors.liquor_licence_number }}</div>
 </div>
 
 <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  
  <select class="form-control form-control-default" v-model="form.address" >
    <option :value="''" disabled selected >Event Address Region</option>
    <option v-for='board_region in computedBoardRegions' :key="board_region" :value=board_region > {{ board_region }}</option>

  </select>
   </div>
 <div v-if="errors.address" class="text-danger">{{ errors.address }}</div>
 </div>

 <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  
  <select class="form-control form-control-default" v-model="form.application_type" >
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
    <input type="text" disabled class="form-control form-control-default" v-model="form.id_or_passport">
     </div>
   <div v-if="errors.id_or_passport" class="text-danger">{{ errors.id_or_passport }}</div>
   </div>

  
    
  <div><button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Save</button></div>
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
