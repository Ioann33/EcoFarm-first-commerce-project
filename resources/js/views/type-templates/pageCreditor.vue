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

                    <router-link :to="{name: 'ListPreSale'}">
                        <i class="fa bg-blue-dark fa-dollar-sign rounded-s">  </i>
                        <span>Незакрытые сделки</span>
                        <strong>Список не закрытых сделок</strong>
                        <i class="fa fa-angle-right"></i>
                    </router-link>
                </div>

            </div>


        </div>
        <!--ПЕРЕДАТЬ ТОВАР    -->
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
