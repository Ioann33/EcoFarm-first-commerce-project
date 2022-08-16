<template>
    <div class="page-content header-clear-medium">
        <!-- ERROR -->  <error :message="message"></error>
        <!-- cardBalance --> <card-balance :storage_id="my_storage_id"></card-balance>


<div class="row">
    <div class="col-4 ps-3 pe-0">
        <div class="card card-style mx-0 mb-3">
            <div class="p-3 bg-yellow-dark ">
                <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">Готовая Продукция </h4>
                <h1 class="font-700 font-34 opacity-60 mb-0 text-center">
                    {{ this.costReady }}</h1>
            </div>
        </div>
    </div>
    <div class="col-4 ps-1 pe-0">
        <div class="card card-style mx-0 mb-3">
            <div class="p-3 bg-yellow-dark ">
                <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">товаров </h4>
                <h1 class="font-700 font-34 opacity-60 mb-0 text-center">
                    {{ this.costIngredients }}</h1>
            </div>
        </div>
    </div>
    <div class="col-4 ps-1 pe-3">
        <div class="card card-style mx-0 mb-3">
            <div class="p-3 bg-yellow-dark ">
                <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">ИТОГО </h4>
                <h1 class="font-700 font-34 opacity-60 mb-0 text-center">
                    {{ this.costProductsTotal }}</h1>
            </div>
        </div>
    </div>
</div>

        <!-- КУПИТЬ / ПРОДАТЬ -->
<!--        <div class="card card-style" style="padding-top: 12px;">-->
            <div class="row mb-1">

                <div class="col-6 ps-3 pe-2">
                    <!-- КУПИТЬ -->
                    <router-link :to="{name: 'buyProducts'}">
                        <div class="card card-style mx-0 mb-3">
                            <div class="p-3 bg-blue-dark text-center">
                                <h1 class="font-700 font-34  opacity-60 mb-0 text-center">
                                    Купить </h1>
                            </div>
                        </div>
                    </router-link>
                </div>


                <div class="col-6 ps-2 pe-3">
                    <!-- ПРОДАТЬ -->
                    <router-link :to="{name: 'saleProducts'}">
                        <div class="card card-style mx-0 mb-3">
                            <div class="p-3 bg-blue-dark text-center">
                                <h1 class="font-700 font-34  opacity-60 mb-0 text-center">
                                    Продать </h1>
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>
<!--        </div>-->

        <!--ПЕРЕДАТЬ ТОВАР    -->
        <div class="card card-style" style="padding-top: 12px;">
            <div class="content mb-3 mt-0">
                <div class="list-group list-custom-small">

                    <!--сделать заказ на перемещение продукции                -->
<!--                    <a href="#">-->
                        <router-link :to="{name: 'MoveGoods'}">
                            <i class="fa bg-green-dark rounded-s"></i>
                            <span class="font-20">Передать продукцию</span>
                        </router-link>
<!--                    </a>-->

                    <div class="row mb-n2">
                        <div class="col-6 ps-2 pe-2">
                            <!-- кнопка - Список продукции - на рассмотрении      -->
                            <!-- на рассмотрении --><card-movement-out-opened :storage_id="my_storage_id"></card-movement-out-opened>
                        </div>
                        <div class="col-6 ps-2" >
                            <!-- кнопка -  Список продукции, которую нужно принять -->
                            <!-- принять --> <card-movement-in-opened :storage_id="my_storage_id"></card-movement-in-opened>
                        </div>
                    </div>

                    <!--Список продукции на складе-->
<!--                    <a href="#">-->
<!--                        <router-link :to="{name: 'pageListGoods', params: {type: 'available'}}">-->
<!--                            <i class="fa bg-green-dark rounded-s"></i>-->
<!--                            <span class="font-20">Товары на складе: {{ my_storage_name }}</span>-->
<!--                        </router-link>-->
<!--                    </a>-->


                </div>

            </div>


        </div>
        <!--ПЕРЕДАТЬ ТОВАР    -->
    </div>
</template>

<script>
    import Error from "../../Components/Error";
    import CardBalance from "../../Components/cardBalance";
    import cardMovementOutOpened from "../../Components/cardMovementOutOpened";
    import cardMovementInOpened from "../../Components/cardMovementInOpened";


    export default {
        name: "pageProduce",
        components: {
            Error,
            CardBalance,
            cardMovementInOpened, cardMovementOutOpened
        },
        data() {
            return {
                message: '',    // сообщения системы
                costReady: -0,
                costIngredients: -0,
                costProductsTotal: -0
            }
        },
        computed: {},
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')
        },

        async mounted() {

            await axios.get('/api/costGoodsOnStock/'+this.my_storage_id+'/ready')
                .then(res => {
                    this.costReady = parseInt(res.data.sum)
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })

            await axios.get('/api/costGoodsOnStock/'+this.my_storage_id+'/ingredients')
                .then(res => {
                    this.costIngredients = parseInt(res.data.sum)
                }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })

            this.costProductsTotal = this.costIngredients + this.costReady

        },
        methods: {
        }
    }
</script>
