import './bootstrap';

window.Vue = require('vue').default;

import router from "./router";


const app = new Vue({
    el: '#app',
    components: {

    },
    router
});
