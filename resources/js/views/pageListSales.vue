<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <list-sales
                :storage_id="my_storage_id"
            ></list-sales>
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
    import listSales from "../Components/listSales";

    export default {
        name: "pagePreSale",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu, listSales
        },
        data(){
            return {
                my_storage: ''
            }
        },
        computed: {},
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')
            //this.storage_name = localStorage.getItem('my_storage_name');
        },
        mounted() {
            // this.my_storage = localStorage.getItem('my_storage_id');
        },
        updated() {


        },
        methods: {
            // getListGoodsMovements(){
            //     axios.get(`/api/getListGoodsMovements/3/2022-08-01/2022-09-01`).then(res => {
            //         this.movements = res.data.data.slice(0,30);
            //         console.log(res.data)
            //     }).catch(e => {
            //         console.log(e)
            //     });
            // }
        }
    }
</script>

<style scoped>

</style>
