<template>
<Layout>
  <Head title="View Person" />
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-6 col-7">
<h6 class="mb-1">Person Info: {{ person.full_name }}</h6>
</div>
<div class="col-lg-6 col-5 my-auto text-end">
<button @click="deletePerson(person.full_name)" type="button" class="btn btn-sm btn-danger">Delete</button></div>
</div>
<div class="row">
<div class="mt-3 row">
<div class="col-12 col-md-12 col-xl-12 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">
<form @submit.prevent="updatePerson">
<input type="hidden" v-model="form.slug">
<div class="row">
         
                  <CheckBoxInputComponent 
                    :label="'Active Person'" 
                    :value="'1'" 
                    :isChecked="person.active == '1'" 
                    :column="'col-md-12'"
                    @input="assignActiveValue($event,1)" 
                  />



                    <TextInputComponent 
                    :inputType="'text'"
                    v-model="form.name" 
                    :value="form.name"  
                    :column="'col-4'" 
                    :label="'Full Name And Surname *'" 
                    :errors="errors.name"
                    :input_id="name"
                    :required="true"
                  />


                  <TextInputComponent 
                    :inputType="'text'"
                    v-model="form.id_or_passport"  
                    :value="form.id_or_passport" 
                    :column="'col-4'" 
                    :label="'ID/Passport Number'" 
                    :errors="errors.id_or_passport"
                    :input_id="id_or_passport"
                  />

                  <TextInputComponent 
                    :inputType="'text'"
                    v-model="form.date_of_birth"  
                    :value="form.date_of_birth" 
                    :column="'col-4'" 
                    :label="'Date of Birth'" 
                    :errors="errors.date_of_birth"
                    :input_id="date_of_birth"
                    :disabled="true"
                  />

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
                      :inputType="'tel'"
                      v-model="form.telephone" 
                      :value="form.telephone"
                      :column="'col-4'" 
                      :label="'Work Number'" 
                      :errors="errors.telephone"
                      :input_id="telephone"
                   />




<hr>
<h6 class="text-center">Documents</h6>

<div class="row">
<div class="col-4">
  <ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="id_document">
    <a :href="`${$page.props.blob_file_path}${id_document.path}`" target="_blank">
    <i v-if="id_document.path.includes('.pdf')" class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    <i v-else class="fa fa-picture-o text-lg text-danger" aria-hidden="true"></i>
    
    </a>
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">ID Document</h6>
      <p v-if="id_document" class="mb-0 text-xs">{{ id_document.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">ID Not Uploaded</p>
    </div>

    <button v-if="id_document" @click="deleteDocument('ID Document',id_document.slug)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('ID Document')" type="button" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>
</ul>
</div>

<div class="col-4">
  <ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="police_clearance">
    <a :href="`${$page.props.blob_file_path}${police_clearance.path}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Police Clearance</h6>
      <p v-if="police_clearance" class="mb-0 text-xs">{{ police_clearance.document_name }}</p>
      <p v-if="police_clearance" class="mb-0 text-xs text-dark">
        Expiry:{{ computeExpiryDate(police_clearance.expiry_date) ? computeExpiryDate(police_clearance.expiry_date) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger">Police Clearance Not Uploaded</p>
    </div>
    <button v-if="police_clearance" @click="deleteDocument('Police Clearance',police_clearance.slug)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>
    <button v-else @click="getDocType('Police Clearance')" type="button" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>
</ul>
</div>

<div class="col-4">
  <ul class="list-group">
 <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="passport_doc">
    <a :href="`${$page.props.blob_file_path}${passport_doc.path}`" target="_blank">
    <i v-if="passport_doc.path.includes('.pdf')" class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    <i v-else class="fa fa-picture-o text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Passport</h6>
      <p v-if="passport_doc" class="mb-0 text-xs">{{ passport_doc.document_name }}</p>
      <p v-if="passport_doc" class="mb-0 text-xs text-dark">
        Expiry:{{ computeExpiryDate(passport_doc.expiry_date) ? computeExpiryDate(passport_doc.expiry_date) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger">Passport Not Uploaded</p>
    </div>

    <button v-if="passport_doc" @click="deleteDocument('Passport',passport_doc.slug)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Passport')" type="button" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>
</ul>
</div>


<div class="col-4">
  <ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="work_permit_doc">
    <a :href="`${$page.props.blob_file_path}${work_permit_doc.path}`" target="_blank">
    <i v-if="work_permit_doc.path.includes('.pdf')" class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    <i v-else class="fa fa-picture-o text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Work Permit</h6>
      <p v-if="work_permit_doc" class="mb-0 text-xs">{{ work_permit_doc.document_name }}</p>
      <p v-if="work_permit_doc" class="mb-0 text-xs text-dark">
        Expiry:{{ computeExpiryDate(work_permit_doc.expiry_date) ? computeExpiryDate(work_permit_doc.expiry_date) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger">Work Permit Not Uploaded</p>
    </div>

    <button v-if="work_permit_doc" @click="deleteDocument('Work Permit',work_permit_doc.slug)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Work Permit')" type="button" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>
</ul>
</div>

<div class="col-4">
  <ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="fingerprints !== null">
    <a :href="`${$page.props.blob_file_path}${fingerprints.path}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Valid Fingerprints</h6>
      <p v-if="fingerprints !== null" class="mb-0 text-xs">{{ fingerprints.document_name }}</p>
      <p v-if="fingerprints !== null" class="mb-0 text-xs text-dark">
        Expiry:{{ computeExpiryDate(fingerprints.expiry_date) ? computeExpiryDate(fingerprints.expiry_date) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger">Fingerprints Not Uploaded</p>
    </div>

    <button v-if="fingerprints !== null" @click="deleteDocument('Fingerprints',fingerprints.slug)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Fingerprints')" type="button" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>
</ul>
</div>
</div>


</div>
<div class="d-flex float-end" style="float: right;">
 <button :disabled="form.processing" class="btn btn-sm btn-primary ms-2" type="submit">
  <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  Save
</button>
</div>
</form>
</div>
</div>

</div>
<!-- //tasks were here -->

</div>

</div>

<div class="row">
  <h6 class="text-center mb-2 ">Companies Linked To : {{ person.full_name ? person.full_name : '' }}</h6>
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
    Position
    </th>
  
  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
  Action
  </th>
  
  </tr>
  </thead>
  <tbody>
    
  <tr v-for="linked_company in person.company">
  <td v-if="linked_company ==1 && linked_company.active !== null" class="text-sm"><i class="fa fa-check text-info" aria-hidden="true"></i></td>
  <td v-else class="text-sm"><i class="fa fa-check text-info" aria-hidden="true"></i></td>
  <td class="align-left text-sm">
  <Link :href="`/view-company/${linked_company.slug}`" class="text-sm text-center align-left">
    <h6 class="mb-0 ">{{ linked_company.name }}</h6>
  </Link>
  </td>

  <td class="align-middle text-sm">
    <Link :href="`/view-company/${linked_company.slug}`" class="text-sm text-center align-middle">
      <h6 class="mb-0 ">{{ linked_company.pivot.position }}</h6>
    </Link>
    </td>

  <td class="text-center">
  <Link :href="`/view-company/${linked_company.slug}`"><i class="fa fa-eye" aria-hidden="true"></i></Link>
  </td>
  </tr>
  </tbody>
  </table>
  </div>
  <hr>
  </div>

<div class="row">
  <div class="col-sm-12 col-lg-4"></div>
  <div class="col-sm-12 col-lg-12">
  <h6 class="text-center">Licences Linked To : {{ person.full_name ? person.full_name : '' }}</h6>
  </div>
  
  
  <div class="table-responsive p-0">
  <table class="table align-items-center mb-0">
  <thead>
    <tr>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
      Trading Name
      </th>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
      Licence Number
      </th>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
      Licence Date
      </th>
  
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
      View
      </th>
      
    </tr>
  </thead>
  <tbody>
    
    <tr v-for="licence in linked_licences.data" :key="licence.id">
      <td>
        <div class="d-flex px-2">    
      <div class="d-flex flex-column">
        <Link :href="`/view-licence?slug=${licence.slug }`"><h6 class="mb-0 text-sm">{{ licence.trading_name ? licence.trading_name : ''  }}</h6></Link>                          
      </div>
        </div>
      </td>
      <td class="text-center"><Link :href="`/view-licence?slug=${licence.slug }`">{{ licence.licence_number ? licence.licence_number : '' }}</Link></td>
      <td class="text-center"><Link :href="`/view-licence?slug=${licence.slug }`">{{ licence.licence_date }}</Link></td>
      <td class="text-center">
      <Link :href="`/view-licence?slug=${licence.slug }`" class="mx-2 ms-2 justify-content-center">
      <i class="fa fa-eye"></i></Link>
      </td>
      
    </tr>
    
    
  </tbody>
  </table>
  </div>
  <Paginate
    :modelName="linked_licences"
    :modelType="ViewPerson"
    />
  <hr>
  </div>
 
  

<Task :tasks="tasks" :model_id="person.id" :errors="errors" :success="success" :error="error" :model_type="'Person'"/>


</div>
</div>

<div v-if="show_doc_modal" class="modal fade" id="documents" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload <span class="text-success">{{ uploadDoc.doc_type }}</span> Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="submitDocument">
      <input type="hidden" v-model="uploadDoc.doc_type">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns" v-if="uploadDoc.doc_type !== 'ID Document'">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Expiry Date</label>
        <input type="date" required class="form-control form-control-default" 
         v-model="uploadDoc.doc_expiry" >
        </div>
        <div v-if="errors.doc_expiry" class="text-danger">{{ errors.doc_expiry }}</div>
        </div>

        <div class="col-md-12 columns">
        <label for="licence-doc" class="btn btn-dark w-100" href="">Select File</label>
         <input type="file" @change="getFileName"
         hidden id="licence-doc"/>
         <div v-if="errors.document" class="text-danger">{{ errors.document }}</div>
         <div v-if="file_name && show_file_name">File Selected: <span class="text-success" v-text="file_name"></span></div>
         <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">Sorry 
          <span class="text-success">{{ file_name }}</span> cannot contain apostrophe(s).Replace apostrophes with backticks.</p>
        </div>
       <div class="col-md-12">
          <progress v-if="uploadDoc.progress" :value="uploadDoc.progress.percentage" max="100">
         {{ uploadDoc.progress.percentage }}%
         </progress>
         </div>
         </div>   

  <div class="col-md-12" v-if="message">
   <div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
   <span class="alert-icon"><i class=""></i></span>
   <span class="alert-text"> 
   <span class="text-sm">{{ message }}</span></span>
   <button type="button" class="btn-close d-flex justify-content-center align-items-center"
    data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="text-lg font-weight-bold">×</span>
    </button>
    </div>
    </div>
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" :disabled="uploadDoc.processing || file_has_apostrophe">
         <span v-if="uploadDoc.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
</Layout>
</template>


<style>
    .columns{
      margin-bottom: 1rem;
    }
    #active-checkbox{
      margin-top: -3px;
      margin-left: 3px;
    }
    .display-text-length{
      margin-left: 10rem;
      font-size: 14px;
    }
    .fa-file-pdf{
      font-size: 30em;
    }
</style>

<script src="./view_person.js"></script>

