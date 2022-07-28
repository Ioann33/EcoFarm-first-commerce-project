<script setup>
import {useStorage} from "../stores/storages";


const Storage = useStorage()
Storage.getMyStorages()
// {{ Storage.store_message }}

// import { storeToRefs } from 'pinia'
// const { store_message } = storeToRefs(useStorage())
// {{ store_message }}


</script>

<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

<div class="page-content header-clear-medium">
<!--{{ store_message }}-->
    {{ Storage.store_message }}
    {{ Storage.storages_id }}
    ddddd{{ Storage.my_storage_id }}
    {{ message }}

    <div data-card-height="150" style="height: 150px" class="card card-style rounded-m shadow-xl preload-img" data-src="images/teplitsa.webp">
        <div class="card-top mt-3 ms-3">
            <h1 class="color-white mb-0 mb-n2 font-22">{{ storage_name }} </h1>
            <p class="bottom-0 color-white opacity-50 under-heading font-11 font-700">{{storage_name}}</p>
        </div>
        <div class="card-center text-center">
            <h1 class="color-white fa-4x">{{ balance }} ₴ </h1>
            <p class="color-white opacity-70 font-11 mb-n5">Баланс</p>
        </div>

        <div class="card-overlay bg-black opacity-40"></div>
    </div>


    <div class="card card-style" v-if="order_in">
        <div class="content mb-0 mt-0">
            <div class="list-group list-custom-small" >
                <router-link :to="{name: 'makeOrder'}">
                    <i class="fa  bg-green-dark rounded-s"></i>
                    <span>Заказы, заявки</span>
                    <i class="fa fa-angle-right"></i>
                </router-link>

                <div class="row mb-n2">
                    <div class="col-4 ps-2 pe-2">
                        <div class="card card-style mx-0 mb-3">
                            <div class="p-3 bg-blue-dark">
                                <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">Открытые заявки</h4>
                                <h1 class="font-700 font-34  opacity-60 mb-0">{{count_order_out}}</h1>
                                <i class="fa fa-arrow-right float-end mt-n3 opacity-20"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-4 pe-2">
                        <div class="card card-style mx-0 mb-3">
                            <div class="p-3 bg-red-dark">
                                <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">Отмененные</h4>
                                <h1 class="font-700 font-34 opacity-60  mb-0">{{count_order_canceled}}</h1>
                                <i class="fa fa-arrow-right float-end mt-n3 opacity-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 ps-2">
                        <div class="card card-style mx-0 mb-3">
                            <div class="p-3 bg-yellow-dark ">
                                <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">В работе</h4>
                                <h1 class="font-700 font-34 opacity-60 mb-0">{{count_order_progres}}</h1>
                                <i class="fa fa-arrow-right float-end mt-n3 opacity-20"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <router-link :to="{name: 'pageOrders', params: { type: 'in' }}" style="margin-left: 50px">
                    <i class="fa font-14 fa-bars rounded-xl shadow bg-blue-dark"></i>
                    <span>Открытые заявки</span>
                    <span class="badge bg-blue-dark font-10">{{count_order_out}}</span>
                    <i class="fa fa-angle-right"></i>
                </router-link>

                <router-link :to="{name: 'pageOrders', params: { type: 'cancelled' }}" style="margin-left: 50px">
                    <i class="fa font-14 fa-cancel rounded-xl shadow bg-red-dark"></i>
                    <span>Отмененные</span>
                    <span class="badge bg-red-dark">{{count_order_canceled}}</span>
                    <i class="fa fa-angle-right"></i>
                </router-link>

                <router-link :to="{name: 'pageOrders', params: { type: 'progress' }}" style="margin-left: 50px">
                    <i class="fa font-14 fa-star rounded-xl shadow bg-yellow-dark"></i>
                    <span>В работе</span>
                    <span class="badge bg-yellow-dark">{{count_order_progres}}</span>
                    <i class="fa fa-angle-right"></i>
                </router-link>

            </div>
        </div>
    </div>


    <div class="card card-style" v-if="order_in">
        <div class="content mb-0 mt-0">
            <div class="list-group list-custom-small" >
                <router-link :to="{name: 'makeOrder'}">
                    <i class="fa  bg-green-dark rounded-s"></i>
                    <span>Входящие заявки</span>
                    <i class="fa fa-angle-right"></i>
                </router-link>
                <a href="#" style="margin-left: 50px">
                    <i class="fa font-14 fa-bars rounded-xl shadow bg-blue-dark"></i>
                    <span>Открытые заявки</span>
                    <span class="badge bg-red-dark font-10">{{count_order_out}}</span>
                    <i class="fa fa-angle-right"></i>
                </a>
                <a href="#" style="margin-left: 50px">
                    <i class="fa font-14 fa-cancel rounded-xl shadow bg-red-dark"></i>
                    <span>Отмененные</span>
                    <span class="badge bg-green-dark">{{count_order_canceled}}</span>
                    <i class="fa fa-angle-right"></i>
                </a>

                <a href="#" style="margin-left: 50px">
                    <i class="fa font-14 fa-star rounded-xl shadow bg-yellow-dark"></i>
                    <span>В работе</span>
                    <span class="badge bg-yellow-dark">{{count_order_progres}}</span>
                    <i class="fa fa-angle-right"></i>
                </a>

            </div>
        </div>
    </div>

    <div class="card card-style">
        <div class="content mt-0 mb-0">
            <div class="list-group list-custom-large short-border">

                <router-link :to="{name: 'makeOrder'}" v-if="order_out">
                    <i class="fa  bg-green-dark rounded-s"></i>
                    <span>Заказать товар </span>
                    <strong>товар или продукцию</strong>
                    <i class="fa fa-angle-right"></i>
                </router-link>

                <a href="component-ad-boxes.html" v-if="order_in">
                    <i class="fa  bg-green-dark rounded-s"></i>
                    <span>Входящие заявки</span>
                    <strong>на товар или продукцию</strong>
                    <i class="fa fa-angle-right"></i>
                </a>

                <a href="component-ad-boxes.html" v-if="move_in">
                    <i class="fa bg-yellow-dark rounded-s"></i>
                    <span>Принять товар</span>
                    <strong>принять товар</strong>
                    <i class="fa fa-angle-right"></i>
                </a>

                <a href="component-ad-boxes.html" v-if="move_out">
                    <i class="fa bg-yellow-dark rounded-s"></i>
                    <span>Отправить</span>
                    <strong>отправить продукцию</strong>
                    <i class="fa fa-angle-right"></i>
                </a>

                <a href="component-ad-boxes.html" v-if="money_in">
                    <i class="fa bg-blue-dark fa-dollar-sign rounded-s">  </i>
                    <span>Получить деньги</span>
                    <strong>отправить продукцию</strong>
                    <i class="fa fa-angle-right"></i>
                </a>

                <a href="component-ad-boxes.html" v-if="money_out">
                    <i class="fa bg-blue-dark fa-dollar-sign rounded-s">  </i>
                    <span>Отправить деньги</span>
                    <strong>отправить продукцию</strong>
                    <i class="fa fa-angle-right"></i>
                </a>

            </div>
        </div>
    </div>




</div>
        <nav-bar-menu></nav-bar-menu>

    </div>
</template>

<script>
import headBar from "../components/headBar";
import NavBar from "../Components/NavBar";
import NavBarMenu from "../Components/NavBarMenu";



export default {
    name: "Home",
    components:{
        headBar, NavBar, NavBarMenu,
    },
    data(){
        return {
            message: '',
            ddd: 'Test name button',
            order_in: false,
            order_out: false,
            move_in: false,
            move_out: false,
            money_in: false,
            money_out: false,
            storage_id: null,
            storage_name: null,
            balance: 343.45,
            count_order_out: 0,
            count_order_in: 0,
            count_order_canceled: 0,
            count_order_progres: 0

        }
    },
    mounted() {
        console.log('Component views/Home mounted....')

        this.storage_id = localStorage.getItem('my_storage_id');
        console.log('show my_storage_id: ' + this.storage_id)

        if(this.storage_id == null) {
            this.$router.push({name: 'selectStorage'});
        }

        this.order_in = localStorage.getItem('order_in');
        this.order_out = localStorage.getItem('order_out');
        this.money_in = localStorage.getItem('money_in');
        this.money_out = localStorage.getItem('money_out');
        this.move_in = localStorage.getItem('move_in');
        this.move_out = localStorage.getItem('move_out');
        this.storage_name = localStorage.getItem('storage_name');


        // document.onreadystatechange = () => {
        //     if (document.readyState == "complete") {
        //         console.log('onreadystatechange=complete in view/Home')
        //     }
        // }

        // this.$nextTick(function () {
        //     console.log('$nextTick')
        // })

        console.log('Component views/Home mounted......done!')
    },
    updated() {
        this.loadStoragesParams()
        console.log('updated')
        init_template2()
    },
    methods: {
        loadStoragesParams(){

            axios.get('/api/getStorageOrderIn/'+ this.storage_id).then(res => {
                this.count_order_out = res.data.data.out
                this.count_order_in = res.data.data.in
                this.count_order_canceled = res.data.data.canceled
                this.count_order_progres = res.data.data.progress

            }).catch(err => {
                console.log(err.response)
                console.log(err.response.data.message)
                console.log(err.response.status)
                this.test = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })
            console.log('load')
        }
    }

}
</script>


