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
                <p class="color-white opacity-70 font-11 mb-n5">Баланс</p>
            </div>

            <!--        <div class="card-top mt-2 ms-3 text-center">-->
            <!--            <h1 class="color-white mb-0 mb-n2 font-22"> cener text </h1>-->
            <!--            <p class="bottom-0 color-white opacity-50 under-heading font-11 font-700"> center small text </p>-->
            <!--        </div>-->

            <div class="card-top mt-2 ms-3 me-2" style="text-align: right !important;">
                <h1 class="color-white me-2 mb-0 mb-n2 font-22 font-500"> 0 </h1>
                <p class="bottom-0 color-white opacity-50 under-heading font-10 font-500">оборот сегодня</p>
            </div>

            <div class="card-bottom">
                <h1 class="text-end me-3 font-22 font-500  color-white mb-0"> 0 </h1>

                <p class="text-end me-2 font-10 font-500 opacity-50 color-white mb-2">оборот за неделю </p>
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




        <div class="ms-3">неделя начиная с {{ this.df}}</div>
        <div class="row">

            <div class="col-4 ps-3 pe-0">
                <router-link :to="{name: 'listMoney', params: {type: 'salary'}}">
                <div class="card card-style mx-0 mb-3">
                    <div class="p-3 bg-yellow-dark ">
                        <h4 class="font-700 text-uppercase font-10 opacity-50 mt-n2">зарплата </h4>
                        <h1 class="font-700 font-24 opacity-60 mb-0 text-center">
                            {{ Intl.NumberFormat().format(this.salaryWeek) }}</h1>
                    </div>
                </div>
                </router-link>
            </div>
            <div class="col-4 ps-1 pe-0">
                <div class="card card-style mx-0 mb-3">
                    <div class="p-3 bg-yellow-dark ">
                        <h4 class="font-700 text-uppercase font-10 opacity-50 mt-n2">капитальные </h4>
                        <h1 class="font-700 font-24 opacity-60 mb-0 text-center">
                            {{ Intl.NumberFormat().format(this.capitalWeek)   }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-4 ps-1 pe-3">
                <div class="card card-style mx-0 mb-3">
                    <div class="p-3 bg-yellow-dark ">
                        <h4 class="font-700 text-uppercase font-10 opacity-50 mt-n2">не профильные </h4>
                        <h1 class="font-700 font-24 opacity-60 mb-0 text-center">
                            {{ Intl.NumberFormat().format(this.non_profitWeek)   }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-style">
            <div class="content mt-0 mb-0">

                <div class="list-group list-custom-large short-border">
                    <router-link :to="{name: 'transferMoney'}">
                        <i class="fa bg-blue-dark fa-dollar-sign rounded-s">  </i>
                        <span>Передать деньги</span>
                        <strong>с одного департамента на другой</strong>
                        <i class="fa fa-angle-right"></i>
                    </router-link>
                </div>

            </div>

        </div>

        <div class="card card-style bg-magenta-dark">
            <div class="content mb-3">
                <h4 class="font-700 text-uppercase font-15 opacity-70 mb-3 mt-n2">Затраты компании</h4>
                <div class="row text-center  mt-n2">
                    <div class="col-12">
                        <router-link :to="{name: 'Spend', params: {type: 'salary'}}">
                            <div class="card card-style me-2 mb-1">
                                <h1 class="pt-2 font-18">Зарплата</h1>
                                <p class="font-11 opacity-50 mt-n2 mb-3">списание ЗП с привязкой к складу</p>
                            </div>
                        </router-link>
                    </div>
                </div>
                <div class="row text-center mb-0 mt-n2">
                    <div class="col-6">
                        <router-link :to="{name: 'Spend', params: {type: 'capital'}}">
                            <div class="card card-style me-2 mb-1">
                                <h1 class="pt-2 font-18">Капитальные затраты</h1>
<!--                                <p class="font-11 opacity-50 mt-n2 mb-3">Капитальные затраты</p>-->
                            </div>
                        </router-link>
                    </div>

                    <div class="col-6">
                        <router-link :to="{name: 'Spend', params: {type: 'non_profit'}}">
                            <div class="card card-style me-2 mb-1">
                                <h1 class="pt-2 font-15">Не профильные затраты</h1>
<!--                                <p class="font-11 opacity-50 mt-n2 mb-3">Не профильный затраты</p>-->
                            </div>

                        </router-link>

                    </div>
                </div>

            </div>
        </div>
        <div class="row text-center mb-0 mt-n2">
            <router-link :to="{name: 'SpendInvest', params: {type: 'out'}}" class="col-6 ps-3">
                <div class="card card-style me-2 mb-3">
                    <i class="fa fa-arrow-up color-red-dark fa-2x mt-3"></i>
                    <h1 class="pt-2 font-15">Вывод инвестиций</h1>
<!--                    <p class="font-11 opacity-50 mt-n2 mb-3">Tap to Transfer Funds</p>-->
                </div>
            </router-link>
            <router-link :to="{name: 'SpendInvest', params: {type: 'in'}}" class="col-6 ps-0">
                <div class="card card-style ms-2 mb-3">
                    <i class="fa fa-arrow-down color-green-dark fa-2x mt-3"></i>
                    <h1 class="pt-2 font-18">Инвестировать</h1>
<!--                    <p class="font-11 opacity-50 mt-n2 mb-3">Tap to Request Funds</p>-->
                </div>
            </router-link>
        </div>
    </div>
</template>

<script>
    import Error from "../../Components/Error";
    import CardBalance from "../../Components/cardBalance";

    export default {
        name: "pageProduce",
        components: {
            Error,
            CardBalance
        },
        data() {
            return {
                message: '',
                capitalWeek: 0,
                salaryWeek: 0,
                non_profitWeek: 0
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

            await axios.get('api/getMoneyByCategoryOnStorage/sum/all/500/all/'+this.df+'/'+this.dt)
                .then(res => {
                    this.non_profitWeek = parseInt(res.data.sum)
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })

            await axios.get('api/getMoneyByCategoryOnStorage/sum/all/600/all/'+this.df+'/'+this.dt)
                .then(res => {
                    this.capitalWeek = parseInt(res.data.sum)
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })

            await axios.get('api/getMoneyByCategoryOnStorage/sum/all/100/all/'+this.df+'/'+this.dt)
                .then(res => {
                    this.salaryWeek = parseInt(res.data.sum)
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })
        },
        methods: {

        }

    }
</script>

<style scoped>

</style>
