<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <div class="card card-style ">

                <div class="content-boxed bg-blue-dark mb-1 pb-3 text-center">
                    <h4 class="color-white">Корректировать остатки на складе</h4>
                </div>

                <div class="content mb-5 p-b5">

                    <select-input :data="storages"
                                  :value="selected_storage"
                                  :label="'Склады'"
                                  @getSelected="selectStorage"
                                  :keyOfValue="'id'">

                    </select-input>

                    <div class="position-relative pb-3">
                        <label class="color-blue-dark position-absolute" style="z-index: 10; left: 9px; top: -12px; background-color: #fff; padding: 0 4px;">Продукт</label>
                        <v-select :options="goods"
                                  :value="'goods_id'"
                                  :label="'goods_name'"
                                  :placeholder="'выбрать продукт'"
                                  @option:selected="changeGoods"
                                  @search="searchGoods"
                        >
                            <template #no-options="{ search, searching, loading }">
                                Ничего не найдено
                            </template>
                            <template #option="{ goods_name, amount, unit, price }">
                                <h6 style="margin: 0">{{ goods_name }}</h6>
                                <em v-if="this.my_storage_type !== 'grow'">{{ amount }} {{ unit }} ➠ {{ price }}грн</em>
                            </template>
                        </v-select>
                    </div>


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
                                       @focus="$event.target.select()"
                                >
                                <label for="amount" class="color-blue-dark">Кол-во</label>
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em>{{ unit }}</em>
                            </div>
                        </div>
                    </div>

                </div>
                <div v-if="selected_storage === 'default' || selected_goods === 'default' || old_amount==amount" class="content-boxed bg-red-dark mt-1 pb-3 text-center text-uppercase">
                    <h4 class="color-white ">Передать на склад</h4>
                </div>

                <a  v-else @click.prevent="correctGoods" href="#" >
                    <div class="content-boxed bg-green-dark mt-1 pb-3 text-center text-uppercase">
                        <h4 class="color-white">Корректировать</h4>
                    </div>
                </a>


<!--                <button type="button" class="btn btn-lg btn-default" :disabled="selected_storage === 'default' || selected_goods === 'default' || old_amount==amount" @click="correctGoods">Корректировать</button>-->
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
    import vSelect from "vue-select"

    export default {
        name: "pagePreSale",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu, SelectInput, vSelect
        },
        data(){
            return {
                storages: [],
                selected_storage: 'default',
                goods: [],
                loadingGoods: false,
                selected_goods: null,
                price: 0,
                old_amount: 0,
                amount: 0,
                unit: 'кг'
            }
        },
        watch: {},
        computed: {},
        mounted() {
            this.getStorages();
        },
        updated() {
        },
        methods: {
            searchGoods(value){
                if(!value || this.selected_storage === 'default') return;
                axios.get('/api/searchStorageGoods/available/'+ this.selected_storage +'/'+value.toLowerCase()).then(res => {
                    this.goods = res.data.data;
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })
            },


            changeGoods(value){
                if(!value) return;
                this.selected_goods = value.goods_id;
                this.price = value.price;
                this.old_amount = value.amount;
                this.amount = value.amount;
                this.unit = value.unit;
            },

            getStorages(){
                axios.get('/api/getListStorages/').then(res => {
                    this.storages = res.data.data;
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })
            },

            selectStorage(value){
                if(value === 'default'){
                    this.goods = [];
                    return;
                }
                this.selected_storage = value;
                //this.getGoods(value)
            },

            // getGoods(storage_id){
            //     this.loadingGoods = true;
            //     axios.get(`/api/getStorageGoods/available/${storage_id}/all`).then(res => {
            //         this.goods = res.data.data;
            //         this.loadingGoods = false;
            //     }).catch(e => {
            //         console.log(e)
            //     })
            // },

            selectGoods(value){
                if(value === 'default') return;
                this.selected_goods = value;
                const current_goods = this.goods.find(el => el.goods_id === value);
                this.price = current_goods.price;
                this.old_amount = current_goods.amount;
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
                    console.log('скорректировали товар('+this.selected_goods+')')
                    this.$router.push({name: 'home'});
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
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
