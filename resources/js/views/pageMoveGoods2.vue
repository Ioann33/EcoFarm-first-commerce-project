<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

            <!-- ERROR -->
            <error :message="message"></error>



            <div class="card card-style">

                <div class="content-boxed bg-4 mb-1 pb-3 text-center">
                    <h4 class="color-white">Передать продукцию <br>(тестовая функция)</h4>
                </div>

                <div class="content mb-5 p-0">

                    <!--выбор склада/департамента. только для главного склада  -->
                    <div class="row position-relative" v-if="canSelectStorageTo">
                        <div class="col-12 p-1">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <label for="f6" class="color-blue-dark">на склад</label>
                                <select id="f6" v-model="selected_storage_id" @change="changeStorage">
                                    <option value="default" disabled>выбрать склад</option>

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
                        <div v-if="loadingStorage" class="spinner-border position-absolute text-light" role="status" style="top: 14px; right: 35px;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <div class="row mb-3 position-relative" v-for="(el, index) in move_goods" :key="index">
                        <div class="position-relative col-7 pb-3">
                            <label class="color-blue-dark position-absolute" style="z-index: 10; left: 22px; top: -12px; background-color: #fff; padding: 0 4px;">Продукт {{ index + 1 }}</label>
                            <v-select :options="listGoods.filter(elem => ![...selected_goods.slice(0,index), ...selected_goods.slice(index+1,selected_goods.length)].includes(elem.goods_id))"
                                      :value="'goods_id'"
                                      :label="'goods_name'"
                                      :placeholder="'выбрать продукт'"
                                      @option:selected="changeGoods($event, index)"
                                      @search="searchGoods"
                                      :disabled="selected_storage_id === 'default'"
                                      :loading="el.loading"
                            >
                                <template #no-options="{ search, searching, loading }">
                                    Ничего не найдено
                                </template>
                                <template #option="{ goods_name, amount, unit, price }">
                                    <h6 style="margin: 0">{{ goods_name }}</h6>
                                    <em v-if="this.my_storage_type !== 'grow'">{{ amount }} {{ unit }} ➠ {{ price }}грн</em>
                                </template>
                            </v-select>
                        </div>


                        <div class="col-3 p-1">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <input type="number" :disabled="el.max_amount === 0" class="form-control focus-color focus-blue validate-name "
                                       id="f1"
                                       v-model="el.amount"
                                       @input="checkAmount(index)"
                                       @focus="$event.target.select()"
                                >
<!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em>{{ el.max_amount }}</em>
                            </div>
                        </div>

                        <div class="col-2 p-1">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <input type="number" disabled class="form-control focus-color focus-blue validate-name text-center"
                                       :placeholder="el.unit"
                                >

                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                            </div>
                        </div>
                        <i v-if="permits[index]" class="fa fa-check font-18 goods-allowed"></i>
                        <span class="goods-not-allowed" v-if="permits[index] === false">Товар не разрешен на выбранном складе</span>
                    </div>

                    <button @click="addGoods" style="padding: 15px 24px; background-color: #A0D468; border-radius: 30px; color: #fff;" class="add-ingredient-btn font-39 ">+</button>

                </div>

                <div v-if="!this.isAllPermits" class="content-boxed bg-red-dark mt-1 pb-3 text-center text-uppercase">
                    <h4 class="color-white">Уберите товар, который нельзя передать на склад</h4>
                </div>

                <a v-else-if="canDoPull" @click.prevent="makeMoveGoodsNEW" href="#">
                    <div class="content-boxed bg-green-dark mt-1 pb-3 text-center text-uppercase mt-5">
                        <h4 class="color-white">Передать</h4>
                    </div>
                </a>
                <div v-else-if="this.move_goods[0].goods_id === 'default'" class="content-boxed bg-red-dark mt-1 pb-3 text-center text-uppercase">
                    <h4 class="color-white">Необходимо выбрать товар для передачи</h4>
                </div>
                <div v-else-if="this.move_goods[0].amount === 0" class="content-boxed bg-red-dark mt-1 pb-3 text-center text-uppercase">
                    <h4 class="color-white">Необходимо установить количество</h4>
                </div>

                <div v-else class="content-boxed bg-red-dark mt-1 pb-3 text-center text-uppercase mt-5">
                    <h4 class="color-white">Необходимо выбрать склад</h4>
                </div>


            </div>


        </div>

        <nav-bar-menu></nav-bar-menu>
        <!-- TOAST -->
        <div id="toast-error" class="snackbar-toast bg-red-dark color-white" data-delay="1500" data-autohide="true"><i class="fa fa-check-circle me-3"></i>{{ this.toast_message}}</div>

    </div>

</template>

<script>
import headBar from "../Components/HeadBar";
import NavBar from "../Components/NavBar";
import NavBarMenu from "../Components/NavBarMenu";

import error from "../Components/Error";
import cardOrder from "../Components/cardOrder";
import vSelect from "vue-select"
import router from "../router/router";

export default {
    name: "MoveGoods",
    components:{
        headBar, NavBar, NavBarMenu,
        error,
        cardOrder, vSelect
    },
    data(){
        return {
            toast_message: '',
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

            move_goods: [{
                goods_id: 'default',
                amount: 0, // количество товара
                max_amount: 0, // максимальное количество товара
                unit: 'кг', // единица измерения товара
                loading: false
            }],
            permits: [],

            order_id: '',      // для входящего параметра из route order_id. если параметр есть - то на основании него и будет формироваться передача продукци
            order: [],     // массив. getOrder/order_id

            dir: 'in',
            status: 'progress',
            rule: 'allowed',     // правило подгрузки списка товаров, на основании storage.type= {теплица, склад, продажи,... }

            loadingStorage: false,
            ListStoragesAllowed: ['sale', 'buy', 'storage', 'creditor', 'cook', 'creditor']
        }
    },
    beforeMount() {
        this.my_storage_id = parseInt(localStorage.getItem('my_storage_id'))
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

        if(this.canSelectStorageTo)
            this.getListStorages();     // Получаем список всех складов и предоставляем их на выбор пользователя
        else
            this.selected_storage_id = localStorage.getItem('main_storage_id')
    },
    computed: {
        isAllPermits(){
            let count = 0;
            this.permits.forEach(el => {
              if(el) count++
            });
            if(count === this.permits.length) return true;
            return false;
        },

        selected_goods(){
            let arr = [];
            this.move_goods.forEach(el => {
                arr.push(el.goods_id);
            })
            return arr;
        },

        canDoPull(){
            // if(this.selected_storage_id!=='default' && this.goods_amount > 0)
            if(this.selected_storage_id!=='default' && this.move_goods[0].amount > 0)
                return 1
            else
                return 0
        },

        canSelectStorageTo() {
            if(this.my_storage_id === parseInt(localStorage.getItem('main_storage_id')))
            {
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
        toast(id, message){
            this.toast_message = message

            var notificationToast = new bootstrap.Toast(document.getElementById(id))
            notificationToast.show()
        },

        changeStorage(){
            // При изменении склада проверить выбранные товары на allowed
            this.move_goods.forEach((el, index) => {
                if(el.goods_id !== 'default'){
                    this.getStorageGoodsPermit(el.goods_id, index);
                }
            });
        },

        getListStorages(){
            axios.get('/api/getListStorages/').then(res => {
              /*
              "data": [
                    {
                    "id": 1,
                    "name": "Главный склад",
                    "type": "storage"
                    },
               */
              //
              this.listStorage = res.data.data.filter(el => el.id !== +this.my_storage_id && this.ListStoragesAllowed.includes(el.type))

            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                console.error(this.message)
            })
        },

        addGoods(){
            this.move_goods.push({
                goods_id: 'default',
                amount: 0,
                max_amount: 0,
                unit: 'кг',
                loading: false
            })
        },

        searchGoods(value){
            if(value === '') return;
            axios.get('/api/searchStorageGoods/' + this.rule + '/' + this.my_storage_id + '/'+value.toLowerCase()).then(res => {
                let name = '';
                this.listGoods=[]
                res.data.data.forEach(el => {

                    if(el.amount>0)
                        name = el.goods_name + ' ('+ el.amount + el.unit + ' ➠ '+ el.price+' грн)'
                    else
                        name = el.goods_name

                    this.listGoods.push({
                        goods_id: el.goods_id,
                        amount: el.amount,
                        price: el.price,
                        unit: el.unit,
                        goods_name: name

                    })

                })
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                console.error(this.message)
            });
        },

        getStorageGoodsPermit(id, index){
            this.move_goods[index].loading = true;
            axios.get('/api/getListStoragesGoodsPermit/' + id).then(res => {
                const allowed = res.data.data.filter(el => el.storage_id === +this.selected_storage_id && el.allowed);
                if(allowed.length > 0){
                    this.permits[index] = true;
                } else {
                    this.toast('toast-error',  'Товар не разрешен на выбранном складе')
                    this.permits[index] = false;
                }
                this.move_goods[index].loading = false;
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                console.error(this.message)
            })
        },

        changeGoods(value, index){
            this.move_goods[index].goods_id = value.goods_id;
            this.move_goods[index].max_amount = value.amount;
            this.move_goods[index].unit = value.unit;

            this.getStorageGoodsPermit(value.goods_id, index)
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

        checkAmount(index){
            if(this.move_goods[index].amount > this.move_goods[index].max_amount){
                this.move_goods[index].amount = this.move_goods[index].max_amount;
            }
        },

// @todo не используется ?
//         getListStoragesGoodsPermit(){
//             this.loadingStorage = true;
//             axios.get('/api/getListStoragesGoodsPermit/' + this.selected_goods_id).then(res => {
//                 this.listStorage = res.data.data.filter(el => el.allowed === true && el.storage_id !== +this.my_storage_id)
//                 this.loadingStorage = false;
//             }).catch(err => {
//                 this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
//                 console.error(this.message)
//             })
//         },

        makeMoveGoodsNEW(){
            if(!this.isAllPermits) return;

            // вариант-1 обработки массива
            // let move = [];
            // this.move_goods.forEach(el => {
            //     // Исключаем пустые инпуты с товаром или количеством 0
            //    if(el.goods_id !== 'default' || el.amount !== 0){
            //        move.push({
            //            storage_id_from: this.my_storage_id,
            //            storage_id_to: this.selected_storage_id,
            //            goods_id: el.goods_id,
            //            amount: el.amount
            //        })
            //    }
            // });
            // console.log(move)

            // вариант-2 обработки массива
            let move = this.move_goods.map(el => {
                    if(el.goods_id !== 'default' || el.amount !== 0){
                        return {
                            storage_id_from: this.my_storage_id,
                            storage_id_to: this.selected_storage_id,
                            goods_id: el.goods_id,
                            amount: el.amount
                        }
                    }
            })
            console.log(move)

            // Теперь непосредственно исполняем запрос
            axios.post('/api/pushPackageGoods', {
                move
            }).then(res => {
                console.log('Запрос исполнен успешно:')
                console.log(res.data)
                // location.reload();
                this.$router.push({name: 'home'});
            }).catch(e => {
                console.log('При выполнении запроса ошибка:')
                console.log(e)
            })
        },
    }

}
</script>
<style>
    .goods-allowed {
        position: absolute;
        z-index: 10;
        left: 100px;
        top: -8px;
        background-color: white;
        width: auto;
        padding: 0 5px;
        color: #A0D468;
    }
    .goods-not-allowed {
        color: #DA4453;
        position: absolute;
        left: 4px;
        bottom: -6px;
    }

    .vs__selected-options {
    flex-wrap: nowrap !important;
    padding: 3px 0 0 0;
}
</style>



