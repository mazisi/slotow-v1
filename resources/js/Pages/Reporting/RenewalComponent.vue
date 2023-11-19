<template>
  <div class=" my-4">
    <div class="table-responsive p-0">
      <table class="table align-items-center mb-0">
        <thead>
          <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
              Active/Deactive
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
            Trading Name
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
            Licence Number
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
            Renewal Date
            </th>                    
            
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
            Quoted
            </th>
           
          </tr>
        </thead>
        <tbody>
          <tr v-for="renewal in renewals.data">
            <td class="align-middle text-sm">
              <i v-if="renewal.licence.is_licence_active === '1'" class="fa fa-check text-success" aria-hidden="true"></i>
              <i v-else class="fa fa-times text-danger" aria-hidden="true"></i>
              
            </td>
            <td class="text-center text-sm">{{ limit(renewal.licence.trading_name) }}</td>
            <td class="text-center text-sm">{{ renewal.licence.licence_number ? renewal.licence.licence_number : '' }}</td>
            <td class="text-center text-sm">{{ renewal.date }}</td>
            <td v-if="renewal.renewal_documents[0] != null" class="text-center text-sm">True</td>
            <td v-else class="text-center text-sm">False</td>                  </tr>
          
         
        </tbody>
      </table>
    </div>
  </div> 
</template>
<script>
  export default {
    props: {
      renewals: Object,
    },
    
    methods: {
      limit(string='', limit = 25) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        }
    }
  }
  </script> 