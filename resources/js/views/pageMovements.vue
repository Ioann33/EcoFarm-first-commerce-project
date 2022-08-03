<template>
<div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

<div class="page-content header-clear-medium">

    <!-- ERROR -->
    <error
        :message="message"
    ></error>


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
        ></card-movement>
    </div>





</div> <!-- page-contend -->

    <nav-bar-menu></nav-bar-menu>

</div>  <!-- id="page" -->
</template>

<script>
import headBar from "../components/headBar";
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

        }
    },
    mounted() {
        this.dir = this.$route.params.dir
        this.status = this.$route.params.status

        // получить мой склад
        this.storage_id = localStorage.getItem('my_storage_id');
        console.log('my_storage_id: ' + this.storage_id)


        // получить все заказы на перемещение входящие/исходящие открытые/выполненные
        axios.get('/api/getMovement/' + this.dir + '/opened/'+ this.storage_id).then(res => {
            console.log(res.data)
            this.listMovements = res.data.data
        }).catch(err => {
            this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
        })


    },
    updated() {
        console.log('updated')
        update_template
    },
    methods: {

        getListOrders(storage_id) {
            axios.get('/api/getStorageGoods/'+ this.dir +'/'+this.status+'/'+storage_id).then(res => {
                this.listOrders = res.data.data
                console.log(this.listOrders)
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                console.log(this.message)
            })
        },


    }

}
</script>


