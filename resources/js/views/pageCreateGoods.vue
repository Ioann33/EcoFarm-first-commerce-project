<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->  <error :message="message"></error>

            <title-page title_main="Создать продукт, товар"></title-page>

            <div class="card card-style overflow-visible p-4 pt-3 mt-3">

                <div class="row mb-0">
                    <div class="col-12 position-relative">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <label class="color-blue-dark" for="product_name">Товар или продукт</label>
                            <input id="product_name" type="text" class="form-control focus-color focus-blue" v-model="name" @blur="searchGoods">
                            <i class="fa fa-times invalid color-red-dark" :class="existing_goods.length ? '' : 'disabled' "></i>
                            <i class="fa fa-check valid color-green-dark" :class="!existing_goods.length && name ? '' : 'disabled' "></i>
                            <em></em>
                        </div>
                        <div class="spinner-border text-light position-absolute" role="status" style="right: 45px; top: 12px;" v-if="searching">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="col-12" v-if="existing_goods.length">
                        <p class="mb-0">Уже существуют похожие товары:</p>
                        <ul>
                            <li v-for="(exist, index) in existing_goods">{{ exist.name }}</li>
                        </ul>
                        <p class="mb-0">Продолжить создания продукта?</p>
                        <div class="col-6 d-flex align-items-center justify-content-between">
                            Нет
                            <div class="custom-control android-switch" style="z-index: 10;">
                                <input type="checkbox" class="android-input" id="toggle-id-4" v-model="ignore" @change="ignoreExistingGoods">
                                <label class="custom-control-label" for="toggle-id-4"></label>
                            </div>
                            Да
                        </div>

                    </div>
                    <div class="col-4">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <label class="color-blue-dark" for="product_unit">Ед. изм.</label>
                            <input id="product_unit" type="text" class="form-control focus-color focus-blue" v-model="unit">
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-check" v-for="item in types" :key="'type_'+item.value">
                            <input class="form-check-input"
                                   :checked="type === item.value" type="radio"
                                   :value="item.value"
                                   @change="changeType(item.value)" name="flexRadioDefault"
                                   :id="'flexRadioDefault'+item.value">
                            <label class="form-check-label" :for="'flexRadioDefault' + item.value">
                                {{ item.label }}
                            </label>
                        </div>
                    </div>
                </div>
                <button type="button" :disabled="searching || existing_goods.length" class="btn btn-lg btn-default" @click="createGoods">Добавить в базу</button>
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
    export default {
        name: "pageSaleProducts",
        components:{
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },

        data() {
            return {
                message: '',
                types: [
                    {
                        label: 'Ингридиент',
                        value: 1
                    },
                    {
                        label: 'Готовая продукция',
                        value: 2
                    }
                ],
                searching: false,
                existing_goods: [],
                ignore: false,
                name: '',
                unit: '',
                type: 1
            }
        },
        computed: {},
        mounted() {},
        updated() {
            update_template()
        },
        methods: {
            changeType(value){
                this.type = value;
            },
            async createGoods(){
                if(!this.name || !this.unit || !this.type){
                    this.message = 'Вы не указали обязательное поле';
                    return;
                }
                const res = await axios.post('/api/addGoods', {
                    name: this.name.toLowerCase(),
                    unit: this.unit.toLowerCase(),
                    type: this.type
                }).then(res => {
                   if(!res.data){
                       console.error('при добавлении товара произошла ошибка')
                       this.message = 'при добавлении товара произошла ошибка'
                   }
                    console.log('товар добавлен в систему')
                    this.$router.push({name: 'home'});
                });
            },
            searchGoods(){
                if(this.name === '') return;
                this.searching = true;
                axios.get(`/api/searchGoods/${this.name}`).then(res => {
                    this.existing_goods = res.data;
                    this.searching = false;
                }).catch(e => {
                   console.log(e)
                });
            },
            ignoreExistingGoods(){
                if(this.ignore){
                    this.existing_goods = []
                    this.ignore = false;
                }
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
    .select-input-spinner{
        position: absolute;
        right: 35px;
        top: 10px;
    }
    .add-goods-btn{
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translate(-50%, 50%);
    }
</style>
