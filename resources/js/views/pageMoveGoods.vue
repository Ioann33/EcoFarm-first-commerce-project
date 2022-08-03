<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

            <!-- ERROR -->
            <error :message="message"></error>


Order_id: [{{ this.order.id }}]

            <div class="card card-style">
                <div class="content-boxed bg-blue-dark mb-1 pb-3 text-center">
                    <h4 class="color-white">Передать продукцию</h4>
                </div>

                <div class="content mb-0 p-0">



                    <div class="row mb-0">

                        <div class="col-7 p-1">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <label for="f6" class="color-blue-dark">Товар</label>
                                <select id="f6" v-model="selected_goods_id" @change="changeProduct" class="form-control">
                                    <option value="default"  selected>выбрать</option>
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

                        <div class="col-3 p-1">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <input type="number" :disabled="max_amount === 0" class="form-control focus-color focus-blue validate-name "
                                       id="f1"
                                       v-model="goods_amount"
                                       @input="checkAmount"
                                >
<!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em>{{ max_amount }}</em>
                            </div>
                        </div>

                        <div class="col-2 p-1">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <input type="number" disabled class="form-control focus-color focus-blue validate-name text-center"
                                       :placeholder="unit"
                                >

                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                            </div>
                        </div>

                    </div>



                    <!--выбор склада/департамента. только для главного склада                    -->
                    <div class="row" v-if="canSelectStorageTo">
                        <div class="col-12 p-1">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <label for="f6" class="color-blue-dark">на склад</label>
                                <select id="f6" v-model="selected_storage_id">
                                    <option value="default" disabled selected>выбрать склад</option>

                                    <option
                                        v-for="(storage, index) in listStorage"
                                        v-bind:value="storage.id"
                                    >
                                        {{ storage.name }}
                                    </option>

                                </select>
                                <span><i class="fa fa-chevron-down"></i></span>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em></em>
                            </div>
                        </div>
                    </div>

                </div>


                <a @click.prevent="makeMoveGoods" href="#">
                    <div class="content-boxed bg-blue-dark mt-1 pb-3 text-center text-uppercase">
                        <h4 class="color-white">Передать на склад</h4>
                    </div>
                </a>
            </div>


        </div>

        <nav-bar-menu></nav-bar-menu>

    </div>
</template>

<script>
import headBar from "../components/headBar";
import NavBar from "../Components/NavBar";
import NavBarMenu from "../Components/NavBarMenu";

import error from "../Components/Error";
import cardOrder from "../Components/cardOrder";

export default {
    name: "SelectStorage",
    components:{
        headBar, NavBar, NavBarMenu,
        error,
        cardOrder
    },
    data(){
        return {
            listGoods: null,
            listStorage: null,
            storage_id: null,
            storage_id_to: null,
            main_storage_id: null,
            message: null,
            selected: null,
            selected_goods_id: 'default',
            selected_storage_id: 'default',

            goods_amount: 0 ,   // количество товара
            max_amount: 0,  // максимальное количество товара
            unit: 'кг',  // единица измерения товара

            order_id: null,      // для входящего параметра из route order_id. если параметр есть - то на основании него и будет формироваться передача продукци
            order: [],     // массив. getOrder/order_id

            dir: 'in',
            status: 'progress',
        }
    },
    mounted() {
        this.order_id           = this.$route.params.order_id
        this.storage_id         = localStorage.getItem('my_storage_id')
        this.main_storage_id    = localStorage.getItem('main_storage_id')

        axios.get('/api/getStorageGoods/available/' + this.storage_id + '/all').then(res => {
            this.listGoods = res.data.data;
            console.log(this.listGoods)
        }).catch(err => {
            this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
            console.log(this.message)
        })

        // Если перешли на эту страницу без order_id
        if(this.order_id == '') {
            axios.get('/api/getStorageGoods/allowed/' + this.storage_id + '/all').then(res => {
                this.listGoods = res.data.data
            }).catch(err => {
                this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                console.log(this.message)
            })
        }else{
            // получить параметры этого ордера, что бы автоматически заполнить поля отгрузки товара
            axios.get('/api/getOrder/' + this.order_id).then(res => {
                this.order = res.data.data
                this.goods_amount = this.order.amount
                this.selected_goods_id = this.order.goods_id
                // установить скдад по-умолчанию на основании ордера/заказа
                this.selected_storage_id=this.order.storage_id_from

            }).catch(err => {
                this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                console.log(this.message)
            })
        }



    },
    computed: {
        canSelectStorageTo() {
            if(localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id'))
            {
                axios.get('/api/getListStorage').then(res => {
                    this.listStorage = res.data.data.filter(el => el.id !== Number.parseInt(this.storage_id))

                }).catch(err => {
                    this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                    console.log(this.message)
                })
                return 1
            }
        }
    },
    updated() {
        //console.log('updated')
        update_template()
    },
    methods: {
        checkAmount(){
            if(this.goods_amount > this.max_amount){
                this.goods_amount = this.max_amount;
            }
        },
        changeProduct(){
            const current_good = this.listGoods.find(el => el.goods_id == this.selected_goods_id);
            if(current_good) {
                const {amount, unit} = current_good;
                this.max_amount = amount;
                this.unit = unit;
            }
        },
        makeMoveGoods(){
            axios.post('/api/goodsMovementPush',{
                storage_id_from: this.storage_id,
                storage_id_to: this.main_storage_id,
                goods_id: this.selected_goods_id,
                amount: this.goods_amount
            }).then(res => {
                console.log('Move Goods Succesful:' +
                    '\ngood_id:' + this.selected_goods_id +
                    ', \nstorage_to: '+ this.main_storage_id +
                    ', \nstorage_from: '+ localStorage.getItem('my_storage_id')+
                    ', \namount: '+ this.goods_amount
                )
                this.$router.push({name: 'home'});

            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                console.log(this.message)
            })
        }

    }

}
</script>


