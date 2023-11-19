<template>
<Layout>
  <Head title="View Company"/>
<div class="container-fluid">
  <Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-9 col-9">
<h6 class="mb-1">Company Info: {{ company.name }}</h6>
</div>
<div class="col-lg-3 col-3 my-auto text-end">
<div v-if="$page.props.auth.has_slowtow_admin_role" class="dropdown float-lg-end pe-4">
<a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
<i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
</a>
<ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable" style="">
<li><Link :href="`#!`" @click="show_modal=true" data-bs-toggle="modal" data-bs-target="#add-company-admin" class="dropdown-item border-radius-md">Add Company Admin</Link></li>

<li><a @click="deleteCompany(company.name)" class="dropdown-item border-radius-md text-danger" ><i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</a></li>
</ul>
</div>
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
      :column="'col-12'"
      :label="'Active Company'"
      :isChecked="company.active == '1'"
      :value="1"
      @input="assignActiveValue($event,1)" 
      />


      <TextInputComponent 
        :inputType="'text'"
        v-model="form.company_name" 
        :value="form.company_name"  
        :column="'col-6'" 
        :label="'Company Name *'" 
        :errors="errors.company_name"
        :input_id="company_name"
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

      



<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Company Type </label>
<select class="form-control form-control-default" v-model="form.company_type">
<option :value="''" disabled>Select Type</option>
<option value="Association">Association</option>
<option value="Close Corporation -CC">Close Corporation&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;-CC</option>
<option value="Individual">Individual</option>
<option value="Non-profit Organization -(NPO)">Non-profit Organization&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;-NPO</option>
<option value="Partnership">Partnership</option>
<option value="Private Company  -(Proprietary) Limited">Private Company&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;-  (Proprietary) Limited</option>
<option value="Public Company">Public Company&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;-Limited</option>
<option value="Sole Proprietor">Sole Proprietor</option>
<option value="Trust">Trust&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;-IT</option>
</select>
</div>
<div v-if="errors.company_type" class="text-danger">{{ errors.company_type }}</div>
</div>
  

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
        :inputType="'text'"
        v-model="form.reg_number" 
        :value="form.reg_number"  
        :column="'col-6'" 
        :label="'Registration Number'" 
        :errors="errors.reg_number"
        :input_id="reg_number"
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
        v-model="form.vat_number" 
        :value="form.vat_number"  
        :column="'col-6'" 
        :label="'Vat Number'" 
        :errors="errors.vat_number"
        :input_id="vat_number"
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

    



<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Website</label>
<input type="url" class="form-control form-control-default" v-model="form.website" >
<Link @click="redirectToWebsite(company.website)" href="#!" class="input-group-text text-primary" id="addon-wrapping">
<i class="material-icons-round opacity-10 fs-5">open_in_new</i></Link>
</div>
<div v-if="errors.website" class="text-danger">{{ errors.website }}</div>
</div>


<TextInputComponent 
:inputType="'text'"
v-model="form.telephone_number_2" 
:value="form.telephone_number_2"  
:column="'col-6'" 
:label="'Phone Number'" 
:errors="errors.telephone_number_2"
:input_id="telephone_number_2"
/>



<h6 class="text-center mt-5">Company Documents</h6>


<div class="col-md-6 columns">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
      <div class="me-3" v-if="company_doc !== ''">
      <a v-if="company_doc" :href="`${$page.props.blob_file_path}${company_doc.document_file}`" target="_blank">
       <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
      </a>    
      </div>
  
      <div class="d-flex align-items-start flex-column" style="margin-left: -1rem;">
        <h6 class="mb-0 text-sm" >Company Documents</h6>
        <p v-if="company_doc" class="mb-0 text-xs">Name: {{ company_doc.document_name }}</p>
        <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
      </div>
  
      <button  v-if="company_doc" @click="deleteDocument(company_doc.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
      <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
      </button>
  
      <button v-else @click="getDocType('Company-Document')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
      <i class="fa fa-upload" aria-hidden="true"></i>
      </button>
    </li>
  
      <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
      <div class="me-3" v-if="cipc_cert">
      <a v-if="cipc_cert" :href="`${$page.props.blob_file_path}${cipc_cert.document_file}`" target="_blank">
      <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
      </a>    
      </div>
  
      <div class="d-flex align-items-start flex-column justify-content-center">
        <h6 class="mb-0 text-sm">CIPC Certificate</h6>
         <p v-if="cipc_cert" class="mb-0 text-xs">Name: {{ cipc_cert.document_name }}</p>
         <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
      </div>
      <button  v-if="cipc_cert" @click="deleteDocument(cipc_cert.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
      <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
      </button>
  
      <button v-else @click="getDocType('CIPC-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
      <i class="fa fa-upload" aria-hidden="true"></i>
      </button>
    </li>
  
      <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
      <div class="me-3" v-if="bee_cert">
      <a v-if="bee_cert" :href="`${$page.props.blob_file_path}${bee_cert.document_file}`" target="_blank">
      <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
      </a>    
      </div>
      <div class="d-flex align-items-start flex-column justify-content-center">
        <h6 class="mb-0 text-sm">BEE Certificate</h6>
        <p v-if="bee_cert" class="mb-0 text-xs">Name: {{ bee_cert.document_name }}</p>
        <p v-if="bee_cert" class="mb-0 text-xs fst-italic">Expiry Date: {{ new Date(bee_cert.expiry_date).toLocaleString().split(',')[0] }}</p>
        <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
      </div>
  
      <button  v-if="bee_cert" @click="deleteDocument(bee_cert.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
      <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
      </button>
     
      <button v-else @click="getDocType('BEE-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
      <i class="fa fa-upload" aria-hidden="true"></i>
      </button>
    </li>
    
    </div>

<div class="col-md-6 columns">
<ul class="list-group">

 <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="lta_cert">
    <a v-if="lta_cert" :href="`${$page.props.blob_file_path}${lta_cert.document_file}`" target="_blank">
    <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">LTA Certificate</h6>
      <p v-if="lta_cert" class="mb-0 text-xs">Name: {{ lta_cert.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="lta_cert" @click="deleteDocument(lta_cert.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>
    <button v-else @click="getDocType('LTA-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>


  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="contrib_cert">
    <a v-if="contrib_cert" :href="`${$page.props.blob_file_path}${contrib_cert.document_file}`" target="_blank">
    <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Contribution Certificate</h6>
      <p v-if="contrib_cert" class="mb-0 text-xs">Name: {{ contrib_cert.document_name }}</p>
      <p v-if="contrib_cert" class="mb-0 text-xs fst-italic">Expiry Date: {{ new Date(contrib_cert.expiry_date).toLocaleString().split(',')[0] }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>

    <button  v-if="contrib_cert" @click="deleteDocument(contrib_cert.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Contribution-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>


  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="sars_cert">
    <a v-if="sars_cert" :href="`${$page.props.blob_file_path}${sars_cert.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">SARS Certificate</h6>
      <p v-if="sars_cert" class="mb-0 text-xs">Name: {{ sars_cert.document_name }}</p>
      <p v-if="sars_cert" class="mb-0 text-xs fst-italic">Expiry Date: {{ new Date(sars_cert.expiry_date).toLocaleString().split(',')[0] }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>

    <button  v-if="sars_cert" @click="deleteDocument(sars_cert.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('SARS-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>

</ul>
</div>

</div>

</div>
</div>
<hr class="vertical dark" />
</div>

<div class="col-4 col-md-4 col-xl-4" style="margin-top: 1rem;">
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
  <select class="form-control form-control-default" v-model="form.postal_province" >
    <option :value="''" disabled selected >Select Province</option>
    <option v-for='province in computedProvinces' :key="province" :value=province> {{ province }}</option>
  </select>
  </div>
  <div v-if="errors.postal_province" class="text-danger">{{ errors.postal_province }}</div>
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
<input style="margin-top: -9px; margin-right: 3px;" @change="copyBusinessAddress" 
 type="checkbox" 
 :checked="form.postal_address == form.business_address &&
        form.postal_address2 == form.business_address2 &&
        form.postal_address3 == form.business_address3" id="same-address"/>
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
  <select class="form-control form-control-default" v-model="form.business_province" >
    <option :value="''" disabled selected >Select Province</option>
    <option v-for='province in computedProvinces' :key="province" :value=province> {{ province }}</option>
  </select>
  </div>
  <div v-if="errors.business_province" class="text-danger">{{ errors.business_province }}</div>
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


<div v-if="$page.props.auth.has_slowtow_admin_role">
<button type="submit" class="btn btn-secondary ms-2"  :disabled="form.processing" :style="{float: 'right'}">
  <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
 Save</button>
</div>

</form>

<hr>

</div>
</div>

<div class="row">
<div class="col-sm-12 col-lg-4"></div>
<div class="col-sm-12 col-lg-4">
<h6 class="text-center">Licences Linked To : {{ company.name ? company.name : '' }}</h6>
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
  :modelType="ViewCompany"
  />
<hr>
</div>

<div class="row">
<div class="col-12 col-lg-4"></div>
<div class="col-12 col-lg-4">
<h6 class="text-center">People Linked To : {{ company.name }}</h6>
</div>
<div class="col-sm-12 col-lg-4 text-end">
<button @click="show_modal = true" data-bs-toggle="modal" data-bs-target="#add-people" type="button" class="btn btn-sm btn-secondary ms-2 float-end justify-content-center">Add</button></div>

<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
  <tr>
    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
    Full Name
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
  <tr v-if="company.people" v-for="person in company.people" :key="person.id">
    <td>
      <div class="d-flex px-2">
    
    <div class="d-flex flex-column">
      <Link :href="`/view-person/${person.slug}`"><h6 class="mb-0 text-sm">{{ person.full_name ? person.full_name : '' }}</h6></Link>                          
    </div>
      </div>
    </td>
    <td class="mx-8">
    <div class="input-group input-group-outline null mx-5 is-filled">
    <div class="mb-3 mx-10">
  <input :value="person.pivot.position" @input="getPositionValue($event.target.value)"
  name="position" class="" id="formFileSm" type="text" 
  style="border: none;background-color: transparent;resize: none;outline: none;" />
</div>
</div>
</td>
    <td class="text-end ">
    <div class="d-flex float-end ">
    <a @click="updatePerson(person.pivot.id)" type="button" 
    class="mx-2 ms-2 btn btn-sm btn-secondary justify-content-center">Update</a>
    <a @click="unlinkPerson(person.full_name, person.pivot.id )" type="button" 
    class=" ms-2 btn btn-sm btn-danger justify-content-center">Unlink</a>
    </div>
    </td>
    
  </tr> 
  <tr v-else>
    <td style="text-align: right;"> No people found.</td>
  </tr>
</tbody>
</table>
</div>

<hr>

</div>

<Task :tasks="tasks" :model_id="company.id" :success="success" :error="error" :errors="errors" :model_type="'Company'"/>

</div>
</div>

<div v-if="show_modal" class="modal" id="company-docs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="submitDocuments">
      <input type="hidden" v-model="documentsForm.doc_type">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns" v-if="documentsForm.doc_type == 'CIPC-Certificate' 
                                          || documentsForm.doc_type == 'BEE-Certificate'
                                          || documentsForm.doc_type == 'SARS-Certificate'">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Expiry Date</label>
        <input type="date" required class="form-control form-control-default" 
         v-model="documentsForm.expiry_date" >
        </div>
        <div v-if="errors.expiry_date" class="text-danger">{{ errors.expiry_date }}</div>
        </div>

        <div class="col-md-12 columns">
        <label for="licence-doc" class="btn btn-dark w-100" href="#!">Select File</label>
         <input type="file" @change="getFileName"
         hidden id="licence-doc" accept=".pdf"/>
         <div v-if="errors.document" class="text-danger">{{ errors.document }}</div>
         <div v-if="file_name && show_file_name">File Selected: <span class="text-success" v-text="file_name"></span></div>
         <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">Sorry <span class="text-success">{{ file_name }}</span> cannot contain apostrophe(s).Replace apostrophes with backtick.</p>  
        </div>
       <div class="col-md-12">
          <progress v-if="documentsForm.progress" :value="documentsForm.progress.percentage" max="100">
         {{ documentsForm.progress.percentage }}%
         </progress>
         </div>
         </div>   
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" :disabled="documentsForm.processing || file_has_apostrophe">
         <span v-if="documentsForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>


<div v-if="show_modal" class="modal" id="add-people" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add People </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="submitPeople">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns">
  <div class="input-group input-group-outline null is-filled">
     <Multiselect
        v-model="addPeopleForm.people"
        mode="tags"
        placeholder="Search People...."
        :options="people_options"
        :searchable="true"
      />
    </div>
 <div v-if="errors.people" class="text-danger">{{ errors.people }}</div>
</div>
</div>  
 </div>
  
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
  <button type="submit" class="btn btn-primary" :disabled="addPeopleForm.processing">
    <span v-if="addPeopleForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    Save</button>
</div>
</form>
    </div>
  </div>
</div>

<div v-if="show_modal" class="modal" id="add-company-admin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Add <span class="text-success">{{ company.name }}</span> Admin</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="addCompanyUser">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns">
          <div class="input-group input-group-outline null is-filled">
            <Multiselect
                v-model="addCompanyUserForm.person_id"
                placeholder="Search Person...."
                :options="people_options"
                :searchable="true"
              />
            </div>
        <div v-if="errors.person_id" class="text-danger">{{ errors.person_id }}</div>
        </div>
         </div>   
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary" :disabled="addCompanyUserForm.processing">
         <span v-if="addCompanyUserForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>


</Layout>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<style>
  #active-checkbox{
    margin-top: 3px;
    margin-left: 3px;
  }
  .columns{
      margin-bottom: 1rem;
    }

</style>

<script src="./view_company.js"></script>

