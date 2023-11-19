<template>
<Layout>
  <Head title="Dashboard" />
  <div class="py-4 container-fluid">
    <div class="row mb-4">
      <div class="col-lg-12 position-relative z-index-2">
        <div class="row">
          <Link :href="`/licences`" class="col-lg-3 col-md-6 col-sm-6">
            <mini-statistics-card
              :title="{ text: 'Licences', value: licences }"
              :icon="{
                name: 'wine_bar',
                color: 'text-white',
                background: 'dark',
              }"
            />
          </Link>
          <Link :href="`/companies`" class="col-lg-3 col-md-6 col-sm-6 mt-lg-0 mt-4">
            <mini-statistics-card
              :title="{ text: 'Companies', value: companies }"
              :icon="{
                name: 'store',
                color: 'text-white',
                background: 'primary',
              }"
            />
          </Link>
          <Link :href="`/people`" class="col-lg-3 col-md-6 col-sm-6 mt-lg-0 mt-4">
            <mini-statistics-card
              :title="{ text: 'People', value: people }"
              :icon="{
                name: 'groups',
                color: 'text-white',
                background: 'success',
              }"
            />
            </Link>
          <Link :href="`/temp-licences`" class="col-lg-3 col-md-6 col-sm-6 mt-lg-0 mt-4">
            <mini-statistics-card
              :title="{ text: 'Temporary Licences', value: temp_licences }"
              :icon="{
                name: 'schedule',
                color: 'text-white',
                background: 'info',
              }"
            />
            </Link>
        </div>
      
        <div class=" row mt-4">
          <div class="d-none col-lg-6 col-md-6 mt-4">
            <chart-holder-card
              title="Licences"
            >
              <reports-bar-chart
                :chart="{
                  labels: month,
                  datasets: {
                    label: 'Licences',
                    data: [50, 80, 20, 10, 22, 50, 10, 40, 55, 70, 65, 15, 25],
                  },
                }"
              />
            </chart-holder-card>
          </div>
          <div class="invisible col-12 col-12 mt-4">
            <chart-holder-card
              title="Licences"
              color="success"
            >
              <reports-line-chart
                :chart="{
                  labels: [
                    'Jan',
                    'Feb',
                    'Mar',
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
                    data: [jan, feb,mar,apr,may,jun,jul,aug,sep,oct,nov,dec],
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
import Layout from "../Shared/Layout.vue";
import ChartHolderCard from "./components/ChartHolderCard.vue";
import ReportsBarChart from "@/examples/Charts/ReportsBarChart.vue";
import ReportsLineChart from "@/examples/Charts/ReportsLineChart.vue";
import MiniStatisticsCard from "./components/MiniStatisticsCard.vue";
import { Link, Head } from '@inertiajs/inertia-vue3';

export default {
  name: "dashboard",
  props: {
    licences: Number,
    companies: Number,
    people: Number,
    temp_licences: Number,
    count_group_licences: Object,
    jan: Number,
    feb: Number,
    mar: Number,
    apr: Number,
    may: Number,
    jun: Number,
    jul: Number,
    aug: Number,
    sep: Number,
    oct: Number,
    nov:Number,
    dec: Number
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
    Link,
    Head
},
};
</script>
