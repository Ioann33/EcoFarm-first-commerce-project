<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

<div class="page-content header-clear-medium">


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


    <div class="card card-style">
        <div class="content mt-0 mb-0">
            <div class="list-group list-custom-large short-border">

                <router-link :to="{name: 'makeOrder'}" v-if="order_out">
                    <i class="fa  bg-green-dark rounded-s"></i>
                    <span>Заказать товар</span>
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
            ddd: 'Test name button',
            order_in: false,
            order_out: false,
            move_in: false,
            move_out: false,
            money_in: false,
            money_out: false,
            storage_id: null,
            storage_name: null,
            balance: 343.45

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
        console.log('updated')
        init_template2()
    },
    methods: {

    }

}
</script>


