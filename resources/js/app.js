import './bootstrap';
import './bootstrap.min'
import './custom'

import { createApp } from 'vue';
// import { createPinia } from 'pinia'

import App from './Page/App'
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




createApp(App)
    .use(router)
//    .use(createPinia())
    .mount("#app")



//new Vue(Vue.util.extend({ router }, app)).$mount('#app');

