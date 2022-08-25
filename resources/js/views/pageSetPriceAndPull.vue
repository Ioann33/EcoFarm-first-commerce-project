<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->  <error :message="message"></error>


            <div class="card card-style">
                <div class="content-boxed bg-blue-dark mb-1 pb-3 text-center">
                    <h4 class="color-white">Установить цену и оприходовать</h4>
                    {{ movement.goods_name }}, {{ movement.amount }} <sup>{{ movement.goods_unit }}</sup>
                </div>



                <div class="content mb-0">


                    <div class="row pb-0 mb-0">
                        <div class="col-5">
                            <div class="input-style has-borders input-style-always-active validate-field mb-0">
                                <input type="number" class="form-control validate-number" id="form111" placeholder="0.00 грн"
                                       @input="checkPrice"
                                       v-model="price"
                                       @focus="$event.target.select()">
                                <label for="form111" class="color-blue-dark">стоимость ед.</label>
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em></em>
                            </div>
                        </div>
                        <div class="col-3 ps-0 pe-0">
                            <div class="input-style  input-style-always-active has-borders no-icon validate-field mb-4">
                                <input type="number" class="form-control validate-number" id="form112" disabled placeholder="" v-model="amount">
                                <label for="form112" class="color-blue-dark">кол-во</label>
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em></em>
                            </div>


                        </div>
                        <div class="col-4">
                            <div class="input-style has-borders no-icon input-style-always-active validate-field mb-4">
                                <input type="number" class="form-control validate-number" disabled id="form113" placeholder="" v-model="total">
                                <label for="form113" class="color-blue-dark">итого</label>
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em></em>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0 mt-0 pt-0">
                        <div class="col-9"></div>
                        <div class="col-1"><i class="fa-fw select-all fas "></i></div>
                        <div class="col-2"></div>
                    </div>

                    <div class="row mt-3 mb-0">
                        <div class="col-6">
                            <div class="input-style has-borders input-style-always-active validate-field mb-4">
                                <input type="number" class="form-control validate-number"  id="form113" placeholder=""
                                       @input="checkCostProduceOne"
                                       v-model="cost_produce_one">
                                <label for="form113" class="color-blue-dark">Изготовление ед.</label>
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em></em>
                            </div>
                        </div>
                        <div class="col-6 p-0">
                            <div class="input-style has-borders  input-style-always-active validate-field mb-4">
                                <input type="number" class="form-control validate-number" id="form112" disabled placeholder="" v-model="total_produce">
                                <label for="form112" class="color-blue-dark">Итого за производство</label>
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em></em>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0 mt-0 pt-0">
                        <div class="col-9"></div>
                        <div class="col-2"><i class="fa-fw select-all fas "></i></div>
                        <div class="col-1"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6 pe-0">
                            <div class="input-style has-borders input-style-always-active validate-field mb-4">
                                <input type="number" class="form-control validate-number" disabled id="form113" placeholder=""  v-model="total_price_one">
                                <label for="form113" class="color-blue-dark">себестоимость ед.</label>
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em></em>
                            </div>
                        </div>

                        <div class="col-6 pe-0">
                            <div class="input-style has-borders input-style-always-active validate-field mb-4">
                                <input type="number" class="form-control validate-number" disabled id="form113" placeholder=""  v-model="total_with_produce">
                                <label for="form113" class="color-blue-dark">ИТОГО за отгрузку</label>
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em></em>
                            </div>
                        </div>
                    </div>


                </div>


                <a v-if="canPull" @click.prevent="doSetPriceAndPull" href="#">
                    <div class="content-boxed bg-green-dark mt-1 pb-3 text-center text-uppercase">
                        <h4 class="color-white">Оприходовать</h4>
                    </div>
                </a>
            </div>

<!--    карточка товара    -->
            <div class="d-flex m-2 pb-1">
                <div>
                    <img src="images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130">
                </div>
                <div class="ms-3">
                    <h3 class="font-600">{{ movement.goods_name }}
                        <!--                            <span class="font-300" style="margin-left: 15px">{{ movement.amount }} <sup>{{ movement.unit }}</sup></span>-->
                    </h3>
                    <h4 class="pt-1 font-600">{{ movement.amount }} <sup>{{ movement.goods_unit }}</sup></h4>
                    <h4 class="pt-1 font-600" v-if="movement.price>0">{{ movement.price }} <sup>₴</sup></h4>
                    <div v-else class="opacity-20">цена не установленна</div>

                    Отгрузка из:     <i class="fa-fw select-all fas "></i> {{ movement.storage_name_from }}
                </div>
                <div class="ms-auto opacity-40 font-11">#{{ movement.id }}</div>
            </div>
<!-- конeц карточка товара -->
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
        name: "Spend",
        components:{
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },

        data() {
            return {
                message: '',
                loading_goods: false,
                selected_storage_id: 'default',
                selected_category_id: 'default',
                size_pay: '',

                movement: [],

                price: 0,
                amount: '',
                total: '',

                cost_produce_one: 0,

                price_with_produce: '',
                total_price_one: '',
                total_produce: '',
                total_with_produce: ''


            }
        },
        computed: {
            canPull(){
                if(this.total_with_produce>0)
                    return 1
            }
        },
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id');
            this.movement_id = this.$route.params.movement_id
        },
        mounted() {
            // загрузить данные по этому перемещению
            axios.get('/api/getMovementInfo/'+this.movement_id).then(res => {
                this.movement = res.data.data
                console.log(this.movement)
                this.price = Number.parseFloat(this.movement.price)
                this.amount = Number.parseFloat(this.movement.amount)
                this.total = (this.movement.price * this.movement.amount).toFixed(2)

                this.total_with_produce = (this.amount * (this.price + Number.parseFloat(this.cost_produce_one))).toFixed(2)




            }).catch(err => {
                this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                console.error(this.message)
            })





            update_template()
        },
        methods: {
            checkPrice(){
                this.total = this.amount * Number.parseFloat(this.price)
                this.total_with_produce = this.amount * (Number.parseFloat(this.price) + Number.parseFloat(this.cost_produce_one))
                this.total_price_one = Number.parseFloat(this.price) + Number.parseFloat(this.cost_produce_one)
            },
            checkCostProduceOne(){
                this.total = (this.amount * Number.parseFloat(this.price)).toFixed(2)
                this.total_with_produce = (this.amount * (Number.parseFloat(this.price) + Number.parseFloat(this.cost_produce_one))).toFixed(2)
                this.total_produce = (this.amount * Number.parseFloat(this.cost_produce_one)).toFixed(2)
                this.total_price_one = (Number.parseFloat(this.price) + Number.parseFloat(this.cost_produce_one)).toFixed(2)
            },
            doSetPriceAndPull(){
                console.log('установить цену и оприходовать')

                axios.post('/api/setPrice/', {
                    movement_id: this.movement_id,
                    price: this.total_price_one
                }).then(res => {
                    console.log(' [serv-' +res.data.status+ '] ' +res.data.message)


                    axios.post('/api/goodsMovementPull/', {
                        movement_id: this.movement_id,
                    }).then(res => {
                        console.log('movements #' + this.movement_id + ' pull is approve')
                        console.log(' [serv-' +res.data.status+ '] ' +res.data.message)

                        this.$router.push({name: 'home'});

                    }).catch(err => {
                        this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                        console.error(this.message)
                    })


                }).catch(err => {
                    this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                    console.error(this.message)
                })
            }
        },
        updated() {
            update_template()
        },

    }
</script>
