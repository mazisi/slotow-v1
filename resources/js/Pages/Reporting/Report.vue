<template>
<Layout>
  <Head title="Reports"/>
  <div class="container-fluid">
    <Banner/>
      
        <div class="card card-body mx-3 mx-md-4 mt-n6">
          <div class="col-12">
          <div class="row">   
             <div class="col-10">
                    <h6>Reports</h6>              
                  </div>
                  <div class="col-2 my-auto text-end">
                    <div class="dropdown float-lg-end pe-4">
                    
                    </div>
                  </div>

           </div>
        </div>

        <div class="row">
          <div class="col-4">
            <button @click="getType($event,'All')" type="button" class="btn btn-success w-50">All</button>
          </div>

          <div class="col-4">
            <button @click="getType($event,'Alterations')" type="button" class=" btn btn-success w-50">Alterations</button>
          </div>

          <div class="col-4">
            <button @click="getType($event,'Existing Licences')" type="button" class=" btn btn-success w-50">Existing Licences</button>
          </div>


          <div class="col-4">
            <button @click="getType($event,'New Applications')" type="button" class=" btn btn-success w-50">New Applications</button>
          </div>

          <div class="col-4">
            <button @click="getType($event,'Nominations')" type="button" class=" btn btn-success w-50">Nominations</button>
          </div>
          
          <div class="col-4">
            <button @click="getType($event,'Renewals')" type="button" class=" btn btn-primary btn-success  w-50" 
            >Renewals</button>
          </div>

          <div class="col-4">
            <button @click="getType($event,'Temporary Licences')" type="button" class=" btn btn-success w-50">Temporary Applications</button>
          </div>

          <div class="col-4">
            <button @click="getType($event,'Transfers')" type="button" class=" btn btn-success w-50">Transfers</button>
          </div>

          
        </div>
<hr/>

<!-- ################################################ -->

 <div class="row" v-if="form.variation">
  

  <h5 class="text-center">{{ form.variation }}</h5>   

 

  <div class="col-6 columns">
     
    <Multiselect
            v-model="form.month_from"           
               :options="computedMonths"
               :taggable="true"
               placeholder="From"
             />
  </div>

  <div class="col-6 columns">
     
    <Multiselect
            v-model="form.month_to"           
               :options="computedMonths"
               :taggable="true"
               :required="true"
               placeholder="To"
             />
  </div>

  <div v-if="form.variation === 'Renewals'" class="col-6 columns" >
    <Multiselect
        v-model="form.renewal_stages"           
        :options="computedRenewalStages"
          mode="tags"
        :taggable="true"
        placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Transfers'" class="col-6 columns" >
    <Multiselect
        v-model="form.transfer_stages"           
        :options="computedTransferStages"
          mode="tags"
        :taggable="true"
        placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Nominations'" class="col-6 columns" >
    <Multiselect
        v-model="form.nomination_stages"           
        :options="computedNominationStages"
        mode="tags"
        :taggable="true"
        placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'New Applications' || form.variation === 'All'" class="col-6 columns" >
    <Multiselect
    v-model="form.new_app_stages"           
    :options="computedNewAppStages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Alterations'" class="col-6 columns" >
    <Multiselect
    v-model="form.alteration_stages"           
    :options="computedAlterationStages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Temporary Licences'" class="col-6 columns">
    <Multiselect
    v-model="form.temp_licence_stages"           
    :options="computedTempLicenceStages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Existing Licences'" class="col-6 columns">
    <Multiselect
    v-model="form.new_app_stages"           
    :options="computedNewAppStages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
  </div>

 
  <div v-if="form.variation !== 'Temporary Licences'" class="col-6 columns" >
      <div class="input-group input-group-outline null is-filled" >
        <Multiselect 
          v-model="form.province"          
          :options="computedProvinces"
           mode="tags"
          :taggable="true"
          @select="fetchNewAppWithStages"
          placeholder="Province"/>
      </div>
    </div>





      <div class="col-6 columns" >
        <Multiselect
         v-model="form.year" 
         :options="years"
        :searchable="true"
        placeholder="Select Year"
        />
      </div>
  
  
  <div v-if="form.variation !== 'Temporary Licences'" class="col-6">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect
      v-model="form.boardRegion"           
      :options="computedBoardRegions"
       mode="tags"
      :taggable="true"
      @select="fetchNewAppWithStages"
      placeholder="Liquor Board Region"/>
      </div>
  </div>

  
  <div v-if="form.variation !== 'Temporary Licences'" class="col-6 ">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect
      v-model="form.licence_types"           
      :options="licenceTypes"
       mode="tags"
      :taggable="true"
      @select="fetchNewAppWithStages"
      placeholder="Licence Type"/>
      </div>
  </div>

  <div v-if="form.variation == 'Temporary Licences'" class="col-6 ">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect
      v-model="form.temp_licence_region"           
      :options="computedBoardRegions"
       mode="tags"
      :taggable="true"
      @select="fetchNewAppWithStages"
      placeholder="Region"/>
      </div>
  </div>
  <!-- :class="{'mt-3' : form.variation == 'All'}"
:class="{'mt-3' : form.variation == 'All'}" -->
  
  <!-- :class="{'mt-3' : form.variation !== 'Temporary Licences'}"  -->
  <div class="col-6" :class="{'mt-3' : form.variation == 'Upcoming Renewals'}" >
    <div class="input-group columns input-group-outline null is-filled">
      <select v-model="form.applicant" class="form-control form-control-default">
      <option :value="''" disabled selected>Select Applicant</option>
      <option value="Company">Company</option>
      <option value="Person">Person</option>
      </select>
      </div>
  </div>
  <div class="col-6 columns ">
    <div class="input-group input-group-outline null is-filled" >
      <select v-model="form.activeStatus" @change="fetchNewAppWithStages" class="input-have-green-color form-control form-control-default">
          <option :value="''" disabled selected>Active/Inactive</option>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
       </select>
    </div>
  </div>
  

<div class="d-flex mt-4" style="justify-content: space-between">
  
    <button @click="resetFilters" class="btn btn-sm btn-primary">Reset All Filters</button>
  
  

      <button @click="exportReport" :disabled="form.processing || !form.variation" 
      type="button" class="btn btn-success float-end">
      <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Export</button>
    
</div>

  
</div>

<div v-if="form.variation === 'New Applications'" class="table-responsive p-0">
  <table class="table align-items-center mb-0">
        <thead>
          <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Trading Name </th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Licence Type </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Licence Number </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Liquor Board Region </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Client Quoted</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Deposit Paid</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Client Invoiced </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Prepare New Application</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Payment to the Liquor Board</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Scanned Application</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Application Lodged</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Initial Inspection</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Final Inspection</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Client Paid</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Activation Fee Paid</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Licence Issued</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Licence Delivered</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="new_application in new_applications" :key="new_application.id">
            <td class="align-left text-center text-sm">
                  <p class="align-left text-xs font-weight-bold mb-0">{{ limit(new_application.trading_name) }}</p>
            </td>
            <td>
              <p class="align-left text-xs font-weight-bold mb-0">{{ new_application.licence_type.licence_type }}</p>
            </td>
            <td class="align-left text-center text-sm">
              <p class="text-xs font-weight-bold mb-0">{{ new_application.licence_number ? new_application.licence_number : '' }}</p>
            </td>
            <td class="align-left align-left text-center">
              <p class="align-left text-xs font-weight-bold mb-0">{{ new_application.board_region }}</p>
            </td>
            <td class="text-center">
              <p class="align-center text-xs font-weight-bold mb-0">
                <input class="align-center" type="checkbox" :checked="new_application.status >= '1'">
              </p>
             </td>
             <td class="text-center">
              <p class="align-center text-xs font-weight-bold mb-0">
                {{ new_application.deposit_paid_at }}
              </p>
             </td>
    
             <td class="text-center">
              <p class="align-center text-xs font-weight-bold mb-0">
                <span v-if="new_application.status >= 3">{{ new_application.is_client_invoiced }}</span>
              </p>
             </td>
    
             
             <td class="text-center">
              <p class="align-center text-xs font-weight-bold mb-0">
                <span v-if="new_application.status >= 4">Application preparation</span>
              </p>
             </td>
    
             <td class="text-center">
              <p v-if="new_application.status >= 5" class="align-center text-xs font-weight-bold mb-0">
                {{ new_application.liquor_board_at }}
              </p>
             </td>
    
             <td class="text-center">
              <p v-if="new_application.status >= 6" class="align-center text-xs font-weight-bold mb-0">
                Application ready for lodgement
              </p>
             </td>
    
             <td class="text-center">
              <p v-if="new_application.status >= 7" class="align-center text-xs font-weight-bold mb-0">
                  {{ new_application.application_lodged_at }}
              </p>
              <p v-if="is_application_logded_doc_uploaded" class="text-xs text-secondary mb-0">Doc Uploaded: <input type="checkbox" 
                :checked="new_application.is_application_logded_doc_uploaded !== null"></p>
             </td>
    
             <td class="text-center">
              {{ new_application.initial_inspection_at }}
             </td>
    
             
             <td class="text-center">
              <span v-if="new_application.status >= 10">{{ new_application.final_inspection_at }}</span>
             </td>
    
             <td class="text-center">
              <span v-if="new_application.status >= 11">{{ new_application.activation_fee_requested_at }}</span><br/>
             </td>
    
             <td class="text-center">
              <span v-if="new_application.status >= 12 
                 && new_application.is_finalisation_doc_uploaded !== null">
                 {{ new_application.is_finalisation_doc_uploaded }}
                </span>
             </td>
    
             <td class="text-center">
              <span v-if="new_application.status >= 13">{{ new_application.client_paid_at }}</span>
             </td>
    
             <td class="text-center">
              <span v-if="new_application.status >= 14">{{ new_application.activation_fee_paid_at }}</span>
             </td>
    
             <td class="text-center">
              <span v-if="new_application.status >= 15">{{ new_application.licence_issued_at }}</span>
             </td>
    
             <td class="text-center">
              <span v-if="new_application.status >= 16">{{ new_application.licence_delivered_at }}</span>
             </td>
          </tr>
         
        </tbody>
      </table>
    </div>
</div>
</div>





</Layout>
</template>
<style scoped>
.columns{
  margin-bottom: 1rem;
}

</style>
<script src="./reports-js/report.js"></script>

<style src="@vueform/multiselect/themes/default.css"></style>