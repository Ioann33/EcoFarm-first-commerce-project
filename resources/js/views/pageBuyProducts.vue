<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium text-center">
            <div class="card card-style p-4 overflow-visible">
                <h3 class="card-title">Купить</h3>
                <div class="row mb-0" v-for="(item, index) in buy_goods" :key="item.goods_id">
                    <select-input :data="list_goods"
                                  :label="'Товар '+ (index+1)"
                                  :value="item.goods_id"
                                  @getSelected="getGoodsId($event, index)">
                    </select-input>
                    <div class="col-4 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" class="form-control focus-color focus-blue validate-name "
                                   :id="'price'+index"
                                   v-model="item.price"
                            >
                            <label :for="'price'+index" class="color-blue-dark">цена за ед.</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
                    <div class="col-4 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" class="form-control focus-color focus-blue validate-name "
                                   :id="'amount'+index"
                                   v-model="item.amount"
                                   @input="calculateTotal(index)"
                            >
                            <label :for="'amount'+index" class="color-blue-dark">кол-во</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>{{ item.unit }}</em>
                        </div>
                    </div>
                    <div class="col-4 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" :disabled="true" class="form-control focus-color focus-blue validate-name "
                                   :id="'total'+index"
                                   v-model="item.total"
                                   @input="calculateTotal(index)"
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
            <button type="button" class="btn btn-lg btn-default" @click="buyProducts">Купить</button>

        </div>

        <nav-bar-menu></nav-bar-menu>
    </div>
</template>

<script>
    import headBar from "../components/headBar";
    import NavBar from "../Components/NavBar";
    import NavBarMenu from "../Components/NavBarMenu";
    import error from "../Components/Error";

    import SelectInput from "../Components/SelectInput"

    export default {
        name: "pageBuyProducts",
        components:{
            error,
            headBar, NavBar, NavBarMenu, SelectInput
        },
        data() {
            return {
                buy_goods: [{
                    goods_id: 'default',
                    amount: 0,
                    price: 0,
                    unit: 'кг',
                    total: 0
                }],
                list_goods: []
            }
        },
        computed: {
          total(){
              let total = 0;
              this.buy_goods.forEach(el => total += el.total);
              return total
          }
        },
        mounted() {
            this.storage_id = localStorage.getItem('my_storage_id');
            this.getAllowedGoods(this.storage_id)
        },
        methods: {
            async getAllowedGoods(storage_id){
                const res = await axios.get(`/api/getStorageGoods/allowed/${storage_id}/all`);
                if(!res.data){
                    console.log('Some error');
                    return ;
                }
                this.list_goods = res.data.data;
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
                    total: 0
                })
            },
            calculateTotal(index){
                this.buy_goods[index].total = this.buy_goods[index].price * this.buy_goods[index].amount;
            },
            async buyProducts(){
                let filtered = this.buy_goods.map(el => {
                   if(el.goods_id === 'default' || el.amount === 0) return false;
                   return {
                       goods_id: el.goods_id,
                       amount: el.amount,
                       price: el.price,
                   }
                }).filter(el => el !== false);
                console.log(filtered)
                return;
                const res = await axios.post('/api/buyGoods', {
                    storage_id: this.storage_id,
                    goods: filtered
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
    .add-goods-btn{
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translate(-50%, 50%);
    }
</style>
