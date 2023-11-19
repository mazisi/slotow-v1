
  <template>
  <Layout>
    <Head title="Process New Application" />
  <div class="container-fluid">
      <Banner/>
      <div class="card card-body mx-3 mx-md-4 mt-n6">
        <div class="row">
    <div class="col-lg-12 col-12">
     <h6>Process New Application for: <Link :href="`/view-licence/?slug=${licence.slug}`" class="text-success">
      {{ licence.trading_name ? licence.trading_name : '' }}</Link></h6>
     <p class="text-sm mb-0">Current Stage: 
      <span class="font-weight-bold ms-1" v-if="licence.status === '1'">Client Quoted</span>
     <span v-else-if="licence.status === '2'" class="font-weight-bold ms-1">Deposit Invoice</span>
     <span v-else-if="licence.status === '3'" class="font-weight-bold ms-1">Deposit Paid</span>
     <span v-else-if="licence.status === '5'" class="font-weight-bold ms-1">Prepare New Application</span>
     <span v-else-if="licence.status === '4'" class="font-weight-bold ms-1">Payment to the Liquor Board</span>
     <span v-else-if="licence.status === '6'" class="font-weight-bold ms-1">Scanned Application</span>
     <span v-else-if="licence.status === '7'" class="font-weight-bold ms-1">Application Lodged</span>
     <span v-else-if="licence.status === '8'" class="font-weight-bold ms-1">Initial Inspection</span>
     <span v-else-if="licence.status === '9'" class="font-weight-bold ms-1">Liquor Board Requests</span>
     <span v-else-if="licence.status === '10'" class="font-weight-bold ms-1">Final Inspection</span>
     <span v-else-if="licence.status === '11'" class="font-weight-bold ms-1">Activation Fee Requested</span>
     <span v-else-if="licence.status === '12'" class="font-weight-bold ms-1">Client Finalisation Invoice</span>
     <span v-else-if="licence.status === '13'" class="font-weight-bold ms-1">Finalisation Paid</span>
     <span v-else-if="licence.status === '14'" class="font-weight-bold ms-1">Activation Fee Paid</span>
     <span v-else-if="licence.status === '15'" class="font-weight-bold ms-1">Licence Issued</span>
     <span v-else-if="licence.status === '16'" class="font-weight-bold ms-1">Licence Delivered</span>
   </p>
  </div>
    <div class="col-lg-6 col-5 my-auto text-end">
      <!-- <button v-if="$page.props.auth.has_slowtow_admin_role"
       @click="deleteRegistration" class="dropdown-item border-radius-md text-danger" >
       <i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</button>     -->
    </div>
  </div>
  
  <div class="row">
          <div class="mt-3 row">
            <div class="col-12 col-md-12 col-xl-12 position-relative">
              <div class="card card-plain h-100">
                <div class="p-3 card-body">
  <form @submit.prevent="updateRegistration">
  <div class="row">
  <div class="col-md-12 columns">
  <div class=" form-switch d-flex ps-0 ms-0  is-filled">
  <input id="client-quoted" class="active-checkbox" type="checkbox" 
  :checked="licence.status >= '1'"
  @input="pushData($event,1)" value="1">
  <label for="client-quoted" class="form-check-label text-body text-truncate status-heading">Client Quoted</label>
  </div>
  </div>  
  
  <ul class="list-group">
      <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
        <div class="avatar me-3" v-if="client_quoted">
        <a :href="`${$page.props.blob_file_path}${client_quoted.document_file}`" target="_blank">
        <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
        </a>
        </div>
    
       <div class="d-flex align-items-start flex-column justify-content-center">
          <h6 v-if="!client_quoted" class="mb-0 text-sm">Document</h6>
          <h6 v-else-if="client_quoted" class="mb-0 text-sm limit-file-name">{{ client_quoted.document_name }}</h6>
        </div>
    
        <a v-if="client_quoted" @click="deleteDocument(client_quoted.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
        <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
        </a>
        <a v-else @click="getDocType(1,'Client Quoted')" data-bs-toggle="modal" data-bs-target="#documents" 
        class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
        <i class="fa fa-upload h5" aria-hidden="true"></i>
        </a>
      </li>
    </ul>
  <hr>
  
  
  <div class="col-md-12 columns">
    <div class=" form-switch d-flex ps-0 ms-0  is-filled">
    <input class="active-checkbox" id="client-invoiced" type="checkbox"
    @input="pushData($event,2)" 
    :checked="licence.status >= 2"
    value="2">
    <label for="client-invoiced" class="form-check-label text-body text-truncate status-heading">Deposit Invoice</label>
    </div>
    </div>
    <ul class="list-group">
      <li class="px-0 border-0 list-group-item d-flex align-items-center">
        <div class="avatar me-3" v-if="client_invoiced !== null">
        <a :href="`${$page.props.blob_file_path}${client_invoiced.document_file}`" target="_blank">
        <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
        </a>
        </div>
    
       <div class="d-flex align-items-start flex-column justify-content-center">
        <h6 v-if="!client_invoiced" class="mb-0 text-sm">Document</h6>
          <h6 v-else-if="client_invoiced" class="mb-0 text-sm limit-file-name">{{ client_invoiced.document_name }}</h6>
        </div>
    
        <a v-if="client_invoiced !== null" @click="deleteDocument(client_invoiced.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" 
        href="javascript:;">
        <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
        </a>
        <a v-if="client_invoiced == null" @click="getDocType(2,'Client Invoiced')" data-bs-toggle="modal" data-bs-target="#documents" 
        class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
        <i class="fa fa-upload h5 upload-icon" aria-hidden="true"></i>
        </a>
      </li>
    </ul>
    <hr>
  <div class="col-md-5 columns">
  <div class="form-switch d-flex ps-0 ms-0  is-filled">
  <input class="active-checkbox" id="deposit-paid"  type="checkbox"
  @input="pushData($event,3)" value="3"
  :checked="licence.status >= 3">
  <label for="deposit-paid" class="form-check-label text-body text-truncate status-heading">Deposit Paid</label>
  </div>
  </div>
  <div class="col-md-1 columns"></div>
  
  <template v-if="licence.deposit_paid_at == null">   
  <div class="col-md-4 columns mb-4">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.deposit_paid_at">
     </div>
   <div v-if="errors.deposit_paid_at" class="text-danger">{{ errors.deposit_paid_at }}</div>
   </div> 

   <div class="col-md-1 columns">
     <button v-if="licence.deposit_paid_at == null" @click="updateRegistrationDate" 
     type="button" class="btn btn-sm btn-secondary">Save</button>
    </div>
   </template>
   
   <template v-else>
    <div class="col-md-4 columns mb-4">
      <div class="input-group input-group-outline null is-filled ">
      <label class="form-label">Date</label>
      <input type="date" class="form-control form-control-default" v-model="form.deposit_paid_at">
       </div>
     <div v-if="errors.deposit_paid_at" class="text-danger">{{ errors.deposit_paid_at }}</div>
     </div> 
  
     <div class="col-md-1 columns">
       <button v-if="$page.props.auth.has_slowtow_admin_role" @click="updateRegistrationDate" 
       type="button" 
       class="btn btn-sm btn-secondary">Save</button>
      </div>
   </template>
  
  <hr>


  <div class="col-md-5 columns">
    <div class=" form-switch d-flex ps-0 ms-0  is-filled">
    <input class="active-checkbox" id="payment" type="checkbox" @input="pushData($event,4)"
    :checked="licence.status >= 4" value="4">
    <label for="payment" class="form-check-label text-body text-truncate status-heading">Payment To The Liquor Board</label>
    </div>
    </div> 
    
    <div class="col-md-1 columns"></div>
     
       <template v-if="licence.liquor_board_at == null">   
        <div class="col-md-4 columns mb-4">
          <div class="input-group input-group-outline null is-filled ">
          <label class="form-label">Date</label>
          <input type="date" class="form-control form-control-default" v-model="form.liquor_board_at">
           </div>
         <div v-if="errors.liquor_board_at" class="text-danger">{{ errors.liquor_board_at }}</div>
         </div> 
         <div class="col-md-1 columns">
          <button v-if="licence.liquor_board_at == null" 
          
          @click="updateRegistrationDate" type="button" class="btn btn-sm btn-secondary">Save</button>
         </div>
         </template>
         
         <template v-else>
            <div class="col-md-4 columns mb-4">
              <div class="input-group input-group-outline null is-filled ">
              <label class="form-label">Date</label>
              <input type="date" class="form-control form-control-default" v-model="form.liquor_board_at">
              </div>
            <div v-if="errors.liquor_board_at" class="text-danger">{{ errors.liquor_board_at }}</div>
            </div> 
            <div class="col-md-1 columns">
              <button v-if="$page.props.auth.has_slowtow_admin_role" 
              @click="updateRegistrationDate" 
               type="button" class="btn btn-sm btn-secondary">Save</button>
            </div>        
           
         </template> 
    
    
    <ul class="list-group">
      <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
        <div class="avatar me-3" v-if="payment_to_liqour_board">
        <a :href="`${$page.props.blob_file_path}${payment_to_liqour_board.document_file}`" target="_blank">
        <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
        </a>
        </div>
    
       <div class="d-flex align-items-start flex-column justify-content-center">
        <h6 v-if="!payment_to_liqour_board" class="mb-0 text-sm">Document</h6>
        <h6 v-else-if="payment_to_liqour_board" class="mb-0 text-sm limit-file-name">{{ payment_to_liqour_board.document_name }}</h6>
        </div>
    
        <a v-if="payment_to_liqour_board" @click="deleteDocument(payment_to_liqour_board.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
        <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
        </a>
        <a v-else @click="getDocType(4,'Payment To The Liquor Board')" data-bs-toggle="modal" data-bs-target="#documents" 
        class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
        <i class="fa fa-upload h5" aria-hidden="true"></i>
        </a>
      </li>
    </ul>
    <hr>

    <div class="col-md-12 columns">
      <div class=" form-switch d-flex ps-0 ms-0  is-filled">
      <input class="active-checkbox" id="prepare-new-app"  type="checkbox"
      @input="pushData($event,5)" 
      :checked="licence.status >= 5"
      value="5">
      <label for="prepare-new-app" class="form-check-label text-body text-truncate status-heading">Prepare New Application</label>
      </div>
      </div>

  <div class="col-md-6">
    <div class="row">
      <div class="col-md-7" >
    <button type="button" class="btn btn-outline-success w-95"> GLB Application Forms</button>
   </div>
    <div class="col-md-1 d-flex">
      <i v-if="gba_application_form === null" 
      @click="getDocType(5,'GLB Application Forms',1)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${gba_application_form.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="gba_application_form !== null" @click="deleteDocument(gba_application_form.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>
      
    </div>
  </div>


    <div class="row"><div class="col-md-7" >
      <button type="button" class="btn btn-outline-success w-95">Proof of Payment</button>
    </div>
    <a v-if="payment_to_liqour_board !== null" :href="`${$page.props.blob_file_path}${payment_to_liqour_board.document_file}`" target="_blank" class="col-md-1">
      <i class="fa fa-link h5 upload-icon col-md-3 disabled"></i>
    </a>
  </div>


    <div class="row">
      <div class="col-md-7">
        <button type="button" class="btn btn-outline-success w-95">Application Forms</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="application_forms === null" 
      @click="getDocType(5,'Application Forms',3)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${application_forms.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="application_forms !== null" @click="deleteDocument(application_forms.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>
      
    </div>
    </div>


    <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Company Documents</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="company_docs === null" 
      @click="getDocType(5,'Company Documents',4)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${company_docs.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="company_docs !== null" @click="deleteDocument(company_docs.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>
      
    </div>
    </div>

     <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">CIPC Documents</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="cipc_docs === null" 
      @click="getDocType(5,'CIPC Documents',5)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${cipc_docs.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="cipc_docs !== null" @click="deleteDocument(cipc_docs.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>
      
    </div>
    </div>

     <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">ID Documents</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="id_docs === null" 
      @click="getDocType(5,'ID Documents',6)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${id_docs.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="id_docs !== null" @click="deleteDocument(id_docs.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

     <div class="row">
      <div class="col-md-7">
        <button type="button" class="btn btn-outline-success w-95">Police Clearance</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="!police_clearance" 
      @click="getDocType(5,'Police Clearance',7)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${police_clearance.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="police_clearance !== null" @click="deleteDocument(police_clearance.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

     <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Tax Clearance</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="tax_clearance === null" 
      @click="getDocType(5,'Tax Clearance',8)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${tax_clearance.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="tax_clearance !== null" @click="deleteDocument(tax_clearance.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

     <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">LTA Certificate</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="lta_certificate === null" 
      @click="getDocType(5,'LTA Certificate',9)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${lta_certificate.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="lta_certificate !== null" @click="deleteDocument(lta_certificate.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

     <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Shareholding Info</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="shareholding_info === null" 
      @click="getDocType(5,'Shareholding Info',10)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${shareholding_info.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="shareholding_info !== null" @click="deleteDocument(shareholding_info.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

     <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Financial Interests</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="financial_interests === null" 
      @click="getDocType(5,'Financial Interests',11)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${financial_interests.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="financial_interests !== null" @click="deleteDocument(financial_interests.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

     <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">500m Affidavit</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="_500m_affidavict === null" 
      @click="getDocType(5,'500m Affidavit',12)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else  :href="`${$page.props.blob_file_path}${_500m_affidavict.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="_500m_affidavict !== null" @click="deleteDocument(_500m_affidavict.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

     <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Government Gazette Adverts</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="government_gazette === null" 
      @click="getDocType(5,'Government Gazette Adverts',13)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else  :href="`${$page.props.blob_file_path}${government_gazette.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="government_gazette !== null" @click="deleteDocument(government_gazette.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

     <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Advert Affidavit</button>      
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="advert_affidavict === null" 
      @click="getDocType(5,'Advert Affidavit',14)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${advert_affidavict.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="advert_affidavict !== null" @click="deleteDocument(advert_affidavict.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Proof of Occupation</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="proof_of_occupation === null" 
      @click="getDocType(5,'Proof of Occupation',15)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${proof_of_occupation.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="proof_of_occupation !== null" @click="deleteDocument(proof_of_occupation.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

    <div class="row"><div class="col-md-7" >
      <button type="button" class="btn btn-outline-success w-95">Representations</button>
    </div>
    <div class="col-md-1 d-flex">
      <i v-if="representations === null" 
      @click="getDocType(5,'Representations',16)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${representations.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="representations !== null" @click="deleteDocument(representations.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
  </div>

    <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Menu-If applicable</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="menu === null" 
      @click="getDocType(5,'Menu',17)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${menu.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="menu !== null" @click="deleteDocument(menu.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

    <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Photographs</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="photographs === null" 
      @click="getDocType(5,'Photographs',18)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${photographs.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="photographs !== null" @click="deleteDocument(photographs.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

    <div class="row" v-if="licence.province === 'Mpumalanga'">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Municipal Consent Ltr</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="consent_letter === null" 
      @click="getDocType(5,'Municipal Consent Ltr',19)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${consent_letter.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="consent_letter !== null" @click="deleteDocument(consent_letter.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

    <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Zoning Certificate</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="zoning_certificate === null" 
      @click="getDocType(5,'Zoning Certificate',20)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${zoning_certificate.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="zoning_certificate !== null" @click="deleteDocument(zoning_certificate.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

    <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Local Authority Letter</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="!local_authority" 
        @click="getDocType(5,'Local Authority Letter',21)" data-bs-toggle="modal" data-bs-target="#documents" 
          class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${local_authority.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="local_authority" @click="deleteDocument(local_authority.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

    <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Mapbook Plans</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="mapbook_plans === null" 
      @click="getDocType(5,'Mapbook Plans',22)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${mapbook_plans.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="mapbook_plans !== null" @click="deleteDocument(mapbook_plans.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

    <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Google Map Plans</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="google_map_plans === null" 
      @click="getDocType(5,'Google Map Plans',23)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${google_map_plans.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="google_map_plans !== null" @click="deleteDocument(google_map_plans.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

    <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Description</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="description === null" 
      @click="getDocType(5,'Description',24)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${description.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="description !== null" @click="deleteDocument(description.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

    <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Site Plans</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="site_plans === null" 
      @click="getDocType(5,'Site Plans',25)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${site_plans.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="site_plans !== null" @click="deleteDocument(site_plans.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

    <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Fully Dimensioned Plans</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="dimensional_plans === null" 
      @click="getDocType(5,'Full Dimensioned Plans',26)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${dimensional_plans.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="dimensional_plans !== null" @click="deleteDocument(dimensional_plans.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

    <div class="row">
      <div class="col-md-7" >
        <button type="button" class="btn btn-outline-success w-95">Advert Photographs</button>
      </div>
      <div class="col-md-1 d-flex">
      <i v-if="advert_photographs === null" 
      @click="getDocType(5,'Advert Photographs',27)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${advert_photographs.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="advert_photographs !== null" @click="deleteDocument(advert_photographs.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
    </div>

    <div class="row">
      <div class="col-md-7" >
      <button type="button" class="btn btn-outline-success w-95">Newspaper Adverts</button>
    </div>
    <div class="col-md-1 d-flex">
      <i v-if="newspaper_adverts === null" 
      @click="getDocType(5,'Newspaper Adverts',28)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="fa fa-upload h5 upload-icon" ></i>
      <a v-else :href="`${$page.props.blob_file_path}${newspaper_adverts.document_file}`" target="_blank">
      <i class="fa fa-file-pdf h5 upload-icon"></i></a>
      <i v-if="newspaper_adverts !== null" @click="deleteDocument(newspaper_adverts.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>      
    </div>
  </div> 
  <br><br>
   <div class="d-flex ">
    <button v-if="gba_application_form !== null      
      && application_forms !== null
      && company_docs !== null
      && cipc_docs !== null
      && id_docs !== null
      && police_clearance !== null
      && tax_clearance !== null
      && local_authority !==null
      && lta_certificate !== null
      && shareholding_info !== null
      && financial_interests !== null
      && _500m_affidavict !== null
      && government_gazette !== null
      && advert_affidavict !== null
      && proof_of_occupation !== null
      && representations !== null
      && payment_to_liqour_board !== null
      && photographs !== null
      // && consent_letter !== null
      && zoning_certificate !== null
      && mapbook_plans !== null
      && google_map_plans !== null
      && description !== null
      && site_plans !== null
      && dimensional_plans !== null
      && advert_photographs !== null
      && newspaper_adverts !== null"
      @click="mergeDocs"
       type="button" class="btn btn-success w-65">Compile Application</button>
       <button v-else type="button" class="btn btn-success w-65" disabled>Compile Application</button>
       <a v-if="licence.merged_document !== null" :href="`/storage/app/public/${licence.merged_document}`" target="_blank" class="btn btn-success w-20 mx-2" >View</a>  

      </div>

</div> 
      <hr>
  
  
  <div class="col-md-12 columns">
  <div class=" form-switch d-flex ps-0 ms-0  is-filled">
  <input class="active-checkbox" id="issued" type="checkbox" 
  @input="pushData($event,6)" :checked="licence.status >= 6" value="6">
  <label for="issued" class="form-check-label text-body text-truncate status-heading"> Scanned Application  </label>
  </div>
  </div> 
  
  
 
  <div class="col-md-6">
  <ul class="list-group">
    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
      <div class="avatar " v-if="scanned_application !== null">
      <a :href="`${$page.props.blob_file_path}${scanned_application.document_file}`" target="_blank">
      <i class="fa fa-file-pdf text-lg text-danger h5" aria-hidden="true"></i>
      </a>
      </div>
      <div class="d-flex align-items-start flex-column justify-content-center">
        <h6 v-if="!scanned_application" class="mb-0 text-sm">Document</h6>
        <h6 v-else-if="scanned_application" class="mb-0 text-sm limit-file-name">{{ scanned_application.document_name }}</h6>
      </div>
      <a v-if="scanned_application !== null" @click="deleteDocument(scanned_application.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
      <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
      </a>
      <a v-else @click="getDocType(6,'Scanned Application')" data-bs-toggle="modal" data-bs-target="#documents" 
      class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;" >
      <i class="fa fa-upload h5" aria-hidden="true"></i>
      </a>
    </li>
  </ul>
  </div>
  <hr>
  


  <div class="col-md-6 columns">
    <div class=" form-switch d-flex ps-0 ms-0  is-filled">
    <input class="active-checkbox" id="application-logded" type="checkbox" 
    @input="pushData($event,7)" :checked="licence.status >= 7" value="7">
    <label for="application-logded" class="form-check-label text-body text-truncate status-heading"> Application Lodged (Proof of Lodgement)  </label>
    </div>
    </div>
      
    
     <template v-if="licence.application_lodged_at == null">   
      <div class="col-md-4 columns mb-4">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Date</label>
        <input type="date" class="form-control form-control-default" v-model="form.application_lodged_at">
         </div>
       <div v-if="errors.application_lodged_at" class="text-danger">{{ errors.application_lodged_at }}</div>
    </div>

      <div class="col-md-1 columns">
        <button v-if="licence.application_lodged_at == null" 
        
        @click="updateRegistrationDate" type="button" class="btn btn-sm btn-secondary">Save</button>
      </div>
       </template>
       
       <template v-else>
        <div class="col-md-4 columns mb-4">
          <div class="input-group input-group-outline null is-filled ">
          <label class="form-label">Date</label>
          <input type="date" class="form-control form-control-default" v-model="form.application_lodged_at">
           </div>
         <div v-if="errors.application_lodged_at" class="text-danger">{{ errors.application_lodged_at }}</div>
      </div>
  
        <div class="col-md-1 columns">
          <button v-if="$page.props.auth.has_slowtow_admin_role" 
           
          @click="updateRegistrationDate" type="button" class="btn btn-sm btn-secondary">Save</button>
        </div>      
         
       </template> 


      <ul class="list-group">
        <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
          <div class="avatar me-3" v-if="application_logded !== null">
          <a :href="`${$page.props.blob_file_path}${application_logded.document_file}`" target="_blank">
          <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
          </a>
          </div>
      
         <div class=" d-flex align-items-start flex-column justify-content-center">
          <h6 v-if="!application_logded" class="mb-0 text-sm">Document</h6>
          <h6 v-else-if="application_logded" class="mb-0 text-sm limit-file-name">{{ application_logded.document_name }}</h6>
          </div>
      
          <a v-if="application_logded !== null" @click="deleteDocument(application_logded.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
          <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
          </a>
          <a v-else @click="getDocType(7,'Application Lodged')" data-bs-toggle="modal" data-bs-target="#documents" 
          class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
          <i class="fa fa-upload h5 " aria-hidden="true"></i>
          </a>
        </li>
      </ul> 
<hr/>

       <div class="col-md-6 columns">
        <div class=" form-switch d-flex ps-0 ms-0  is-filled">
        <input class="active-checkbox" id="initial" type="checkbox"
        @input="pushData($event,8)" :checked="licence.status >= 8" value="8">
        <label for="initial" class="form-check-label text-body text-truncate status-heading"> Initial Inspection</label>
        </div>  
        </div>
  
        
        
         <template v-if="licence.initial_inspection_at == null">   
          <div class="col-md-4 columns mb-4">
            <div class="input-group input-group-outline null is-filled ">
            <label class="form-label">Date</label>
          <input type="date" class="form-control form-control-default" v-model="form.initial_inspection_at">
            </div>
          <div v-if="errors.initial_inspection_at" class="text-danger">{{ errors.initial_inspection_at }}</div>
        </div>
        <div class="col-md-1 columns">
          <button v-if="licence.initial_inspection_at == null" 
         
          @click="updateRegistrationDate" type="button" class="btn btn-sm btn-secondary">Save</button>
         </div>
         </template>
           
           <template v-else>
            <div class="col-md-4 columns mb-4">
              <div class="input-group input-group-outline null is-filled ">
              <label class="form-label">Date</label>
            <input type="date" class="form-control form-control-default" v-model="form.initial_inspection_at">
              </div>
            <div v-if="errors.initial_inspection_at" class="text-danger">{{ errors.initial_inspection_at }}</div>
          </div>
          <div class="col-md-1 columns">
            <button v-if="$page.props.auth.has_slowtow_admin_role" 
            
            @click="updateRegistrationDate" type="button" class="btn btn-sm btn-secondary">Save</button>
           </div>    
          </template>  

        <ul class="list-group">
          <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
            
            <div class="avatar me-3" v-if="initial_inspection_doc !== null">
            <a :href="`${$page.props.blob_file_path}${initial_inspection_doc.document_file}`" target="_blank">
            <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
            </a>
            </div>
        
           <div class="d-flex align-items-start flex-column justify-content-center">
              <h6 class="mb-0 text-sm">Document</h6>
              
                <p v-if="initial_inspection_doc !== null" class="mb-0 text-xs limit-file-name">
                  {{ initial_inspection_doc.document_name }}
                </p>
           
              
            </div>
        
            <a v-if="initial_inspection_doc !== null" @click="deleteDocument(initial_inspection_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
            <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
            </a>
            <a v-else @click="getDocType(8,'Initial Inspection')" data-bs-toggle="modal" data-bs-target="#documents" 
            class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
            <i class="fa fa-upload h5" aria-hidden="true"></i>
            </a>
          </li>
        </ul>  

          <hr/>

  
  <div class="col-md-6 columns">
  <div class=" form-switch d-flex ps-0 ms-0  is-filled">
  <input class="active-checkbox" id="final-inspection" type="checkbox"
  @input="pushData($event,10)" :checked="licence.status >= 10" value="10">
  <label for="final-inspection" class="form-check-label text-body text-truncate status-heading"> Final Inspection</label>
  </div>
  </div>

  <template v-if="licence.final_inspection_at == null"> 
       
  <div class="col-md-4 columns mb-4">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.final_inspection_at">
     </div>
   <div v-if="errors.	final_inspection_at" class="text-danger">{{ errors.final_inspection_at }}</div>
</div>
<div class="col-md-1 columns">
  <button v-if="licence.final_inspection_at == null" 
  @click="updateRegistrationDate" 
   type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>
   
   </template>
           
  <template v-else>      
  <div class="col-md-4 columns mb-4">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.final_inspection_at">
     </div>
   <div v-if="errors.	final_inspection_at" class="text-danger">{{ errors.final_inspection_at }}</div>
</div>
<div class="col-md-1 columns">
  <button v-if="$page.props.auth.has_slowtow_admin_role" 
  @click="updateRegistrationDate" 
   
  type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>

   
 </template>  


  <ul class="list-group">
    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
      <div class="avatar me-3" v-if="final_inspection_doc !== null">
      <a :href="`${$page.props.blob_file_path}${final_inspection_doc.document_file}`" target="_blank">
      <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
      </a>
      </div>
  
     <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 v-if="!final_inspection_doc" class="mb-0 text-sm">Document</h6>
      <h6 v-else-if="final_inspection_doc" class="mb-0 text-sm limit-file-name">{{ final_inspection_doc.document_name }}</h6>
      </div>
  
      <a v-if="final_inspection_doc !== null" @click="deleteDocument(final_inspection_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
      <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
      </a>
      <a v-else @click="getDocType(10,'Final Inspection')" data-bs-toggle="modal" data-bs-target="#documents" 
      class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
      <i class="fa fa-upload h5" aria-hidden="true"></i>
      </a>
    </li>
  </ul> 
  <hr/>




  <div class="col-md-6 columns">
    <div class=" form-switch d-flex ps-0 ms-0  is-filled">
    <input class="active-checkbox" id="activation-fee" type="checkbox"
    @input="pushData($event,11)" :checked="licence.status >= 11" value="11">
    <label for="activation-fee" class="form-check-label text-body text-truncate status-heading"> Activation Fee Requested </label>
    </div>
    </div>
    
  
    

    <template v-if="licence.activation_fee_requested_at == null"> 
       
      <div class="col-md-4 columns mb-4">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.activation_fee_requested_at">
      </div>
    <div v-if="errors.activation_fee_requested_at" class="text-danger">
      {{ errors.activation_fee_requested_at }}</div>
    </div>
    <div class="col-md-1 columns">
    <button v-if="licence.activation_fee_requested_at == null" 
    @click="updateRegistrationDate" 
    type="button" class="btn btn-sm btn-secondary">Save</button>
    </div>
 </template>
               
 <template v-else>      
        <div class="col-md-4 columns mb-4">
          <div class="input-group input-group-outline null is-filled ">
          <label class="form-label">Date</label>
      <input type="date" class="form-control form-control-default" v-model="form.activation_fee_requested_at">
        </div>
      <div v-if="errors.activation_fee_requested_at" class="text-danger">
        {{ errors.activation_fee_requested_at }}</div>
      </div>
   
      <div class="col-md-1 columns">
      <button v-if="$page.props.auth.has_slowtow_admin_role" 
      @click="updateRegistrationDate" 
      type="button" class="btn btn-sm btn-secondary">Save</button>
      </div>
     </template>  

    <hr/> 

    <div class="col-md-12 columns">
      <div class=" form-switch d-flex ps-0 ms-0  is-filled">
      <input class="active-checkbox" id="finat" type="checkbox"
      @input="pushData($event,12)" :checked="licence.status >= 12" value="12">
      <label for="finat" class="form-check-label text-body text-truncate status-heading"> Client Finalisation Invoice </label>
      </div>
      </div>
            
      <ul class="list-group">
        <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
          <div class="avatar me-3" v-if="client_finalisation !== null">
          <a :href="`${$page.props.blob_file_path}${client_finalisation.document_file}`" target="_blank">
          <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
          </a>
          </div>
      
         <div class="d-flex align-items-start flex-column justify-content-center">
          <h6 v-if="!client_finalisation" class="mb-0 text-sm">Document</h6>
          <h6 v-else-if="client_finalisation" class="mb-0 text-sm limit-file-name">{{ client_finalisation.document_name }}</h6>
          </div>
      
          <a v-if="client_finalisation !== null" @click="deleteDocument(client_finalisation.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
          <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
          </a>
          <a v-else @click="getDocType(12,'Client Finalisation Invoiced')" data-bs-toggle="modal" data-bs-target="#documents" 
          class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
          <i class="fa fa-upload h5" aria-hidden="true"></i>
          </a>
        </li>
      </ul>
      <hr/>
      
      <div class="col-md-6 columns">
        <div class=" form-switch d-flex ps-0 ms-0  is-filled">
        <input class="active-checkbox" id="client-paid" type="checkbox"
        @input="pushData($event,13)" :checked="licence.status >= 13" value="13">
        <!-- this was previouly client paid -->
        <label for="client-paid" class="form-check-label text-body text-truncate status-heading"> Finalisation Paid </label>
        </div>
        </div>
        
 <template v-if="licence.client_paid_at == null"> 
       
  <div class="col-md-4 columns mb-4">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.client_paid_at">
     </div>
   <div v-if="errors.client_paid_at" class="text-danger">{{ errors.client_paid_at }}</div>
   </div>
   <div class="col-md-1 columns">
    <button v-if="licence.client_paid_at == null" 
    
    @click="updateRegistrationDate" type="button" class="btn btn-sm btn-secondary">Save</button>
   </div>   
</template>
                     
<template v-else>      
  <div class="col-md-4 columns mb-4">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.client_paid_at">
     </div>
   <div v-if="errors.client_paid_at" class="text-danger">{{ errors.client_paid_at }}</div>
   </div>
   <div class="col-md-1 columns">
    <button v-if="$page.props.auth.has_slowtow_admin_role" 
    
     @click="updateRegistrationDate" type="button" class="btn btn-sm btn-secondary">Save</button>
   </div>  
 </template>  
        <hr/>

        <div class="col-md-6 columns">
          <div class=" form-switch d-flex ps-0 ms-0  is-filled">
          <input class="active-checkbox" id="activ-fee" type="checkbox" 
          @input="pushData($event,14)" :checked="licence.status >= 14" value="14">
          <label for="activ-fee" class="form-check-label text-body text-truncate status-heading"> Activation Fee Paid </label>
          </div>
          </div>
          
          


           <template v-if="licence.activation_fee_paid_at == null"> 
       
            <div class="col-md-4 columns">
              <div class="input-group input-group-outline null is-filled ">
              <label class="form-label">Date</label>
          <input type="date" class="form-control form-control-default" v-model="form.activation_fee_paid_at">
            </div>
          <div v-if="errors.activation_fee_paid_at" class="text-danger">{{ errors.activation_fee_paid_at }}</div>
      </div>
          <div class="col-md-1 columns">
            <button @click="updateRegistrationDate" 
            
            type="button" class="btn btn-sm btn-secondary">Save</button>
           </div>  
          </template>
                               
          <template v-else>      
            <div class="col-md-4 columns">
              <div class="input-group input-group-outline null is-filled ">
              <label class="form-label">Date</label>
          <input type="date" class="form-control form-control-default" v-model="form.activation_fee_paid_at">
            </div>
          <div v-if="errors.activation_fee_paid_at" class="text-danger">{{ errors.activation_fee_paid_at }}</div>
             </div>
          <div class="col-md-1 columns">
            <button v-if="$page.props.auth.has_slowtow_admin_role" 
            
             @click="updateRegistrationDate" type="button" class="btn btn-sm btn-secondary">Save</button>
           </div>  
       </template> 



          <ul class="list-group">
            <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
              <div class="avatar me-3" v-if="activation_fee_paid !== null">
              <a :href="`${$page.props.blob_file_path}${activation_fee_paid.document_file}`" target="_blank">
              <i class="fa fa-file-pdf text-danger" aria-hidden="true"></i>
              </a>
              </div>
          
             <div class="d-flex align-items-start flex-column justify-content-center">
              <h6 v-if="!activation_fee_paid" class="mb-0 text-sm">Document</h6>
              <h6 v-else-if="activation_fee_paid" class="mb-0 text-sm limit-file-name">{{ activation_fee_paid.document_name }}</h6>
              </div>
          
              <a v-if="activation_fee_paid !== null" @click="deleteDocument(activation_fee_paid.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
              <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
              </a>
              <a v-else @click="getDocType(14,'Activation Fee Paid')" data-bs-toggle="modal" data-bs-target="#documents" 
              class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
              <i class="fa fa-upload h5" aria-hidden="true"></i>
              </a>
            </li>
          </ul>
          <hr/>
          
          <div class="col-md-6 columns">
            <div class=" form-switch d-flex ps-0 ms-0  is-filled">
            <input class="active-checkbox" id="licence-issued" type="checkbox"
            @input="pushData($event,15)" :checked="licence.status >= 15" value="15">
            <label for="licence-issued" class="form-check-label text-body text-truncate status-heading"> Licence Issued </label>
            </div>
            </div>
            
           
   
             
             <template v-if="licence.licence_issued_at == null"> 
       
              <div class="col-md-4 columns">
                <div class="input-group input-group-outline null is-filled ">
                <label class="form-label">Date</label>
              <input type="date" class="form-control form-control-default" v-model="form.licence_issued_at">
                </div>
              <div v-if="errors.licence_issued_at" class="text-danger">{{ errors.licence_issued_at }}</div>
             </div>

            <div class="col-md-1 columns">
              <button v-if="licence.licence_issued_at == null" 
             
              @click="updateRegistrationDate" type="button" class="btn btn-sm btn-secondary">Save</button>
             </div>
          </template>
                               
          <template v-else>      
            <div class="col-md-4 columns">
              <div class="input-group input-group-outline null is-filled ">
              <label class="form-label">Date</label>
            <input type="date" class="form-control form-control-default" v-model="form.licence_issued_at">
              </div>
            <div v-if="errors.licence_issued_at" class="text-danger">{{ errors.licence_issued_at }}</div>
        </div>

          <div class="col-md-1 columns">
            <button v-if="$page.props.auth.has_slowtow_admin_role" 
            
            @click="updateRegistrationDate" type="button" class="btn btn-sm btn-secondary">Save</button>
           </div>
       </template> 


            <ul class="list-group">
              <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                <div class="avatar me-3" v-if="licence_issued_doc !== null">
                <a :href="`${$page.props.blob_file_path}${licence_issued_doc.document_file}`" target="_blank">
                <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
                </a>
                </div>
            
               <div class="d-flex align-items-start flex-column justify-content-center">
                <h6 v-if="!licence_issued_doc" class="mb-0 text-sm">Document</h6>
                <h6 v-else-if="licence_issued_doc" class="mb-0 text-sm limit-file-name">{{ licence_issued_doc.document_name }}</h6>
                </div>
            
                <a v-if="licence_issued_doc !== null" @click="deleteDocument(licence_issued_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
                <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
                </a>
                <a v-else @click="getDocType(15,'Licence Issued')" data-bs-toggle="modal" data-bs-target="#documents" 
                class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
                <i class="fa fa-upload h5" aria-hidden="true"></i>
                </a>
              </li>
            </ul> 
            <hr/>




            <div class="col-md-6 columns mb-4">
              <div class=" form-switch d-flex ps-0 ms-0  is-filled">
              <input class="active-checkbox" id="licence-delivered" type="checkbox" 
              @input="pushData($event,16)" :checked="licence.status >= 16">
              <label for="licence-delivered" class="form-check-label text-body text-truncate status-heading"> Licence Delivered </label>
              </div>
              </div>
              
              
               <template v-if="licence.licence_delivered_at == null"> 
       
                <div class="col-md-4 columns">
                  <div class="input-group input-group-outline null is-filled ">
                  <label class="form-label">Date</label>
            <input type="date" class="form-control form-control-default" v-model="form.licence_delivered_at">
              </div>
               <div v-if="errors.licence_delivered_at" class="text-danger">{{ errors.licence_delivered_at }}</div>
                </div>

              <div class="col-md-1 columns">
                <button @click="updateRegistrationDate" 
                 
                type="button" class="btn btn-sm btn-secondary">Save</button>
               </div>
            </template>
                                 
            <template v-else>      
              <div class="col-md-4 columns">
                  <div class="input-group input-group-outline null is-filled ">
                  <label class="form-label">Date</label>
            <input type="date" class="form-control form-control-default" v-model="form.licence_delivered_at">
              </div>
               <div v-if="errors.licence_delivered_at" class="text-danger">{{ errors.licence_delivered_at }}</div>
                </div>

              <div class="col-md-1 columns">
                <button  v-if="$page.props.auth.has_slowtow_admin_role" 
                 
                @click="updateRegistrationDate" type="button" class="btn btn-sm btn-secondary">Save</button>
               </div>
         </template> 


              <ul class="list-group">
                <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                  <div class="avatar me-3" v-if="licence_delivered !== null">
                  <a :href="`${$page.props.blob_file_path}${licence_delivered.document_file}`" target="_blank">
                  <i class="fa fa-file-pdf text-danger" aria-hidden="true"></i>
                  </a>
                  </div>
              
                 <div class="d-flex align-items-start flex-column justify-content-center">
                  <h6 v-if="!licence_delivered" class="mb-0 text-sm">Document</h6>
                  <h6 v-else-if="licence_delivered" class="mb-0 text-sm limit-file-name">{{ licence_delivered.document_name }}</h6>
                  </div>
              
                  <a v-if="licence_delivered !== null" @click="deleteDocument(licence_delivered.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
                  <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
                  </a>
                  <a v-else @click="getDocType(16,'Licence Delivered')" data-bs-toggle="modal" data-bs-target="#documents" 
                  class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
                  <i class="fa fa-upload h5" aria-hidden="true"></i>
                  </a>
                </li>
              </ul> 
  
  <div>
    <div v-if="form.status >= 15" class="text-xs text-danger d-flex">Please note that this licence will no longer be a 
      new application and this action is irreversible once saved.</div>
  </div>
  </div>
  </form>
    </div>
  </div>
  <hr class="vertical dark" />
  </div>
          
  </div>
  
  </div>
  <hr>
 
  <Task 
  v-if="licence.status >= 15"
  :tasks="tasks" 
  :model_id="licence.id" 
  :success="success" 
  :error="error" 
  :errors="errors" 
  :model_type="'Licence'"/>



  </div>
  </div>
  
  <div v-if="show_modal" class="modal fade" id="documents" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
          
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <p v-if="uploadDoc.doc_type == 'Licence Issued'
        || uploadDoc.doc_type == 'Licence Delivered'" class="p-2 text-danger">
          Please note that this licence will no longer be a new application and this action 
          is irreversible once saved.</p>

        <form @submit.prevent="submitDocument">
        <input type="hidden" v-model="uploadDoc.doc_type">
        <input type="hidden" v-model="uploadDoc.num">
        <div class="modal-body">      
          <div class="row">    
          <div class="col-md-12 columns">
          <label for="licence-doc" class="btn btn-dark w-100" href="">Select File</label>
           <input type="file" @change="getFileName"
           hidden id="licence-doc" accept=".pdf"/>
           <div v-if="errors.doc" class="text-danger">{{ errors.doc }}</div>
           <div v-if="file_name && show_file_name">File Selected: <span class="text-success" v-text="file_name"></span></div>
             <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">Sorry <span class="text-success">{{ file_name }}</span> cannot contain apostrophe(s).Replace apostrophes with backticks.</p>  
           </div>
         <div class="col-md-12">
            <progress v-if="uploadDoc.progress" :value="uploadDoc.progress.percentage" max="100">
           {{ uploadDoc.progress.percentage }}%
           </progress>
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

  <div v-if="show_modal" class="modal" id="edit-board-request" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Board Request</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form @submit.prevent="updateBoardRequest">
        <div class="modal-body">      
          <div class="row">
          <div class="col-md-12 columns">
            <div class="col-md-12 columns">
              <div class="input-group input-group-outline null is-filled ">
              <textarea required class="form-control form-control-default" 
               v-model="editBoardRequestForm.body"></textarea>
              </div>
              <div v-if="errors.body" class="text-danger">{{ errors.body }}</div>
          </div>
          
          </div>
           </div>   
        </div>
    
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" :disabled="editBoardRequestForm.processing">
           <span v-if="editBoardRequestForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
           Save</button>
        </div>
        </form>
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
  .upload-icon{
    cursor: pointer;
  }
  .active-checkbox{
    margin-top: -10px;
    margin-left: 3px;
  }
  .status-heading{
    font-weight: 700;
  }
  .list-group{
    margin-top: -1.4rem;
  }

  
  </style>

  <script src="./registration.js"></script>
  
  