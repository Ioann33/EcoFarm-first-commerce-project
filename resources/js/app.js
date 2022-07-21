require('./bootstrap');

window.Vue = require('vue').default;


Vue.component('App', require('./src/App.vue').default);


const app = new Vue({
    el: '#app',
});


