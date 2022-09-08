<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

            <title-page title_main="Редактировать товар"></title-page>

            <div class="card card-style p-4 pt-3 mt-3">
                <div class="position-relative pb-3">
                    <label class="color-blue-dark position-absolute" style="z-index: 10; left: 9px; top: -12px; background-color: #fff; padding: 0 4px;">Продукт</label>
                    <v-select :options="list_goods"
                              :value="'id'"
                              :label="'name'"
                              :placeholder="'выбрать продукт'"
                              @option:selected="changeGoods"
                              @search="searchGoods"
                    >
                    </v-select>
                </div>


<!--                <select-input :data="goods"-->
<!--                              :keyOfValue="'goods_id'"-->
<!--                              :value="selected_goods"-->
<!--                              :label="'Продукт'"-->
<!--                              :defaultOption="'выбрать продукт'"-->
<!--                              :loading="loadingGoods"-->
<!--                              @getSelected="selectGoods"-->
<!--                >-->
<!--                </select-input>-->

                <select-input :data="types"
                              :keyOfValue="'id'"
                              :value="selected_type"
                              :label="'Тип'"
                              :defaultOption="'выбрать тип'"
                              @getSelected="selectType"
                >
                </select-input>

                <div class="d-flex">
                    <div class="col-7 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="text" class="form-control focus-color focus-blue validate-name "
                                   id="name"
                                   v-model="name"
                            >
                            <label for="name" class="color-blue-dark">Название</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
                    <div class="col-4 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="text" class="form-control focus-color focus-blue validate-name "
                                   id="unit"
                                   v-model="unit"
                            >
                            <label for="unit" class="color-blue-dark">Ед. изм.</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
                    <div class="col-1">
                       <sup class="opacity-20">  {{goods_id}} </sup>
                    </div>

                </div>
                <button type="button" class="btn btn-lg btn-default" :disabled="disabled_btn" @click="editGoods">Сохранить изменения</button>
            </div>
        </div>

        <success v-if="success" :success="success"></success>

        <!-- TOAST -->
        <div id="toast-successful" class="snackbar-toast bg-green-dark color-white" data-delay="1500" data-autohide="true"><i class="fa fa-check-circle me-3"></i>{{ this.toast_message}}</div>

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
    import Success from "../Components/Success";
    import vSelect from "vue-select"

    export default {
        name: "pagePreSale",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu, SelectInput, Success, vSelect
        },
        data(){
            return {
                loadingGoods: false,
                goods: [],
                list_goods: [],
                selected_goods: 'default',
                types: [
                    {id: 1, name: 'Ингредиент'},
                    {id: 2, name: 'Продукт'},
                ],
                selected_type: 'default',
                name: '',
                unit: '',
                goods_id: '',
                success: '',
                toast_message: '',
            }
        },
        computed: {
            disabled_btn(){
                if(this.selected_type === 'default' ||
                    this.selected_goods === 'default' ||
                    !this.name ||
                    !this.unit){
                    return true;
                } else {
                    return false;
                }
            }
        },
        mounted() {
            this.getListGoods();
        },
        updated() {
        },
        methods: {
            toast(id, message){
                this.toast_message = message

                var notificationToast = new bootstrap.Toast(document.getElementById(id))
                notificationToast.show()
            },

            searchGoods(value){
                if(!value) return;
                // axios.get('/api/searchStorageGoods/allowed/all/'+value.toLowerCase()).then(res => {
                axios.get('/api/searchGoods/'+value.toLowerCase()).then(res => {
                    this.list_goods = res.data;
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })
            },

            changeGoods(value){
                console.log(value)
                if(!value) return;
                this.selected_goods = value.id;
                this.selected_type = value.type;
                this.name = value.name;
                this.unit = value.unit;
            },

            getListGoods(){
                this.loadingGoods = true;
                axios.get('/api/getListGoods').then(res => {
                    this.goods = res.data.data;
                    this.loadingGoods = false;
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })
            },

            editGoods(){
                axios.post('/api/updateGoods', {
                    name: this.name,
                    unit: this.unit,
                    type: this.selected_type,
                    goods_id: this.selected_goods,
                    group_id: 2,
                    image: ''
                }).then(res => {

                    this.getListGoods()
                    this.toast('toast-successful', 'Изменения сохранены')
                    //this.success = 'Изменения сохранены'
                    //setTimeout(() => this.success = '', 1500)
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })
            },

            selectGoods(value){
                this.selected_goods = value;
                if(value !== 'default'){
                    const current = this.goods.find(el => el.id === value);
                    this.name = current.name;
                    this.unit = current.unit;
                    this.goods_id = current.id
                    this.selected_type = current.type;
                } else {
                    this.name = this.unit = '';
                    this.selected_type = 'default';
                }
            },

            selectType(value){
                this.selected_type = value
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
