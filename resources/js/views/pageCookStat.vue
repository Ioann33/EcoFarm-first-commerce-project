<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
<!--            <div class="card card-style p-4 pt-3 mt-3 content">-->
            <title-page title_main="Готовая продукция произведеная складом"></title-page>
            <div class="card card-style m-2 p-2" v-for="goods in listMovements">
                <div class="d-flex mb-0 ms-3 pb-0">
                    <div>
                        <img src="images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130">
                        <!--                        <img v-if="movement.goods_img !== null"  :src="'images/goods/'+movement.goods_id+'.jpg'" class="rounded-m shadow-xl" width="130">-->
                        <!--                        <img v-else src="images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130">-->
                    </div>
                    <div class="ms-3">
                        <h4 class="font-600">{{ goods.goods_name }}
                            <!--                            <span class="font-300" style="margin-left: 15px">{{ movement.amount }} <sup>{{ movement.unit }}</sup></span>-->
                        </h4>
                        <h5 class="pt-1 font-600">{{ goods.amount }} <sup>{{ goods.goods_unit }}</sup></h5>
                        <h4 class="pt-1 font-600" v-if="goods.price>0">{{ goods.cost_with_production }} <sup>₴</sup></h4>
                        <div v-else class="opacity-20">цена не установленна</div>

                        <p></p>
                    </div>
                    <div class="ms-auto opacity-40 font-11 me-4">#{{ goods.id }}</div>
                </div>


                <div class="row mb-0 mt-2 me-2 font-10 text-end color-dark-light">
                    <div class="col-12 pe-1">
                        <div>
                            <i class="fa fa-pencil-alt pe-1"></i>   Приготовил
                            <i class="fa fa-user ps-2 pe-1"> </i>   {{ goods.user_name_created }} ({{ goods.storage_name_to }})
                            <i class="fa fa-clock ps-2 pe-2"></i>   {{ goods.date_created }}
                        </div>
                    </div>
                </div>


                <div class="content mb-0 mt-0">

                    <table class="table table-sm">
                        <tbody>
                        <tr>
                            <th></th>

                            <th>цена <sup>₴</sup></th>
                            <th>кол-во <sup>{{ goods.goods_unit}}</sup></th>
                            <th>Итого<sup>₴</sup></th>
                        </tr>
                        <tr >
                            <th scope="row">Цена изготовления</th>
                            <template v-if="goods.goodsReady_is_accepted == 'yes'">
                                <td> {{ goods.production }} </td>
                                <td> {{ goods.amount }}</td>
                                <td> {{ (goods.production *  goods.amount).toFixed(2)}} </td>
                            </template>
                            <template v-else>
                                <td colspan="3" class="text-center"> товар в пути </td>
                            </template>
                        </tr>
                        <tr >
                            <th scope="row">Себестоимость</th>

                            <td> {{ goods.prime_cost }}</td>
                            <td> {{ goods.amount }}</td>
                            <td> {{ (goods.prime_cost *  goods.amount).toFixed(2)}}</td>
                        </tr><tr >
                            <th scope="row">Итоговая цена продажи</th>
                            <td> {{ goods.cost_with_production }}</td>
                            <td> {{ goods.amount }}</td>
                            <td> {{ (goods.cost_with_production *  goods.amount).toFixed(2)}}</td>

                        </tr>
                        </tbody>
                    </table>


                </div>
            </div>


<!--            </div>-->
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
        name: "pagePreSale",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },
        data(){
            return {
                listMovements: [],
                df: '',
                dt: ''
            }
        },
        computed: {},
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.df = localStorage.getItem('df')
            this.dt = localStorage.getItem('dt')
        },
        mounted() {
            axios.get('api/getListGoodsMovements/null/'+this.my_storage_id+'/ready/'+this.df+'/'+this.dt)
                .then(res => {
                    console.log('list ready goods: ')
                    console.log(res.data.data)

                    this.listMovements = res.data.data

                    this.listMovements.forEach((el, index) => {
                        this.listMovements[index].production =                  (this.listMovements[index].cost_with_production - this.listMovements[index].prime_cost).toFixed(2)
                        this.listMovements[index].totalCost_with_production =   (this.listMovements[index].cost_with_production * this.listMovements[index].amount).toFixed(2)
                    })

                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })
        },
        updated() {
        },
        methods: {

        }
    }
</script>

<style scoped>

</style>
