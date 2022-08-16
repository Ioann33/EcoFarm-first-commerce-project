<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <title-page title_main="Статистика"></title-page>

        <div class="card card-style overflow-visible p-4 pt-3 mt-3">
        Остатки на складах


            <div class="d-flex no-effect bg-light rounded-l "
                 data-trigger-switch="toggle-id-4"
                 data-bs-toggle="collapse"
                 href="#collapseExample4"
                 role="button"
                 aria-expanded="false"
                 aria-controls="collapseExample4">

                <div class="ps-2">
                    <h6  class="font-400">готовая продукция</h6>
                </div>

                <div class="ms-auto me-4 pe-2">
                    <div class="custom-control android-switch">
                        <input type="checkbox" class="android-input" id="toggle-id-4">
                        <label class="custom-control-label" for="toggle-id-4"></label>
                    </div>
                </div>

                <div class="pe-2">
                    <h6 class="font-400">ингридиенты</h6>
                </div>
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
    import {forEach} from "lodash";

    export default {
        name: "FinanceDashboard",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },
        data(){
            return {
                listStorages: [
                    {id: 2, name: 'Кухня', sum: 43234.54},
                    {id: 2, name: 'Магазин', sum: 3234.54}
                ],
            }
        },
        computed: {},
        async mounted() {
            this.AllowedStorages = ['sale', 'buy', 'storage', 'creditor', 'cook', 'debtor']

            await axios.get('/api/getListStorages').then(res => {
                this.listStorages = res.data.data.filter(el => this.AllowedStorages.includes(el.type))

                console.log(this.listStorages)

                this.listStorages.forEach( async (el) => {
                    console.log(el.id)

                    // в зависимости от toggle
                    this.rule = 'ingredient'
                    this.rule = 'ready'

                    await axios.get('/api/costGoodsOnStock/'+el.id+'/'.this.rule).then(res => {
                        console.log('Остаток продукции('+this.rule+') на складе '+el.name+': '+res.data.sum+'грн')




                    }).catch(err => {
                        this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                        console.error(this.message)
                    })
                })

            }).catch(err => {
                this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                console.error(this.message)
            })
        },
        updated() {
        },
        methods: {

        }
    }
</script>

<style scoped>

</style>
