import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/Home.vue'


const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: import('../views/Home.vue')
        },
        {
            path: '/a',
            name: 'about',
            // route level code-splitting
            // this generates a separate chunk (About.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: HomeView
            //component: () => import('../views/AboutView.vue')
        }
    ]
})

export default router
