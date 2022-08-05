<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium" style="text-align: center">
            <div class="card card-style card-custom-products">
                <div class="row mb-0">
                    <div class="col-10 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <label for="prod_1" class="color-blue-dark">Готовя продукция</label>
                            <select id="prod_1" v-model="selected_goods_id" class="form-control">
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
                    <div class="col-2 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" class="form-control focus-color focus-blue validate-name "
                                   id="f4"
                                   v-model="amount"
                            >
                            <!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
                </div>
                <div class="row mb-0" v-for="(ingredient, i) in ingredients" :key="i">
                    <div class="col-7 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <label for="prod_2" class="color-blue-dark">Ингредиент {{i + 1}}</label>
                            <select id="prod_2" v-model="ingredients[i].goods_id" @change="changeIngredient(i)" class="form-control">
                                <option value="default" selected>выбрать</option>
                                <option
                                    v-for="(goods, index) in listIngredients"
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

                    <div class="col-3 p-1">
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

                    <div class="col-2 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" disabled class="form-control focus-color focus-blue validate-name text-center"
                                   :placeholder="ingredient.unit"
                            >

                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                        </div>
                    </div>
                </div>
                <button @click="addIngredient" style="padding: 15px 24px; background-color: #A0D468; border-radius: 28px; color: #fff;" class="add-ingredient-btn">+</button>
            </div>

            <button type="button" class="btn btn-success btn-lg create-product-btn" @click="createProduct">Создать ГТ</button>

        </div>

        <nav-bar-menu></nav-bar-menu>

    </div>
</template>

<script>
import headBar from "../components/headBar";
import NavBar from "../Components/NavBar";
import NavBarMenu from "../Components/NavBarMenu";

export default {
    name: 'MakeProducts',
    components: {
        headBar, NavBar, NavBarMenu
    },
    data() {
        return {
            listGoods: [],
            selected_goods_id: 'default',
            amount: 0,
            listIngredients: [],
            ingredients: [{
                goods_id: 'default',
                amount: '',
                max_amount: 0,
                unit: 'кг'
            }]
        }
    },
    async mounted() {
        await this.getGoodsAllowed();
        await this.getGoodsAvailable();
    },
    methods: {
        async getGoodsAllowed(){
            let res = await axios.get('/api/getStorageGoods/allowed/1/all');

            if(!res.data) {
                return ;
            }

            this.listGoods = res.data.data;
            console.log(this.listGoods)

        },
        async getGoodsAvailable(){
            let res = await axios.get('/api/getStorageGoods/available/1/all');

            if(!res.data) {
                return ;
            }

            this.listIngredients = res.data.data;
            console.log(this.listGoods)

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
            const res = axios.post('/api/makeProduct', {
                goods_id: this.selected_goods_id,
                amount: '',
                ingredients: this.ingredients
            });

            if(!res.data){
                alert('Sorry, happened error')
                return ;
            }

            this.$router.push({name: 'home'})
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
        overflow: visible;
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
