<template>
  <div class=" my-4">
    <div class="table-responsive p-0">
      <table class="table align-items-center mb-0">
        <thead>
          <tr>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
            Trading Name
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
            Renewal Date
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
            Message
            </th>                    
            
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
            Action
            </th>
           
          </tr>
        </thead>
        <tbody>
          <tr v-for="email in emails">
            <td class="text-center text-sm">
              {{ email.licence_renewals.licence.trading_name ? email.licence_renewals.licence.trading_name : '' }}              
            </td>
            <td class="text-center text-sm">
              <Link :href="`/view-licence-renewal/${email.licence_renewals.slug}`" class=" font-weight-bold"> 
                {{ email.licence_renewals.date }} </Link>
                </td>
            <td class="text-center text-sm text-danger">{{ email.feedback }}</td>
            
            <td class="text-center text-sm">
              <Link :href="`/email-comms/get-mail-template/${email.licence_renewals.slug}/renewals`" class="btn btn-sm btn-success"> 
               Resend </Link>
            </td>
           </tr>
          
         
        </tbody>
      </table>
    </div>
  </div> 
</template>
<script>
  import { Link } from '@inertiajs/inertia-vue3';
  export default {
    props: {
      emails: Object,
    },
    components:{
      Link
    }
  }
  </script> 