<template>
<div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

<div class="page-content header-clear-medium">

    <!-- ERROR -->  <error :message="message"></error>


    <div class="content-boxed bg-blue-dark mb-3 pb-3 text-uppercase">
        <h4 class="color-white text-center">
            <div      v-if="this.dir=='in'">    На склад поступила продукция - подвердите     </div>
            <div v-else-if="this.dir=='out'">   Переданная продукция на рассмотрении    </div>
        </h4>
    </div>


    <div v-for="(movement, index) in listMovements" :key="movement.id">
        <card-movement
            :movement="movement"
            :dir='this.dir'
            :status="this.status"
            :editPrice="this.editPrice"
            @getMovementId="setMovementId"
            @showIngredients="showIngredients"

        ></card-movement>
    </div>





</div> <!-- page-contend -->

    <!-- меню показать Ингредиенты -->
    <div id="menu-showIngredients" class="menu menu-box-bottom menu-box-detached" data-menu-effect="menu-over">
        <div class="menu-title"><h1>Состав готовой продукции</h1><p class="color-green-dark">ингредиенты</p><a href="#" class="close-menu color-gray-dark"><i class="fa fa-times"></i></a></div>
        <div class="divider" style="margin: -4px 20px 16px 16px;"></div>


        <div class="d-flex mb-0 ms-3 pb-0">
            <div>
                <img src="images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130">
                <!--                        <img v-if="movement.goods_img !== null"  :src="'images/goods/'+movement.goods_id+'.jpg'" class="rounded-m shadow-xl" width="130">-->
                <!--                        <img v-else src="images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130">-->
            </div>
            <div class="ms-3">
                <h4 class="font-600">{{ activeModal.goods_name }}
                    <!--                            <span class="font-300" style="margin-left: 15px">{{ movement.amount }} <sup>{{ movement.unit }}</sup></span>-->
                </h4>
                <h5 class="pt-1 font-600">{{ activeModal.amount }} <sup>{{ activeModal.unit }}</sup></h5>
                <h5 class="pt-1 font-600" v-if="activeModal.price>0">{{ activeModal.price }} <sup>₴</sup></h5>
                <div v-else class="opacity-20">цена не установленна</div>
                <p></p>
            </div>
            <div class="ms-auto opacity-40 font-11 me-4">#{{ activeModal.id }}</div>
        </div>


        <div class="row mb-0 mt-2 ms-0 font-10 text-start color-dark-light">
            <div class="col-12 pe-1">
                <div>
                    <i class="fa fa-pencil-alt pe-1"></i>   Приготовил
                    <i class="fa fa-user ps-2 pe-1"> </i>   {{ activeModal.user_name_created }}
                    <i class="fa fa-clock ps-2 pe-2"></i>   {{ activeModal.date_created}}
                </div>
            </div>
        </div>


        <div class="content mb-0">

            <table class="table table-sm">
                <tbody>
                <tr>
                    <th>#</th>
                    <th>Ингредиент</th>
                    <th>кол-во</th>
                    <th>цена</th>
                </tr>
                <tr v-for="(ingredient, index) in activeModal.ingredients">
                    <th scope="row">{{ index + 1 }}</th>
                    <td> {{ ingredient.goods_name }}</td>
                    <td> {{ ingredient.amount }} <sup>{{ ingredient.unit }} </sup> </td>
                    <td> {{ ingredient.price }} <sup>₴</sup></td>
                </tr>
                </tbody>
            </table>

            <div class="row mb-3">
                <div class="col-8">
                    <a href="#" v-if="activeModal.storage_id_to == my_storage_id" class="close-menu btn btn-m font-800 rounded-sm btn-full text-uppercase bg-red-dark"> удалить приготовление</a>
                </div>
                <div class="col-4">
                    <a href="#" class="close-menu btn btn-m font-800 rounded-sm btn-full text-uppercase bg-gray-dark">закрыть</a>
                </div>
            </div>

        </div>
    </div>

    <!-- меню Установить цену -->
    <div id="menu-setPrice" class="menu menu-box-bottom menu-box-detached bg-orange-light rounded-m" data-menu-effect="menu-over" data-menu-height="200">
        <div class="menu-title mt-n1">
            <h1>Установить цену</h1>
            <a href="#" class="close-menu"><i class="fa fa-times"></i></a>
        </div>
        <div class="content mb-0 mt-2">
            <div class="divider mb-3"></div>
<!--            <div class="input-style no-borders no-icon validate-field">-->
<!--                <input type="text" class="form-control validate-text" id="form2a63"  placeholder="0.00" v-model="price">-->
<!--                <label for="form2a63" class="color-highlight">цена</label>-->
<!--                <i class="fa fa-times disabled invalid color-red-dark"></i>-->
<!--                <i class="fa fa-check disabled valid color-green-dark"></i>-->
<!--                <em>(обязательно)</em>-->
<!--            </div>-->

            <div class="input-group input-group-lg">
                <span class="input-group-text">₴</span>
                <input type="number" class="form-control validate-text" id="form2a63"  placeholder="0.00"
                       v-model="price"
                       @focus="$event.target.select()">
            </div>

            <a href="#" @click.prevent="pullGoods(movement_id)"  data-menu="menu-setPrice" class="btn btn-l mt-4 rounded-sm btn-full bg-blue-dark text-uppercase font-800">Установить цену</a>
        </div>
    </div>

    <nav-bar-menu></nav-bar-menu>

</div>  <!-- id="page" -->
</template>

<script>
import headBar from "../Components/HeadBar";
import NavBar from "../Components/NavBar";
import NavBarMenu from "../Components/NavBarMenu";
import Error from "../Components/Error";

import CardMovement from "../Components/cardMovement";


/* Страница отображения заявок на перемещение товара */
export default {
    name: "pageMovements",
    components:{
        Error,headBar, NavBar, NavBarMenu,
        CardMovement,
    },
    data(){
        return {
            listMovements: [],      //  массив всех перемещений согласно типу
            dir: null,              // { in | out }
            status: null,           // { opened | canceled | progress }
            message: null,           // for error message
            movement_id: '',
            price: -0,
            editPrice: false,
            oneUpdate: 0,
            my_storage_type: '',
            activeModal: {},
            my_storage_id: 0,
            main_storage_id: 0,

        }
    },
    beforeMount() {
        this.my_storage_id = localStorage.getItem('my_storage_id')
        this.main_storage_id = localStorage.getItem('main_storage_id')

    },
    mounted() {
        this.dir = this.$route.params.dir
        this.status = this.$route.params.status

        // получить мой склад
        this.storage_id = localStorage.getItem('my_storage_id');
        //console.log('my_storage_id: ' + this.storage_id)

        // получить основной склад
        this.main_storage_id = localStorage.getItem('main_storage_id');

        // если выбранный склад - главный - то можно менять цену
        this.editPrice = this.storage_id == this.main_storage_id;
        console.log('editPrice: ' + this.editPrice)

        // получить все заказы на перемещение входящие/исходящие открытые/выполненные
        axios.get('/api/getMovement/' + this.dir + '/opened/'+ this.storage_id).then(res => {
            console.log('listGoods:')
            console.log(res.data)
            this.listMovements = res.data.data
        }).catch(err => {
            this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            console.error (' [serv] '+this.message)
        })
    },
    updated() {
            //update_template()
    },
    methods: {
        showIngredients(id){

            console.log('ingredients:')
            axios.get(`/api/getIngredients/${id}`).then(res => {
                this.activeModal = res.data.data
                console.log(res.data.data)


                showMenu('menu-showIngredients')

            }).catch(e => {
                console.log(e)
            });
        },
        setMovementId(e){
            this.movement_id = e;
            console.log('Получили movement_id из карточки товара : ' + this.movement_id)
            // если editPrice == false т.е. это не главный склад - то сразу перейти к процедуре - перемещение товара, без установления цены
            if(!this.editPrice){
                this.pullGoods(this.movement_id)
            } else {


                // установить цену на товар "по умолчанию" взятую из этого перемещения
                axios.get('/api/getMovementInfo/' + this.movement_id).then(res => {
                    // если отгрузка из type=grow - то цену  нужно брать рекомендованную
                    axios.get('api/getStorageProp/' + res.data.data.storage_id_from).then(res2 => {

                        // если отгрузка из type=grow - то цену  нужно брать рекомендованную
                        if(res2.data.data[0].type==='grow')
                        {
                            // api/getStorageGoods/available/1/2
                            axios.get('/api/getStorageGoods/available/' + res.data.data.storage_id_to +'/'+ res.data.data.goods_id).then(res3 => {
                                this.price = res3.data.data[0].price
                                console.log('цену продукта взять из стока склада: ' + this.price)
                            }).catch(err => {
                                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                                console.error(this.message)
                            })
                        }
                        else {
                            this.price = res.data.data.price
                            console.log('цена продукта взять из базы: ' + this.price)
                        }

                        showMenu('menu-setPrice')

                    }).catch(err => {
                        this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                        console.error(this.message)
                    })

                    // this.price = res.data.data.price
                    // console.log('цена продукта взять из базы: ' + this.price)

                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })



            }


        },
        getListOrders(storage_id) {
            axios.get('/api/getStorageGoods/'+ this.dir +'/'+this.status+'/'+storage_id).then(res => {
                this.listOrders = res.data.data
                console.log(this.listOrders)
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                console.log(this.message)
            })
        },
        pullGoods(movement_id){
            // если выбранный склад - это главный склад, то оприходование товара прошло через модальное окно "установить цену"
            if(this.editPrice) {
                axios.post('/api/setPrice/', {
                    movement_id,
                    price: this.price
                }).then(res => {
                    console.log('[serv-'+res.data.status+'] '+res.data.message)


                    axios.post('/api/goodsMovementPull/', {
                        movement_id
                    }).then(res => {
                        console.log('movements #' + movement_id + ' pull is approve')
                        console.log('[serv-'+res.data.status+'] '+res.data.message)
                        this.$router.push({name: 'home'});
                    }).catch(err => {
                        this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                    })


                }).catch(err => {
                    this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                })
            }else {
                axios.post('/api/goodsMovementPull/', {
                    movement_id
                }).then(res => {
                    console.log('movements #' + movement_id + ' pull is approve')
                    this.$router.push({name: 'home'});
                }).catch(err => {
                    this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                })
            }

        },


    }

}
</script>


