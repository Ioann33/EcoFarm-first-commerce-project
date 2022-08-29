<template>
    <div class="page-content header-clear-medium">
        <!-- ERROR -->  <error :message="message"></error>
        <!-- cardBalance --> <card-balance :storage_id="my_storage_id"></card-balance>



        <div class="card card-style">
            <div class="content mb-0">
                <div class="row mb-0">
                    <div class="col-6 pe-1">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">отгружено</h6>
                            <h3 class="color-green-dark font-20 mb-0">{{ this.sumMovement }}</h3>
                        </div>
                    </div>
                    <div class="col-6 ps-1">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">ЗП</h6>
                            <h3 class="color-red-dark font-20 mb-0">{{ this.sumSalary }}</h3>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!--ИСХОДЯЩИЕ ЗАКАЗЫ-->
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
        <!--ИСХОДЯЩИЕ ЗАКАЗЫ-->

        <!--ВХОДЯЩИЕ ЗАЯВКИ-->
        <div class="card card-style" v-if="0">
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
        <div class="card card-style" style="padding-top: 12px;" >
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
                            <card-movement-out-opened :storage_id="this.my_storage_id"></card-movement-out-opened>
                        </div>



                    </div>


                </div>
            </div>
        </div>

        <!--ПЕРЕДАТЬ ТОВАР    -->



        <router-link :to="{name: 'listMovements'}">
            <a href="">список операций</a>
        </router-link>



    </div>
</template>

<script>
    import cardMovementInOpened from "../../Components/cardMovementInOpened";
    import cardMovementOutOpened from "../../Components/cardMovementOutOpened";
    import Error from "../../Components/Error";
    import CardBalance from "../../Components/cardBalance";

    export default {
        name: "pageStorage",
        components: {
            Error,
            CardBalance,
            cardMovementInOpened, cardMovementOutOpened
        },
        data() {
            return {
                message: '',
                storage_id: null,




                count_order_out_opened: 0,
                count_order_out_canceled: 0,
                count_order_out_progres: 0,

                count_order_in_opened: 0,
                count_order_in_canceled: 0,
                count_order_in_progres: 0,

                count_movement_out_opened: 0,
                count_movement_in_opened: 0,

                sumMovement: '',    // сумма отгруженых товаров
                sumSalary: '',     //  ЗП
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
        },
        mounted() {
            console.log('     Component views/Home mounted....')
            this.df = '2022-06-01 00:00:00'
            this.dt = '2022-09-05 00:00:00'


            // получим все перемещений на этом складе
            // getListGoodsMovements/3/2022-08-01/2022-09-01
            let total = 0
            axios.get('/api/getListGoodsMovements/'+ this.my_storage_id+'/'+this.df+'/'+this.dt).then(res => {
                res.data.data.forEach((el, index) => {
                    if(el.category === 'move' && el.user_id_accepted !== null) // товар отгружен и принят гл.складом
                    {
                        total += el.price * el.amount
                        this.sumMovement = total
                        //console.log('+ id:'+el.id+' goods:'+el.goods_id+ ': '+ el.price +'*'+ el.amount+' = '+total)
                    }
                    //else
                        //console.log('-'+el.id+' '+el.goods_id)
                })
            }).catch(err => {
                console.error(err)
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })

            // получим затраты на ЗП
            // api/getSalary/total/3/100/2022-06-01 00:00:00/2022-09-05 00:00:00
            total = 0
            axios.get('/api/getSalary/total/'+ this.my_storage_id+'/100/'+this.df+'/'+this.dt).then(res => {
                this.sumSalary = res.data.sum
            }).catch(err => {
                console.error(err)
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })

            this.loadStoragesParams()
            console.log('     Component views/Home mounted......done!')
            update_template()
        },
        updated() {
            update_template()
        },
        methods: {
            loadStoragesParams(){

                axios.get('/api/getStorageOrder/out/'+ this.my_storage_id).then(res => {
                    //console.log(res.data)
                    this.count_order_out_opened = res.data.data.opened
                    this.count_order_out_canceled = res.data.data.canceled
                    this.count_order_out_progres = res.data.data.progress

                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                })

                axios.get('/api/getStorageOrder/in/'+ this.my_storage_id).then(res => {
                    //console.log(res.data)
                    this.count_order_in_opened = res.data.data.opened
                    this.count_order_in_canceled = res.data.data.canceled
                    this.count_order_in_progres = res.data.data.progress

                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                })

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

                console.log('loaded all params for storage: ' + this.my_storage_id)
            }
        }
    }
</script>

<style scoped>

</style>
