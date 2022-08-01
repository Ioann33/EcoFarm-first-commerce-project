<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

            <!-- ERROR -->
            <error :message="message"></error>




            <div class="card card-style">
                <div class="content-boxed bg-blue-dark mb-1 pb-3 text-center">
                    <h4 class="color-white">Передать продукцию</h4>
                </div>

                <div class="content mb-0 p-0">



                    <div class="row mb-0">

                        <div class="col-7 p-1">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <label for="f6" class="color-blue-dark">Выбрать продукцию для заказа</label>
                                <select id="f6" v-model="selected_goods_id" class="form-control">
                                    <option value="default"  selected>продукция</option>

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
                                <input type="number" class="form-control focus-color focus-blue validate-name "
                                       id="f1"
                                       v-model="goods_amount"
                                >
                                <label for="f1" class="color-blue-dark">кол-во</label>
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em>0</em>
                            </div>
                        </div>

                        <div class="col-2 p-1">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <input type="number" disabled class="form-control focus-color focus-blue validate-name text-center"
                                       placeholder="кг"
                                >

                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                            </div>
                        </div>

                    </div>

                    <!--выбор склада/департамента. только для главного склада                    -->
<!--                    <div class="row">-->
<!--                        <div class="col-12 p-1">-->
<!--                            <div class="input-style input-style-always-active has-borders no-icon">-->
<!--                                <label for="f6" class="color-blue-dark">Выбрать склад</label>-->
<!--                                <select id="f6" v-model="selected_goods_id">-->
<!--                                    <option value="default" disabled selected>продукция</option>-->

<!--                                    <option-->
<!--                                        v-for="(goods, index) in listGoods"-->
<!--                                        v-bind:value="goods.goods_id"-->
<!--                                    >-->
<!--                                        {{ goods.name }} ({{ goods.goods_id }})-->
<!--                                    </option>-->

<!--                                </select>-->
<!--                                <span><i class="fa fa-chevron-down"></i></span>-->
<!--                                <i class="fa fa-check disabled valid color-green-dark"></i>-->
<!--                                <em></em>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
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
import StorageButton from "../Components/StorageButton";
import error from "../Components/Error";

export default {
    name: "SelectStorage",
    components:{
        headBar, NavBar, NavBarMenu,
        StorageButton,
        error
    },
    data(){
        return {
            listGoods: null,
            storage_id: null,
            storage_id_to: null,
            main_storage_id: null,
            message: null,
            selected: null,
            selected_goods_id: null,

            goods_amount: 0 ,// количество товара


        }
    },
    mounted() {
        //console.log('Component views/Home mounted....')
        this.storage_id = localStorage.getItem('my_storage_id')
        this.main_storage_id = localStorage.getItem('main_storage_id')

        axios.get('/api/getStorageGoods/allowed/' + this.storage_id + '/all').then(res => {
            this.listGoods = res.data.data
        }).catch(err => {
            this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            console.log(this.message)
        })

    },
    updated() {
        //console.log('updated')
        //init_template2()
    },
    methods: {

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


