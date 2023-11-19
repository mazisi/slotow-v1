<template>
<Layout>
  <div class="py-4 container-fluid">
    <div class="row mb-4">
      <div class="col-lg-12 position-relative z-index-2">
        <div class="row">
          <Link :href="`/company/licences`" class="col-lg-3 col-md-6 col-sm-6">
            <mini-statistics-card
              :title="{ text: 'Licences', value: licences }"
              :icon="{
                name: 'weekend',
                color: 'text-white',
                background: 'dark',
              }"
            />
          </Link>
          <Link :href="`/company/my-companies`" class="col-lg-3 col-md-6 col-sm-6 mt-lg-0 mt-4">
            <mini-statistics-card
              :title="{ text: 'Companies', value: user.company.length }"
              :icon="{
                name: 'leaderboard',
                color: 'text-white',
                background: 'primary',
              }"
            />
          </Link>
          <!-- <Link :href="`people`" class="col-lg-3 col-md-6 col-sm-6 mt-lg-0 mt-4">
            <mini-statistics-card
              :title="{ text: 'Licence Renewals', value: licences.licence_renewals }"
              :icon="{
                name: 'person',
                color: 'text-white',
                background: 'success',
              }"
            />
            </Link> -->
          <Link :href="`/company/my-temp-licences`" class="col-lg-3 col-md-6 col-sm-6 mt-lg-0 mt-4">
            <mini-statistics-card
              :title="{ text: 'Temporary Licences', value: temp_licences }"
              :icon="{
                name: 'weekend',
                color: 'text-white',
                background: 'info',
              }"
            />
            </Link>
        </div>

        <div class="invisible row mt-4">
          <div class="col-lg-6 col-md-6 mt-4">
            <chart-holder-card
              title="Licences"
            >
              <reports-bar-chart
                :chart="{
                  labels: month,
                  datasets: {
                    label: 'Licences',
                    data: [50, 20, 10, 22, 50, 10, 40, 55, 70, 65, 15, 25],
                  },
                }"
              />
            </chart-holder-card>
          </div>
          <div class="col-lg-6 col-md-6 mt-4">
            <chart-holder-card
              title="Licences"
              color="success"
            >
              <reports-line-chart
                :chart="{
                  labels: [
                    'Apr',
                    'May',
                    'Jun',
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec',
                  ],
                  datasets: {
                    label: 'Licences',
                    data: [50, 40, 300, 320, 500, 350, 200, 230, 500],
                  },
                }"
              />
            </chart-holder-card>
          </div>
        </div>
      </div>
    </div>
   
  </div>
  </Layout>
</template>
<script>
import Layout from "../../Shared/Layout.vue";
import ChartHolderCard from "./../components/ChartHolderCard.vue";
import ReportsBarChart from "@/examples/Charts/ReportsBarChart.vue";
import ReportsLineChart from "@/examples/Charts/ReportsLineChart.vue";
import MiniStatisticsCard from "./../components/MiniStatisticsCard.vue";
import { Link } from '@inertiajs/inertia-vue3';

export default {
  name: "dashboard-company-admin",
  props: {
    licences: Number,
    user: Object,
    temp_licences: Number
  },

  data() {
    return {
    };
  },
  components: {
    ChartHolderCard,
    ReportsBarChart,
    ReportsLineChart,
    MiniStatisticsCard,
    Layout,
    Link
},
};
</script>
