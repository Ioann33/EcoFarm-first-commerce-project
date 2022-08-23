<template>
    <div class="page-content header-clear-medium">
        <!-- ERROR -->  <error :message="message"></error>
        <!-- cardBalance --> <card-balance :storage_id="my_storage_id"></card-balance>

        неделя:
        <div class="row">

            <div class="col-4 ps-3 pe-0">
                <div class="card card-style mx-0 mb-3">
                    <div class="p-3 bg-yellow-dark ">
                        <h4 class="font-700 text-uppercase font-10 opacity-50 mt-n2">зарплата </h4>
                        <h1 class="font-700 font-24 opacity-60 mb-0 text-center">
                            {{ this.salaryWeek }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-4 ps-1 pe-0">
                <div class="card card-style mx-0 mb-3">
                    <div class="p-3 bg-yellow-dark ">
                        <h4 class="font-700 text-uppercase font-10 opacity-50 mt-n2">капитальные </h4>
                        <h1 class="font-700 font-24 opacity-60 mb-0 text-center">
                            {{ this.capitalWeek }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-4 ps-1 pe-3">
                <div class="card card-style mx-0 mb-3">
                    <div class="p-3 bg-yellow-dark ">
                        <h4 class="font-700 text-uppercase font-10 opacity-50 mt-n2">не профильные </h4>
                        <h1 class="font-700 font-24 opacity-60 mb-0 text-center">
                            {{ this.non_profitWeek }}</h1>
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
        },
        async mounted() {
            this.df = '2022-06-01 00:00:00'
            this.dt = '2022-09-05 00:00:00'


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
