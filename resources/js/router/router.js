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
        },
        {
            path: '/selectStorage',
            name: 'selectStorage',
            component: () => import('../views/SelectStorage')
        },
        {
            path: '/pageOrders/:type/:status',
            name: 'pageOrders',
            component: () => import('../views/pageOrders')
        },
        {
            path: '/makeOrder',
            name: 'makeOrder',
            component: () => import('../views/pageMakeOrder')
        },
        {
            path: '/moveGoods',
            name: 'moveGoods',
            component: () => import('../views/pageMoveGoods')
        }
    ]
})

router.beforeEach((to, from, next)=>{
    const token = localStorage.getItem('x_xsrf_token')

    if(!token){
        if(to.name==='welcome'){
            return next()
        } else {
            return next({
                name: 'welcome'
            })
        }
    }

    if((to.name==='welcome') && token){
        return next({
            name: 'home'
        })
    }

    next()
})

export default router
