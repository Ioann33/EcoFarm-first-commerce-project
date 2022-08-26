<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium m-3">
            <!-- ERROR -->  <error  :message="message"></error>

            <span>Посмотреть остатки продукта на всех складах</span>
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
<!--            <div class="row " v-for="(goods, index) in goods_in_storages" :key="goods.storage_id">-->
                <div class="card card-style p-4 pt-3 mt-3">
                    <div class="row m-0" v-for="(goods, index) in goods_in_storages" :key="goods.storage_id">
                        <div class="col-8">{{goods.storage_name}}</div>
                        <div class="col-4 align-content-end">{{goods.amount}} <sup class="opacity-50">{{goods.unit}}</sup></div>


                    </div>
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
                message: '',
                available_goods: [],
                selected_goods: 'default',
                loading_goods: false,
                goods_in_storages: []
            }
        },
        computed: {},
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')
        },

        mounted() {
            //this.getStorageGoods()
        },
        updated() {
        },
        methods: {
            // async getStorageGoods(){
            //     this.loading_goods = true;
            //     const res = await axios.get(`/api/getListGoods`);
            //     this.loading_goods = false;
            //     if(!res.data){
            //         console.error('Error get search')
            //         return ;
            //     }
            //     this.available_goods = res.data.data;
            //     console.log(res.data)
            // },
            changeGoods(value){
                this.selected_goods = value.id;
                console.log('selected goods: '+this.selected_goods)

                axios.get('/api/getStorageGoods/available/all/'+this.selected_goods).then(res => {
                    this.goods_in_storages = res.data.data.filter(el => el.amount >0);
                    console.log(this.goods_in_storages)
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })

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
