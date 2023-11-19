<style>
.navbar-vertical.navbar-expand-xs .navbar-nav .nav-link {
    padding-top: 0.2rem;
    padding-bottom: 0.2rem;
   
}
</style>

<template v-if="$page.props.auth" >
  <div class="w-auto h-auto collapse navbar-collapse max-height-vh-100 h-100" id="sidenav-collapse-main">
    <ul class="navbar-nav">

       <li class="nav-item" v-if="$page.props.auth.has_slowtow_admin_role
       || $page.props.auth.has_slowtow_user_role">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'licences'}"
          collapseRef="/licences"
          navText="Licences">
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">wine_bar</i>
          </template>
        </sidenav-collapse>
      </li>

      <li class="nav-item" v-if="$page.props.auth.has_company_admin_role">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'company_admin_licences'}"
          :collapseRef="route('company_admin_licences')"
          navText="Licences">
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">receipt_long</i>
          </template>
        </sidenav-collapse>
      </li>



      <li class="nav-item" v-if="$page.props.auth.has_company_admin_role">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'my_companies'}"
          collapseRef="/company/my-companies"
          navText="Companies">
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">table_view</i>
          </template>
        </sidenav-collapse>
      </li>

      <li class="nav-item" v-if="$page.props.auth.has_slowtow_admin_role
      || $page.props.auth.has_slowtow_user_role">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'companies'}"
          collapseRef="/companies"
          navText="Companies"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">store</i>
          </template>
        </sidenav-collapse>
      </li>

      


 <li class="nav-item" v-if="$page.props.auth.has_slowtow_admin_role
 || $page.props.auth.has_slowtow_user_role">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'people'}"
          collapseRef="/people"
          navText="People">
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5"
              >groups</i
            >
          </template>
        </sidenav-collapse>
  </li>

   <li class="nav-item" v-if="$page.props.auth.has_company_admin_role">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'my_temp_licences'}"
          collapseRef="/company/my-temp-licences"
          navText="Temporary Licences">
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5"
              >schedule</i
            >
          </template>
        </sidenav-collapse>
  </li>


   <li class="nav-item" v-if="$page.props.auth.has_slowtow_admin_role
   || $page.props.auth.has_slowtow_user_role">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'temp_licences'}"
          collapseRef="/temp-licences"
          navText="Temporary Licences">
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5"
              >timer</i
            >
          </template>
        </sidenav-collapse>
  </li>
     
    <hr>
      <li v-if="$page.props.auth.has_slowtow_admin_role || $page.props.auth.has_slowtow_user_role
      " class="mt-3 nav-item"><h6 class="text-xs ps-4 text-uppercase font-weight-bolder text-white ms-2"> ACTIONS </h6></li>


      <li v-if="$page.props.currentRoute == 'issues'" class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          collapseRef="/create-issue"
          navText="Add New Issue"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">control_point_duplicate</i>
          </template>
        </sidenav-collapse>
      </li>

      <li v-if="$page.props.currentRoute == 'companies' 
      || $page.props.currentRoute == 'view_company'
      && $page.props.auth.has_slowtow_admin_role 
      " class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'create_company'}"
          collapseRef="/create-company"
          navText="New Company"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">add</i>
          </template>
        </sidenav-collapse>
      </li>


      <li v-if="$page.props.currentRoute == 'people' 
              || $page.props.currentRoute == 'view_person'
              || $page.props.currentRoute == 'create_person'
              && ($page.props.auth.has_slowtow_admin_role || $page.props.auth.has_slowtow_user_role)
              " class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'create_person'}"
          collapseRef="/create-person"
          navText="New Person"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">add_ic_call</i>
          </template>
        </sidenav-collapse>
      </li>

      <li v-if="$page.props.currentRoute == 'licences' && ($page.props.auth.has_slowtow_admin_role || $page.props.auth.has_slowtow_user_role)
      " class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'create_licence'}"
          collapseRef="/create-licence"
          navText="Existing licence"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">add</i>
          </template>
        </sidenav-collapse>
      </li>

      <li v-if="$page.props.currentRoute == 'licences' && ($page.props.auth.has_slowtow_admin_role || $page.props.auth.has_slowtow_user_role)
      " class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'create_new_app'}"
          collapseRef="/create-new-app"
          navText="New Application"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">add_task</i>
          </template>
        </sidenav-collapse>
      </li>
      
<!-- <<<<<<<<<<<<<<<<<<<<<<<<===============Licence variations Starts ==============>>>>>>>>>>>>>>>> -->

     <LicenceActions/>
      <!-- <<<<<<<<<<<<<<<<<<<<<<<<===============Licence variations Ends ==============>>>>>>>>>>>>>>>> -->


      <li v-if="$page.props.currentRoute == 'temp_licences' 
               || $page.props.currentRoute == 'create_temp_licence'" class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'create_temp_licence'}"
          collapseRef="/create-temp-licence"
          navText="New Temporary Licence">
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">add</i>
          </template>
        </sidenav-collapse>
      </li>

<li class="nav-item">
        <Link v-if="$page.props.currentRoute == 'view_temp_licence'" data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
        :class="{ active:  $page.props.currentRoute == 'transfer_licence'}"
        :href="`/process-temp-application?slug=${$page.props.slug}`">
       <div class="text-center d-flex align-items-center justify-content-center me-2">
       <i class="material-icons-round opacity-10 fs-5">account_tree</i>
       </div>
       <span class="nav-link-text ms-1">Process Application</span>
       
       </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'contacts' 
      || $page.props.currentRoute == 'upload_contacts'
      && $page.props.auth.has_slowtow_admin_role
      " class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'upload_contacts'}"
          collapseRef="/upload-contacts"
          navText="Upload Contacts"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">upload</i>
          </template>
        </sidenav-collapse>
      </li>



      <Company-Admin-Vue v-if="$page.props.auth.has_company_admin_role"/>  
      

   
          <hr v-if="$page.props.auth.has_slowtow_admin_role || $page.props.auth.has_slowtow_user_role"/>


 <li class="nav-item" v-if="$page.props.auth.has_slowtow_admin_role || $page.props.auth.has_slowtow_user_role
 ">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'contacts'}"
          collapseRef="/goverify-contacts"
          navText="Contacts">
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5"
              >contact_phone</i
            >
          </template>
        </sidenav-collapse>
 </li>


      <li class="nav-item" v-if="$page.props.auth.has_slowtow_admin_role || $page.props.auth.has_slowtow_user_role
      ">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active:  $page.props.currentRoute == 'reports'}"
          collapseRef="/reports"
          navText="Reports"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">insights</i>
          </template>
        </sidenav-collapse>
      </li>

      <li class="nav-item" v-if="$page.props.auth.has_slowtow_admin_role
       || $page.props.auth.has_slowtow_user_role">
        <sidenav-collapse 
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active: $page.props.currentRoute == 'email_comms'
          || $page.props.currentRoute == 'get_licence_transfers'
          || $page.props.currentRoute == 'get_nominations'}"
          collapseRef="/email-comms"
          navText="Email Comms"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">email</i>
            
          </template>
        </sidenav-collapse>
      </li>
      
      <li class="nav-item">
        <a @click="goBack" aria-expanded="false" class="nav-link" url="#" href="#!">
          <div class="text-center d-flex align-items-center justify-content-center me-2">
          <i class="material-icons-round opacity-10 fs-5">arrow_back</i></div>
          <span class="nav-link-text ms-1">Back</span>
        </a>
        <div class="collapse"></div>
      </li>

      <li class="nav-item d-none" >
        <sidenav-collapse 
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          :class="{ active: $page.props.currentRoute === 'issues'
          || $page.props.currentRoute == 'create_issue'
          || $page.props.currentRoute == 'view_issue'}"
          collapseRef="/issues"
          navText="Issues">
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">bug_report</i>            
          </template>
        </sidenav-collapse>
      </li>
      <hr v-if="$page.props.auth.has_slowtow_admin_role"/>

    </ul>
  </div>
</template>
<script>
import SidenavCollapse from "./SidenavCollapse.vue";
import { Link } from '@inertiajs/inertia-vue3';
import CompanyAdminVue from "./CompanyAdmin.vue";
import LicenceActions from "./LicenceActions.vue";
import { usePage } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import { computed} from 'vue';

export default {
  name: "SidenavList",
  props: {
    cardBg: String,
  },
  data() {
    return {
      title: "NavBar",
      controls: "dashboardsExamples",
      isActive: "active"
    };
  },
  components: {
    SidenavCollapse,
    Link,
    CompanyAdminVue,
    LicenceActions
  },
  methods: {
    goBack(){
      history.back()
      
    }
  }
};
</script>

