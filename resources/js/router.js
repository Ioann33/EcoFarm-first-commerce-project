import vueRouter from 'vue-router';
import Vue from 'vue';

Vue.use(vueRouter);

const router = new vueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            name: 'root',
            component: Welcome
        },
    ]
})
