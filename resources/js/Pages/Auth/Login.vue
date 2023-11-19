
<template>
  <div class="page-header align-items-start min-vh-100" 
  style="background-image: url('https://slotowstorage.blob.core.windows.net/images/10.jpg');
    " loading="lazy">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container my-auto">
      <div class="row">
        <div class="col-lg-4 col-md-8 col-12 mx-auto">
          <div class="card z-index-0 fadeIn3 fadeInBottom">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div
                class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1"
              >
                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">
                  Sign in
                </h4>
              </div>
            </div>
            <div class="card-body">
         
  <form @submit.prevent="submit" role="form" class="text-start mt-3">
  <div class="mb-3">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Email</label>
    <input v-model="form.email" type="email" autocomplete="on" class="form-control form-control-default">
    </div>
    <p v-if="errors.email" class="text-danger">{{ errors.email }}</p>
  </div>
  <div class="mb-3">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Password</label>
    <input v-model="form.password" type="password" class="form-control form-control-default" >
    </div>
    <p v-if="errors.password" class="text-danger">{{ errors.password }}</p>
  </div>
  <div class="form-check form-switch d-flex">
  <input v-model="form.remember"  class="form-check-input" type="checkbox">
  <label class="form-check-label ms-3" for="rememberMe">Remember me</label>
   </div>
  <div class="text-center">
  
  <button :disabled="form.processing" type="submit" class="btn mb-0 bg-gradient-success btn-md w-100 null my-4 mb-2 d-flex justify-content-center"> 

  <span class="mr-5" v-if="form.processing">
  <div class="half-circle-spinner">
  <div class="circle circle-1"></div>
  <div class="circle circle-2"></div>
</div>
</span>
 Sign In
  </button>

  <div v-if="$page.message" class="alert text-white alert-danger alert-dismissible fade show font-weight-light" role="alert">
    <span class="alert-icon"><i class=""></i></span>
    <span class="alert-text"> 
      <span class="text-sm">{{ $page.message }}</span>
    </span>
    <button type="button" class="btn-close d-flex justify-content-center align-items-center" data-bs-dismiss="alert" aria-label="Close">
    <span aria-hidden="true" class="text-lg font-weight-bold">×</span>
  </button>
</div>

  </div>
</form>

            </div>
          </div>
        </div>
      </div>
    </div>
    <footer class="footer position-absolute bottom-2 py-2 w-100">
      <div class="container">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-12 col-md-6 my-auto">
            <div class="copyright text-center float-right text-sm text-white text-lg-start">
              © {{ new Date().getFullYear() }}, Copyright &copy;
              <a
                href="#"
                class="font-weight-bold text-white"
                target="_blank">Slotow Family Trust</a
              >
            </div>
          </div>
         
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import { HalfCircleSpinner } from 'epic-spinners'

export default {
  name: "sign-in",
 props: {
    errors: Object,
    message: String
  },

  setup () {

    const form = useForm({
        email: '',
        password: '',
        remember: '',   
    })

    function submit() {
      form.post('/login', {
        onFinish: () => form.reset('password'),
      })
      
    }

    return { form, submit }
  },

   components: {
    HalfCircleSpinner
  },
  
};

</script>
