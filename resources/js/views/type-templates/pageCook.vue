<template>
    <div class="page-content header-clear-medium">
        <!-- ERROR -->  <error :message="message"></error>
        <!-- cardBalance -->
<!--        <card-balance :storage_id="my_storage_id"></card-balance>-->



        <div data-card-height="120" style="height: 150px" class="card card-style rounded-m shadow-xl preload-img"
             data-src="images/teplitsa.webp">
            <div class="card-top mt-2 ms-3" style="text-align: left !important;">
                <h1 class="color-white mb-0 mb-n2 font-22">{{ this.my_storage_name }}</h1>
                <p class="bottom-0 color-white opacity-50 under-heading font-11 font-700">{{this.my_storage_type}} {{this.my_storage_id}}</p>
            </div>
            <div class="card-top text-center mt-5">
                <h1 class="color-white fa-4x">0 ₴ </h1>
                <p class="color-white opacity-70 font-11 mb-n5">Баланс наличка</p>
            </div>

            <!--        <div class="card-top mt-2 ms-3 text-center">-->
            <!--            <h1 class="color-white mb-0 mb-n2 font-22"> cener text </h1>-->
            <!--            <p class="bottom-0 color-white opacity-50 under-heading font-11 font-700"> center small text </p>-->
            <!--        </div>-->

            <div class="card-top mt-2 ms-3 me-2" style="text-align: right !important;">
                <h1 class="color-white me-2 mb-0 mb-n2 font-22 font-500"> 0 </h1>
                <p class="bottom-0 color-white opacity-50 under-heading font-10 font-500">произведено сегодня</p>
            </div>

            <div class="card-bottom">
                <h1 class="text-end me-3 font-22 font-500  color-white mb-0"> {{Intl.NumberFormat().format(this.sumProduce)}} </h1>

                <p class="text-end me-2 font-10 font-500 opacity-50 color-white mb-2">произведено на неделе </p>
                <!--            <h1 class="color-white mb-0 mb-n2 font-22">22</h1>-->
                <!--            <p class="bottom-0 color-white opacity-50 under-heading font-11 font-700">продано сегодня</p>-->
            </div>


            <div class="card-bottom">
                <!--    <p class="ms-3 font-10 font-500 opacity-50 color-white mb-2">Exp: 10/22</p>-->
            </div>
            <!--        <div class="card-bottom">-->
            <!--            <p class="text-end me-3 font-10 font-500 opacity-50 color-white mb-2"><i class="fab fa-cc-visa font-20 rotate-90"></i></p>-->
            <!--        </div>-->

            <div class="card-overlay bg-black opacity-40"></div>
        </div>








        <!--статистика эффективность         -->
        <div class="ms-3">текущая неделя, начиная с {{ this.df}}</div>
        <div class="card card-style">
            <div class="content mb-0">
                <div class="row mb-0">
                    <div class="col-6 pe-1">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">Утилизировано</h6>
                            <h3 class="color-red-dark font-20 mb-0">{{Intl.NumberFormat().format(this.sumTrash.toFixed(2))}} </h3>
                        </div>
                    </div>

                    <div class="col-6 ps-1">
                        <router-link :to="{name: 'cookStat'}">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">Произведено</h6>
                            <h3 class="color-red-dark font-20 mb-0"> {{Intl.NumberFormat().format(this.sumProduce)}}</h3>
                        </div>
                        </router-link>
                    </div>

                    <div class="col-6 pe-1">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">Прочие затраты</h6>
                            <h3 class="color-brown-dark font-20 mb-0">{{Intl.NumberFormat().format(this.otherSpending.toFixed(2)) }}</h3>
                        </div>
                    </div>
                    <div class="col-6 ps-1">
                        <router-link :to="{name: 'cookStat'}">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">Себестоимость</h6>
                            <h3 class="color-blue-dark font-20 mb-0">{{ Intl.NumberFormat().format(this.sumCostPrice)  }}</h3>
                        </div>
                        </router-link>
                    </div>
                    <div class="col-6 pe-1">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">ЗП</h6>
                            <h3 class="color-green-dark font-20 mb-0">{{ Intl.NumberFormat().format(this.sumSalary.toFixed(2))}}</h3>
                        </div>
                    </div>
                    <div class="col-6 ps-1">
                        <router-link :to="{name: 'cookStat'}">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">Изготовление</h6>
                            <h3 class="color-green-dark font-20 mb-0">{{  Intl.NumberFormat().format(this.sumCostProduce)  }}</h3>
                        </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>

        <!-- ПРИГОТОВИТЬ -->
        <div class="row mb-n2 align-content-center p-3">
            <div class="col-12 ">
                <router-link :to="{name: 'makeProducts'}">
                    <div class="card card-style mx-0 mb-5">
                        <div class="p-3 bg-grass-light text-center">
                            <h1 class="font-700 font-34  opacity-60 mb-0 text-center">
                                Приготовить </h1>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>

        <!-- КУПИТЬ / ПРОДАТЬ -->

        <div class="card card-style" style="padding-top: 12px;" v-if="0">
            <div class="row mb-n2">

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
        </div>

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
                            <!-- кнопка -  Список продукции, которую нужно принять -->
                            <!-- принять --> <card-movement-in-opened :storage_id="my_storage_id"></card-movement-in-opened>
                        </div>
                    </div>

                </div>

            </div>


        </div>
        <!--ПЕРЕДАТЬ ТОВАР    -->

        <!--УТИЛИЗАРОВАТЬ ТОВАР    -->
        <div class="row mb-n2 align-content-center p-3">
            <div class="col-12 ">
                <router-link :to="{name: 'trashProducts'}">
                    <div class="card card-style mx-0 mb-5">
                        <div class="p-3 bg-grass-light text-center">
                            <h1 class="font-700 font-34  opacity-60 mb-0 text-center">
                                Утилизировать </h1>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>

        <router-link :to="{name: 'listMovements'}">
            <a href="">список операций</a>
        </router-link>

        <list-goods :storage_id="this.my_storage_id"></list-goods>

    </div>
</template>

<script>
    import Error from "../../Components/Error";
    import CardBalance from "../../Components/cardBalance";
    import cardMovementOutOpened from "../../Components/cardMovementOutOpened";
    import cardMovementInOpened from "../../Components/cardMovementInOpened";
    import listGoods from "../../Components/listGoods";

    export default {
        name: "pageProduce",
        components: {
            Error,
            CardBalance,
            cardMovementOutOpened,
            cardMovementInOpened,
            listGoods
        },
        data() {
            return {
                my_storage_id: 0,
                my_storage_name: '',
                message: '',
                listMovements: [],  // весь список отгруженных, утилизированных, затраченых на ГП товаров
                listTrash: '',  // список утилизированного

                sumProduce: 0,  // итого сколько произведено и отгружено
                sumTrash: 0,   // сумма утилизированного
                sumSalary: 0,  // сумма ЗП
                sumCostProduce: 0, // стоимость изготовления продукции (ЗП)
                sumCostPrice: 0,   // сумма ГП по себестоимости
                otherSpending: 0   // другие затраты
            }
        },
        computed: {},
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')
            this.my_storage_type = localStorage.getItem('my_storage_type')
            this.df = localStorage.getItem('df')
            this.dt = localStorage.getItem('dt')
        },
        async mounted() {

            // ЗАРПЛАТА
            // api/getSalary/total/2/100/2022-06-01 00:00:00/2022-09-05 00:00:00
            await axios.get('api/getSalary/total/'+this.my_storage_id+'/100/'+this.df+'/'+this.dt)
                .then(res => {
                    this.sumSalary = Math.abs(res.data.sum)
                    //console.log(res.data)
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })

            // УТИЛИЗАЦИЯ
            //api/getListGoodsMovements/21/null/trash/2022-08-01/2022-09-01
            await axios.get('api/getListGoodsMovements/'+this.my_storage_id+'/null/trash/'+this.df+'/'+this.dt)
                .then(res => {
                    res.data.data.forEach((el ,index) => {
                        this.sumTrash = +el.amount * el.price
                    })
                })
                .catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })

            await axios.get('api/getReportAboutMadeProduct',
                {params: {
                    storage_id: this.my_storage_id,
                    date_from: this.df,
                    date_to: this.dt,
                    }})
                .then(res =>{
                    console.log(res.data)
                    this.sumCostPrice = res.data.self_cost
                    this.sumCostProduce = res.data.profit
                    this.sumProduce = res.data.market_cost
                })
                .catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })

            //api/getListGoodsMovements/2/trash/2022-08-01/2022-09-01
            // await axios.get('api/getListGoodsMovements/'+this.my_storage_id+'/move/'+this.df+'/'+this.dt)
            //     .then(res => {
            //         this.listMovements = res.data.data
            //         // this.listTrash = this.listMovements.filter(el => el.category=='trash')
            //         let totalCostPrice = 0
            //         let totalProduce = 0
            //         this.listMovements.forEach((el ,index) => {
            //             if(el.category == 'move' && el.link_id !== null){
            //                 // отгружена ГП
            //                 // если null то это было перемещение сырья, а не ГП
            //
            //                 //console.log(el.link_id)
            //                 totalProduce += parseFloat(el.amount) * parseFloat(el.price)
            //                 this.sumProduce = totalProduce
            //                 // получить данные про изготовление ГП
            //                 // api/getMovementInfo/538
            //                  axios.get('api/getMovementInfo/'+el.link_id)
            //                     .then(res => {
            //                         totalCostPrice +=  Number.parseFloat(res.data.data.price) * Number.parseFloat(res.data.data.amount)
            //                         this.sumCostPrice = totalCostPrice.toFixed(2)
            //                         this.sumCostProduce = (this.sumProduce - this.sumCostPrice).toFixed(2)
            //                         console.log(el.link_id + ': '+res.data.data.price+' * '+res.data.data.amount+' === '+totalCostPrice)
            //                     }).catch(err => {
            //                         this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            //                         console.error(this.message)
            //                     })
            //             }
            //
            //         })
            //
            //
            //     }).catch(err => {
            //         this.message = 'Error: ('+err.response+'): '+err;
            //         console.error(this.message)
            //     })

            // api/getSumMoneyMovementGoods/20/2022-08-01/2022-09-05
            this.otherSpending = 0
            // await axios.get('api/getSumMoneyMovementGoods/'+this.my_storage_id+'/'+this.df+'/'+this.dt)
            //     .then(res => {
            //         this.sumProduce = res.data.sum
            //         console.log(res.data)
            //     }).catch(err => {
            //         this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            //         console.error(this.message)
            //     })

            update_template()
        },
        updated() {
            update_template()
        },
        methods: {


        }
    }
</script>

