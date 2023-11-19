<template>
<Layout>
  <Head title="Upload Contacts" />
  <div class="container-fluid">
<Banner/>
    
    <div class="card mx-3 mx-md-4 mt-n6">
      <div class="col-12">
  
  <div class=" card-body">
    <div class="p-3 pb-0 card-header" >
          <h6 class="mb-0" >Upload CSV Document</h6>
        </div>
    <div class="card-body px-0 pb-2">
 <form @submit.prevent="submit">
 <ul class="list-group">
  <!-- <div class="d-flex">
  <label>Tick if first row is a header</label>
    <input v-model="form.header" type="checkbox" id="has-header">
  </div> -->
<li class="list-group-item d-flex align-items-center border-0 mb-2 rounded" style="background-color: #f4f6f7;">

  <input @input="form.csv_file = $event.target.files[0]" class="form-control form-control-lg" id="formFileLg" type="file">
<p v-if="errors.csv_file" class="text-danger">{{ errors.csv_file }}</p>
</li>
<progress v-if="form.progress" :value="form.progress.percentage" max="100">
      {{ form.progress.percentage }}%
    </progress>
</ul>
<button type="submit" class="btn btn-sm btn-secondary upload-btn">Upload</button>
</form>

          </div>
        </div>
      </div>
    </div>
  </div>

  </Layout>
</template>

<style>
 
  .table thead th {
    padding: 0;
    }
    .upload-btn{
      float: right!important;
    }
    #has-header{
  margin-left: 3px;
}
</style>
<script>
import Layout from "../../Shared/Layout.vue";
import { useForm, Head } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';

export default {
  setup () {
    const form = useForm({
      csv_file: null,
      header: '',
    })

    function submit() {
      form.post('/submit-contacts')
    }

    return { form, submit }
  },
  props: {
    success: String,
    errors: Object
  },
 components: {
    Layout,
    Banner,
    Head
},
}
</script>