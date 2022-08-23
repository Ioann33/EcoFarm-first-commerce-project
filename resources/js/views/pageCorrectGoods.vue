<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <div class="card card-style p-4 pt-3 mt-3">
                <select-input :data="storages"
                              :value="selected_storage"
                              :label="'Склады'"
                              @getSelected="selectStorage"
                              :keyOfValue="'id'">

                </select-input>

                <select-input :data="goods"
                              :value="selected_goods"
                              :label="'Товары'"
                              @getSelected="selectGoods"
                              :keyOfValue="'goods_id'" :loading="loadingGoods">

                </select-input>

                <div class="d-flex">
                    <div class="col-6 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input id="price" type="number" disabled class="form-control focus-color focus-blue validate-name"
                                   v-model="price"
                            >
                            <label for="price" class="color-blue-dark">Цена</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>₴</em>
                        </div>
                    </div>
                    <div class="col-6 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" class="form-control focus-color focus-blue validate-name "
                                   id="amount"
                                   v-model="amount"
                            >
                            <label for="amount" class="color-blue-dark">Кол-во</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>{{ unit }}</em>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-lg btn-default" :disabled="selected_storage === 'default' || selected_goods === 'default'" @click="correctGoods">Корректировать</button>
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
    import SelectInput from "../Components/SelectInput";

    export default {
        name: "pagePreSale",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu, SelectInput
        },
        data(){
            return {
                storages: [],
                selected_storage: 'default',
                goods: [],
                loadingGoods: false,
                selected_goods: 'default',
                price: 0,
                amount: 0,
                unit: 'кг'
            }
        },
        computed: {},
        mounted() {
            this.getStorages();
        },
        updated() {
        },
        methods: {
            getStorages(){
                axios.get('/api/getListStorages/').then(res => {
                    this.storages = res.data.data;
                }).catch(e => {
                    console.log(e)
                });
            },
            selectStorage(value){
                if(value === 'default'){
                    this.goods = [];
                    return;
                }
                this.getGoods(value)
            },
            getGoods(storage_id){
                this.loadingGoods = true;
                axios.get(`/api/getStorageGoods/available/${storage_id}/all`).then(res => {
                    this.goods = res.data.data;
                    this.loadingGoods = false;
                }).catch(e => {
                    console.log(e)
                })
            },
            selectGoods(value){
                if(value === 'default') return;
                const current_goods = this.goods.find(el => el.goods_id === value);
                this.price = current_goods.price;
                this.amount = current_goods.amount;
                this.unit = current_goods.unit;
            },
            correctGoods(){
                axios.post('/api/correctGoods', {
                    old_amount: this.goods.find(el => el.goods_id === this.selected_goods).amount,
                    new_amount: this.amount,
                    price: this.price,
                    storage_id: this.selected_storage,
                    goods_id: this.selected_goods
                }).then(res => {

                }).catch(e => {
                    console.log(e)
                })
            }
        }
    }
</script>

<style>
    .btn-default {
        background-color: #A0D468;
        border-radius: 10px;
        color: #fff;
    }
    .btn-default:hover {
        color: #fff;
    }
</style>
