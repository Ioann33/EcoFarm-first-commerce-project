<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->          <error :message="message"></error>
            <!-- cardBalance -->    <card-balance :storage_id="my_storage_id"></card-balance>

            <title-page title_main="Покупка"></title-page>

            <div class="card card-style p-4 overflow-visible">

                <div class="row mb-0" v-for="(item, index) in buy_goods" :key="index">
                    <div class="position-relative pb-3">
                        <label class="color-blue-dark position-absolute" style="z-index: 10; left: 25px; top: -12px; background-color: #fff; padding: 0 4px;">Продукт</label>
                        <v-select :options="list_goods"
                                  :value="'goods_id'"
                                  :label="'goods_name'"
                                  :placeholder="'выбрать продукт'"
                                  @option:selected="changeGoods($event, index)"
                                  @search="searchGoods"
                        >
                        </v-select>
                    </div>

<!--                    <select-input :data="list_goods"-->
<!--                                  :label="'Товар '+ (index+1)"-->
<!--                                  :value="item.goods_id"-->
<!--                                  @getSelected="getGoodsId($event, index)">-->
<!--                    </select-input>-->

                    <div class="col-12 d-flex justify-content-end">
                        <div class="form-check icon-check">
                            <input v-model="item.totalMode" @input="inputTotal(index)" class="form-check-input" type="checkbox" value="" :id="'check'+index" checked="">
                            <label class="form-check-label" :for="'check'+index">Указать итого</label>
                            <i class="icon-check-1 far fa-square color-gray-dark font-16"></i>
                            <i class="icon-check-2 far fa-check-square font-16 color-highlight"></i>
                        </div>
                    </div>
                    <div class="col-4 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" class="form-control focus-color focus-blue validate-name "
                                   :id="'price'+index"
                                   v-model="item.price"
                                   :disabled="item.totalMode"
                                   @input="calculateTotal(index)"
                                   @focus="$event.target.select()"
                            >
                            <label :for="'price'+index" class="color-blue-dark">цена за ед.</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>грн</em>

                        </div>
                    </div>
                    <div class="col-4 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" class="form-control focus-color focus-blue validate-name "
                                   :id="'amount'+index"
                                   v-model="item.amount"
                                   @input="calculateTotal(index)"
                                   @focus="$event.target.select()"
                            >
                            <label :for="'amount'+index" class="color-blue-dark">кол-во</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>{{ item.unit }}</em>
                        </div>
                    </div>
                    <div class="col-4 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" :disabled="!item.totalMode" class="form-control focus-color focus-blue validate-name "
                                   :id="'total'+index"
                                   v-model="item.total"
                                   @input="calculateTotal(index)"
                                   @focus="$event.target.select()"
                            >
                            <label :for="'total'+index" class="color-blue-dark">итого</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>

                </div>
                <button @click="addGoods" style="padding: 15px 24px; background-color: #A0D468; border-radius: 28px; color: #fff;" class="add-goods-btn">+</button>
            </div>

            <div class="row mb-0 d-flex justify-content-center align-items-center pt-3">
                <div class="col-3 text-uppercase">Итого</div>
                <div class="col-4 p-1">
                    <div class="input-style input-style-always-active has-borders no-iceron">
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

            <div class="row mb-n2 align-content-center p-3">
                    <a href="#" @click.prevent="buyProducts">
                        <div class="card card-style mx-0 mb-5">
                            <div class="p-3 bg-grass-light text-center">
                                <h1 class="font-700 font-34  opacity-60 mb-0 text-center">
                                    Купить </h1>
                            </div>
                        </div>
                    </a>
            </div>

<!--            <button type="button" class="btn btn-lg btn-default" @click="buyProducts">Купить</button>-->

        </div>

        <nav-bar-menu></nav-bar-menu>
    </div>
</template>

<script>
    import headBar from "../Components/HeadBar";
    import NavBar from "../Components/NavBar";
    import NavBarMenu from "../Components/NavBarMenu";
    import error from "../Components/Error";
    import TitlePage from '../Components/Title'
    import SelectInput from "../Components/SelectInput"
    import cardBalance from "../Components/cardBalance";
    import vSelect from "vue-select"

    export default {
        name: "pageBuyProducts",
        components:{
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu, SelectInput,
            cardBalance, vSelect
        },
        data() {
            return {
                message:'',
                buy_goods: [{
                    goods_id: 'default',
                    amount: 0,
                    price: 0,
                    unit: 'кг',
                    total: 0,
                    totalMode: false
                }],
                list_goods: []
            }
        },
        computed: {
          total(){
              let total = 0;
              this.buy_goods.forEach(el => total += Number.parseFloat(el.total));
              return total
          }
        },
        updated() {
            update_template()
        },
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')
        },
        mounted() {
            this.my_storage_id = localStorage.getItem('my_storage_id');
            // this.getAllowedGoods(this.my_storage_id)
        },
        methods: {
            searchGoods(value){
                if(!value) return;
                axios.get('/api/searchStorageGoods/allowed/' + this.my_storage_id + '/'+value.toLowerCase()).then(res => {
                    this.list_goods = res.data.data;
                }).catch(e => {
                    this.message = e.response.data.message
                    console.log(e)
                });
            },
            changeGoods(value, index){
                this.buy_goods[index].unit = value.unit;
                this.buy_goods[index].goods_id = value.goods_id;
            },
            async getAllowedGoods(storage_id){
                const res = await axios.get(`/api/getStorageGoods/available/${storage_id}/all`);
                if(!res.data){
                    console.error('Some error');
                    return ;
                }

                // из списка покупаемого продукта исклчюим готовую продукцию (type=2)
                //this.list_goods = res.data.data.filter(el => el.type!=2);
                let name = '';
                res.data.data.forEach(el => {
                    if(el.type !=2) {
                        if(el.amount>0)
                            name = el.name + ' ('+ el.amount+el.unit+' ➠ '+ el.price+'грн)'
                        else
                            name = el.name

                       this.list_goods.push({
                            goods_id: el.goods_id,
                            amount: el.amount,
                            price: el.price,
                           name
                        })
                    }
                })
                console.log(res)
            },
            getGoodsId(e, index) {
                if(e === 'default') return;
                const current = this.list_goods.find(el => el.goods_id === e);
                this.buy_goods[index].unit = current.unit;
                this.buy_goods[index].goods_id = e;
            },
            addGoods(){
                this.buy_goods.push({
                    goods_id: 'default',
                    amount: 0,
                    price: 0,
                    unit: 'кг',
                    total: 0,
                    totalMode: false
                })
            },
            calculateTotal(index){
                if(this.buy_goods[index].totalMode){
                    this.buy_goods[index].price = (this.buy_goods[index].total / this.buy_goods[index].amount).toFixed(2)
                } else {
                    this.buy_goods[index].total = (this.buy_goods[index].price * this.buy_goods[index].amount).toFixed(2)
                }
            },
            inputTotal(index){
              if(this.buy_goods[index].totalMode && this.buy_goods[index].amount != 0){
                  this.buy_goods[index].price = (this.buy_goods[index].total / this.buy_goods[index].amount).toFixed(2)
              }
            },
            async buyProducts(){
                let buy = this.buy_goods.map(el => {
                   if(el.goods_id === 'default' || el.amount === 0) return false;
                   return {
                       goods_id: el.goods_id,
                       amount: el.amount,
                       price: el.price,
                       storage_id: this.my_storage_id
                   }
                }).filter(el => el !== false);


                console.log('>>> покупка товара: ')
                console.log(buy)
                console.table(buy)


                axios.post('/api/doBuy', {
                    buy: buy
                }).then(res => {
                    console.log('<<< товар куплен')


                   this.$router.push({name: 'home'});
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                })


                // const res = await axios.post('/api/buyGoods', {
                //     storage_id: this.my_storage_id,
                //     goods: filtered
                // })
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
    .add-goods-btn{
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translate(-50%, 50%);
    }
    .icon-check {
        margin-right: 0;
    }
    .icon-check label {
        padding: 0px 0px 0px 40px;
    }
</style>
