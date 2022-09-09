<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium text-center">
            <!-- ERROR -->  <error :message="message"></error>

            <title-page title_main="Продажа"></title-page>

            <div class="card card-style overflow-visible p-4 pt-3 mt-3">

                <div class="row mb-0" v-for="(item, i) in sale_goods" :key="index">
                    <div class="col-12 p-1 position-relative">
                        <button v-if="sale_goods.length > 1" class="fa fa-times-circle font-18 delete-product" @click="deleteProduct(i)"></button>
                        <div class="position-relative pb-3">
                            <label class="color-blue-dark position-absolute" style="z-index: 10; left: 9px; top: -12px; background-color: #fff; padding: 0 4px;">Продукт {{i+ 1}}</label>
                            <v-select :options="available_goods.filter(el => ![...selected_goods.slice(0,i), ...selected_goods.slice(i+1,selected_goods.length)].includes(el.goods_id))"
                                      :value="'goods_id'"
                                      :label="'goods_name'"
                                      :placeholder="'выбрать продукт'"
                                      @option:selected="changeGoods($event, i)"
                                      @search="searchGoods($event, i)"
                            >
                                <template #no-options="{ search, searching, loading }">
                                    Ничего не найдено
                                </template>
                                <template #option="{ goods_name, amount, unit, price }">
                                    <h6 style="margin: 0">{{ goods_name }}</h6>
                                    <em v-if="this.my_storage_type !== 'grow'">{{ amount }} {{ unit }} ➠ {{ price }}грн</em>
                                </template>
                            </v-select>
                            <div v-if="loading_goods" class="spinner-border text-light select-input-spinner" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                    </div>
                    <div class="col-4 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input :id="'price'+i" type="number" :disabled="true" class="form-control focus-color focus-blue validate-name "
                                   id="f15"
                                   v-model="item.price"
                            >
                            <!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>₴</em>
                        </div>
                    </div>
                    <div class="col-4 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" :disabled="item.max_amount === 0" class="form-control focus-color focus-blue validate-name "
                                   id="f21"
                                   v-model="item.amount"
                                   @input="checkAmount(i)"
                                   @focus="$event.target.select()"
                            >
                            <!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>{{ item.max_amount + ' ' + item.unit }}</em>
                        </div>
                    </div>
                    <div class="col-4 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" :disabled="true" class="form-control focus-color focus-blue validate-name "
                                   id="f13"
                                   v-model="item.total"
                            >
                            <!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>итого</em>
                        </div>
                    </div>
                </div>
                <button @click="addGoods" style="padding: 15px 24px; background-color: #A0D468; border-radius: 28px; color: #fff;" class="add-goods-btn">+</button>
            </div>

            <div class="row mb-0 d-flex justify-content-center align-items-center pt-3">
                <div class="col-3 text-uppercase">Итого</div>
                <div class="col-4 p-1">
                    <div class="input-style input-style-always-active has-borders no-icon">
                        <input type="number" :disabled="true" class="form-control focus-color focus-blue validate-name "
                               id="total"
                               v-model="total"
                        >
                        <!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                        <i class="fa fa-times disabled invalid color-red-dark"></i>
                        <i class="fa fa-check disabled valid color-green-dark"></i>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-lg btn-default" @click="saleProducts">Продать</button>
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

    export default {
        name: "pageSaleProducts",
        components:{
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu, vSelect
        },

        data() {
            return {
                message: '',
                loading_goods: false,
                available_goods: [],
                sale_goods: [{
                    goods_id: 'default',
                    amount: 0,
                    max_amount: 0,
                    unit: 'кг',
                    price: 5,
                    total: 0
                }]
            }
        },
        computed: {
            total() {
                let total = 0;
                this.sale_goods.forEach(el => {
                    total += Number.parseFloat(el.total);
                })
                return total;
            },
            selected_goods(){
                let arr = [];
                this.sale_goods.forEach(el => {
                    arr.push(el.goods_id);
                })
                return arr;
            }
        },
        async mounted() {
            this.my_storage_id = localStorage.getItem('my_storage_id');
            // await this.getStorageGoods(this.my_storage_id);

        },
        updated() {
            update_template()
        },
        methods: {
            searchGoods(value, index){
                if(!value) return;
                axios.get('/api/searchStorageGoods/available/' + this.my_storage_id + '/'+value.toLowerCase()).then(res => {
                    this.available_goods = res.data.data;
                }).catch(e => {
                    this.message = e.response.data.message
                    console.log(e)
                });
            },
            changeGoods(value, index){
                // Сохраняем те данные, которые мы получили от апи поиска по товарах
                this.sale_goods[index].goods_id = value.goods_id
                this.sale_goods[index].unit = value.unit
                this.sale_goods[index].max_amount = value.amount
                this.sale_goods[index].price = value.price;

                // Теперь нужно получить конкретные показатели по товару на текущем складе,
                // например, максимальное к-во
                // this.loading_goods = true;
                // axios.get('/api/getStorageGoods/available/' + this.my_storage_id + '/' + value.goods_id).then(res => {
                //     console.log(res.data.data)
                //     if(res.data.data[0]){
                //         this.sale_goods[index].max_amount = res.data.data[0].amount;
                //     }
                //     this.loading_goods = false;
                // }).catch(err => {
                //     this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                //     console.error(this.message)
                // })
            },
            deleteProduct(i){
               this.sale_goods.splice(i, 1);
            },
            selectedGoods(id, index){
                if(!Number.isInteger(id)) return ;

                const selected = this.available_goods.find(el => el.goods_id === id);
                this.sale_goods[index].max_amount = selected.amount;
                this.sale_goods[index].amount = 0;
                this.sale_goods[index].unit = selected.unit;
                this.sale_goods[index].price = selected.price;

            },
            async getStorageGoods(id){
                this.loading_goods = true;
                const res = await axios.get(`/api/getStorageGoods/available/${id}/all`);
                this.loading_goods = false;
                if(!res.data){
                    return ;
                }
                this.available_goods = res.data.data.filter(el => el.amount >0);
            },
            checkAmount(i){
                if(this.sale_goods[i].amount > this.sale_goods[i].max_amount){
                    this.sale_goods[i].amount = this.sale_goods[i].max_amount;
                }
                this.sale_goods[i].total = (this.sale_goods[i].amount * this.sale_goods[i].price).toFixed(2);
            },

            addGoods(){
                this.sale_goods.push({
                    goods_id: 'default',
                    amount: 0,
                    max_amount: 0,
                    unit: 'кг',
                    price: 0,
                    total: 0
                });
            },
            async saleProducts(){
                let sales = [];
                this.sale_goods.forEach(el => {
                    if(el.goods_id === 'default' || el.amount === 0) return;
                    sales.push({
                        storage_id: this.my_storage_id,
                        goods_id: el.goods_id,
                        amount: el.amount,
                        price: el.price
                    })
                })
                console.log('>>> продажа товара: ')
                console.log(sales)
                console.dir(sales)

                axios.post('/api/doSale', {
                    sales: sales
                }).then(res => {
                    console.log('<<< товар продан')


                    this.$router.push({name: 'home'});
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                })

                // const res = await axios.post(`/api/doSale`, {
                //     prepareData
                // });
                // if(!res.data){
                //     console.log('Some error');
                // }
            }
        }
    }
</script>

<style>
    .delete-product {
        position: absolute;
        z-index: 1;
        right: -2px;
        top: 68px;
        color: #DA4453;
    }
    .btn-default {
        background-color: #A0D468;
        border-radius: 10px;
        color: #fff;
    }
    .btn-default:hover {
        color: #fff;
    }
    .select-input-spinner{
        position: absolute;
        right: 45px;
        top: 14px;
    }
    .add-goods-btn{
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translate(-50%, 50%);
    }
</style>
