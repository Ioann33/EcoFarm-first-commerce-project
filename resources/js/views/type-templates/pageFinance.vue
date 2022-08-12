<template>
    <div class="page-content header-clear-medium">
        <!-- ERROR -->  <error :message="message"></error>
        <!-- cardBalance --> <card-balance :storage_id="my_storage_id"></card-balance>

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
                                <p class="font-11 opacity-50 mt-n2 mb-3">Капитальные затраты</p>
                            </div>
                        </router-link>
                    </div>

                    <div class="col-6">
                        <router-link :to="{name: 'Spend', params: {type: 'non_profit'}}">
                            <div class="card card-style me-2 mb-1">
                                <h1 class="pt-2 font-18">Не профильный затраты</h1>
                                <p class="font-11 opacity-50 mt-n2 mb-3">Не профильный затраты</p>
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
                    <h1 class="pt-2 font-18">Вывод инвестиций</h1>
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
