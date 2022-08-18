<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <title-page title_main="Статистика"></title-page>

        <div class="card card-style overflow-visible p-4 pt-3 mt-3 content" id="tab-group-7">

            <div class="card-title text-center">Остатки на складах</div>

            <div class="tab-controls tabs-small tabs-rounded mb-2" data-highlight="bg-blue-dark">
                <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-10" class="bg-blue-dark no-click">Продукты</a>
                <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-11" class="collapsed" @click.once="changeRule('ingredients')">Ингредиенты</a>
                <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-12" class="collapsed">Все</a>
            </div>

            <div data-bs-parent="#tab-group-7" class="collapse show" id="tab-10" style="">
                <div v-if="loading.ready" class="spinner-border text-light position-absolute" style="top: 100px; left: 50%;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <table class="table text-center rounded-sm shadow-l" style="overflow: hidden;"> <!-- table-borderless -->
                    <thead>
                    <tr class="bg-grass-light">
                        <th scope="col" class="color-black opacity-50">Склад</th>
                        <th scope="col" class="color-black opacity-50">Итого, грн</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(storage, index) in listStorages" :key="storage.id">
                        <th scope="row" class="align-self-center ">{{ storage.name }}</th>
                        <td ><h4 class="font-600">{{ sum.ready[storage.id] ? sum.ready[storage.id] : '...' }}</h4></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div data-bs-parent="#tab-group-7" class="collapse" id="tab-11" style="">
                <div v-if="loading.ingredients" class="spinner-border text-light position-absolute" style="top: 100px; left: 50%;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <table class="table text-center rounded-sm shadow-l" style="overflow: hidden;"> <!-- table-borderless -->
                    <thead>
                    <tr class="bg-grass-light">
                        <th scope="col" class="color-black opacity-50">Склад</th>
                        <th scope="col" class="color-black opacity-50">Итого, грн</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(storage, index) in listStorages" :key="storage.id">
                        <th scope="row" class="align-self-center ">{{ storage.name }}</th>
                        <td ><h4 class="font-600">{{ sum.ingredients[storage.id] ? sum.ingredients[storage.id] : '...' }}</h4></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div data-bs-parent="#tab-group-7" class="collapse" id="tab-12" style="">
                <table class="table text-center rounded-sm shadow-l" style="overflow: hidden;"> <!-- table-borderless -->
                    <thead>
                    <tr class="bg-grass-light">
                        <th scope="col" class="color-black opacity-50">Склад</th>
                        <th scope="col" class="color-black opacity-50">Итого, грн</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(storage, index) in listStorages" :key="storage.id">
                        <th scope="row" class="align-self-center ">{{ storage.name }}</th>
                        <td ><h4 class="font-600">{{ Number.parseFloat(sum.ready[storage.id]) + Number.parseFloat(sum.ingredients[storage.id]) }}</h4></td>
                    </tr>
                    </tbody>
                </table>
            </div>





        </div>
        <nav-bar-menu></nav-bar-menu>
    </div>
    </div>
</template>

<script>
    import headBar from "../Components/HeadBar";
    import NavBar from "../Components/NavBar";
    import NavBarMenu from "../Components/NavBarMenu";
    import error from "../Components/Error";
    import TitlePage from "../Components/Title";
    // import {forEach} from "lodash";

    export default {
        name: "FinanceDashboard",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },
        data(){
            return {
                loading: {
                    ready: false,
                    ingredients: false
                },
                listStorages: [],
                sum: {
                    ready: {},
                    ingredients: {}
                },
                rule: 'ready'
            }
        },
        computed: {},
        async mounted() {
            this.AllowedStorages = ['sale', 'buy', 'storage', 'creditor', 'cook', 'debtor']

            await axios.get('/api/getListStorages').then(res => {
                this.listStorages = res.data.data.filter(el => this.AllowedStorages.includes(el.type))

                console.log(this.listStorages)

                this.loading.ready = true;
                this.listStorages.forEach((el, index) => {
                    this.getCostGoodsOnStock(el.id, index, this.listStorages.length, 'ready')
                })
            }).catch(err => {
                this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                console.error(this.message)
            })
        },
        updated() {
            update_template()
        },
        methods: {
             changeRule(rule) {

                this.loading[rule] = true;
                console.log('Стартуем список запросов')
                this.listStorages.forEach((el, index) => {
                    this.getCostGoodsOnStock(el.id, index, this.listStorages.length, rule)
                })
            },
            getCostGoodsOnStock(id, index, length, rule) {
                axios.get('/api/costGoodsOnStock/'+id+'/'+rule).then(res => {
                    this.sum[rule][id] = res.data.sum;

                    if((index+1) === length ) {
                        this.loading[rule] = false;
                        console.log('Получили последний ответ')
                    }
                }).catch(err => {
                    this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                    console.error(this.message)
                })
            }
        }
    }
</script>

<style scoped>

</style>
