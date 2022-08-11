<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->  <error :message="message"></error>

            <title-page title_main="Разрешить выбранный товар"></title-page>

            <div class="card card-style p-4 pt-3 mt-3">
                <div class="row mb-0">
                    <select-input :data="available_goods"
                                  :label="'Весь список товаров'"
                                  :value="'goods_id'"
                                  :loading="loading_goods">
                    </select-input>
                </div>

                <div class="row mb-0">
                    <div class="col-8">
                        Магазин
                    </div>
                    <div class="col-4">
                        {{item}}
                        <div class="custom-control ios-switch ios-switch-icon">
                            <input v-model="item" type="checkbox" class="ios-input" id="toggle-id-2">
                            <label class="custom-control-label" for="toggle-id-2"></label>
                            <i class="fa fa-check font-11 color-white"></i>
                            <i class="fa fa-times font-11 color-white"></i>
                        </div>
                    </div>
                </div>
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
    import SelectInput from "../Components/SelectInput";

    export default {
        name: "pagePermitGoods",
        components:{
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
            SelectInput
        },
        data(){
            return {
                message: '',
                loading_goods: false,
                available_goods: [],
                item: true
            }
        },
        computed: {},
        updated() {
        },
        mounted() {
            this.getStorageGoods()
        },
        methods: {
            async getStorageGoods(){
                this.loading_goods = true;
                const res = await axios.get(`/api/getStorageGoods/available/1/all`);
                this.loading_goods = false;
                if(!res.data){
                    return ;
                }
                this.available_goods = res.data.data;
            },
        }
    }
</script>

<style scoped>

</style>
