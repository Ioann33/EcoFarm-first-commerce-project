<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

            <!-- ERROR -->
            <error
                :message="message"
            ></error>

            <div class="content-boxed bg-blue-dark mb-3 pb-1 text-uppercase text-center">
                <h4 class="color-white">
                    Товары на складе
                </h4>
                <p class="color-white">
                   <i class="fa-fw select-all fas "></i> {{ storage_name }}
                </p>
            </div>














            <div class="card card-style bg-theme pb-0">
                <div class="content" id="tab-group-2">
                    <div class="tab-controls tabs-small tabs-rounded" data-highlight="bg-blue-dark">
                        <a href="#" data-active data-bs-toggle="collapse" data-bs-target="#tab-5"><i class="fa fa-heart"></i></a>
                        <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-6"><i class="fa fa-star"></i></a>
                        <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-7"><i class="fa fa-battery "></i></a>
                    </div>
                    <div class="clearfix mb-3"></div>
                    <div data-bs-parent="#tab-group-2" class="collapse show" id="tab-5">

<!--  Вид-1  -->
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
                                    <th scope="row" class="align-self-center ">{{ goods.name }}</th>
                                    <td ><h4 class="font-600">{{ goods.price }} <sup class="font-400">₴</sup></h4></td>
                                    <td><h4 class="font-600">{{ goods.amount }} <sup class="font-400">{{ goods.unit }}</sup></h4></td>
                                </tr>
                                </tbody>
                            </table>
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
            </div>


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
            storage_id: null,
            storage_name: null, // имя текущего склада
        }
    },
    updated() {
        update_template()
    },
    mounted() {

        this.type = this.$route.params.type
        this.storage_id = localStorage.getItem('my_storage_id')
        this.storage_name = localStorage.getItem('my_storage_name')

        // Получить список продукции на складе
        axios.get('/api/getStorageGoods/'+ this.type + '/' + this.storage_id+'/all').then(res => {
            this.listGoods = res.data.data.filter(el => el.amount >0)
            console.log('listGoods:')
            console.log(this.listGoods)
        }).catch(err => {
            this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            console.log(this.message)
        })
    }
}
</script>

<style scoped>

</style>
