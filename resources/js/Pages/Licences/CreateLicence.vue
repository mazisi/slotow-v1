<template>
<Layout>

  <Head title="Create Licence" />

<div class="container-fluid">

<Banner/>

<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-6 col-7">
<h6 class="mb-1">New Licence </h6>
</div>

</div>
<div class="row">
<div class="mt-3 ">
<form class="row" @submit.prevent="submit">
<div class="col-8 col-md-8 col-xl-8 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">

<div class="row">


<CheckBoxInputComponent 
:label="'Active'" 
:value="'1'" 
:isChecked="form.is_licence_active == '1'" 
:column="'col-md-12'" 
/>

<TextInputComponent 
  :inputType="'text'"
  :required="true"
  :label="'Trading Name *'" 
  v-model="form.trading_name" 
  :column="'col-md-6'" 
  :value="form.trading_name"
  :errors="errors.trading_name "
  :input_id="trading_name"
/>
 


<div class="col-6 columns" >
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Applicant</label>
  <select v-model="form.belongs_to" @change="selectApplicant($event)" class="form-control form-control-default" required>
  <option :value="''" disabled selected>Select Applicant</option>
  <option value="Company">Company</option>
  <option value="Person">Person</option>
  </select>
  </div>
  <div v-if="errors.belongs_to" class="text-danger">{{ errors.belongs_to }}</div>
  </div>

<div class="col-md-6 columns" v-if="form.belongs_to === 'Company'">
 <Multiselect
     v-model="form.company"
        placeholder="Search company"
        :options="company_options"
        :searchable="true"
        :class="multiselect"
      />
<div v-if="errors.company" class="text-danger">{{ errors.company }}</div>
</div>


<div class="col-6 columns" v-if="form.belongs_to === 'Person'">
  <Multiselect
      v-model="form.person"
         placeholder="Search Person"
         :options="people_options"
         :searchable="true"
         style="margin:top: 1rem;"
       />
 <div v-if="errors.person" class="text-danger">{{ errors.person }}</div>
 </div>


<LicenceTypeDropDownComponent 
  :dropdownList="licence_dropdowns" 
  :label="'Licence Type *'" 
  :defaultDisabledText="'Select Licence Type'"
  :column="'col-6'"
  :value="form.licence_type"
  v-model="form.licence_type"
  :errors="errors.licence_type"
  :input_id="licence_type"
  :required="true"
/>



<TextInputComponent 
  :inputType="'text'"
  :required="true"
  v-model="form.licence_number" 
  :value="form.licence_number"
  :column="'col-6'" 
  :label="'Licence Number'" 
  :errors="errors.licence_number"
  :input_id="licence_number"
/>

<TextInputComponent 
  :inputType="'text'"
  v-model="form.old_licence_number"
  :value="form.old_licence_number" 
  :column="'col-6'" 
  :label="'Old Licence Number'" 
  :errors="errors.old_licence_number"
  :input_id="old_licence_number"
/>


<TextInputComponent 
  :inputType="'date'"
  v-model="form.licence_date" 
  :value="form.licence_date" 
  :column="'col-6'" 
  :label="'Licence Date'" 
  :errors="errors.licence_date"
  :input_id="licence_date"
/>


</div>

</div>
</div>
<hr class="vertical dark" />
</div>

<div class="col-4 col-md-4 col-xl-4" style="margin-top: 3.4rem;">


<TextInputComponent 
  :inputType="'text'"
  v-model="form.address"
  :value="form.address"  
  :column="'col-12'" 
  :label="'Address Line 1'" 
  :errors="errors.address"
  :input_id="address"
/>

<TextInputComponent 
  :inputType="'text'"
  v-model="form.address2"
  :value="form.address2"   
  :column="'col-12'" 
  :label="'Address Line 2'" 
  :errors="errors.address2"
  :input_id="address2"
/>

<TextInputComponent 
  :inputType="'text'"
  v-model="form.address3"
  :value="form.address3"   
  :column="'col-12'" 
  :label="'Address Line 3'" 
  :errors="errors.address3"
  :input_id="address3"
/>



<div class="col-12 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<select class="form-control form-control-default" v-model="form.province" required>
<option :value="''" disabled selected>Select Province</option>
<option v-for="province in computedProvinces" :key="province" :value=province>{{ province }}</option>
</select>
</div>
</div>


<TextInputComponent 
  :inputType="'text'"
  v-model="form.postal_code" 
  :value="form.postal_code"  
  :column="'col-12'" 
  :label="'Postal Code'" 
  :errors="errors.postal_code"
  :input_id="postal_code"
/>



<LiquorBoardRegionComponent 
      :dropdownList="computedBoardRegions" 
      :label="'Liquor Board Region*'" 
      :defaultDisabledText="'Select Liquor Board Region'"
      :column="'col-12'"
      :value="form.board_region"
      v-model="form.board_region"
      :errors="errors.board_region"
      :input_id="board_region"
     />

</div>
<div>
  <button :disabled="form.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary ms-2" type="submit">
  <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span class="visually-hidden">Loading...</span> Submit</button>
</div>
</form>
<hr>

</div>

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
import common from '../common-js/common.js';
import { computed } from 'vue';
import TextInputComponent from '../components/input-components/TextInputComponent.vue';
import CheckBoxInputComponent from '../components/input-components/CheckBoxInputComponent.vue';
import LicenceTypeDropDownComponent from '../components/input-components/LicenceTypeDropDownComponent.vue'
import LiquorBoardRegionComponent from '../components/input-components/LiquorBoardRegionComponent.vue'

export default {
 props: {
    errors: Object,
    licence_dropdowns: Object,
    companies: Array,
    people: Array,
    success: String,
    error: String,
  },
  
  
  setup (props) {
    let company_options = props.companies;
    let people_options = props.people;

    const form = useForm({
          trading_name: '',
          licence_type: '',
          is_licence_active: '1',
          licence_number: '',
          old_licence_number: '',
          licence_date: null,
          address: '',
          address2: '',
          address3: '',
          province: '',
          board_region: '',
          company: '',//company id
          person: '', //person id
          postal_code: '',
          belongs_to: ''   
    })

    function submit() {
      form.post('/submit-licence', {
        preserveScroll: true,
      })
      
    }

     function selectApplicant(event){
      if(form.belongs_to === 'Company'){
        form.belongs_to = event.target.value;
        form.person='';
      }else{
        form.belongs_to = event.target.value;
        form.company='';
      }

     }
    
     const computedProvinces = computed(() => {
          return common.getProvinces();
        })

        const computedBoardRegions = computed(() => {
          return common.getBoardRegions();
        })

    return { submit,selectApplicant,company_options, people_options, form, computedProvinces,computedBoardRegions}
  },
   components: {
    Layout,
    Link,
    Head,
    Multiselect,
    Banner,
    TextInputComponent,
    CheckBoxInputComponent,
    LicenceTypeDropDownComponent,
    LiquorBoardRegionComponent
},
  
};
</script>

