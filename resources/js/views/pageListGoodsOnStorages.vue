<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium m-0">
            <!-- ERROR -->  <error  :message="message"></error>

            <div class="content" id="tab-group-3">
                <div class="tab-controls tabs-small tabs-rounded" data-highlight="bg-green-dark">
                    <a href="#" class="no-effect bg-green-dark no-click" data-active="" data-bs-toggle="collapse" data-bs-target="#tab-8" aria-expanded="true">Товар</a>
                    <a href="#" class="no-effect collapsed" data-bs-toggle="collapse" data-bs-target="#tab-9" aria-expanded="false">Склад</a>
                    <a href="#" class="no-effect collapsed" data-bs-toggle="collapse" data-bs-target="#tab-10" aria-expanded="false">Движение</a>
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

                    <select-input
                        :data="list_storages"
                        :label="'Склады'"
                        :value="selected_storage"
                        :keyOfValue="'id'"
                        @getSelected="selectStorage"
                    ></select-input>

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
                <div data-bs-parent="#tab-group-3" class="collapse" id="tab-10" style="">
                    <span>Посмотреть движение продукта на всех складах</span>
                    <v-select :options="available_goods"
                              :value="'id'"
                              :label="'name'"
                              :placeholder="'выбрать продукт'"
                              :map-keydown="handlers"
                              @option:selected="showMovementsGoods"
                              @search="searchGoods"
                    >
                    </v-select>

                    <div class="content m-0 mt-3" id="tab-group-20">
                        <div class="tab-controls tabs-small tabs-rounded" data-highlight="bg-green-dark">
                            <a href="#" class="no-effect bg-green-dark no-click" data-active="" data-bs-toggle="collapse" data-bs-target="#tab-20" aria-expanded="true"><i class="fa fa-heart"></i></a>
                            <a href="#" class="no-effect collapsed" data-bs-toggle="collapse" data-bs-target="#tab-21" aria-expanded="false"><i class="fa fa-star"></i></a>
                        </div>
                        <div class="clearfix mb-3"></div>

                        <div data-bs-parent="#tab-group-20" class="collapse show" id="tab-20" style="">
                            <div class="card card-style p-0 m-0 pt-2">
                                <p class="text-center" v-if="selected_goods === 'default'">Выберете продукт</p>
                                <div v-if="loading_goods" class="spinner-border text-light" role="status" style="margin: 0 auto;">
                                    <span class="sr-only">Loading...</span>
                                </div>

                                <div class="row m-0" v-for="(movement, index) in movements_goods" :key="movement.storage_id">
                                    <div class="col-8"><span v-if="movement.storage_name_from !== null">{{movement.storage_name_from}} ➠</span><sup>{{movement.category}}</sup>➠ {{movement.storage_name_to}} <br> <span class="opacity-40 font-10">{{movement.date_created}} {{movement.user_name_created}}</span></div>
                                    <div class="col-2 align-content-end p-0">
                                        <span class="font-600">{{movement.amount}} <sup class="opacity-50">{{movement.goods_unit}}</sup></span> <br>
                                        <span class="font-10" v-if="movement.price">{{movement.price}}   <sup class="opacity-50">₴</sup> </span>
                                    </div>
                                    <div class="col-2 align-content-end p-0">
                                        <div class="icon icon-m rounded-s shadow-l me-1 p-0 bg-twitter">
                                            <i class="fa-solid fa-right-from-bracket font-25 bg-blue-dark rounded-s" v-if="movement.category==='move'"></i>
                                            <i class="fas fa-check-to-slot font-25 bg-green-dark  rounded-s" v-if="movement.category==='ready'"></i>
                                            <i class="fa-solid fa-recycle" v-if="movement.category==='correct'"></i>
                                            <i class="fa-solid fa-seedling font-25 bg-green-dark rounded-s" v-if="movement.category==='grow'"></i>
                                            <i class="fa-solid fa-dollar font-25 bg-green-dark rounded-s" v-if="movement.category==='buy'"></i>
                                            <i class="fas fa-boxes-alt font-25 bg-gray-dark rounded-s" v-if="movement.category==='ingredients'"></i>
                                        </div>
                                    </div>
                                    <hr class="opacity-10">
                                </div>

                            </div>
                        </div>
                        <div data-bs-parent="#tab-group-20" class="collapse" id="tab-21" style="">
                            <div v-if="this.selected_goods" class="mt-3">
                                <list-movements
                                    :goods_id="this.selected_goods"
                                >
                                </list-movements>

                            </div>
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
    import listMovements from "../Components/listMovements";



    export default {
        name: "pageListGoodsOnStorages",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
            SelectInput, vSelect,
            listMovements
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
                empty_storage: false,
                movements_goods: []
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
                  //console.log(res.data)
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
            showMovementsGoods(value){
                this.selected_goods = value.id;
                console.log('selected goods: '+this.selected_goods)
                this.loading_goods = true;

                //получить движение продукта по складам
                axios.get('/api/getListGoodsMovementsOnStorages/'+this.selected_goods+'/2022-08-01/2022-10-01').then(res => {
/*
{
  "id": 849,
  "user_id_created": 31,
  "user_name_created": "Наталья Коваль",
  "date_created": "2022-08-27 19:17:17",
  "storage_id_from": null,
  "storage_name_from": null,
  "storage_id_to": 3,
  "storage_name_to": "Теплица-1",
  "goods_id": 1,
  "goods_name": "помидор",
  "goods_unit": "кг",
  "amount": "24.5",
  "user_id_accepted": 31,
  "user_name_accepted": "Наталья Коваль",
  "price": null,
  "order_main": null,
  "date_accepted": "2022-08-27 19:17:17",
  "link_id": null,
  "category": "move"
}
*/
                    console.log(res.data.data);
                    this.movements_goods = res.data.data
                }).catch(e => {
                    this.message = e.response.data.message
                    console.log(e)
                });

                // this.movements_goods = [
                //     {"storage_name_from": "fff","amount":4444,"unit":"кг"},
                //     {"storage_name_from": "fff","amount":4444,"unit":"кг"}
                //     ]

                // console.log(this.movements_goods)
                this.loading_goods = false;
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
