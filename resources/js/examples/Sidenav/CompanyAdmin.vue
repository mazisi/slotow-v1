<template>
    <li class="nav-item" v-if="$page.props.currentRoute == 'view_my_licences' || $page.props.currentRoute == 'company_registration'">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'company_registration'}"
         :href="`/company/registration?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">app_registration</i>
        </div>
        <span class="nav-link-text ms-1">Registration</span>
        
        </Link>
      </li>

      <li class="nav-item" v-if="$page.props.currentRoute == 'view_my_licences'">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'my_renewals'}"
         :href="`/company/my-renewals?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">autorenew</i>
        </div>
        <span class="nav-link-text ms-1">Renewals</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'view_my_licences'"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'my_transfer_history'}"
         :href="`/company/my-transfer-history?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">group</i>
        </div>
        <span class="nav-link-text ms-1">Transfers</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'view_my_licences'
             || $page.props.currentRoute == 'my_nominations'
             || $page.props.currentRoute == 'view_nomination'"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'my_nominations' 
        || $page.props.currentRoute == 'view_nomination'}"
         :href="`/company/nominations?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">move_down</i>
        </div>
        <span class="nav-link-text ms-1">Nominations</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'view_my_licences'
             || $page.props.currentRoute == 'company_alterations'
             || $page.props.currentRoute == 'view_company_alteration'"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'company_alterations' 
        || $page.props.currentRoute == 'view_company_alteration'}"
         :href="`/company/alterations?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">settings_suggest</i>
        </div>
        <span class="nav-link-text ms-1">Alterations</span>
        
        </Link>
      </li>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue3';

export default{
    components: {
        Link
    }
}
</script>