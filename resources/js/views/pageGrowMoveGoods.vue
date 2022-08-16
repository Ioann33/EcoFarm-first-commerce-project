<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

            <!-- ERROR --><error :message="message"></error>



            <div class="card card-style">
                <div class="content-boxed bg-blue-dark mb-1 pb-3 text-center">
                    <h4 class="color-white">Забрать продукцию с теплицы</h4>
                </div>

                <div class="content mb-0 p-0">

                    <!--выбор теплицы, с которой забирать товар                   -->
                    <div class="row mb-0">
                        <div class="col-12 p-1">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <label for="grow-select" class="color-blue-dark">Теплица</label>
                                <select id="grow-select" v-model="selected_storage_id" @change="changeStorage" class="form-control">
                                    <option value="default" disabled selected>выбрать теплицу</option>

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

                    <div class="row mb-0">

                        <div class="col-7 p-1">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <label for="prod-select" class="color-blue-dark">Продукт</label>
                                <select id="prod-select" v-model="selected_goods_id" @change="changeProduct" class="form-control">
                                    <option value="default" disabled  selected>выбрать продукт</option>
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
                                       @focus="$event.target.select()"
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
                    <div class="row mb-0">
                      <div class="col-12">
                          <div class="input-style has-borders has-icon validate-field mb-4">
                              <i class="text-black-50 font-29 text-left " style="top: 20px; padding-left: 7px  !important;">₴</i>
                              <input type="number" class="form-control validate-number" id="form111" placeholder="Цена 0.00 грн" v-model="price">
                              <label for="form111" class="color-blue-dark">Цена</label>
                              <i class="fa fa-times disabled invalid color-red-dark"></i>
                              <i class="fa fa-check disabled valid color-green-dark"></i>
                              <em>(обязательно)</em>
                          </div>
                      </div>
                    </div>





                </div>


                <a v-if="this.selected_storage_id!=='default'" @click.prevent="makeGrowMoveGoods" href="#" >
                    <div class="content-boxed bg-blue-dark mt-1 pb-3 text-center text-uppercase">
                        <h4 class="color-white">Передать на склад</h4>
                    </div>
                </a>
                <div v-else class="content-boxed bg-red-dark mt-1 pb-3 text-center text-uppercase">
                    <h4 class="color-white">Необходимо выбрать склад</h4>
                </div>
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
import cardOrder from "../Components/cardOrder";

export default {
    name: "GrowMoveGoods",
    components:{
        headBar, NavBar, NavBarMenu,
        error,
        cardOrder
    },
    data(){
        return {
            listGoods: null,
            listStorage: null,
            my_storage_id: null,
            storage_id_prop: [],
            storage_id_to: null,
            main_storage_id: null,
            message: '',
            selected: null,
            selected_goods_id: 'default',
            selected_storage_id: 'default',

            goods_amount: 0 ,   // количество товара
            max_amount: 0,  // максимальное количество товара
            price: '',
            unit: 'кг',  // единица измерения товара

            order_id: '',      // для входящего параметра из route order_id. если параметр есть - то на основании него и будет формироваться передача продукци
            order: [],     // массив. getOrder/order_id

            dir: 'in',
            status: 'progress',

        }
    },
    beforeMount() {
        this.my_storage_id = localStorage.getItem('my_storage_id')
        this.my_storage_name = localStorage.getItem('my_storage_name')
    },
    mounted() {

        // при переходе на эту страницу в роуте не обязательный параметр  path: '/MoveGoods/:order_id?',
        // возникает ошибка, если этот параметр не указан, но к нему обращаемся
        //this.order_id           = this.$route.params.order_id
        this.order_id           = ''
        //-----------------




        axios.get('/api/getStorageProp/'+this.my_storage_id).then(res => {
            this.storage_id_prop = res.data.data[0]

            axios.get('/api/getListStorages').then(res => {
                this.listStorage = res.data.data.filter(el => el.type === 'grow')

            }).catch(err => {
                this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                console.log(this.message)
            })




        }).catch(err => {
            this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
            console.log(this.message)
        })

    },
    computed: {
    },
    updated() {
        update_template()
    },
    methods: {
        getStorageProp(storage_id){
            axios.get('/api/getStorageProp/'+storage_id).then(res => {
                this.storage_id_prop = res.data.data[0]
                console.log(this.storage_id_prop )
            }).catch(err => {
                this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                console.log(this.message)
            })
        },
        checkAmount(){
            if(this.goods_amount > this.max_amount){
                this.goods_amount = this.max_amount;
            }
        },
        changeStorage(){
            axios.get('/api/getStorageGoods/allowed/' + this.selected_storage_id + '/all').then(res => {
                this.listGoods = res.data.data  // отобразим все разрешенные товары
            }).catch(err => {
                this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                console.log(this.message)
            })
        },
        changeProduct(){
            const current_good = this.listGoods.find(el => el.goods_id === this.selected_goods_id);
            if(current_good) {
                const {amount, unit} = current_good;
                this.max_amount = amount;
                this.unit = unit;
            }
        },
        makeGrowMoveGoods(){
            console.log('Move Goods:' +
                '\n     goods: ' + this.selected_goods_id +
                '\n     amount: ' + this.goods_amount + ' ' + this.unit +
                '\n     storage_to: ' + this.my_storage_id +
                '\n     storage_from: ' + this.selected_storage_id +
                '\n     price: ' + this.price
            )


            axios.post('/api/GrowAndMoveOnMainStorage',{
                storage_id_from: this.selected_storage_id,
                storage_id_to: this.my_storage_id,
                goods_id: this.selected_goods_id,
                amount: this.goods_amount,
                price: this.price
            }).then(res => {
                if(res.data.status === 'ok') {
                    console.log('[server]: '+res.data.message)
                    this.$router.push({name: 'home'});
                }else {
                    console.error(res.data.message)
                    this.message = res.data.message
                }
            }).catch(err => {
                console.error(this.message)
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })


        }

    }

}
</script>


