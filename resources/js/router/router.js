import { createRouter, createWebHistory, createWebHashHistory } from 'vue-router'

import Welcome from "../views/Welcome";

const router = createRouter({
    //history: createWebHistory(),
    history: createWebHashHistory(),
    routes: [
        {
            path: '/',
            name: 'welcome',
            component: Welcome
        },
        {
            path: '/home',
            name: 'home',
            component: () => import('../views/Home')
        },
        {
            path: '/selectStorage',
            name: 'selectStorage',
            component: () => import('../views/pageSelectStorage')
        },
        {
            path: '/pageOrders/:dir/:status',
            name: 'pageOrders',
            component: () => import('../views/pageOrders')
        },
        {
            path: '/pageMovements/:dir/:status',
            name: 'pageMovements',
            component: () => import('../views/pageMovements')
        },
        {
//@todo не используется ? 2022-09-08
            path: '/pageSetPriceAndPull/:movement_id',
            name: 'SetPriceAndPull',
            component: () => import('../views/pageSetPriceAndPull')
        },
        {
            path: '/pageListGoods/:type',
            name: 'pageListGoods',
            component: () => import('../views/pageListGoods')
        },
        {
            path: '/pageListGoodsOnStorages',
            name: 'pageListGoodsOnStorages',
            component: () => import('../views/pageListGoodsOnStorages')
        },
        {
            path: '/makeOrder',
            name: 'makeOrder',
            component: () => import('../views/pageMakeOrder')
        },
        {
            path: '/MoveGoods/:order_id?',
            name: 'MoveGoods',
            component: () => import('../views/pageMoveGoods')
        },
        {
            path: '/MoveGoods2/:order_id?',
            name: 'MoveGoods2',
            component: () => import('../views/pageMoveGoods2')
        },
        {
            path: '/GrowMoveGoods/',
            name: 'GrowMoveGoods',
            component: () => import('../views/pageGrowMoveGoods')
        },
        {
            path: '/makeProducts',
            name: 'makeProducts',
            component: () => import('../views/pageMakeProducts')
        },
        {
            path: '/trashProducts/',
            name: 'trashProducts',
            component: () => import('../views/pageTrashProducts')
        },
        {
            path: '/saleProducts',
            name: 'saleProducts',
            component: () => import('../views/pageSaleProducts')
        },
        {
            path: '/preSale',
            name: 'preSale',
            component: () => import('../views/pagePreSale')
        },
        {
            path: '/transferMoney',
            name: 'transferMoney',
            component: () => import('../views/pageTransferMoney')
        },,
        {
            path: '/FinanceDashboard',
            name: 'FinanceDashboard',
            component: () => import('../views/pageFinanceDashboard')
        },
        {
            path: '/buyProducts',
            name: 'buyProducts',
            component: () => import('../views/pageBuyProducts')
        },
        {
            path: '/Grow',
            name: 'grow',
            component: () => import('../views/pageGrow')
        },
        {
            path: '/CreateGoods',
            name: 'CreateGoods',
            component: () => import('../views/pageCreateGoods')
        },
        {
            path: '/Spend/:type?',
            name: 'Spend',
            component: () => import('../views/pageSpend'),

        },
        {
            path: '/SpendInvest/:type',
            name: 'SpendInvest',
            component: () => import('../views/pageSpendInvest'),
        },
        {
            path: '/PermitGoods/:goods_id?/:goods_name?',
            name: 'PermitGoods',
            component: () => import('../views/pagePermitGoods')
        },
        {
            path: '/ListPreSale',
            name: 'ListPreSale',
            component: () => import('../views/pageListPreSale')
        },
        {
            path: '/PreSaleProducts',
            name: 'PreSaleProducts',
            component: () => import('../views/pagePreSaleProducts')
        },
        {
            path: '/permitUsers',
            name: 'permitUsers',
            component: () => import('../views/pagePermitUsers')
        },
        {
            path: '/addUser',
            name: 'addUser',
            component: () => import('../views/pageAddUser')
        },
        {
            path: '/addStorage',
            name: 'addStorage',
            component: () => import('../views/pageAddStorage')
        },
        {
            path: '/cookStat',
            name: 'cookStat',
            component: () => import('../views/pageCookStat')
        },
        {
            path: '/correctGoods',
            name: 'correctGoods',
            component: () => import('../views/pageCorrectGoods')
        },
        {
            path: '/editGoods',
            name: 'editGoods',
            component: () => import('../views/pageEditGoods')
        },
        {
            path: '/listMovements',
            name: 'listMovements',
            component: () => import('../views/pageListMovements')
        },
        {
            path: '/listSales',
            name: 'listSales',
            component: () => import('../views/pageListSales')
        },
        {
            path: '/listMoney/:type?',
            name: 'listMoney',
            component: () => import('../views/pageListMoney')
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
        console.log('(route.js) Redirect to home')
        return next({
            name: 'home'
        })
    }

    next()
})

export default router
