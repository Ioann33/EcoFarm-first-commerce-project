<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <title-page title_main="Статистика"></title-page>

        <div class="card card-style overflow-visible p-4 pt-3 mt-3">

            <div class="card-title text-center">Остатки на складах</div>

            <div class="d-flex no-effect bg-light rounded-m justify-content-between p-2 pb-1 mb-2"
                 data-trigger-switch="toggle-id-4"
                 data-bs-toggle="collapse"
                 href="#collapseExample4"
                 role="button"
                 aria-expanded="false"
                 aria-controls="collapseExample4">

                <div class="ps-2 pt-1">
                    <h6 class="font-400">готовая продукция</h6>
                </div>

                <div class=" me-4 pe-2">
                    <div class="custom-control android-switch">
                        <input type="checkbox" class="android-input" id="toggle-id-4" @change="changeRule">
                        <label class="custom-control-label" for="toggle-id-4"></label>
                    </div>
                </div>

                <div class="pe-2 pt-1">
                    <h6 class="font-400">ингридиенты</h6>
                </div>
            </div>
            <div v-if="loading" class="spinner-border text-light position-absolute" style="top: 112px; left: 50%;" role="status">
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
                    <td ><h4 class="font-600">{{ storage.sum }} </h4></td>
                </tr>
                </tbody>
            </table>


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
                loading: true,
                listStorages: [],
                rule: 'ready'
            }
        },
        computed: {},
        async mounted() {
            this.AllowedStorages = ['sale', 'buy', 'storage', 'creditor', 'cook', 'debtor']

            await axios.get('/api/getListStorages').then(res => {
                this.listStorages = res.data.data.filter(el => this.AllowedStorages.includes(el.type))

                console.log(this.listStorages)

                this.loading = true;
                this.listStorages.forEach((el, index) => {
                    this.getCostGoodsOnStock(el.id, index, this.listStorages.length)
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
             changeRule() {
                switch (this.rule) {
                    case 'ready':
                        this.rule = 'ingredient';
                        break;
                    case 'ingredient':
                        this.rule = 'ready';
                        break;
                }

                this.loading = true;
                console.log('Стартуем список запросов')
                this.listStorages.forEach((el, index) => {
                    this.getCostGoodsOnStock(el.id, index, this.listStorages.length)
                })
            },
            getCostGoodsOnStock(id, index, length) {
                axios.get('/api/costGoodsOnStock/'+id+'/'+this.rule).then(res => {
                    let current = this.listStorages.find(el => el.id === id);
                    current.sum = res.data.sum;

                    if((index+1) === length ) {
                        this.loading = false;
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
