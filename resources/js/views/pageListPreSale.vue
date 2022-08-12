<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

            <title-page title_main="Список не закрытых сделок"></title-page>

            <div class="card card-style p-4 pt-3 mt-3">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th scope="col">Дата</th>
                        <th scope="col">Сумма</th>
                        <th scope="col">Название товара</th>
                        <th scope="col">К-во</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td><button class="btn btn-sm btn-success">Отгрузить</button></td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                        <td><button class="btn btn-sm btn-success">Отгрузить</button></td>
                    </tr>
                    </tbody>
                </table>
            </div>


        </div>

        <nav-bar-menu></nav-bar-menu>
    </div>
</template>

<script>
    import headBar from "../components/headBar";
    import NavBar from "../Components/NavBar";
    import NavBarMenu from "../Components/NavBarMenu";
    import error from "../Components/Error";
    import TitlePage from "../Components/Title";

    export default {
        name: "pagePreSale",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },
        data(){
            return {
                list: []
            }
        },
        computed: {},
        mounted() {
            this.getListMoneyByCategoryOnStorage();
        },
        updated() {
        },
        methods: {
            async getListMoneyByCategoryOnStorage(){
                const res = await axios.get('/api/getListMoneyByCategoryOnStorage/7/701/2022-06-01/00:00:00/2022-09-05 00:00:00').then(res => {
                    console.log(res.data)

                    this.list = res.data.list;
                    console.log(this.list)
                }).catch(e => {
                    console.log(e)
                })
            }
        }
    }
</script>

<style scoped>

</style>
