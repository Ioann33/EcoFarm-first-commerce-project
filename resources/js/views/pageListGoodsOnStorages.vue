<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium m-3">
            <!-- ERROR -->  <error  :message="message"></error>

            <div class="content" id="tab-group-3">
                <div class="tab-controls tabs-small tabs-rounded" data-highlight="bg-green-dark">
                    <a href="#" class="no-effect bg-green-dark no-click" data-active="" data-bs-toggle="collapse" data-bs-target="#tab-8" aria-expanded="true">Товар</a>
                    <a href="#" class="no-effect collapsed" data-bs-toggle="collapse" data-bs-target="#tab-9" aria-expanded="false">Склад</a>
                </div>
                <div class="clearfix mb-3"></div>
                <div data-bs-parent="#tab-group-3" class="collapse show" id="tab-8" style="">
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
                    <div class="card card-style p-4 pt-3 mt-3" style="margin: 0 0 30px 0;">
                        <p class="text-center" v-if="selected_goods === 'default'">Выберете продукт</p>
                        <div v-if="loading_goods" class="spinner-border text-light" role="status" style="margin: 0 auto;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="row m-0" v-for="(goods, index) in goods_in_storages" :key="goods.storage_id">
                            <div class="col-8">{{goods.storage_name}}</div>
                            <div class="col-4 align-content-end">{{goods.amount}} <sup class="opacity-50">{{goods.unit}}</sup></div>
                        </div>
                    </div>
                </div>
                <div data-bs-parent="#tab-group-3" class="collapse" id="tab-9" style="">
                    <p class="mb-2 text-center">Посмотреть все продукты на выбранном складе</p>
                    <select-input :data="list_storages" :label="'Склады'" :value="selected_storage" :keyOfValue="'id'" @getSelected="selectStorage"></select-input>
                    <div class="card card-style p-4 pt-3 mt-1" style="margin: auto 4px 30px 4px;">
                        <p class="text-center mb-0" v-if="selected_storage === 'default'">Нужно выбрать склад</p>
                        <p class="text-center mb-0" v-if="empty_storage">На выбраном складе нет товара</p>
                        <div v-if="loading_list_goods" class="spinner-border text-light" role="status" style="margin: 0 auto;">
                            <span class="sr-only">Loading...</span>
                        </div>


                        <div class="row m-0" v-for="(goods, index) in storage_goods" :key="goods.storage_id">
                            <div class="col-8">{{goods.name}}</div>
                            <div class="col-4 align-content-end">{{goods.amount}} <sup class="opacity-50">{{goods.unit}}</sup></div>
                        </div>
                    </div>
                </div>
            </div>


<!--            <div v-if="loading_storages_goods" class="spinner-border spinner-loading_storages_goods text-light" role="status">-->
<!--                <span class="sr-only">Loading...</span>-->
<!--            </div>-->
<!--            <div class="row " v-for="(goods, index) in goods_in_storages" :key="goods.storage_id">-->


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
                goods_in_storages: [],
                list_storages: [],
                selected_storage: 'default',
                storage_goods: [],
                loading_list_goods: false,
                empty_storage: false
            }
        },
        computed: {},
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')
        },

        mounted() {
            //this.getStorageGoods()
            this.getListStorages();
        },
        updated() {
        },
        methods: {
            getListStorages(){
              axios.get('/api/getListStorages/').then(res => {
                  this.list_storages = res.data.data;
                  console.log(res.data)
              }).catch(e => {
                  console.log(e)
              });
            },
            getStorageGoods(){
                this.loading_list_goods = true;
                this.storage_goods = [];
                this.empty_storage = false;
              axios.get(`/api/getStorageGoods/available/${this.selected_storage}/all`).then(res => {
                  this.storage_goods = res.data.data.filter(el => Number.parseFloat(el.amount) !== 0 );
                  if(this.storage_goods.length === 0){
                      this.empty_storage = true;
                  }
                  this.loading_list_goods = false;
              }).catch(e => {
                  console.log(e)
              });
            },
            selectStorage(value){
                this.selected_storage = value;
                if(value === 'default') return;
                this.getStorageGoods();
            },
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
                this.loading_goods = true;
                axios.get('/api/getStorageGoods/available/all/'+this.selected_goods).then(res => {
                    this.goods_in_storages = res.data.data.filter(el => el.amount >0);
                    this.loading_goods = false;
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
