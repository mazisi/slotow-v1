require('./bootstrap');

import { createApp, h } from 'vue';
import store from "./store";
import "./assets/css/nucleo-icons.css";
import "./assets/css/nucleo-svg.css";
import MaterialDashboard from "./material-dashboard";
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { Link } from '@inertiajs/inertia-vue3';
import Vue3Toasity from 'vue3-toastify';

import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(store)
            .use(Vue3Toasity,{
                autoClose: 3000
              })
            .use(MaterialDashboard)
            .component('InertiaLink', Link)
            .component('Datepicker', Datepicker)
            .mixin({ methods: { route } })
            .mount(el);
            
    },
});

InertiaProgress.init({ color: '#FF0000' });



