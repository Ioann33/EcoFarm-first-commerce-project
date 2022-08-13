<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->
            <error  :message="message"></error>

<!--            <div class="ms-3 me-3 mb-5 alert alert-small rounded-s shadow-xl bg-red-dark" role="alert" v-if="message">-->
<!--                <span><i class="fa fa-times"></i></span>-->
<!--                <strong> {{ message }} </strong>-->
<!--                <button @click="message=''" type="button" class="close color-white opacity-60 font-16"  aria-label="Close">&times;</button>-->
<!--            </div>-->


            <title-page title_main="Список не закрытых сделок"></title-page>

            <div class="card card-style p-4 pt-3 mt-3">
                <table class="table table-sm table-pre-sale">
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
                    <tr v-for="(item, index) in list" :key="index">
                        <th scope="row">{{ item.date.split(' ')[0] }}</th>
                        <td>{{item.size_pay}}</td>
                        <td>{{item.description}}</td>
                        <td>-0</td>
                        <td><button class="btn-close-pre-sale" @click="closePreSale(item.id)">Отгрузить</button></td>
                    </tr>
                    </tbody>
                </table>
            </div>


        </div>

        <nav-bar-menu></nav-bar-menu>
    </div>
</template>

<script>
    import headBar from "../Components/HeadBar";
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
                message:'',
                list: []
            }
        },
        computed: {},
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
        },
        mounted() {
            this.getListMoneyByCategoryOnStorage();
        },
        updated() {
        },
        methods: {
            async getListMoneyByCategoryOnStorage(){
                const res = await axios.get('/api/getListMoneyByCategoryOnStorage/'+this.my_storage_id+'/701/2022-06-01 00:00:00/2022-09-05 00:00:00').then(res => {

                    console.log(res.data)

                    this.list = res.data.list;
                    console.log(this.list)
                }).catch(e => {
                    console.log(e)
                })
            },
            async closePreSale(id){
                const res = await axios.post('/api/closePreSale', {
                    money_id: id
                }).then(res => {
                    console.log(res.data)
                    if(res.data.status=='error')
                        this.message = res.data.message
                }).catch(e => {
                    console.log(e)
                })
            }
        }
    }
</script>

<style>
    .btn-close-pre-sale{
        padding: 3px 6px;
        border-radius: 6px;
        background-color: #A0D468;
        color: #fff;
    }
    .table-pre-sale {
        vertical-align: middle;
    }
</style>
