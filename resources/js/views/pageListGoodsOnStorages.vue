<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium m-3">


            <span>Весь список товаров</span>
            <v-select :options="available_goods"
                      :value="'id'"
                      :label="'name'"
                      :placeholder="'выбрать продукт'"
                      :map-keydown="handlers"
                      @option:selected="changeGoods"
                      @search="searchGoods"
            >
            </v-select>
<!--            <div v-if="loading_storages_goods" class="spinner-border spinner-loading_storages_goods text-light" role="status">-->
<!--                <span class="sr-only">Loading...</span>-->
<!--            </div>-->

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
    import vSelect from "vue-select"
    import 'vue-select/dist/vue-select.css';
    import SelectInput from "../Components/SelectInput";


    export default {
        name: "pageListGoodsOnStorages",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
            SelectInput, vSelect
        },
        data(){
            return {
                available_goods: [],
                selected_goods: 'default',
                loading_goods: false,
            }
        },
        computed: {},
        mounted() {
            this.getStorageGoods()
        },
        updated() {
        },
        methods: {
            async getStorageGoods(){
                this.loading_goods = true;
                const res = await axios.get(`/api/getListGoods`);
                this.loading_goods = false;
                if(!res.data){
                    console.log('Error get search')
                    return ;
                }
                this.available_goods = res.data.data;
                console.log(res.data)
            },
            changeGoods(value){
                this.selected_goods = value.id;
                console.log('selected goods: '+this.selected_goods)
                //this.getListStoragesGoodsPermit(value.id)
            },
            handlers: (map, vm) => ({
                ...map,
                13: (e) => {
                    e.preventDefault()
                    vm.deselect()
                    vm.onSearchBlur()
                },
            }),
            searchGoods(value){
                if(!value) return;
                axios.get('/api/searchGoods/'+value.toLowerCase()).then(res => {
                    this.available_goods = res.data;
                }).catch(e => {
                    this.message = e.response.data.message
                    console.log(e)
                });
            },

        }
    }
</script>

<style>
.vs__selected-options > input, .vs__selected-options > input:focus {
    padding: 12px 6px;
    border: none;
}
.spinner-loading_storages_goods {
    position: absolute;
    left: calc(50% - 16px);
    top: 90px;
}
.alert-save-goods-custom {
    position: fixed;
    bottom: 55px;
    left: 0;
    right: 0;
}
</style>
