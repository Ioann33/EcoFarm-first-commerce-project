import './bootstrap';

import { createApp } from 'vue';

import app from './Page/app.vue'
import router from './router/router'

// import Home from "./views/Home.vue";
//
// const routes = [
//     { path: '/', component: Home },
//     //{ path: '/about', component: About },
// ]
//
// const router = VueRouter.createRouter({
//     // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
//     history: VueRouter.createWebHashHistory(),
//     routes, // short for `routes: routes`
// })

createApp(app)
    .use(router)
    .mount("#app")



//new Vue(Vue.util.extend({ router }, app)).$mount('#app');

