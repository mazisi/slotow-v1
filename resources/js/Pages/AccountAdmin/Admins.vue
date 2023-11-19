<style scoped>
        .columns{
          margin-bottom: 1rem;
        }
        #active-checkbox{
          margin-left: 3px;
        }
    
    </style>
    <template>
  <Layout>

    <Head title="All Users" />
  <div class="container-fluid">
  
  <Banner/>
  
  <div class="card card-body mx-3 mx-md-4 mt-n6">
    <div class="row">
                  <div class="col-lg-6 col-7">
                    <h6 class="mx-2">Slotow Admins</h6>
                    </div>
                  <div class="col-lg-6 col-5 my-auto text-end">
                    <a @click="show_modal = true" data-bs-toggle="modal" data-bs-target="#add-user" href="#!" class="float-end btn btn-dark">
                      <i class="material-icons-round">person_add_alt</i>
                     </a>
                  </div>
                </div>
  <div class="col-12">
  <div class="my-4">
  
  <div class="px-0 pb-2">
  <div class="table-responsive p-0">
    <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Full Name </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 "> Function </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Status </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Last Activity </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="user in users.data" :key="user.id">
                        <td>
                          <div class="d-flex px-2 py-1">
                            <div>
                              
                              <img v-if="user.picture" :src="`${$page.props.blob_file_path}${user.picture}`" 
                              class="avatar avatar-sm me-3 border-radius-lg" alt="user1">

                              <img v-else loading="lazy"
                               :src="`https://eu.ui-avatars.com/api/?background=random&amp;name=${user.name}`" 
                              class="avatar avatar-sm me-3 border-radius-lg" alt="user1">

                            </div>
                            <div class="d-flex flex-column justify-content-center">
                              <h6 class="mb-0 text-sm">{{ user.name }}</h6>
                              <p class="text-xs text-secondary mb-0"> {{ user.email.toLowerCase() }} </p>
                            </div>
                          </div>
                        </td>
                        <td>
                          <p v-for="role in user.roles" class="text-xs font-weight-bold mb-0">
                           <span v-if="role.name === 'slowtow-admin'">Super Admin</span>
                           <span v-else-if="role.name === 'slowtow-user'">Admin</span>
                           <span v-else >User</span>
                          </p>
                        </td>
                        <td class="align-middle text-center text-sm">
                          <span v-if="user.is_active" class="badge badge-sm bg-gradient-success">Active</span>
                          <span v-else class="badge badge-sm bg-gradient-warning">Deactivated</span>
                        </td>
                        <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">
                          {{ user.last_activity_at  }}</span></td>
                        <td class="align-middle text-center">
          
  
    <div class="dropdown float-lg-end pe-4">
      <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
      </a>
      <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable" >
        <li><a @click="editUser(user.id,user.name,user.email,user.roles[0].name)" 
          data-bs-toggle="modal" data-bs-target="#edit-user" class="dropdown-item active" href="#!">
          <i class="fa fa-pencil"></i> Edit</a>
        </li>
        
        <li v-if="user.is_active" @click="deActivateuser(user.id, user.is_active)">
          <a  
           class="dropdown-item border-radius-md" href="javascript:;"> 
           <i class="fa fa-minus-square"></i> Deactivate </a>
          </li>
          <li v-else @click="deActivateuser(user.id, user.is_active)">
            <a class="dropdown-item border-radius-md" href="javascript:;">
              <i class="fa fa-plus-square-o"></i> Activate </a>
          </li>
      <li><a @click="deleteUser(user.name, user.id)" class="dropdown-item text-danger" href="javascript:;"> 
        <i class="fa fa-trash-o"></i> Delete </a></li>
    </ul></div>
  </td>
                      </tr>
                   
                    </tbody>
                  </table>
  
  
  </div>
  </div>
  <Paginate
  :modelName="users"
  :modelType="Users"
  />
  </div>
  </div>
  </div>
  </div>


  <div v-if="show_modal" class="modal fade" id="add-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form @submit.prevent="submitUser">        
          <div class="modal-body">      
            <div class="row">
              <div class="col-12 columns">
                <div class="input-group input-group-outline null is-filled ">
                <label class="form-label">Full Name</label>
                <input type="text" required class="form-control form-control-default" v-model="form.full_name" >
                </div>
                <div v-if="errors.full_name" class="text-danger">{{ errors.full_name }}</div>
                </div>
  
                <div class="col-12 columns">
                  <div class="input-group input-group-outline null is-filled ">
                  <label class="form-label">Email</label>
                  <input type="email" required class="form-control form-control-default" v-model="form.email" >
                  </div>
                  <div v-if="errors.email" class="text-danger">{{ errors.email }}</div>
                  </div>                
                  <div class="col-12 columns">
                    <div class="input-group input-group-outline null is-filled ">
                    <label class="form-label">Function/Role</label>
                    <select class="form-control form-control-default" v-model="form.role">
                      <option :value="''">Select Role..</option>
                      <option value="slowtow-admin">Super Admin</option>
                      <option value="slowtow-user">Admin</option>
                    </select>
                    </div>
                    <div v-if="errors.role" class="text-danger">{{ errors.role }}</div>
                  </div>
  
                  <div class="col-9 columns">
                    <div class="input-group input-group-outline null is-filled ">
                    <label class="form-label">Password</label>
                    <input type="text" class="form-control form-control-default" v-model="form.password" >
                    </div>
                    <div v-if="errors.password" class="text-danger">{{ errors.password }}</div>
                  </div>
                  <div class="col-3 columns">
                    <button type="button" @click="generatePassword" class="btn btn-sm btn-secondary">Generate</button>
                  </div>
             </div>
          </div>
      
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" :disabled="form.processing">
             <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
             Save</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  
    <div v-if="show_modal" class="modal fade" id="edit-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit {{ editForm.full_name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form @submit.prevent="updateUser">        
          <div class="modal-body">      
            <div class="row">
              <div class="col-12 columns">
                <div class="input-group input-group-outline null is-filled ">
                <label class="form-label">Full Name</label>
                <input type="text" required class="form-control form-control-default" v-model="editForm.full_name" >
                </div>
                <div v-if="errors.full_name" class="text-danger">{{ errors.full_name }}</div>
                </div>
  
                <div class="col-12 columns">
                  <div class="input-group input-group-outline null is-filled ">
                  <label class="form-label">Email</label>
                  <input type="email" required class="form-control form-control-default" v-model="editForm.email" >
                  </div>
                  <div v-if="errors.email" class="text-danger">{{ errors.email }}</div>
                  </div> 
                  
                  <div class="col-9 columns">
                    <div class="input-group input-group-outline null is-filled ">
                    <label class="form-label">Change Password</label>
                    <input type="text" class="form-control form-control-default" v-model="editForm.password" >
                    </div>
                    <div v-if="errors.password" class="text-danger">{{ errors.password }}</div>
                  </div>
                  <div class="col-2 columns">
                    <button type="button" @click="generateEditPassword" class="btn btn-sm btn-secondary">Generate</button>
                  </div>

                  <div class="col-12 columns">
                    <div class="input-group input-group-outline null is-filled ">
                    <label for="propic" class="btn mb-0 bg-gradient-dark btn-md null w-100">Change Picture</label>
                    <input type="file" @change="getFileName($event)" hidden id="propic" />
                      <div v-if="file_name"><span class="text-success" v-text="file_name"></span></div>
                      <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">Sorry 
                        <span class="text-success">{{ file_name }}</span> cannot contain apostrophe(s).</p>  
                    </div>
                    <div v-if="errors.picture" class="text-danger">{{ errors.picture }}</div>
                  </div>

                  <div class="col-md-12">
                    <progress v-if="editForm.progress" :value="editForm.progress.percentage" max="100">
                      {{ editForm.progress.percentage }}%
                      </progress>
                    
                   </div>

             </div>
          </div>
      
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" :disabled="editForm.processing">
             <span v-if="editForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
             Update</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </Layout>
  </template>
  
  <style>
  .filters{
   margin-top: 10px;
  }
    .table thead th {
      padding: 0;
      }
   
    #with-thrashed{
      margin-top: 3px;
      margin-left: 3px;
    }
  </style>
  
  <script>
  import Layout from "../../Shared/Layout.vue";
  import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
  import { Inertia } from '@inertiajs/inertia';
  import { ref } from 'vue';
  import Banner from '../components/Banner.vue';
  import Paginate from '../../Shared/Paginate.vue';
  import { toast } from 'vue3-toastify';
   import 'vue3-toastify/dist/index.css';
  
  export default {
   props: {
      errors: Object,
      users: Object,
      success: String,
      error: String
    },
    
    
    setup (props) {

          let showMenu = ref(false);
          let show_modal = ref(true); 
          let file_name = ref('');
          let file_has_apostrophe = ref(''); 
  
          const form = useForm({
            full_name: '',
            email: '',
            role: '',
            password: '' 
          }) 

          const editForm = useForm({
            full_name: '',
            email: '',
            role: '',
            password: '',
            id: '',
            profilePic: null
          }) 
        
          function submitUser() {
            form.post('/submit-user', {
              onSuccess: () => {
                this.show_modal = false;
                document.querySelector('.modal-backdrop').remove();
                if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
                    }
                form.reset('body','full_name','email','role');
              }
             })
          }
  
          function generatePassword(){
            let chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            let passwordLength = 8;

            if(this.form.password == ''){
              for (var i = 0; i <= passwordLength; i++) {
                  var randomNumber = Math.floor(Math.random() * chars.length);
                  this.form.password += chars.substring(randomNumber, randomNumber +1);
             }
            }else{
              this.form.password = '';
              for (var i = 0; i <= passwordLength; i++) {
                  var randomNumber = Math.floor(Math.random() * chars.length);
                  this.form.password += chars.substring(randomNumber, randomNumber +1);
               }
            }            
          }

          //DRY PRNCIPLE VIOLATED--Will come to this!!!!!!!!!!!!!
          function generateEditPassword(){
            let chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            let passwordLength = 8;

            if(this.editForm.password == ''){
              for (var i = 0; i <= passwordLength; i++) {
                  var randomNumber = Math.floor(Math.random() * chars.length);
                  this.editForm.password += chars.substring(randomNumber, randomNumber +1);
             }
            }else{
              this.editForm.password = '';
              for (var i = 0; i <= passwordLength; i++) {
                  var randomNumber = Math.floor(Math.random() * chars.length);
                  this.editForm.password += chars.substring(randomNumber, randomNumber +1);
               }
            }            
          }

          function editUser(user_id,name,email,role){
            this.editForm.full_name = name;
            this.editForm.email = email;
            this.editForm.role = role;
            this.editForm.id = user_id;
            this.show_modal = true;
          }

          function updateUser(){
            editForm.post('/update-user', {
              onSuccess: () => {
                this.show_modal = false;
                document.querySelector('.modal-backdrop').remove();
                if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
                    }
              }
             })
          }

          function deActivateuser(id, status){
            Inertia.post(`/deactivate-user/${id}/${status}`,{
              onSuccess: () => {
                if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
                    }
              }
            })
          }

          function deleteUser(name='', user_id){
            if(confirm(`${name} will be deleted permanently. Continue ?`)){
                Inertia.patch(`/delete-user/${user_id}`,{
                onSuccess: () => {
                  if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
                    }
                }
                })
             }
          }


          function getFileName(e){
            this.editForm.profilePic = e.target.files[0];
            this.file_name = e.target.files[0].name;
            this.file_has_apostrophe = this.file_name.includes("'");
          }

          const notify = (message) => {
            if(props.success){
              toast.success(message, {
              autoClose: 2000,
            });
            
            }else if(props.error){
              toast.error(message, {
              autoClose: 2000,
            });
            }
        }
  
      return {
        toast,
        form,
        notify,
        editForm,
        deleteUser,
        file_name,
        getFileName,
        file_has_apostrophe,
        showMenu,
        show_modal,
        submitUser,
        generatePassword,
        generateEditPassword,
        editUser,
        updateUser,
        deActivateuser,
        
      }
    },
     components: {
      Layout,
      Link,
      Head,
      Banner,
      Paginate,
      
    },
    
  };
  </script>