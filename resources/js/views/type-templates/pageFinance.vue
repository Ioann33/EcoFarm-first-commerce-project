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
                    <router-link :to="{name: 'PreSale'}">
                        <i class="fa bg-blue-dark fa-dollar-sign rounded-s">  </i>
                        <span>Незакрытые сделки</span>
                        <strong>Список не закрытых сделок</strong>
                        <i class="fa fa-angle-right"></i>
                    </router-link>
                </div>
            </div>
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
