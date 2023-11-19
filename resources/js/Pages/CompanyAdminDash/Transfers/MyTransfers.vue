<template>
    <Layout>
      <div class="container-fluid">
        <Banner/>
        <div class="card card-body mx-3 mx-md-4 mt-n6">
          <div class="col-12">
          <div class="row">
      <div class="col-lg-6 col-7">
       <h5>Transfers for: <Link :href="`/company/view-my-licences/${licence.slug}`" class="text-success">{{ licence.trading_name ? licence.trading_name : '' }}</Link></h5>
      </div>
      <div class="col-lg-6 col-5 my-auto text-end">
        
      </div>
    </div>
            <div class=" my-4">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Previous Licence Holder
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Current Licence Holder
                        </th>
                        
                        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Stage
                        </th>
                        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        View
                        </th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for=" (  transfer, index  ) in transfers" :key="index" >
                        <td >
                        <div class="d-flex justify-content-left">
                            <Link v-if="transfer.transfered_from === 'Company'" :href="`/company/view-my-transfer/${transfer.slug}`">
                              <h6 class="mb-0 mx-4 text-sm">
                              {{ limit(transfer.old_company.name) }}
                               </h6>    
                            </Link> 
    
                            <Link v-if="transfer.transfered_from === 'Person'" :href="`/company/view-my-transfer/${transfer.slug}`">
                              <h6 class="mb-0 text-sm">
                              {{ limit(transfer.old_person.full_name) }}
                               </h6>    
                            </Link>                      
                            </div>
                            </td>
                        <td >
                          
                          <Link v-if="transfer.transfered_to === 'Company'" :href="`/company/view-my-transfer/${transfer.slug}`">
                            <h6 class="text-sm">
                            {{ limit(transfer.new_company.name) }}
                          </h6>    
                            </Link>  
                            <Link v-if="transfer.transfered_to === 'Person'" :href="`/company/view-my-transfer/${transfer.slug}`">
                              <h6 class="text-sm">
                              {{ limit(transfer.new_person.full_name) }}
                            </h6>    
                              </Link>  
                         
                        </td>
              <td  v-if="transfer.status == 1"><Link :href="`/company/view-my-transfer/${transfer.slug}`">Client Quoted</Link></td>
              <td  v-else-if="transfer.status == 2"><Link :href="`/company/view-my-transfer/${transfer.slug}`">Client Invoiced</Link></td>
              <td  v-else-if="transfer.status == 3"><Link :href="`/company/view-my-transfer/${transfer.slug}`">Client Paid</Link></td>
              <td  v-else-if="transfer.status == 4"><Link :href="`/company/view-my-transfer/${transfer.slug}`">Prepare Transfer Application</Link></td>
              <td  v-else-if="transfer.status == 5"><Link :href="`/company/view-my-transfer/${transfer.slug}`">Payment To The Liquor Board</Link></td>
              <td  v-else-if="transfer.status == 6"><Link :href="`/company/view-my-transfer/${transfer.slug}`">Scanned Application</Link></td>
              <td  v-else-if="transfer.status == 7"><Link :href="`/company/view-my-transfer/${transfer.slug}`">Application Logded</Link></td>
              <td  v-else-if="transfer.status == 8"><Link :href="`/company/view-my-transfer/${transfer.slug}`">Activation Fee Paid</Link></td>
              <td  v-else-if="transfer.status == 9"><Link :href="`/company/view-my-transfer/${transfer.slug}`">Transfer Issued</Link></td>
              <td  v-else-if="transfer.status == 10"><Link :href="`/company/view-my-transfer/${transfer.slug}`">Transfer Delivered</Link></td>
              
    
                      <td >
                        <Link :href="`/company/view-my-transfer/${transfer.slug}`">
                        <i class="fa fa-eye  mx-4" aria-hidden="true"></i>
                        </Link>
                          
                        </td>
                      </tr>
                      
                     
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Layout>
    </template>
    
    <script>
    import Layout from "../../../Shared/Layout.vue";
    import { Link } from '@inertiajs/inertia-vue3';
    import Banner from '../../components/Banner.vue'
    
    export default {
      name: "dashboard-default",
      props: {
        transfers: Object,
        licence: Object,
        success: String,
        error: String,
        errors: Object
      },
      
      components: {
        Layout,
        Link,
        Banner
        },
        methods: {
          limit(string='', limit=25){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        },
    
    };
    
    //The following are status keys
    // 1 => Client Quoted
    // 2 => Client Invoiced
    // 3 => Client Paid
    // 4 => Collate Transfer Documents
    // 5 => Payment To The Liquor Board
    // 6 => Transfer Logded
    // 7 => Activation Fee Paid
    // 8 => Transfer Issued
    // 9 => Transfer Delivered
    </script>
    
    