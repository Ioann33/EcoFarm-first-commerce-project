<template>
    <div class="page-content header-clear-medium">
        <!-- ERROR -->  <error :message="message"></error>

        <!-- cardBalance --> <card-balance :storage_id="my_storage_id"></card-balance>


        <!--ЗАКАЗЫ-->

        <div class="card card-style" v-if="0">
            <div class="content mb-0 mt-0">
                <div class="list-group list-custom-small">
                    <router-link :to="{name: 'makeOrder'}">
                        <i class="fa  bg-green-dark rounded-s"></i>
                        <span class="font-20">Заказать</span>
                        <i class="fa fa-angle-right"></i>
                    </router-link>

                    <div class="row mb-n2">
                        <div class="col-4 ps-2 pe-2">
                            <router-link :to="{name: 'pageOrders', params: { dir: 'out', status:'opened' }}">
                                <div class="card card-style mx-0 mb-3">
                                    <div class="p-3 bg-blue-dark">
                                        <!--                                <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">Открытые</h4>-->
                                        <h1 class="font-700 font-34  opacity-60 mb-0 text-center">
                                            {{count_order_out_opened}}</h1>

                                    </div>
                                </div>
                            </router-link>
                        </div>

                        <div class="col-4 pe-2">
                            <router-link :to="{name: 'pageOrders', params: { dir: 'out', status: 'canceled' }}">
                                <div class="card card-style mx-0 mb-3">
                                    <div class="p-3 bg-red-dark">
                                        <!--                                <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">Отмененн</h4>-->
                                        <h1 class="font-700 font-34 opacity-60  mb-0 text-center">
                                            {{count_order_out_canceled}}</h1>

                                    </div>
                                </div>
                            </router-link>
                        </div>

                        <div class="col-4 ps-2">
                            <router-link :to="{name: 'pageOrders', params: { dir: 'out', status: 'progress' }}">
                                <div class="card card-style mx-0 mb-3">
                                    <div class="p-3 bg-yellow-dark ">
                                        <!--                                <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">В работе</h4>-->
                                        <h1 class="font-700 font-34 opacity-60 mb-0 text-center">
                                            {{count_order_out_progres}}</h1>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                    </div>

                    <!--Исходящие заявки - открытые                 -->
                    <router-link :to="{name: 'pageOrders', params: { dir: 'out', status:'opened' }}"
                                 style="margin-left: 20px">
                        <i class="fa font-14 fa-bars rounded-xl shadow bg-blue-dark"></i>
                        <span>Открытые заявки</span>
                        <span class="badge bg-blue-dark font-10">{{count_order_out_opened}}</span>
                    </router-link>

                    <!--Исходящие заявки - отмененные                 -->
                    <router-link :to="{name: 'pageOrders', params: { dir: 'out', status: 'canceled' }}"
                                 style="margin-left: 20px">
                        <i class="fa font-14 fa-cancel rounded-xl shadow bg-red-dark"></i>
                        <span>Отмененные</span>
                        <span class="badge bg-red-dark">{{count_order_out_canceled}}</span>
                    </router-link>

                    <!--Исходящие заявки - в работе                 -->
                    <router-link :to="{name: 'pageOrders', params: { dir: 'out', status: 'progress' }}"
                                 style="margin-left: 20px">
                        <i class="fa font-14 fa-star rounded-xl shadow bg-yellow-dark"></i>
                        <span>В работе</span>
                        <span class="badge bg-yellow-dark">{{count_order_out_progres}}</span>
                    </router-link>

                </div>
            </div>
        </div>
        <!--ЗАКАЗЫ-->

        <!--ВХОДЯЩИЕ ЗАЯВКИ-->

        <div class="card card-style" v-if="1">
            <div class="content mb-3 mt-0">
                <div class="list-group list-custom-small">
                    <a href="#">
                        <i class="fa fa-inbox  bg-green-dark rounded-s"></i>
                        <span class="font-20">Входящие заявки</span>
                    </a>


                    <div class="row mb-n2">
                        <div class="col-6 ps-2 pe-2">
                            <router-link :to="{name: 'pageOrders', params: { dir: 'in', status: 'opened' }}">

                                <div class="card card-style mx-0 mb-3">
                                    <div class="p-3 bg-blue-dark">
                                        <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">Открытые</h4>
                                        <h1 class="font-700 font-34  opacity-60 mb-0 text-center">
                                            {{count_order_in_opened}}</h1>

                                    </div>
                                </div>

                            </router-link>
                        </div>


                        <div class="col-6 ps-2">
                            <router-link :to="{name: 'pageOrders', params: { dir: 'in', status: 'progress' }}">
                                <div class="card card-style mx-0 mb-3">
                                    <div class="p-3 bg-yellow-dark ">
                                        <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">В работе</h4>
                                        <h1 class="font-700 font-34 opacity-60 mb-0 text-center">
                                            {{count_order_in_progres}}</h1>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <!--ВХОДЯЩИЕ ЗАЯВКИ-->


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

                        <!-- кнопка - Список продукции - на рассмотрении      -->
                        <div class="col-6 ps-2 pe-2">
                            <router-link :to="{name: 'pageMovements', params: { dir: 'out', status: 'opened' }}">
                                <div class="card card-style mx-0 mb-3">
                                    <div class="p-3 bg-blue-dark">
                                        <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">на
                                            рассмотрении</h4>
                                        <h1 class="font-700 font-34  opacity-60 mb-0 text-center">
                                            {{count_movement_out_opened}}</h1>

                                    </div>
                                </div>
                            </router-link>
                        </div>


                        <!-- кнопка -  Список продукции, которую нужно принять -->
                        <div class="col-6 ps-2" >
                            <router-link :to="{name: 'pageMovements', params: { dir: 'in', status: 'opened' }}">
                                <div class="card card-style mx-0 mb-3">
                                    <div class="p-3 bg-yellow-dark ">
                                        <i class="fa bg-yellow-light rounded-s">  </i>
                                        <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">Принять </h4>
                                        <h1 class="font-700 font-34 opacity-60 mb-0 text-center">
                                            {{count_movement_in_opened}}</h1>
                                    </div>
                                </div>
                            </router-link>
                        </div>


                    </div>

                </div>
            </div>
        </div>




<!--УТИЛИЗАРОВАТЬ ТОВАР    -->
        <div class="row mb-n2 align-content-center p-3">
            <div class="col-6 ">
                <router-link :to="{name: 'trashProducts'}">
                    <div class="card card-style mx-0 mb-5">
                        <div class="p-3 bg-grass-light text-center">
                            <h1 class="font-700 font-34  opacity-60 mb-0 text-center">
                                <i class="fa fa-trash  rounded-s"></i> </h1>
                            <div class="opacity-50">утилизация</div>
                        </div>
                    </div>
                </router-link>
            </div>
            <div class="col-6">
                <router-link :to="{name: 'correctGoods'}">
                    <div class="card card-style mx-0 mb-5">
                        <div class="p-3  bg-fade-teal-dark text-center">
                            <h1 class="font-700 font-34  opacity-60 mb-0 text-center">
                                <i class="fa fa-recycle rounded-s"></i>
                                </h1>
                            <div class="opacity-50">корректировка остатков</div>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>


<!--        <div class="card card-style">-->
<!--            <div class="content mt-0 mb-0">-->
<!--                <div class="list-group list-custom-large short-border">-->



<!--                    <router-link :to="{name: 'PermitGoods'}" v-if="isMain">-->
<!--                        <i class="fa bg-blue-dark fa-dollar-sign rounded-s">  </i>-->
<!--                        <span>Разрешить товар</span>-->
<!--                        <strong>Разрешить выбранный товар на складах</strong>-->
<!--                        <i class="fa fa-angle-right"></i>-->
<!--                    </router-link>-->

<!--                </div>-->
<!--            </div>-->
<!--        </div>-->


        <div class="card card-style">
            <div class="content mt-0 mb-0">
<!--                <div class="list-group list-custom-large short-border">-->
<!--                    &lt;!&ndash;Список продукции на складе&ndash;&gt;-->
<!--                    <router-link :to="{name: 'pageListGoods', params: {type: 'available'}}">-->
<!--                        <i class="fa bg-blue-dark fa-dollar-sign rounded-s">  </i>-->
<!--                        <span>Товары на складе</span>-->
<!--                        <strong>{{ my_storage_name }}</strong>-->
<!--                        <i class="fa fa-angle-right"></i>-->
<!--                    </router-link>-->

<!--                </div>-->
                <!--Забрать выращенную продукцию со склада и установить цену              -->
                <div class="list-group list-custom-large short-border">

                    <router-link :to="{name: 'GrowMoveGoods'}">
                        <i class="fa fa-inbox  bg-green-dark rounded-s"></i>
                        <span>Забрать продукцию с теплиц</span>
                        <strong>установить цену и оприходовать на главный склад</strong>
                        <i class="fa fa-angle-right"></i>
                    </router-link>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
import CardBalance from "../../Components/cardBalance";
import Error from "../../Components/Error";

    export default {
        name: "pageStorage",
        components: {
            CardBalance,
            Error
        },
        data() {
            return {
                message: '',
                // ddd: 'Test name button',
                // order_in: false,
                // order_out: false,
                // move_in: false,
                // move_out: false,
                // money_in: false,
                // money_out: false,
                my_storage_id: null,
                my_storage_name: null,
                balance: 1000,


                count_order_out_opened: 0,
                count_order_out_canceled: 0,
                count_order_out_progres: 0,

                count_order_in_opened: 0,
                count_order_in_canceled: 0,
                count_order_in_progres: 0,

                count_movement_out_opened: 0,
                count_movement_in_opened: 0
            }
        },
        computed: {
            isMain(){
                const main_id = localStorage.getItem('main_storage_id');
                if(main_id === this.my_storage_id) {
                    return true;
                }
                return false;
            }
        },
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')
            //this.storage_name = localStorage.getItem('my_storage_name');
        },
        mounted() {
            console.log('     Component views/Home mounted....')


            // если склад не выбран - перекинуть на страницу выбор склада
            if(this.my_storage_id == null) {
                console.log('my_storage_id is null \nRedirect to /selectStorage')
                this.$router.push({name: 'selectStorage'});
            }

            // иначе - склад выбран - и нужно подтянуть привелегии этого склада
            // this.order_in = localStorage.getItem('order_in');
            // this.order_out = localStorage.getItem('order_out');
            // this.money_in = localStorage.getItem('money_in');
            // this.money_out = localStorage.getItem('money_out');
            // this.move_in = localStorage.getItem('move_in');
            // this.move_out = localStorage.getItem('move_out');


            console.log('storage_name: '+this.my_storage_name)

            // получим сумму перемещений на этом складе
            // this.date_from = '2022-08-01'
            // this.date_to = '2022-08-05'
            // axios.get('/api/getSumMoneyMovementGoods/'+ this.my_storage_id+'/'+this.date_from+'/'+this.date_to).then(res => {
            //     this.balance = res.data.sum
            //
            // }).catch(err => {
            //     console.log(err)
            //     this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            // })

            this.loadStoragesParams()
            console.log('Component views/Home mounted......done!')
            update_template()
        },
        updated() {
            update_template()
        },
        methods: {
            loadStoragesParams(){

                // axios.get('/api/getStorageOrder/out/'+ this.my_storage_id).then(res => {
                //     //console.log(res.data)
                //     this.count_order_out_opened = res.data.data.opened
                //     this.count_order_out_canceled = res.data.data.canceled
                //     this.count_order_out_progres = res.data.data.progress
                //
                // }).catch(err => {
                //     this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                // })

                // axios.get('/api/getStorageOrder/in/'+ this.my_storage_id).then(res => {
                //     //console.log(res.data)
                //     this.count_order_in_opened = res.data.data.opened
                //     this.count_order_in_canceled = res.data.data.canceled
                //     this.count_order_in_progres = res.data.data.progress
                //
                // }).catch(err => {
                //     this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                // })

                // получить ОТКРЫТЫЕ ИСХОДЯЩИЕ заявки на ПЕРЕДАЧУ товара  (нужно только количество, для отображения на главной странице)
                axios.get('/api/getMovement/out/opened/'+ this.my_storage_id).then(res => {
                    this.count_movement_out_opened = res.data.data.length
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                })

                // получить ОТКРЫТЫЕ ВХОДЯЩИЕ заявки на ПОЛУЧЕНИЕ товара (нужно только количество, для отображения на главной странице)
                axios.get('/api/getMovement/in/opened/'+ this.my_storage_id).then(res => {
                    this.count_movement_in_opened = res.data.data.length
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                })

                console.log('Загружены все параметры для склада: ' + this.my_storage_id)
            }
        }
    }
</script>
