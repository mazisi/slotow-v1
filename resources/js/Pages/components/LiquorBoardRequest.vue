<template>
  <div class="row">
    <h6 class="text-center">Liquor Board Requests</h6>
    <div class="col-xl-8">
    <div class="row">
    <div v-for="liquor_board_request in liqour_board_requests" :key="liquor_board_request.id" class="mb-4 col-xl-12 col-md-12 mb-xl-0">
    <div class="alert text-white alert-dark alert-dismissible fade show font-weight-light" role="alert">
    <span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
    <span class="text-sm">{{ liquor_board_request.body }}</span>
    </span>
    <div class="d-flex ">
      
    </div>
    <div class="d-flex ">
      <div class="d-flex ">
        <button @click="editBoardRequest(liquor_board_request.body, liquor_board_request.id)" 
          data-bs-toggle="modal" data-bs-target="#edit-board-request"
          type="button" class="mx-2 btn-close d-flex">
           <i class="fa fa-pencil mx-2" aria-hidden="true"></i>
        </button>
      
      </div>
    </div>
    <p style=" font-size: 12px"><i class="fa fa-clock-o" ></i> {{ new Date(liquor_board_request.created_at).toLocaleString().split(',')[0] }}</p>
    </div>
    </div>


    <div v-if="!liqour_board_requests" class="text-center text-danger">No board requests found.</div>
    </div>
    </div>
    <div class="col-xl-4">
    <form @submit.prevent="submitBoardRequest">
    <div class="col-md-12 columns">
    <label class="form-check-label text-body text-truncate status-heading">New Board Request:
    <span><i class="fa fa-clock-o mx-2" aria-hidden="true"></i>{{ new Date().toISOString().split('T')[0] }}</span></label>
    </div>

    <div class="col-12">    
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">New Board Request</label>
    <textarea v-model="submitBoardRequestForm.body" class="form-control form-control-default" rows="3" ></textarea>
    </div>
    <!-- <div v-if="errors.body" class="text-danger">{{ errors.body }}</div> -->
    </div>
    
    <button :disabled="submitBoardRequestForm.processing" class="btn btn-sm btn-secondary ms-2 mt-1 float-end justify-content-center" type="submit">
      <span v-if="submitBoardRequestForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Save
    </button>
    </form>
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
                <!-- <div v-if="errors.body" class="text-danger">{{ errors.body }}</div> -->
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
</template>
<script>
import { ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3';

export default {
  props: {
    liqour_board_requests: Object,
    model_type: String,
    model_id: Number,
    errors: Object
  },

  setup (props) {
    let show_modal = ref(true);  
    const submitBoardRequestForm = useForm({
          body: '',
          model_type: props.model_type,
          model_id: props.model_id,     
    })

    const editBoardRequestForm = useForm({
          body: '',
          id: '',     
    })

    function submitBoardRequest(){
      submitBoardRequestForm.post('/submit-board-request', {
          onSuccess: () => submitBoardRequestForm.reset(),
      })
    }

    function editBoardRequest(body, request_id){
      this.show_modal = true     
      this.editBoardRequestForm.body = body,
      this.editBoardRequestForm.id = request_id      
      }

     function updateBoardRequest(){
      editBoardRequestForm.patch('/update-board-request', {
          preserveScroll: true,
          onSuccess: () => {
            this.show_modal = false;
            document.querySelector('.modal-backdrop').remove() 
            }
        })
     }

    return {
      submitBoardRequestForm,
      show_modal,
      submitBoardRequest,
      editBoardRequestForm,
      editBoardRequest,
      updateBoardRequest
     }
  },
  components: {
  }
  
};
</script>
