<template>
<div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

<div class="page-content header-clear-medium">

    <!-- ERROR -->
    <error
        :message="message"
    ></error>






<!-- OPENED >>>> -->
<div  v-if="this.status=='opened'">
<!--    <div class="card card-style bg-blue-dark shadow-bg shadow-bg-l">-->
<!--        <p class="content mb-4 text-uppercase font-900 font-15 text-center color-white ">-->
<!--            <div      v-if="this.dir=='in'">    Новые заказы ко мне     </div>-->
<!--            <div v-else-if="this.dir=='out'">   Открытые мои заказы     </div>-->
<!--        </p>-->
<!--    </div>-->

    <div class="content-boxed bg-blue-dark mb-3 pb-3 text-uppercase">
        <h4 class="color-white text-center">
            <div      v-if="this.dir=='in'">    Новые заказы ко мне     </div>
            <div v-else-if="this.dir=='out'">   Открытые мои заказы     </div>
        </h4>
    </div>


    <div v-for="(order, index) in listOrders" :key="order.id">

        <card-order
            :order="order"
            :status='this.status'
            :dir='this.dir'
        ></card-order>

    </div>


    <!--TABLE OUT-->
    <div class="card card-style">
        <div class="content mb-2">
            <h3>Заказы</h3>
            <table class="table table-borderless text-center rounded-sm shadow-l" style="overflow: hidden;">
                <thead>
                <tr class="bg-blue-dark">
                    <th scope="col" class="color-white">Продукт</th>
                    <th scope="col" class="color-white">Кол-во</th>
                    <th scope="col" class="color-white">Открыл</th>
                    <th scope="col" class="color-white">Дата</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="(order, index) in listOrders" :key="order.id">
                    <th scope="row">{{ order.name }} </th>
                    <td class="color-red-dark">{{ order.amount }} {{ order.unit }}</td>
                    <td>{{ order.user_name_created }}</td>
                    <td>{{ order.date_created }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!--end TABLE OUT-->

</div>
<!-- <<<<< OPENED -->

<!-- CANCELED >>>> -->
<div v-else-if="this.status=='canceled'">
<!--    <div class="card card-style bg-red-dark shadow-bg shadow-bg-l" >-->
<!--        <p class="content mb-4 text-uppercase font-900 font-15 text-center color-white ">-->
<!--            Отмененные заявки-->
<!--        </p>-->
<!--    </div>-->

    <div class="content-boxed bg-red-dark mb-3 pb-3 text-uppercase">
        <h4 class="color-white text-center">
            Отмененные заявки
        </h4>
    </div>


    <div v-for="(order, index) in listOrders" :key="order.id">
        <card-order
            :order="order"
            :type='this.type'
            :status='this.status'
        ></card-order>
    </div>

    <!--TABLE Canceled-->
    <div class="card card-style">
        <div class="content mb-2">
            <h3>Заказы Отмененные</h3>
            <table class="table table-borderless text-center rounded-sm shadow-l" style="overflow: hidden;">
                <thead>
                <tr class="bg-red-dark">
                    <th scope="col" class="color-white">Продукт</th>
                    <th scope="col" class="color-white">Кол-во</th>
                    <th scope="col" class="color-white">Заказал</th>
                    <th scope="col" class="color-white">Отменил</th>
                    <th scope="col" class="color-white">создали </th>
                    <th scope="col" class="color-white">отменили</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="(order, index) in listOrders" :key="order.id">
                    <th scope="row">{{ order.name }} </th>
                    <td class="color-red-dark">{{ order.amount }} {{ order.unit }}</td>
                    <td>{{ order.user_name_created }}</td>
                    <td>{{ order.user_name_handler }}</td>
                    <td>{{ order.date_created }}</td>
                    <td>{{ order.date_status }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!--end TABLE Canceled-->
</div>
<!-- <<<<< CANCELED -->

<!-- PROGRESS >>>> -->
<div v-if="this.status=='progress'">

<!--    <div class="card card-style bg-yellow-dark  shadow-bg-l" >-->
<!--        <p class="content mb-4 text-uppercase font-900 font-15 text-center color-black opacity-60">-->
<!--            Заявки взяты в работу-->
<!--        </p>-->
<!--    </div>-->

    <div class="content-boxed bg-yellow-dark mb-3 pb-3 text-uppercase">
        <h4 class="color-black opacity-60 text-center">
            Заявки взяты в работу
        </h4>
    </div>

    <div v-for="(order, index) in listOrders" :key="order.id">
        <card-order
            :order="order"
            :dir='this.dir'
            :status='this.status'
        ></card-order>
    </div>


    <!--TABLE progress -->
    <div class="card card-style">
        <div class="content mb-2">
            <h3>Заказы Выполняются</h3>
            <table class="table table-borderless text-center rounded-sm shadow-l" style="overflow: hidden;">
                <thead>
                <tr class="bg-yellow-dark">
                    <th scope="col" class="color-white">Продукт</th>
                    <th scope="col" class="color-white">Кол-во</th>
                    <th scope="col" class="color-white">Заказал</th>
                    <th scope="col" class="color-white">Взял в работу</th>
                    <th scope="col" class="color-white">создал</th>
                    <th scope="col" class="color-white">в работе с</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="(order, index) in listOrders" :key="order.id">
                    <th scope="row">{{ order.name }} </th>
                    <td class="color-red-dark">{{ order.amount }} {{ order.unit }}</td>
                    <td>{{ order.user_name_created }}</td>
                    <td>{{ order.user_name_handler }}</td>
                    <td>{{ order.date_created }}</td>
                    <td>{{ order.date_status }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!--end TABLE progress-->
</div>
<!-- <<<<< PROGRESS -->



</div> <!-- page-contend -->

    <nav-bar-menu></nav-bar-menu>

</div>  <!-- id="page" -->
</template>

<script>
import Error from "../Components/Error";
import headBar from "../components/headBar";
import NavBar from "../Components/NavBar";
import NavBarMenu from "../Components/NavBarMenu";

import CardOrder from "../Components/cardOrder";

export default {
    name: "pageOrders",
    components:{
        Error,headBar, NavBar, NavBarMenu,
        CardOrder

    },
    data(){
        return {
            listOrders: [],
            type: null,
            dir: null,
            status: null,
            message: null
        }
    },
    mounted() {
        //console.log('Component views/Home mounted....')
        this.storage_id = localStorage.getItem('my_storage_id')
        this.type = this.$route.params.type
        this.dir = this.$route.params.dir
        this.status = this.$route.params.status
        this.getListOrders(this.storage_id)


        console.log('Component views/pageOrders mounted......done!')
    },
    updated() {
        console.log('updated')
        init_template2()
    },
    methods: {
        getListOrders(storage_id) {
            axios.get('/api/getListOrder/'+ this.dir +'/'+this.status+'/'+storage_id).then(res => {
                this.listOrders = res.data.data
                console.log('listOrders: ')
                console.log(this.listOrders)
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                console.log(this.message)
            })
        },


    }

}
</script>


