<template>
    <div class="page-content header-clear-medium">
        <!-- ERROR -->  <error :message="message"></error>
        <!-- cardBalance --> <card-balance :storage_id="my_storage_id"></card-balance>


        1. продажа товара без отгрузки <br>
        2. просмотр всех сделок без откгрузки <br>
        3. кнопка отгрузки в списке всех сделок, если такое кол-во товара есть на текущем складе <br>

        <!--ПЕРЕДАТЬ ТОВАР    -->
        <div class="card card-style" style="padding-top: 12px;">
            <div class="content mb-3 mt-0">
                <div class="list-group list-custom-small">

                    <!--сделать заказ на перемещение продукции                -->
                    <router-link :to="{name: 'MoveGoods'}">
                        <i class="fa bg-green-dark rounded-s"></i>
                        <span class="font-20">Передать продукцию</span>
                    </router-link>

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

        <div class="row text-center mb-0 mt-n2">
            <router-link :to="{name: 'preSale'}" class="col-6 ps-3">
                <div class="card card-style me-2 mb-3">
                    <i class="fa fa-arrow-up color-red-dark fa-2x mt-3"></i>
                    <h1 class="pt-2 font-18">Продажа без отгрузки</h1>
                    <p class="font-11 opacity-50 mt-n2 mb-3">деньги поступят на счет этого склада. движение товара отсутствует</p>
                </div>
            </router-link>
            <router-link :to="{name: 'SpendInvest', params: {type: 'in'}}" class="col-6 ps-0">
                <div class="card card-style ms-2 mb-3">
                    <i class="fa fa-arrow-down color-green-dark fa-2x mt-3"></i>
                    <h1 class="pt-2 font-18">Посмотреть открытые сделки</h1>
                    <p class="font-11 opacity-50 mt-n2 mb-3">список сделок, и закрытие сделки по наличию товара на текущем складе</p>
                </div>
            </router-link>
        </div>
    </div>
</template>

<script>
    import Error from "../../Components/Error";
import cardBalance from "../../Components/cardBalance";
import cardMovementOutOpened from "../../Components/cardMovementOutOpened";
import cardMovementInOpened from "../../Components/cardMovementInOpened";
    export default {
        name: "pageProduce",
        components: {
            Error,
            cardBalance,
            cardMovementInOpened, cardMovementOutOpened
        },
        data() {
            return {

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
