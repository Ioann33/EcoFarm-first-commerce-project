<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <list-movements :storage_id="my_storage"></list-movements>
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
    import listMovements from "../Components/listMovements";

    export default {
        name: "pagePreSale",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu, listMovements
        },
        data(){
            return {
                my_storage: ''
            }
        },
        computed: {},
        mounted() {
            this.my_storage = localStorage.getItem('my_storage_id');
        },
        updated() {


        },
        methods: {
            getListGoodsMovements(){
                axios.get(`/api/getListGoodsMovements/3/2022-08-01/2022-09-01`).then(res => {
                    this.movements = res.data.data.slice(0,30);
                    console.log(res.data)
                }).catch(e => {
                    console.log(e)
                });
            }
        }
    }
</script>

<style scoped>

</style>
