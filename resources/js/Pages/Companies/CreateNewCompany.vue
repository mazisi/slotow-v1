
<script>
import Layout from "../../Shared/Layout.vue";
import Banner from '../components/Banner.vue';
import { Head} from '@inertiajs/inertia-vue3';
import  common from '../common-js/common.js';
import CompanyTypesSelectDropdownComponent from './company-components/CompanyTypesSelectDropdownComponent.vue';
import TextInputComponent from '../components/input-components/TextInputComponent.vue';
import CheckBoxInputComponent from '../components/input-components/CheckBoxInputComponent.vue';




export default {
 props: {
    errors: Object,
  },
  data() {
    return {
       form: {
        copy_address: '',
        company_name: '',
        company_type: '',
        reg_number: '',
        vat_number: '',
        fax_number: '',
        email_address_1: '',
        email_address_2: '',
        email_address_3: '',
        telephone_number_1: '',
        telephone_number_2: '',
        website: '',
        business_address: '',
        business_address2: '',
        business_address3: '',
        business_province: '',
        business_address_postal_code: '',
        postal_address: '',
        postal_address2: '',
        postal_address3: '',
        postal_province: '',
        postal_code: '',
        active: '1',
      },
      showMenu: false,
    };
  },
    methods: {
    submit() {
      this.$inertia.post('/submit-company', this.form)
        
    },

    copyBusinessAddress(){
      if(this.form.copy_address){
        this.form.copy_address = true;
        this.form.postal_address = this.form.business_address;
        this.form.postal_address2 = this.form.business_address2;
        this.form.postal_address3 = this.form.business_address3;
        this.form.postal_province = this.form.business_province;
        this.form.postal_code = this.form.business_address_postal_code;
      }else{
        this.form.copy_address = false;
        this.form.postal_address = ''
        this.form.postal_address2 = '';
        this.form.postal_address3 = '';
        this.form.postal_province = '';
        this.form.postal_code = '';
        this.form.business_province = '';
      }
    },


  },
  components: {
    Layout,
    Banner,
    Head,
    CompanyTypesSelectDropdownComponent,
    TextInputComponent,
    CheckBoxInputComponent
  },

  computed: {
    computedProvinces() {
      return common.getProvinces();
    },

    computedCompanyTypes() {
      return common.getCompanyTypes();
    }
  },

  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>
<template>
<Layout>
  <Head title="Create Company"/>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-6 col-7">
<h6 class="mb-1">New Company </h6>
</div>

</div>



<div class="row">
<div class="mt-3 ">
<form class="row" @submit.prevent="submit">
<input type="hidden" v-model="form.slug">
<div class="col-8 col-md-8 col-xl-8 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">
<div class="row">

      <CheckBoxInputComponent 
        :label="'Active Company'" 
        :value="'1'" 
        :isChecked="form.active == '1'" 
        :column="'col-md-12'" 
      />

     <TextInputComponent 
        :inputType="'text'"
        v-model="form.company_name" 
        :value="form.company_name"  
        :column="'col-6'" 
        :label="'Company Name *'" 
        :errors="errors.company_name"
        :input_id="company_name"
        :required="true"
      />

      <CompanyTypesSelectDropdownComponent 
        :dropdownList="computedCompanyTypes" 
        :label="'Company Type *'" 
        :defaultDisabledText="'Select Company Type'"
        :column="'col-6'"
        :value="form.company_type"
        v-model="form.company_type"
        :errors="errors.company_type"
        :input_id="company_type"
        :required="true"
      />

      <TextInputComponent 
        :inputType="'text'"
        v-model="form.reg_number" 
        :value="form.reg_number"  
        :column="'col-6'" 
        :label="'Registration Number'" 
        :errors="errors.reg_number"
        :input_id="reg_number"
      />

      <TextInputComponent 
        :inputType="'text'"
        v-model="form.vat_number" 
        :value="form.vat_number"  
        :column="'col-6'" 
        :label="'Vat Number'" 
        :errors="errors.vat_number"
        :input_id="vat_number"
      />

      <TextInputComponent 
        :inputType="'email'"
        v-model="form.email_address_1" 
        :value="form.email_address_1"  
        :column="'col-6'" 
        :label="'Email Address'" 
        :errors="errors.email_address_1"
        :input_id="email_address_1"
      />

      <TextInputComponent 
        :inputType="'email'"
        v-model="form.email_address_2" 
        :value="form.email_address_2"  
        :column="'col-6'" 
        :label="'Email Address'" 
        :errors="errors.email_address_2"
        :input_id="email_address_2"
      />

      <TextInputComponent 
        :inputType="'email'"
        v-model="form.email_address_3" 
        :value="form.email_address_3"  
        :column="'col-6'" 
        :label="'Email Address'" 
        :errors="errors.email_address_3"
        :input_id="email_address_3"
      />

      <TextInputComponent 
        :inputType="'text'"
        v-model="form.telephone_number_1" 
        :value="form.telephone_number_1"  
        :column="'col-6'" 
        :label="'Phone Number'" 
        :errors="errors.telephone_number_1"
        :input_id="telephone_number_1"
      />

      <TextInputComponent 
        :inputType="'text'"
        v-model="form.telephone_number_2" 
        :value="form.telephone_number_2"  
        :column="'col-6'" 
        :label="'Phone Number'" 
        :errors="errors.telephone_number_2"
        :input_id="telephone_number_2"
      />

      <TextInputComponent 
        :inputType="'url'"
        v-model="form.website" 
        :value="form.website"  
        :column="'col-6'" 
        :label="'Website'" 
        :errors="errors.website"
        :input_id="website"
      />

      




</div>

</div>
</div>
<hr class="vertical dark" />
</div>

<div class="col-4 col-md-4 col-xl-4" style="margin-top: 3.4rem;">
<div class="row">


<TextInputComponent 
        :inputType="'text'"
        v-model="form.business_address" 
        :value="form.business_address"  
        :column="'col-12'" 
        :label="'Business Address Line 1'" 
        :errors="errors.business_address"
        :input_id="business_address"
      />

      <TextInputComponent 
        :inputType="'text'"
        v-model="form.business_address2" 
        :value="form.business_address2"  
        :column="'col-12'" 
        :label="'Business Address Line 2'" 
        :errors="errors.business_address2"
        :input_id="business_address2"
      />


      <TextInputComponent 
        :inputType="'text'"
        v-model="form.business_address3" 
        :value="form.business_address3"  
        :column="'col-12'" 
        :label="'Business Address Line 3'" 
        :errors="errors.business_address3"
        :input_id="business_address3"
      />

    
      <div class="col-6 columns">                  
        <div class="input-group input-group-outline null is-filled">
        <label class="form-label">Province</label>
        <select class="form-control form-control-default" v-model="form.business_province" >
          <option :value="''" disabled selected >Select Province</option>
          <option v-for='province in computedProvinces' :key="province" :value=province> {{ province }}</option>
        </select>
        </div>
        <div v-if="errors.business_province" class="text-danger">{{ errors.business_province }}</div>
        </div>

      <TextInputComponent 
        :inputType="'text'"
        v-model="form.business_address_postal_code" 
        :value="form.business_address_postal_code" 
        :column="'col-6'" 
        :label="'Postal Code'" 
        :errors="errors.business_address_postal_code"
        :input_id="business_address_postal_code"
      />


<hr>
<div class="col-12 columns">            
  <div class="input-group input-group-outline is-filled">
  <input id="same-address" style="margin-top: -6px; margin-right: 3px;" @change="copyBusinessAddress" 
   type="checkbox" v-model="form.copy_address" >
  <label for="same-address">Same as Business Address</label>
  </div>
  <div v-if="errors.postal_address" class="text-danger">{{ errors.postal_address }}</div>
  </div> 
  


  <TextInputComponent 
        :inputType="'text'"
        v-model="form.postal_address" 
        :value="form.postal_address" 
        :column="'col-12'" 
        :label="'Postal Address Line 1'" 
        :errors="errors.postal_address"
        :input_id="postal_address"
      />

      <TextInputComponent 
        :inputType="'text'"
        v-model="form.postal_address2" 
        :value="form.postal_address2" 
        :column="'col-12'" 
        :label="'Postal Address Line 2'" 
        :errors="errors.postal_address2"
        :input_id="postal_address2"
      />

      <TextInputComponent 
        :inputType="'text'"
        v-model="form.postal_address3" 
        :value="form.postal_address3" 
        :column="'col-12'" 
        :label="'Postal Address Line 3'" 
        :errors="errors.postal_address3"
        :input_id="postal_address3"
      />

    

      <div class="col-6 columns">                  
        <div class="input-group input-group-outline null is-filled">
        <label class="form-label">Province</label>
        <select class="form-control form-control-default" v-model="form.postal_province" >
          <option :value="''" disabled selected >Select Province</option>
          <option v-for='province in computedProvinces' :key="province" :value=province> {{ province }}</option>
        </select>
        </div>
        <div v-if="errors.postal_province" class="text-danger">{{ errors.postal_province }}</div>
        </div>


      <TextInputComponent 
        :inputType="'text'"
        v-model="form.postal_code" 
        :value="form.postal_code" 
        :column="'col-6'" 
        :label="'Postal Code'" 
        :errors="errors.postal_code"
        :input_id="postal_code"
      />



</div>
</div>
<div>
<button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Create</button>
</div>
</form>
<hr>
</div>
</div>



</div>
</div>
</Layout>
</template>
<style>
  #active-checkbox{
    margin-top: -3px;
    margin-left: 3px;
  }
  .columns{
      margin-bottom: 1rem;
    }
</style>



