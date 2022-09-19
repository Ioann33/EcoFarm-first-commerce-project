<template>
    <div class="page-content header-clear-medium">
        <!-- ERROR -->  <error :message="message"></error>
        <!-- cardBalance --> <card-balance :storage_id="my_storage_id"></card-balance>

        <!--статистика эффективность         -->
        <div class="card card-style">
            <div class="content mb-0">
                <div class="row mb-0">
                    <div class="col-6 pe-1">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">Утилизировано</h6>
                            <h3 class="color-red-dark font-20 mb-0">{{this.sumTrash.toFixed(2)}} </h3>
                        </div>
                    </div>

                    <div class="col-6 ps-1">
                        <router-link :to="{name: 'cookStat'}">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">Произведено</h6>
                            <h3 class="color-red-dark font-20 mb-0">{{ this.sumProduce.toFixed(2) }}</h3>
                        </div>
                        </router-link>
                    </div>

                    <div class="col-6 pe-1">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">Прочие затраты</h6>
                            <h3 class="color-brown-dark font-20 mb-0">{{ this.otherSpending.toFixed(2)}}</h3>
                        </div>
                    </div>
                    <div class="col-6 ps-1">
                        <router-link :to="{name: 'cookStat'}">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">Себестоимость</h6>
                            <h3 class="color-blue-dark font-20 mb-0">{{  this.sumCostPrice }}</h3>
                        </div>
                        </router-link>
                    </div>
                    <div class="col-6 pe-1">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">ЗП</h6>
                            <h3 class="color-green-dark font-20 mb-0">{{ this.sumSalary.toFixed(2) }}</h3>
                        </div>
                    </div>
                    <div class="col-6 ps-1">
                        <router-link :to="{name: 'cookStat'}">
                        <div class="mx-0 mb-3">
                            <h6 class="font-12 font-800 text-uppercase opacity-30">Изготовление</h6>
                            <h3 class="color-green-dark font-20 mb-0">{{  this.sumCostProduce }}</h3>
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
        },
        async mounted() {
            this.df = '2022-06-01 00:00:00'
            this.dt = '2022-10-05 00:00:00'


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
            //api/getListGoodsMovements/2/move/2022-08-01/2022-09-01
            await axios.get('api/getListGoodsMovements/'+this.my_storage_id+'/trash/'+this.df+'/'+this.dt).then(res => {
                res.data.data.forEach((el ,index) => {
                    this.sumTrash = +el.amount * el.price
                })
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                console.error (' [serv] '+this.message)
            })

            //api/getListGoodsMovements/2/trash/2022-08-01/2022-09-01
            await axios.get('api/getListGoodsMovements/'+this.my_storage_id+'/move/'+this.df+'/'+this.dt)
                .then(res => {
                    this.listMovements = res.data.data
                    // this.listTrash = this.listMovements.filter(el => el.category=='trash')
                    let totalCostPrice = 0
                    let totalProduce = 0
                    this.listMovements.forEach((el ,index) => {
                        // if(el.category == 'trash'){ // Утилизация
                        //     this.sumTrash =+ el.amount * el.price
                        // }else
                        if(el.category == 'move' && el.link_id !== null){
                            // отгружена ГП
                            // если null то это было перемещение сырья, а не ГП

                            //console.log(el.link_id)
                            totalProduce += parseFloat(el.amount) * parseFloat(el.price)
                            this.sumProduce = totalProduce
                            // получить данные про изготовление ГП
                            // api/getMovementInfo/538
                             axios.get('api/getMovementInfo/'+el.link_id)
                                .then(res => {
                                    totalCostPrice +=  Number.parseFloat(res.data.data.price) * Number.parseFloat(res.data.data.amount)
                                    this.sumCostPrice = totalCostPrice.toFixed(2)
                                    this.sumCostProduce = (this.sumProduce - this.sumCostPrice).toFixed(2)
                                    console.log(el.link_id + ': '+res.data.data.price+' * '+res.data.data.amount+' === '+totalCostPrice)
                                }).catch(err => {
                                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                                    console.error(this.message)
                                })
                        }

                    })


                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })

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

