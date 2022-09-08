<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

            <!-- ERROR -->
            <error :message="message"></error>

<router-link to="MoveGoods2" class="ms-3">передать сразу много товара (тестовая функция)</router-link> <br><br>

            <div class="card card-style">
                <div class="content-boxed bg-blue-dark mb-1 pb-3 text-center">
                    <h4 class="color-white">Передать продукцию</h4>
                </div>

                <div class="content mb-0 p-0">



                    <div class="row mb-0">
                        <div class="position-relative col-7 pb-3">
                            <label class="color-blue-dark position-absolute" style="z-index: 10; left: 22px; top: -12px; background-color: #fff; padding: 0 4px;">Продукт</label>
                            <v-select :options="listGoods"
                                      :value="'goods_id'"
                                      :label="'goods_name'"
                                      :placeholder="'выбрать продукт'"
                                      @option:selected="changeGoods"
                                      @search="searchGoods"
                            >
                            </v-select>
                        </div>
<!--                        <div class="col-7 p-1">-->
<!--                            <div class="input-style input-style-always-active has-borders no-icon">-->
<!--                                <label for="f6" class="color-blue-dark">Товар</label>-->
<!--                                <select id="f6" v-model="selected_goods_id" @change="changeProduct" class="form-control">-->
<!--                                    <option value="default" disabled selected>выбрать</option>-->
<!--                                    <option-->
<!--                                        v-for="(goods, index) in listGoods"-->
<!--                                        v-bind:value="goods.goods_id"-->
<!--                                    >-->
<!--                                        {{ goods.name }}<span v-if="this.rule==='available'">, {{ goods.amount }} {{ goods.unit }}</span>-->
<!--                                    </option>-->

<!--                                </select>-->
<!--                                <span><i class="fa fa-chevron-down"></i></span>-->
<!--                                <i class="fa fa-check disabled valid color-green-dark"></i>-->
<!--                                <em></em>-->
<!--                            </div>-->
<!--                        </div>-->

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



                    <!--выбор склада/департамента. только для главного склада                    -->
                    <div class="row position-relative" v-if="canSelectStorageTo">
                        <div class="col-12 p-1">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <label for="f6" class="color-blue-dark">на склад</label>
                                <select id="f6" v-model="selected_storage_id">
                                    <option value="default" disabled selected>выбрать склад</option>

                                    <option
                                        v-for="(storage, index) in listStorage"
                                        v-bind:value="storage.storage_id"
                                    >
                                        {{ storage.storage_name }}
                                    </option>

                                </select>
                                <span><i class="fa fa-chevron-down"></i></span>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em></em>
                            </div>
                        </div>
                        <div v-if="loadingStorage" class="spinner-border position-absolute text-light" role="status" style="top: 14px; right: 35px;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                </div>


                <a v-if="canDoPull" @click.prevent="makeMoveGoods" href="#" >
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
import vSelect from "vue-select"

export default {
    name: "MoveGoods",
    components:{
        headBar, NavBar, NavBarMenu,
        error,
        cardOrder, vSelect
    },
    data(){
        return {
            listGoods: [],
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
            unit: 'кг',  // единица измерения товара

            order_id: '',      // для входящего параметра из route order_id. если параметр есть - то на основании него и будет формироваться передача продукци
            order: [],     // массив. getOrder/order_id

            dir: 'in',
            status: 'progress',
            rule: 'allowed',     // правило подгрузки списка товаров, на основании storage.type= {теплица, склад, продажи,... }

            loadingStorage: false
        }
    },
    beforeMount() {
        this.my_storage_id = localStorage.getItem('my_storage_id')
        this.my_storage_name = localStorage.getItem('my_storage_name')
        this.my_storage_type = localStorage.getItem('my_storage_type')
    },
    mounted() {

        // при переходе на эту страницу в роуте не обязательный параметр  path: '/MoveGoods/:order_id?',
        // возникает ошибка, если этот параметр не указан, но к нему обращаемся
        //this.order_id           = this.$route.params.order_id
        this.order_id           = ''
        //-----------------

        if(this.my_storage_type === 'grow')
        {
            this.selected_storage_id    = localStorage.getItem('main_storage_id')

            this.rule = 'allowed'   // для теплиц
        }
        else
        {
            this.rule = 'available' // для всего остального (не теплицы)
        }

        // получить все разрешенные/доступные товары на текущей теплице
        // axios.get('/api/getStorageGoods/' + this.rule + '/' + this.my_storage_id + '/all').then(res => {
        //
        //     if(this.rule === 'available')
        //         this.listGoods = res.data.data.filter(el => el.amount >0)   // отобразим только те товары, которые есть на складе: amount >0
        //     else // allowed
        //         this.listGoods = res.data.data  // отобразим все разрешенные товары
        // }).catch(err => {
        //     this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
        //     console.error(this.message)
        // })

        // Если перешли на эту страницу без order_id
        // if(this.order_id.length ===  0) {
        //
        //     axios.get('/api/getStorageGoods/'+rule+'/' + this.storage_id + '/all').then(res => {
        //         // отобразим только те товары, которые есть на складе
        //         this.listGoods = res.data.data.filter(el => el.amount >0)
        //     }).catch(err => {
        //         this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
        //             console.log(this.message)
        //     })
        // } else {
        //     // получить параметры этого ордера, что бы автоматически заполнить поля отгрузки товара
        //     axios.get('/api/getOrder/' + this.order_id).then(res => {
        //         this.order = res.data.data
        //         this.goods_amount = this.order.amount
        //         this.selected_goods_id = this.order.goods_id
        //         // установить скдад по-умолчанию на основании ордера/заказа
        //         this.selected_storage_id = this.order.storage_id_from
        //
        //     }).catch(err => {
        //         this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
        //         console.log(this.message)
        //     })
        // }
    },
    computed: {
        canDoPull(){
            if(this.selected_storage_id!=='default' && this.goods_amount > 0)
                return 1
            else
                return 0
        },
        canSelectStorageTo() {
            if(this.my_storage_id === localStorage.getItem('main_storage_id'))
            {
                // axios.get('/api/getListStorages').then(res => {
                //     this.listStorage = res.data.data.filter(el => el.id !== Number.parseInt(this.storage_id))
                //
                // }).catch(err => {
                //     this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                //     console.log(this.message)
                // })

                return 1
            } else
            {
                this.selected_storage_id = localStorage.getItem('main_storage_id')
                return 0
            }
        }
    },
    updated() {
        update_template()
    },
    methods: {
        getStorageGoodsOne(goods_id){
            axios.get('/api/getStorageGoods/' + this.rule + '/' + this.my_storage_id + '/' + goods_id).then(res => {
                this.max_amount = res.data.data[0].amount;
            })
        },
        searchGoods(value){
            if(!value) return;
            axios.get('/api/searchStorageGoods/allowed/' + this.my_storage_id + '/'+value.toLowerCase()).then(res => {
                this.listGoods = res.data.data;
            }).catch(e => {
                this.message = e.response.data.message
                console.log(e)
            });
        },
        changeGoods(value){
            this.selected_goods_id = value.goods_id;
            this.unit = value.unit;

            // В запросе /api/searchStorageGoods мы не получаем max_amount этого товара,
            // поэтому делаем запрос на /api/getStorageGoods с конкретным goods_id

            // Этот запрос чтобы получить именно мксимальное количество товара
            this.getStorageGoodsOne(value.goods_id);

            // А этот запрос уже для получения списка доступных департаментов
            this.getListStoragesGoodsPermit();
        },
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
        // Метод для тарой версии инпута без возможности поиска
        changeProduct(){
            const current_good = this.listGoods.find(el => el.goods_id === this.selected_goods_id);
            if(current_good) {
                const {amount, unit} = current_good;
                this.max_amount = amount;
                this.unit = unit;

            }

            // после смены товара - отобразить на каких складах он доступен
            axios.get('/api/getListStoragesGoodsPermit/' + this.selected_goods_id).then(res => {
                this.listStorage = res.data.data.filter(el => el.allowed === true && el.storage_id !== +this.my_storage_id)
            }).catch(err => {
                this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                console.error(this.message)
            })
        },
        getListStoragesGoodsPermit(){
            this.loadingStorage = true;
            axios.get('/api/getListStoragesGoodsPermit/' + this.selected_goods_id).then(res => {
                this.listStorage = res.data.data.filter(el => el.allowed === true && el.storage_id !== +this.my_storage_id)
                this.loadingStorage = false;
            }).catch(err => {
                this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                console.error(this.message)
            })
        },
        makeMoveGoods(){
            console.log('Move Goods:' +
                '\n     goods: ' + this.selected_goods_id +
                '\n     amount: ' + this.goods_amount + ' ' + this.unit +
                '\n     storage_to: ' + this.selected_storage_id +
                '\n     storage_from: ' + this.my_storage_id
            )

            this.type=localStorage.getItem('my_storage_type')
            if(this.type === 'grow')
            {
                axios.post('/api/GrowAndMove',{
                    storage_id_from: this.my_storage_id,
                    storage_id_to: this.selected_storage_id,    // в данном случае this.selected_storage_id = localStorage.getItem('main_storage_id')
                    goods_id: this.selected_goods_id,
                    amount: this.goods_amount
                }).then(res => {
                    if(res.data.status === 'ok') {
                        console.log('[server]: ' + res.data.message)
                        this.$router.push({name: 'home'});
                    }else {
                        console.error(res.data.message)
                        this.message = res.data.message
                    }
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })


            }
            // это не теплица
            else {
                axios.post('/api/goodsMovementPush',{
                    storage_id_from: this.my_storage_id,
                    storage_id_to: this.selected_storage_id,
                    goods_id: this.selected_goods_id,
                    amount: this.goods_amount
                }).then(res => {
                    if(res.data.status === 'ok') {
                        console.log('[server]: ' + res.data.message)
                        this.$router.push({name: 'home'});
                    }else {
                        console.error(res.data.message)
                        this.message = res.data.message
                    }
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })
            }
        }

    }

}
</script>


