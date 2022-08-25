<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium" style="text-align: center">

            <!-- ERROR -->  <error :message="message"></error>

            <div class="card card-style overflow-visible card-custom-products pt-2 pb-0 bg-orange-light">
                <div class="row mb-0">
                    <div class="col-7 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <label for="prod_1" class="color-white bg-orange-light">Готовая продукция</label>
                            <select id="prod_1" v-model="selected_goods_id" class="form-control bg-orange-light">
                                <option value="default" selected>выбрать</option>
                                <option
                                    v-for="(goods, index) in listGoods"
                                    v-bind:value="goods.goods_id"
                                >
                                    {{ goods.name }}
                                </option>

                            </select>
                            <span><i class="fa fa-chevron-down"></i></span>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
                    <div class="col-4 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" class="form-control focus-color focus-blue validate-name bg-orange-light"
                                   id="f4"
                                   v-model="amount"
                                   @focus="$event.target.select()"
                            >
                            <label for="f1" class="bg-orange-light">кол-во</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
                    <div class="col-1">
<!-- unit from selected_goods_id -->
                    </div>
                </div>
            </div>


            <div class="card card-style overflow-visible card-custom-products">
                <div class="row mb-0" v-for="(ingredient, i) in ingredients" :key="i">
                    <div class="col-7 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <label for="prod_2" class="color-blue-dark">Ингредиент {{i + 1}}</label>
                            <select id="prod_2" v-model="ingredients[i].goods_id" :value="ingredients[i].goods_id" @change="changeIngredient(i)" class="form-control">
                                <option value="default" selected>выбрать</option>
                                <option
                                    v-for="(goods, index) in listIngredients.filter(el => ![...selected_ingredients.slice(0, i), ...selected_ingredients.slice(i+1, selected_ingredients.length)].includes(el.goods_id))"
                                    v-bind:value="goods.goods_id"
                                >
                                    {{ goods.name }}, {{ goods.amount }} {{ goods.unit }}
                                </option>

                            </select>
                            <span><i class="fa fa-chevron-down"></i></span>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>

                    <div class="col-4 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" :disabled="ingredient.max_amount === 0" class="form-control focus-color focus-blue validate-name "
                                   id="f1"
                                   v-model="ingredient.amount"
                                   @input="checkAmount(i)"
                            >
                            <!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>{{ ingredient.max_amount }}</em>
                        </div>
                    </div>
                    <div class="col-1 p-1 text-center">
                    {{ingredient.unit}}
                    </div>
<!--                    <div class="col-2 p-1">-->
<!--                        <div class="input-style input-style-always-active has-borders no-icon">-->
<!--                            <input type="number" disabled class="form-control focus-color focus-blue validate-name text-center"-->
<!--                                   :placeholder="ingredient.unit"-->
<!--                            >-->

<!--                            <i class="fa fa-times disabled invalid color-red-dark"></i>-->
<!--                            <i class="fa fa-check disabled valid color-green-dark"></i>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
                <button @click="addIngredient" style="padding: 15px 24px; background-color: #A0D468; border-radius: 28px; color: #fff;" class="add-ingredient-btn font-39">+</button>
            </div>

            <button v-if="isAllRight" type="button" class="btn btn-success btn-lg create-product-btn" @click="createProduct">Создать ГТ и передать ее на главный склад</button>

        </div>

        <nav-bar-menu></nav-bar-menu>

    </div>
</template>

<script>
import headBar from "../Components/HeadBar";
import NavBar from "../Components/NavBar";
import NavBarMenu from "../Components/NavBarMenu";
import Error from "../Components/Error";

export default {
    name: 'MakeProducts',
    components: {
        Error,
        headBar, NavBar, NavBarMenu
    },
    data() {
        return {
            message: '',
            listGoods: [],
            selected_goods_id: 'default',
            amount: 0,
            listIngredients: [],
            ingredients: [{
                goods_id: 'default',
                amount: '',
                max_amount: 0,
                unit: ''
            }]
        }
    },
    computed: {
        selected_ingredients(){
            let arr = [];
            this.ingredients.forEach(el => {
              arr.push(el.goods_id)
            });
            return arr;
        },
        isAllRight(){
          if(this.selected_goods_id!='default' && this.amount>0 && this.ingredients[0].amount>0)
            return 1
        }
    },
    beforeMount() {
        this.my_storage_id = localStorage.getItem('my_storage_id')
        this.my_storage_name = localStorage.getItem('my_storage_name')
    },
    async mounted() {
        await this.getGoodsAllowed();
        await this.getGoodsAvailable();
    },
    methods: {
        async getGoodsAllowed(){
            let res = await axios.get('/api/getStorageGoods/allowed/'+this.my_storage_id+'/all');

            if(!res.data) {
                return ;
            }

            this.listGoods = res.data.data.filter(el => el.type === 2);
            console.log(this.listGoods)

        },
        async getGoodsAvailable(){
            let res = await axios.get('/api/getStorageGoods/available/'+this.my_storage_id+'/all');

            if(!res.data) {
                return ;
            }

            this.listIngredients = res.data.data.filter(el => el.type === 1).filter(el => el.amount > 0);
            console.log(this.listIngredients)

        },
        changeIngredient(i, value){
            const selected_id = this.ingredients[i].goods_id;
            if(!Number.isInteger(selected_id)){
                return;
            }
            const current = this.listIngredients.find(el => el.goods_id === selected_id);
            this.ingredients[i].max_amount = current.amount;
            this.ingredients[i].unit = current.unit;
        },
        async createProduct(){

            axios.post('/api/makeProduct/', {
                storage_id: this.my_storage_id,
                goods_id: this.selected_goods_id,
                amount: this.amount,
                ingredients: this.ingredients
            }).then(res => {

                if(res.data.status==='ok') {
                    console.log('приготовили: ' + res.data)
                    this.$router.push({name: 'home'})
                    this.message = 'приготовили' // это не работает. @todo уведомлять пользователя про успех
                }else {
                    this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                    console.error(' [serv] '+this.message)
                }
            }).catch(err => {
                this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                console.error(' [serv] '+this.message)
            })

        },
        addIngredient(){
            this.ingredients.push({
                goods_id: 'default',
                amount: '',
                max_amount: 0,
                unit: 'кг'
            });
        },
        checkAmount(i){
            if(this.ingredients[i].amount > this.ingredients[i].max_amount){
                this.ingredients[i].amount = this.ingredients[i].max_amount;
            }
        }
    }
}
</script>

<style>
    .card-custom-products {
        padding: 25px;
    }
    .add-ingredient-btn {
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translate(-50%, 50%);
    }
    .create-product-btn {
        margin-top: 30px;
        color: #fff;
    }
</style>
