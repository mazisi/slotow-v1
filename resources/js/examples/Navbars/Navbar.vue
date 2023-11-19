<template>
  <nav
    class="shadow-none navbar navbar-main navbar-expand-lg border-radius-xl"
    v-bind="$attrs"
    id="navbarBlur"
    data-scroll="true"
    :class="isAbsolute ? 'mt-4' : 'mt-0'"
  >
    <div class="px-3 py-1 container-fluid">
      <breadcrumbs :currentPage="company" :color="color" />
      <div
        class="mt-2 collapse navbar-collapse mt-sm-0 me-md-0 me-sm-4"
        :class="isRTL ? 'px-0' : 'me-sm-4'"
        id="navbar"
      >
      <div
      class="invisible pe-md-3 d-flex align-items-center"
      :class="isRTL ? 'me-md-auto' : 'ms-md-auto'">
      
    </div>

           
        <ul class="navbar-nav justify-content-end">
          <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
            <div class="avatar me-3">
              <img :src="`https://eu.ui-avatars.com/api/?background=random&amp;name=${$page.props.auth.user.name}`" 
              alt="kal" class="avatar avatar-sm border-radius-lg">
            </div>
            
            </li>

          <li v-if="$page.props.auth.has_slowtow_admin_role" class="nav-item d-flex align-items-center">
            <inertia-link href="/slotow-admins" class="px-0 nav-link font-weight-bold lh-1 text-body">
              <i class="material-icons me-sm-1"> admin_panel_settings </i>
            </inertia-link>
          </li>
          
          <li class="px-3 nav-item d-flex align-items-center">
            <a data-bs-toggle="modal" data-bs-target="#edit-password" class="p-0 nav-link lh-1 text-body">
              <i class="material-icons fixed-plugin-button-nav cursor-pointer"> settings </i>
            </a>
        </li>
          <li class="nav-item d-flex align-items-center">
            <inertia-link
              :href="`/logout`"
              class="px-0 nav-link font-weight-bold lh-1 "
              :class="color ? color : 'text-body'"
            >
              <i class="material-icons text-danger" :class="isRTL ? 'ms-sm-2' : 'me-sm-1'">
                logout
              </i>
            </inertia-link>
          </li>
        </ul>
        <ul class="navbar-nav justify-content-end">        
         
          <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
            <a
              href="#"
              @click="toggleSidebar"
              class="p-0 nav-link text-body lh-1"
              id="iconNavbarSidenav"
            >
              <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div v-if="show_modal" class="modal fade" id="edit-password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Your Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form @submit.prevent="updatePassword">        
        <div class="modal-body">      
          <div class="row">
            <div class="col-12 columns">
              <div class="input-group input-group-outline null is-filled ">
              <label class="form-label">New Password</label>
              <input type="password" required class="form-control form-control-default" v-model="form.password" >
              </div>
              </div>

              <div class="col-12 columns">
                <div class="input-group input-group-outline null is-filled ">
                <label class="form-label">Confirm Password</label>
                <input type="password" required class="form-control form-control-default" v-model="form.password_confirmation" >
                </div>
                </div>     
           </div>
        </div>
    
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" :disabled="form.processing">
           <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
           Update</button>
        </div>
        </form>
      </div>
    </div>
  </div>
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
import Breadcrumbs from "../Breadcrumbs.vue";
import { mapMutations, mapState } from "vuex";
import { Inertia } from '@inertiajs/inertia'

export default {
  name: "navbar",
  props: {
    minNav: String,
    color: String,
    error: String,
    success: String
  },

  data() {
    return {
      showMenu: false,
      show_modal: true,
      showFlashMessage: false,
      form: {
         password: '',
         password_confirmation: '',
         processing: false
      
      },
    };
  },
  
  created() {
    this.minNav;
  },
  methods: {
    updatePassword(){

Inertia.visit('/update-my-password', {
  method: 'post',
  data: {
    password: this.form.password,
    password_confirmation: this.form.password_confirmation,
  },
      onProgress: progress => {
        this.form.processing = true
      },
      onSuccess: page => {
      document.querySelector('.modal-backdrop').remove()
      },
})

    },
    ...mapMutations(["navbarMinimize", "toggleConfigurator"]),

    toggleSidebar() {
      this.navbarMinimize();
    },
  },
  components: {
    Breadcrumbs,
    Inertia
  },
  computed: {
    ...mapState(["isRTL", "isAbsolute"]),

    
  },
};
</script>
