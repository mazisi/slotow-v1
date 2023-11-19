<template>
<Layout>
  <Head title="Create Person" />
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row ">
       
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">New Person</h5>
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

                  <CheckBoxInputComponent 
                    :label="'Active Person'" 
                    :value="'1'" 
                    :isChecked="form.active == '1'" 
                    :column="'col-md-12'" 
                  />


                  <TextInputComponent 
                    :inputType="'text'"
                    v-model="form.name" 
                    :value="form.name"  
                    :column="'col-4'" 
                    :label="'Name *'" 
                    :errors="errors.name"
                    :input_id="name"
                    :required="true"
                  />


                  <TextInputComponent 
                    :inputType="'text'"
                    v-model="form.surname"  
                    :value="form.surname" 
                    :column="'col-4'" 
                    :label="'Surname *'" 
                    :errors="errors.surname"
                    :input_id="surname"
                    :required="true"
                  />

                  

                  <div class="col-md-4 columns">            
                    <div class="input-group input-group-outline null is-filled">
                    <label class="form-label">ID/Passport Number</label>
                    <input @keyup="getDateOfBirth" @paste="getDateOfBirth" required type="text"
                      class="form-control form-control-default" v-model="form.id_or_passport"
                      max="11" />
                      </div>
                      <div v-if="errors.id_or_passport" class="text-danger">{{ errors.id_or_passport }}</div>
                    </div>

                    
                   <div class="col-md-4 columns">            
                    <div class="input-group input-group-outline null is-filled">
                    <label class="form-label">Date of Birth</label>
                    <input type="date"
                      class="form-control form-control-default" v-model="form.date_of_birth"
                      max="11" />
                      </div>
                      <div v-if="errors.date_of_birth" class="text-danger">{{ errors.date_of_birth }}</div>
                    </div>

                   <TextInputComponent 
                      :inputType="'email'"
                      v-model="form.email_address_1" 
                      :value="form.email_address_1"  
                      :column="'col-4'" 
                      :label="'Email Address'" 
                      :errors="errors.email_address_1"
                      :input_id="email_address_1"
                   />

                                
                                
                   <TextInputComponent 
                      :inputType="'email'"
                      v-model="form.email_address_2"
                      :value="form.email_address_2"  
                      :column="'col-4'" 
                      :label="'Email Address'" 
                      :errors="errors.email_address_2"
                      :input_id="email_address_2"
                   />
                                    
                                  
                                
                   <TextInputComponent 
                      :inputType="'text'"
                      v-model="form.cell_number"
                      :value="form.cell_number"  
                      :column="'col-4'" 
                      :label="'Phone Number'" 
                      :errors="errors.cell_number"
                      :input_id="cell_number"
                   />

                   <TextInputComponent 
                      :inputType="'text'"
                      v-model="form.telephone" 
                      :value="form.telephone"
                      :column="'col-4'" 
                      :label="'Work Number'" 
                      :errors="errors.telephone"
                      :input_id="telephone"
                   />
            
                      

                    <div><button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}" :disabled="form.processing">
                      <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      Create</button>
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
import Banner from '../components/Banner.vue';
import { Head,useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import TextInputComponent from '../components/input-components/TextInputComponent.vue';
import CheckBoxInputComponent from '../components/input-components/CheckBoxInputComponent.vue';

export default {
 props: {
    errors: Object,
    error: String
  },
  setup() {
    let showMenu = ref(false);
    const form = useForm({
        name: null,
        surname: null,
        date_of_birth: '',
        id_or_passport: '',
        passport_number: null,
        email_address_1: null,
        email_address_2: null,
        cell_number: null,
        telephone: null,
        active: '1',
        });

        function submit() {
          form.post(`/submit-person`)
        }

        function getDateOfBirth(){//needs refactoring
          
          if(this.form.id_or_passport.length === 13){
            let year = this.form.id_or_passport.substring(0,2);
            let month = this.form.id_or_passport.substring(2,4);
            let day = this.form.id_or_passport.substring(4,6);
            let century = '';
            if(year > 20){
              century = '19';
            }else{
              century = '20';
            }
            this.form.date_of_birth = century + year +'-' + month +'-'+ day + '';
          }else{
            this.form.date_of_birth = ''
          }
        }
  

    return {
      showMenu,
      form,submit,
      getDateOfBirth
      
    };
  },
      

  components: {
    Layout,
    Banner,
    Head,
    TextInputComponent,
    CheckBoxInputComponent
  },
  
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>


