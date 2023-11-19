<script>
import { Link } from '@inertiajs/inertia-vue3';

export default {
  name: "SidenavList",
  props: {
    cardBg: String,
  },

  components: {
    Link
  }
};
</script>
<template>
     <li v-if="$page.props.currentRoute == 'view_licence'"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'new-application'}"
         :href="`/new-application?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">app_registration</i>
        </div>
        <span class="nav-link-text ms-1">New Application</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'view_licence' && $page.props.viewed_licence.status >= 15"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'renew_licence'}"
         :href="`/renew-licence?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">autorenew</i>
        </div>
        <span class="nav-link-text ms-1">Renewals</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'view_licence' && $page.props.viewed_licence.status >= 15"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'transfer_history'}"
         :href="`/transfer-history?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">move_down</i>
        </div>
        <span class="nav-link-text ms-1">Transfers</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'transfer_history' && $page.props.viewed_licence.status >= 15"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'transfer_licence'}"
         :href="`/transfer-licence?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">move_down</i>
        </div>
        <span class="nav-link-text ms-1">New Transfer</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'view_licence' && $page.props.viewed_licence.status >= 15"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'transfer_licence'}"
         :href="`/nominations?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">group</i>
        </div>
        <span class="nav-link-text ms-1">Nominations</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'nominations' && $page.props.viewed_licence.status >= 15"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'nominate'}"
         :href="`/nominate?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">person_add</i>
        </div>
        <span class="nav-link-text ms-1">New Nomination</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'view_licence' && $page.props.viewed_licence.status >= 15"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'alterations'}"
         :href="`/alterations?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">settings_suggest</i>
        </div>
        <span class="nav-link-text ms-1">Alterations</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'alterations' && $page.props.viewed_licence.status >= 15"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'transfer_licence'}"
         :href="`/new-alteration?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">settings_suggest</i>
        </div>
        <span class="nav-link-text ms-1">New Alteration</span>
        
        </Link>
      </li>
</template>