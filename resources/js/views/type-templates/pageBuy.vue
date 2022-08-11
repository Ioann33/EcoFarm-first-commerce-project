<template>
    <div class="page-content header-clear-medium">
        <!-- ERROR -->  <error :message="message"></error>
        <!-- cardBalance --> <card-balance :storage_id="my_storage_id"></card-balance>

        <!-- КУПИТЬ / ПРОДАТЬ -->
        <div class="card card-style" style="padding-top: 12px;">
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
        <!-- КУПИТЬ / ПРОДАТЬ -->


        <!--ПЕРЕДАТЬ ТОВАР    -->
        <div class="card card-style" style="padding-top: 12px;">
            <div class="content mb-3 mt-0">
                <div class="list-group list-custom-small">

                    <!--сделать заказ на перемещение продукции                -->
                    <a href="#">
                        <router-link :to="{name: 'MoveGoods'}">
                            <i class="fa bg-green-dark rounded-s"></i>
                            <span class="font-20">Передать продукцию</span>
                        </router-link>
                    </a>

                    <div class="row mb-n2">
                        <div class="col-6 ps-2 pe-2">
                            <!-- кнопка - Список продукции - на рассмотрении      -->
                            <!-- на рассмотрении --><card-movement-out-opened :storage_id="my_storage_id"></card-movement-out-opened>
                        </div>
                        <div class="col-6 ps-2" >
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


    export default {
        name: "pageProduce",
        components: {
            Error,
            CardBalance,
            cardMovementOutOpened
        },
        data() {
            return {
                balance: 0,
                my_storage_name: '',
                message: ''
            }
        },
        computed: {},
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')
        },
        mounted() {
        },
        methods: {

        }

    }
</script>

<style scoped>

</style>
