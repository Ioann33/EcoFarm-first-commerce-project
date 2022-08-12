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

                <div class="list-group list-custom-large short-border">
                    <router-link :to="{name: 'transferMoney'}">
                        <i class="fa bg-blue-dark fa-dollar-sign rounded-s">  </i>
                        <span>Выдать Зарплату</span>
                        <strong>с привязкой к складу</strong>
                        <i class="fa fa-angle-right"></i>
                    </router-link>
                </div>

                <div class="list-group list-custom-large short-border">
                    <router-link :to="{name: 'transferMoney'}">
                        <i class="fa bg-blue-dark fa-dollar-sign rounded-s">  </i>
                        <span>Списать на капитальные вложения</span>
                        <strong>покупка основных средств</strong>
                        <i class="fa fa-angle-right"></i>
                    </router-link>
                </div>

                <div class="list-group list-custom-large short-border">
                    <router-link :to="{name: 'transferMoney'}">
                        <i class="fa bg-blue-dark fa-dollar-sign rounded-s">  </i>
                        <span>Списать на не профильные нужды</span>
<!--                        <strong></strong>-->
                        <i class="fa fa-angle-right"></i>
                    </router-link>
                </div>


            </div>
        </div>
        <div class="row text-center mb-0 mt-n2">
            <a href="#" data-menu="menu-transaction-transfer" class="col-6 pe-0">
                <div class="card card-style me-2 mb-3">
                    <i class="fa fa-arrow-up color-red-dark fa-2x mt-3"></i>
                    <h1 class="pt-2 font-18">Вывод инвестиций</h1>
<!--                    <p class="font-11 opacity-50 mt-n2 mb-3">Tap to Transfer Funds</p>-->
                </div>
            </a>
            <a href="#" data-menu="menu-transaction-request" class="col-6 ps-0">
                <div class="card card-style ms-2 mb-3">
                    <i class="fa fa-arrow-down color-green-dark fa-2x mt-3"></i>
                    <h1 class="pt-2 font-18">Инвестировать</h1>
<!--                    <p class="font-11 opacity-50 mt-n2 mb-3">Tap to Request Funds</p>-->
                </div>
            </a>
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
