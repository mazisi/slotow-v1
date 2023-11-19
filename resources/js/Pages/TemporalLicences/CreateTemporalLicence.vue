<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import Banner from '../components/Banner.vue';
import { Head } from '@inertiajs/inertia-vue3';
import common from '../common-js/common.js';
import TextInputComponent from '../components/input-components/TextInputComponent.vue';
import ApplicationType from "./temp-licence-components/ApplicationType.vue";

export default {
  name: "CreateTemporalLicence",
 props: {
    errors: Object,
    companies: Array,
    people: Array
  },
  data() {
    return {
      showMenu: false,
      form: {
        event_name: '',
        start_date: null,
        end_date: null,
        company: null,
        person: null,
        belongs_to: '',
        latest_lodgment_date: null,
        address: '',
        application_type: ''
    },
    options: this.companies,
    persons: this.people,
    };
  },
  watch: {
    'form.start_date'() {
      var d = new Date(this.form.start_date);
      d.setDate(d.getDate() - 14);
      this.form.latest_lodgment_date = d.toLocaleString().split(' ')[0].replace(/,/g, '')
    }
  },
  methods: {
      submit() {
          this.$inertia.post(`/submit-temp-licence`, this.form)
        }
  },

  computed: {

    computedBoardRegions() {
          return common.getBoardRegions();
        }
  },

  components: {
    Layout,
    Multiselect,
    Banner,
    Head,
    TextInputComponent,
    ApplicationType,
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>
<style src="@vueform/multiselect/themes/default.css"></style>
<style>
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
  <Head title="Create Temporal Licence" />
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row ">
       
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">New Temporary Licence</h5>
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
  <form @submit.prevent="submit">
<div class="row">
                  
  <div class="col-md-4 columns d-none">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label"></label>
    <input type="text" class="form-control form-control-default" v-model="form.liquor_licence_number" >
     </div>
   <div v-if="errors.liquor_licence_number" class="text-danger">{{ errors.liquor_licence_number }}</div>
   </div>

                 <TextInputComponent 
                    :inputType="'text'"
                    v-model="form.event_name" 
                    :value="form.event_name"  
                    :column="'col-4'" 
                    :label="'Event Name'" 
                    :errors="errors.event_name"
                    :input_id="event_name"
                    :required="true"
                  />

                  <TextInputComponent 
                    :inputType="'date'"
                    v-model="form.start_date" 
                    :value="form.start_date"  
                    :column="'col-4'" 
                    :label="'Event Start Date'" 
                    :errors="errors.start_date"
                    :input_id="start_date"
                    :required="true"
                  />

                  <TextInputComponent 
                    :inputType="'date'"
                    v-model="form.end_date" 
                    :value="form.end_date"  
                    :column="'col-4'" 
                    :label="'Event Ending Date'" 
                    :errors="errors.end_date"
                    :input_id="end_date"
                    :required="true"
                  />

                  <TextInputComponent 
                    :inputType="'text'"
                    v-model="form.latest_lodgment_date" 
                    :value="form.latest_lodgment_date"  
                    :column="'col-4'" 
                    :label="'Event Ending Date'" 
                    :errors="errors.latest_lodgment_date"
                    :input_id="latest_lodgment_date"
                    :required="true"
                    :disabled="true"
                  />

  

 
 <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
  
  <select class="form-control form-control-default" v-model="form.address" >
    <option :value="''" disabled selected >Event Address Region</option>
    <option v-for='board_region in computedBoardRegions' :key="board_region" :value=board_region > {{ board_region }}</option>

  </select>
   </div>
 <div v-if="errors.address" class="text-danger">{{ errors.address }}</div>
 </div>

      <ApplicationType 
        v-model="form.application_type" 
        :errors="errors.application_type"
        />

<div class="col-md-4 columns">
<div class="input-group input-group-outline null is-filled">
  <label class="form-label">Company or Individual?</label>
  <select v-model="form.belongs_to" required class="form-control form-control-default">
    <option :value="''" disabled selected>Company or Individual?</option>
    <option value="Company">Company</option>
    <option value="Person">Person</option>
    </select>
</div>
<div v-if="errors.belongs_to" class="text-danger">{{ errors.belongs_to }}</div>
</div>


   <div class="col-md-4 columns" v-if="form.belongs_to =='Company'">
    <div class="input-group input-group-outline null is-filled ">
     <Multiselect
       v-model="form.company"
        placeholder="Search Company..."
        :options="options"
        :searchable="true"
      />
  </div>
  <div v-if="errors.company" class="text-danger">{{ errors.company }}</div>
  </div>

  <div class="col-md-4 columns" v-if="form.belongs_to =='Person'">
    <div class="input-group input-group-outline null is-filled ">
     <Multiselect
       v-model="form.person"
        placeholder="Search Person..."
        :options="persons"
        :searchable="true"
      />
  </div>
  <div v-if="errors.person" class="text-danger">{{ errors.person }}</div>
  </div>


  
    
  <div><button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Create</button></div>
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
