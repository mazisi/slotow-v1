<template>
<Layout>
  <Head title="Transfers" />

  <div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="col-12">
      <div class="row">
  <div class="col-lg-9 col-9">
   <h5>Transfers for: <Link :href="`/view-licence?slug=${licence.slug}`" class="text-success">{{ licence.trading_name ? licence.trading_name : '' }}</Link></h5>
  </div>
  <div class="col-lg-3 col-3 my-auto text-end">
    <div class="dropdown float-lg-end pe-4">
     <Link :href="`/transfer-licence?slug=${$page.props.slug}`" class="btn btn-sm btn-secondary">New Transfer</Link>
    </div>
  </div>
</div>
        <div class=" my-4">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Previous Licence Holder
                    </th>
                    <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Current Licence Holder
                    </th>
                    
                    <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Stage
                    </th>
                    <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    View
                    </th>
                    
                  </tr>
                </thead>
                <tbody>
                  <tr v-for=" (  transfer, index  ) in transfers.data" :key="index" >
                    <td >
                    <div class="d-flex flex-column">
                        <Link class="ml-1" v-if="transfer.transfered_from === 'Company'" :href="`/view-transfered-licence/${transfer.slug}`">
                          <h6 class="mb-0 text-sm">
                          {{ limit(transfer.old_company.name) }}
                           </h6>    
                        </Link> 

                        <Link class="ml-1" v-if="transfer.transfered_from === 'Person'" :href="`/view-transfered-licence/${transfer.slug}`">
                          <h6 class="mb-0 text-sm">
                          {{ limit(transfer.old_person.full_name) }}
                           </h6>    
                        </Link>                      
                        </div>
                        </td>
                    <td >
                      
                      <Link class="ml-1" v-if="transfer.transfered_to === 'Company'" :href="`/view-transfered-licence/${transfer.slug}`">
                        <h6 class="text-sm">
                        {{ limit(transfer.new_company.name) }}
                      </h6>    
                        </Link>  
                        <Link class="ml-1" v-if="transfer.transfered_to === 'Person'" :href="`/view-transfered-licence/${transfer.slug}`">
                          <h6 class="text-sm">
                          {{ limit(transfer.new_person.full_name) }}
                        </h6>    
                          </Link>  
                     
                    </td>
                   
          <td   v-if="transfer.status == 1"><Link class="ml-1 badge bg-dark text-default" :href="`/view-transfered-licence/${transfer.slug}`">Client Quoted</Link></td>
          <td   v-else-if="transfer.status == 2"><Link class="ml-1 badge bg-info text-default" :href="`/view-transfered-licence/${transfer.slug}`">Client Invoiced</Link></td>
          <td   v-else-if="transfer.status == 3"><Link class="ml-1 badge bg-light text-dark" :href="`/view-transfered-licence/${transfer.slug}`">Client Paid</Link></td>
          <td   v-else-if="transfer.status == 4"><Link class="ml-1 badge bg-warning text-default" :href="`/view-transfered-licence/${transfer.slug}`">Prepare Transfer Application</Link></td>
          <td   v-else-if="transfer.status == 5"><Link class="ml-1 badge bg-secondary text-default" :href="`/view-transfered-licence/${transfer.slug}`">Payment To The Liquor Board</Link></td>
          <td   v-else-if="transfer.status == 6"><Link class="ml-1 badge bg-success text-default" :href="`/view-transfered-licence/${transfer.slug}`">Scanned Application</Link></td>
          <td   v-else-if="transfer.status == 7"><Link class="ml-1" :href="`/view-transfered-licence/${transfer.slug}`">Application Logded</Link></td>
          <td   v-else-if="transfer.status == 8"><Link class="ml-1" :href="`/view-transfered-licence/${transfer.slug}`">Activation Fee Paid</Link></td>
          <td   v-else-if="transfer.status == 9"><Link class="ml-1" :href="`/view-transfered-licence/${transfer.slug}`">Transfer Issued</Link></td>
          <td   v-else-if="transfer.status == 10"><Link class="ml-1" :href="`/view-transfered-licence/${transfer.slug}`">Transfer Delivered</Link></td>
          <td   v-else></td>
          

                 <td >
                    <Link class="ml-1" :href="`/view-transfered-licence/${transfer.slug}`">
                    <i class="fa fa-eye  " aria-hidden="true"></i>
                    </Link>
                      
                    </td>
                  </tr>
                  
                 
                </tbody>
              </table>
            </div>
          </div>
          <Paginate
            :modelName="transfers"
            :modelType="Transfers"
            />
        </div>
      </div>
    </div>
  </Layout>
</template>
<style scoped>
    .ml-1{margin-left: 1.2rem;}
</style>
<script>
import Layout from "../../Shared/Layout.vue";
import { Link, Head } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';
import Paginate from '../../Shared/Paginate.vue';
import { onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia'
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
  name: "Transfers",
  props: {
    transfers: Object,
    licence: Object,
    success: String,
    error: String,
    errors: Object
  },
  setup(props){

    Inertia.on('navigate', (event) => {
          Inertia.visit(`${event.detail.page.url}`, { preserveState: true, preserveScroll: true });
        })

    function limit(string='', limit=25){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
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

        onMounted(() => {
          if(props.success){
            notify(props.success)
          }else if(props.error){
            notify(props.error)
          }
        });

    return {
      limit,
      notify,
      toast
    }
  },

  components: {
    Layout,
    Link,
    Head,
    Banner,
    Paginate
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

