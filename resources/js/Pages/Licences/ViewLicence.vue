
<script src="./view_licence.js"></script>
<template>
<Layout>

  <Head title="View Licence" />
  
<div class="container-fluid">
  <Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-11 col-11">
<h5 class="mb-1 text-center">Licence Info: {{ licence.trading_name ? licence.trading_name : '' }}</h5>
</div>


<div class="col-lg-1 col-1 my-auto text-end">
<div class="dropdown float-lg-end pe-4">
<a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
<i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
</a>
<ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
<li><Link :href="`/renew-licence?slug=${licence.slug }`" class="dropdown-item border-radius-md">Renewals</Link></li>

<li><Link :href="`/transfer-history?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Transfers</Link></li>

<li><Link :href="`/nominations?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Nominations</Link></li>

<li><Link :href="`/alterations?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Alterations</Link></li>

<li><hr class="text-danger"></li>
<li v-if="$page.props.auth.has_slowtow_admin_role" ><button 
  @click="deleteLicence" class="dropdown-item border-radius-md text-danger" >
<i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</button></li>
</ul>
</div>
</div>
</div>
<div class="row">
<div class="mt-3 ">
<form class="row" @submit.prevent="updateLicence">
<div class="col-6 col-md-6 col-xl-6 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">

<div class="row">

<CheckBoxInputComponent 
      :column="'col-12'"
      :label="'Active'"
      :isChecked="licence.is_licence_active == '1'"
      :value="1"
      @input="assignActiveValue($event,1)" 
  />

<TextInputComponent 
  v-model="form.trading_name" 
  :column="'col-md-12'" 
  :label="'Trading Name *'" 
  :value="form.trading_name"
  :errors="errors.trading_name"
  :input_id="trading_name"
/>


<div class="col-12 columns" v-if="$page.props.auth.has_slowtow_admin_role">
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


  
        <div class="col-12 columns" v-if="form.belongs_to === 'Company' && $page.props.auth.has_slowtow_admin_role">
          <Multiselect
            v-model="form.company_id"
            :options="companyOptions"
            :searchable="true"
            placeholder="Search Company..."
            class="form-label"
            />              
        </div>

        <div class="col-12 columns" v-else-if="form.belongs_to === 'Person' && $page.props.auth.has_slowtow_admin_role">
          <Multiselect
            :options="peopleOptions"
            v-model="form.person_id"
            :searchable="true"
            placeholder="Search Person..."
            class="form-label"
          />
      </div>
     

      
            <div class="col-12 columns" v-if="form.belongs_to === 'Company' && $page.props.auth.has_slowtow_user_role">
              <Multiselect
                  v-model="form.company_id"
                  :options="companyOptions"
                  :searchable="true"
                  :disabled="true"
                  placeholder="Search Company..."
                  class="form-label"
                  />   
          </div>

          <div class="col-12 columns" v-else-if="form.belongs_to === 'Person' && $page.props.auth.has_slowtow_user_role">
            <Multiselect
              :options="peopleOptions"
              v-model="form.person_id"
              :searchable="true"
              :disabled="true"
              placeholder="Search Person..."
              class="form-label"
            />
        </div> 



  <div class="col-md-12 columns">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Licence Type *</label>
    <select v-model="form.licence_type" class="form-control form-control-default">
      <option :value="''" disabled selected>Licence Type</option>
    <option v-for='licence_dropdown in licence_dropdowns' :value=licence_dropdown.id> {{ licence_dropdown.licence_type }}</option>
    </select>
    </div>
    <div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
    </div>

  <TextInputComponent
    v-model="licence.licence_date"
    :disabled="true" 
    :column="'col-md-12'" 
    :label="'Licence Date'" 
    :value="licence.licence_date"
    :errors="errors.licence_date"
    :input_id="licence_date"
    :inputType="'date'"
/>

<TextInputComponent
    v-model="form.licence_number" 
    :column="'col-md-12'" 
    :label="'Licence Number'" 
    :value="form.licence_number"
    :errors="errors.licence_number"
    :input_id="licence_number"
    :inputType="'text'"
/>

<TextInputComponent
    v-model="form.old_licence_number" 
    :column="'col-md-12'" 
    :label="'Old Licence Number'" 
    :value="form.old_licence_number"
    :errors="errors.old_licence_number"
    :input_id="old_licence_number"
    :inputType="'text'"
/>


</div>




</div>
</div>
<!-- <hr class="vertical dark" /> -->
</div>
<div class="col-6 col-md-6 col-xl-6 position-relative">
<div class="col-12 columns invisible">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Address Line 1</label>
<input type="text" class="form-control form-control-default">
</div>
</div>  



<TextInputComponent
    v-model="form.address" 
    :column="'col-12'" 
    :label="'Address Line 1'" 
    :value="form.address"
    :errors="errors.address"
    :input_id="address"
    :inputType="'text'"
/>

<TextInputComponent
    v-model="form.address2" 
    :column="'col-12'" 
    :label="'Address Line 2'" 
    :value="form.address2"
    :errors="errors.address2"
    :input_id="address2"
    :inputType="'text'"
/>

<TextInputComponent
    v-model="form.address3" 
    :column="'col-12'" 
    :label="'Address Line 3'" 
    :value="form.address3"
    :errors="errors.address3"
    :input_id="address3"
    :inputType="'text'"
/>

<div class="col-12 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<select class="form-control form-control-default" v-model="form.province" >
<option :value="''" disabled selected >Select Province</option>
<option v-for='province in computedProvinces' :key="province" :value=province> {{ province }}</option>
</select>
</div>
<div v-if="errors.province" class="text-danger">{{ errors.province }}</div>
</div>

<TextInputComponent
    v-model="form.postal_code" 
    :column="'col-12'" 
    :label="'Postal Code'" 
    :value="form.postal_code"
    :errors="errors.postal_code"
    :input_id="postal_code"
    :inputType="'text'"
/>

<div class="col-12 columns">
  <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Liquor Board Region</label>
<select class="form-control form-control-default" v-model="form.board_region" >
  <option :value="''" disabled selected >Select Board Region</option>
  <option v-for='board_region in computedBoardRegions' :key="board_region" :value=board_region > {{ board_region }}</option>

</select>
 </div>
<div v-if="errors.board_region" class="text-danger">{{ errors.board_region }}</div>
</div>


<TextInputComponent
    v-if="licence.status >= 8"
    v-model="form.latest_renewal" 
    :column="'col-12'" 
    :label="'Latest Renewal'" 
    :value="form.latest_renewal"
    :errors="errors.latest_renewal"
    :input_id="latest_renewal"
    :inputType="'text'"
/>

<div class="input-group-custom mb-2">
  <div class="add-on">R</div>
  <input type="number" class="input-field form-control-default" v-model="form.renewal_amount" placeholder="Renewal amount">
</div>

<!-- <TextInputComponent
    v-model="form.renewal_amount" 
    :column="'col-12'" 
    :label="'Renewal Amount'" 
    :value="form.renewal_amount"
    :errors="errors.renewal_amount"
    :input_id="renewal_amount"
    :inputType="'number'"
/> -->




</div>

<h6 class="text-center">Documents</h6>
<div class="row">
<div class="col-md-6 columns">
<ul class="list-group">
  <template v-if="original_lic">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="original_lic">
    <a v-if="original_lic" :href="`${$page.props.blob_file_path}${original_lic.document_file}`" target="_blank">
    <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Original Licence</h6>
      <p v-if="original_lic" class="mb-0 text-xs">
        {{ original_lic.document_name ? removeFilePath(original_lic.document_name) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="original_lic" @click="deleteDocument(original_lic.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Original-Licence')" type="button" data-bs-toggle="modal" data-bs-target="#licence-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>
</template>

<template v-else-if="licence_issued">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="licence_issued">
    <a :href="`${$page.props.blob_file_path}${licence_issued.document_file}`" 
    target="_blank" >
    <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Original Licence</h6>
      <p v-if="licence_issued" class="mb-0 text-xs">
        {{ licence_issued.document_name ? removeFilePath(licence_issued.document_name) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
  </li>
</template>

  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="duplicate_original_lic">
    <a v-if="duplicate_original_lic" :href="`${$page.props.blob_file_path}${duplicate_original_lic.document_file}`" target="_blank">
    <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Duplicate Original</h6>
      <p v-if="duplicate_original_lic" class="mb-0 text-xs">
        {{ duplicate_original_lic.document_name ? removeFilePath(duplicate_original_lic.document_name) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="duplicate_original_lic" @click="deleteDocument(duplicate_original_lic.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Duplicate-Licence')" type="button" data-bs-toggle="modal" data-bs-target="#licence-docs" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>
</ul>
<hr class="vertical dark" />
</div>

<div class="col-md-6 columns">
<ul class="list-group">
  <template v-if="original_lic_delivered">
    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
      <div class="me-3" v-if="original_lic_delivered">
      <a v-if="original_lic_delivered" :href="`${$page.props.blob_file_path}${original_lic_delivered.document_file}`" target="_blank" >
      <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
      </a>    
      </div>

      <div class="d-flex align-items-start flex-column justify-content-center">
        <h6 class="mb-0 text-sm">Original Licence Delivered</h6>
        <p v-if="original_lic_delivered" class="mb-0 text-xs">{{ original_lic_delivered.document_name ? removeFilePath(original_lic_delivered.document_name) : '' }}</p>
        <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
      </div>

      <button  v-if="original_lic_delivered" @click="deleteDocument(original_lic_delivered.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
      <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
      </button>

      <button v-else @click="getDocType('Original-Licence-Delivered')" type="button" data-bs-toggle="modal" data-bs-target="#licence-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
      <i class="fa fa-upload" aria-hidden="true"></i>
      </button>
    </li>
</template>

<template v-else-if="licence_delivered">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="licence_delivered">
    <a v-if="licence_delivered" :href="`${$page.props.blob_file_path}${licence_delivered.document_file}`" 
    target="_blank" >

    <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Original Licence Delivered</h6>
      <p v-if="licence_delivered" class="mb-0 text-xs">
        {{ licence_delivered.document_name ? removeFilePath(licence_delivered.document_name) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
  </li>
</template>


    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
      <div class="me-3" v-if="duplicate_original_lic_delivered">
      <a v-if="duplicate_original_lic_delivered" :href="`${$page.props.blob_file_path}${duplicate_original_lic_delivered.document_file}`" 
      target="_blank" >

      <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
      </a>    
      </div>
      <div class="d-flex align-items-start flex-column justify-content-center">
        <h6 class="mb-0 text-sm">Duplicate Original Delivered</h6>
        <p v-if="duplicate_original_lic_delivered" class="mb-0 text-xs">
          {{ duplicate_original_lic_delivered.document_name ? removeFilePath(duplicate_original_lic_delivered.document_name) : '' }}</p>
        <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
      </div>
      <button  v-if="duplicate_original_lic_delivered" @click="deleteDocument(duplicate_original_lic_delivered.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
      <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
      </button>

      <button v-else @click="getDocType('Duplicate-Original-Licence-Delivered')" type="button" data-bs-toggle="modal" data-bs-target="#licence-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
      <i class="fa fa-upload" aria-hidden="true"></i>
      </button>
    </li>



</ul>
</div>
</div>
<div>
<button type="submit" class="btn btn-primary ms-2" :style="{float: 'right'}">Save</button>
</div>
</form>
<hr>

<div class="row">
<h6 class="text-center mb-2 ">Companies Linked To : {{ licence.trading_name ? licence.trading_name : '' }}</h6>
<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
<tr>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
Active
</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Company Name
</th>

<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Action
</th>

</tr>
</thead>
<tbody>
<tr>
<td v-if="licence.belongs_to ==='Company' && licence.company.active !== null" class="text-sm"><i class="fa fa-check text-info" aria-hidden="true"></i></td>
<td v-else-if="licence.belongs_to ==='Person' && licence.people.active !== null" class="text-sm"><i class="fa fa-check text-info" aria-hidden="true"></i></td>
<td v-else class=" text-sm text-danger"><i class="fa fa-times"></i></td>
<td class="align-middle text-sm">
<Link v-if="licence.belongs_to ==='Company'" :href="`/view-company/${licence.company.slug}`" class="text-sm text-center align-middle">
  <h6 class="mb-0 ">{{ limit(licence.company.name) }}</h6></Link>
  <Link v-else-if="licence.belongs_to ==='Person'" :href="`/view-person/${licence.people.slug}`" class="text-sm text-center align-middle">
    <h6 class="mb-0 ">{{ limit(licence.people.full_name) }}</h6></Link>
</td>
<td class="text-center">
<Link v-if="licence.belongs_to ==='Company'" :href="`/view-company/${licence.company.slug}`"><i class="fa fa-eye" aria-hidden="true"></i></Link>
<Link v-else-if="licence.belongs_to ==='Person'" :href="`/view-person/${licence.people.slug}`"><i class="fa fa-eye" aria-hidden="true"></i></Link>
</td>
</tr>
</tbody>
</table>
</div>
<hr>
</div>


<Task 
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
</div>


<div v-if="show_modal" class="modal fade" id="licence-docs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="uploadOriginalLicenceDoc">
      <input type="hidden" v-model="originalLicenceForm.doc_type">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns">
        <label for="licence-doc" class="btn btn-dark w-100" href="">Select File</label>
         <input type="file" @change="getFileName"
         hidden id="licence-doc" accept=".pdf"/>
         <div v-if="errors.document_file" class="text-danger">{{ errors.document_file }}</div>
         <div v-if="file_name">File Selected: <span class="text-success" v-text="file_name"></span></div>
         <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">Sorry <span class="text-success">{{ file_name }}</span> cannot contain apostrophe(s).Replace apostrophes with backticks.</p>  
       </div>
       
<!-- 
       <file-pond
            name="test"
            ref="pond"
            class-name="my-pond"
            label-idle="Drop files here..."
            allow-multiple="true"
            accepted-file-types="image/jpeg, image/png"
            server="/upload-licence-document?licence=msebele"
        /> -->

        
       <div class="col-md-12">
          <progress v-if="originalLicenceForm.progress" :value="originalLicenceForm.progress.percentage" max="100">
         {{ originalLicenceForm.progress.percentage }}%
         </progress>
         </div>
         </div>
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" :disabled="originalLicenceForm.processing || file_has_apostrophe">
         <span v-if="originalLicenceForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

</Layout>
</template>

<style scoped>
  .input-group-custom {
    display: flex;
    
  }

  .add-on {
    background-color: #f0f0f0;
    border: 2px solid #4caf50;
    padding: 5px 10px;
    border-right: none;
  }


 
  .input-field {
    flex: 1;
    border-color: #4caf50 !important;
    background: none;
    border: 2px solid #d2d6da;
    padding: 5px 10px;
  }

  .input-field:focus {
    outline: none;
    border-color: #4caf50;
    box-shadow: none;
  }
    .columns{
      margin-bottom: 1rem;
    }
    #active-checkbox{
      margin-left: 3px;
    }

</style>

<style src="@vueform/multiselect/themes/default.css"></style>


