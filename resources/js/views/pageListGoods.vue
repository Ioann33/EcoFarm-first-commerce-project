<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR --> <error :message="message"></error>

            <div class="content-boxed bg-blue-dark mb-3 pb-1 text-uppercase text-center">
                <h4 class="color-white">
                    Товары на складе
                </h4>
                <p class="color-white">
                   <i class="fa-fw select-all fas "></i> {{ my_storage_name }}
                </p>
            </div>














<!--            <div class="card card-style bg-theme pb-0">-->
                <div class="content" id="tab-group-2">
                    <div class="tab-controls tabs-small tabs-rounded" data-highlight="bg-blue-dark">
                        <a href="#" data-active data-bs-toggle="collapse" data-bs-target="#tab-5"><i class="fa fa-heart"></i></a>
                        <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-6"><i class="fa fa-star"></i></a>
                        <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-7"><i class="fa fa-battery "></i></a>
                    </div>
                    <div class="clearfix mb-3"></div>
                    <div data-bs-parent="#tab-group-2" class="collapse show" id="tab-5">
                        <!--  Вид-1  -->
                        <div class="content" id="tab-group-11">

                            <div class="tab-controls tabs-small tabs-rounded mb-2" data-highlight="bg-blue-dark">
                                <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-15" class="collapsed">Продукты</a>
                                <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-16" class="collapsed">Продукция</a>
                                <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-17" class="bg-blue-dark no-click">Все</a>
                            </div>

                            <div data-bs-parent="#tab-group-11" class="collapse show" id="tab-15" style="">
                                <div class=" mb-0">
                                    <table class="table text-center rounded-sm shadow-l" style="overflow: hidden;"> <!-- table-borderless -->
                                        <thead>
                                            <tr class="bg-grass-light">
                                                <th scope="col" class="color-black opacity-50">Продукт</th>
                                                <th scope="col" class="color-black opacity-50">Цена</th>
                                                <th scope="col" class="color-black opacity-50">Количество</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(goods, index) in listGoods.filter(el => el.type === 1 )" :key="goods.id">
                                                    <th scope="row" class="align-self-center ">{{ goods.name }} <sup class="opacity-10">{{goods.goods_id}}</sup></th>
                                                    <td ><h4 class="font-600">{{ goods.price }} <sup class="font-400">₴</sup></h4></td>
                                                    <td><h4 class="font-600">{{ goods.amount }} <sup class="font-400">{{ goods.unit }}</sup></h4></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-2" style="font-size: 18px;">
                                    ИТОГО: {{ Number.parseInt(totalAmount1.split('.')[0]).toLocaleString('en-US').replace(',', ' ') }},{{ totalAmount1.split('.')[1]}} грн
                                </div>
                            </div>
                            <div data-bs-parent="#tab-group-11" class="collapse" id="tab-16" style="">
                                <div class=" mb-0">
                                    <table class="table text-center rounded-sm shadow-l" style="overflow: hidden;"> <!-- table-borderless -->
                                        <thead>
                                        <tr class="bg-grass-light">
                                            <th scope="col" class="color-black opacity-50">Продукт</th>
                                            <th scope="col" class="color-black opacity-50">Цена</th>
                                            <th scope="col" class="color-black opacity-50">Количество</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(goods, index) in listGoods.filter(el => el.type === 2 )" :key="goods.id">
                                            <th scope="row" class="align-self-center ">{{ goods.name }} <sup class="opacity-10">{{goods.goods_id}}</sup></th>
                                            <td ><h4 class="font-600">{{ goods.price }} <sup class="font-400">₴</sup></h4></td>
                                            <td><h4 class="font-600">{{ goods.amount }} <sup class="font-400">{{ goods.unit }}</sup></h4></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-2" style="font-size: 18px;">
                                    ИТОГО: {{ Number.parseInt(totalAmount2.split('.')[0]).toLocaleString('en-US').replace(',', ' ') }},{{ totalAmount2.split('.')[1]}} грн
                                </div>
                            </div>
                            <div data-bs-parent="#tab-group-11" class="collapse" id="tab-17" style="">
                                <div class=" mb-0">
                                    <table class="table text-center rounded-sm shadow-l" style="overflow: hidden;"> <!-- table-borderless -->
                                        <thead>
                                        <tr class="bg-grass-light">
                                            <th scope="col" class="color-black opacity-50">Продукт</th>
                                            <th scope="col" class="color-black opacity-50">Цена</th>
                                            <th scope="col" class="color-black opacity-50">Количество</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(goods, index) in listGoods" :key="goods.id">
                                            <th scope="row" class="align-self-center ">{{ goods.name }} <sup class="opacity-10">{{goods.goods_id}}</sup></th>
                                            <td ><h4 class="font-600">{{ goods.price }} <sup class="font-400">₴</sup></h4></td>
                                            <td><h4 class="font-600">{{ goods.amount }} <sup class="font-400">{{ goods.unit }}</sup></h4></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center mt-2" style="font-size: 18px;">
                                    ИТОГО: {{ Number.parseInt(total.split('.')[0]).toLocaleString('en-US').replace(',', ' ') }},{{ total.split('.')[1]}} грн
                                </div>
                            </div>
                        </div>

                    </div>
                    <div data-bs-parent="#tab-group-2" class="collapse" id="tab-6">

<!--  Вид-2  -->
<!--                        <div class="card card-style mt-2">-->
                            <div class="content mb-0">
                                <div class="d-flex mb-0" v-for="(goods, index) in listGoods" :key="goods.id">
                                    <div class="align-self-center">{{ goods.name }}</div>
                                    <div class="ms-auto align-self-center"><h4 class="pt-3 font-600">{{ goods.amount }} <sup class="font-400">{{ goods.unit }}</sup></h4>   </div>
                                </div>
                            </div>
<!--                        </div>-->


                    </div>
                    <div data-bs-parent="#tab-group-2" class="collapse" id="tab-7">

<!--  Вид-3  -->
                        <div v-for="(goods, index) in listGoods" :key="goods.id">
                            <card-goods
                                :goods="goods"
                            ></card-goods>
                        </div>


                    </div>
                </div>
<!--            </div>-->


        </div> <!-- page-contend -->

        <nav-bar-menu></nav-bar-menu>

    </div>  <!-- id="page" -->
</template>

<script>
import Error from "../Components/Error";
import headBar from "../Components/HeadBar";
import NavBar from "../Components/NavBar";
import NavBarMenu from "../Components/NavBarMenu";
import CardGoods from "../Components/cardGoods";

export default {
    name: "pageListGoods",
    components:{
        Error,headBar, NavBar, NavBarMenu,
        CardGoods,
    },
    data(){
        return {
            listGoods: [],
            type: null,         // { available | allowed }
            message: null,      // for error reporting
            my_storage_id: null,
            my_storage_name: null, // имя текущего склада
        }
    },
    computed: {
      totalAmount1() {
          let total = 0;
          this.listGoods.forEach(el => {
              if(el.type === 1) total += el.amount * el.price
          });
          return total.toFixed(2);
      },
        totalAmount2() {
            let total = 0;
            this.listGoods.forEach(el => {
                if(el.type === 2) total += el.amount * el.price
            });
            return total.toFixed(2);
        },
        total() {
            let total = 0;
            this.listGoods.forEach(el => {
                total += el.amount * el.price
            });
            return total.toFixed(2);
        }
    },
    updated() {
        update_template()
    },
    beforeMount() {
        this.my_storage_id = localStorage.getItem('my_storage_id')
        this.my_storage_name = localStorage.getItem('my_storage_name')
    },
    mounted() {

        this.type = this.$route.params.type

        // Получить список продукции на складе
        // api/getStorageGoods/ {available | allowed} / {storage_id | all} / {goods_id | all}
        axios.get('/api/getStorageGoods/'+ this.type + '/' + this.my_storage_id+'/all').then(res => {
            this.listGoods = res.data.data.filter(el => el.amount >0)
            console.log('listGoods:')
            console.log(this.listGoods)
        }).catch(err => {
            this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            console.log(this.message)
        })
    },
    methods: {

    }
}
</script>

<style scoped>

</style>
