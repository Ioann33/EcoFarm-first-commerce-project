import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home'
import Welcome from "../views/Welcome";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'welcome',
            // component: import('../views/Welcome.vue')
            component: Welcome
        },
        {
            path: '/home',
            name: 'home',
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: Home
            //component: () => import('../views/AboutView.vue')
        }
    ]
})

export default router
