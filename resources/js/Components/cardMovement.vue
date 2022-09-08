<template>

    <div class="card card-style">
        <div class="content mb-0">

                <div class="d-flex mb-0 pb-2">
                    <div>
                        <img src="images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130">
<!--                        <img v-if="movement.goods_img !== null"  :src="'images/goods/'+movement.goods_id+'.jpg'" class="rounded-m shadow-xl" width="130">-->
<!--                        <img v-else src="images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130">-->
                    </div>
                    <div class="ms-3">
                        <h3 class="font-600">{{ movement.goods_name }}
<!--                            <span class="font-300" style="margin-left: 15px">{{ movement.amount }} <sup>{{ movement.unit }}</sup></span>-->
                        </h3>
                        <h4 class="pt-3 font-600">{{ movement.amount }} <sup>{{ movement.unit }}</sup></h4>
                        <h4 class="pt-3 font-600" v-if="movement.price>0">{{ movement.price }} <sup>₴</sup></h4>
                        <div v-else class="opacity-20">цена не установленна</div>
<p></p>
                        Отгрузка <span v-if="dir=='in'">из</span> <span v-else>в</span> <br> <i class="fa-fw select-all fas "></i> {{ movement.storage_name }}
                    </div>
                    <div class="ms-auto opacity-40 font-11">#{{ movement.id }}</div>
                </div>

                <div class="row mb-0 font-10 text-start color-dark-light">
                    <div class="col-6 pe-1">
                        <div><i class="fa fa-pencil-alt pe-2"></i>Отгрузил</div>
                        <div><i class="fa fa-user pe-2"></i><b>({{ movement.storage_name }})</b>  {{ movement.user_name_created }}</div>
                        <div><i class="fa fa-clock pe-2"></i>{{ movement.date_created}}</div>
                    </div>
                    <div class="col-6 pe-1 text-end">
                        <div v-if="status=='canceled'">Отменил <i class="fa fa-cancel pe-2 color-red-dark"></i></div>
                        <div v-if="status=='canPullGoods'">оприходовать <i class="fa fa-handshake pe-2 color-yellow-dark"></i></div>

                        <div v-if="status=='canceled'">{{ movement.user_name_handler}}  <i class="fa fa-user pe-2"></i></div>
                        <div v-if="status=='progress'">{{ movement.user_name_handler}}  <i class="fa fa-user pe-2"></i></div>

                        <div v-if="status=='canceled'">{{ movement.date_status}}  <i class="fa fa-calendar pe-2"></i></div>
                        <div v-if="status=='progress'">{{ movement.date_status}}  <i class="fa fa-calendar pe-2"></i></div>
                    </div>

                    <div class="col-12">
<!--                        <a href="#" v-if="canReOrder"       class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-magenta-dark">передать в другой отдел</a>-->
                    </div>
                    <div class="col-6 pe-1">
<!--                        <a href="#" v-if="canCancel"        @click.prevent="setOrderStatus(movement.order_id, 'canceled')" class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-red-dark">Отменить</a>-->


                    </div>
                    <div class="col-6 pe-1">

<div v-if="this.dir !== 'out'">
    <div v-if="movement.goods_type === 2">
                            <router-link v-if="editPrice" :to="{name: 'SetPriceAndPull', params: {movement_id: movement.id}}">
                                <a class="btn btn-m btn-full mb-3 rounded-0 text-uppercase font-900 shadow-s bg-red-light">
                                    Установить цену продукции и Оприходовать</a>
                            </router-link>

                            <a href="#" v-else-if="this.dir==='in'" @click='this.$emit("getMovementId", movement.id)'                           class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark">
                                Оприходовать</a>

    </div>
    <div v-else>

                            <a href="#" v-if="editPrice"            @click='this.$emit("getMovementId", movement.id)' data-menu="menu-setPrice" class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark">
                                Установить цену продукта и Оприходовать</a>
                            <a href="#" v-else-if="this.dir==='in'" @click='this.$emit("getMovementId", movement.id)'                           class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark">
                                Оприходовать</a>
    <!--                        <a href="#" v-if="canMoveGoods"     @click.prevent="setOrderStatus(movement.order_id, 'canceled')" class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark">Отгрузить</a>-->
    </div>
</div>
<div v-else>
    <a @click='cancelMovements(movement.id)' class="btn btn-m btn-full mb-3 rounded-0 text-uppercase font-900 shadow-s bg-red-light">
        отменить <sup>(в разработке)</sup> </a>
</div>

                    </div>

                </div>






        </div>
    </div>



</template>

<script>
export default {
    name: "cardMovement",
    data(){
        return {
            price: null,
            editPrice2: false,
            my_storage_id: 0,
            main_storage_id: 0,
        }
    },
    props: [
        'movement',
        'status',
        'dir',
        'editPrice'
    ],
    computed:{
        // canCancel(){
        //     //if( localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id')
        //     if(
        //         (this.status == 'opened' &&
        //             localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id'))
        //         ||
        //         (
        //             this.status == 'progress' &&
        //             localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id')
        //         )
        //     )
        //         return 1
        //
        //     // если на деапртамент прилетела заявка(in). текущий статус - в работе(progress)
        //     if(this.dir == 'in' && this.status == 'progress')
        //         return 1
        // },
        // canGetToProgress(){
        //
        //     // для Главного склада
        //     if( ( this.status == 'canceled' || this.status == 'opened') &&
        //         localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id'))
        //     return 1
        //
        //     // если на деапртамент прилетела заявка(in). текущий статус - открыт(opened)
        //     if(this.dir == 'in' && this.status == 'opened')
        //         return 1
        // },
        // canNewOrder(){
        //     if(
        //         (this.status == 'opened' || this.status == 'progress') &&
        //             localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id')
        //
        //     )
        //         return 1
        // },
        // canMoveGoods(){
        //     if(
        //         (this.status == 'opened' || this.status == 'progress') &&
        //         localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id')
        //
        //     )
        //         return 1
        //
        //     if(this.dir == 'in' && this.status == 'progress')
        //         return 1
        // },
        // canReOrder(){
        //     if(
        //         (this.status == 'opened' || this.status == 'progress') &&
        //         localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id')
        //
        //     )
        //         return 1
        // },
        // canPullGoods(){
        //     // if(this.dir == 'in' && this.status == 'opened' )
        //     if(this.storage_id == this.main_storage_id;)
        //         return 1
        // },
        needEditPrice(){
            if(this.my_storage_id == this.main_storage_id)
                return true
        }
    },
    beforeMount() {
        this.my_storage_id = localStorage.getItem('my_storage_id')
        this.main_storage_id = localStorage.getItem('main_storage_id')

    },
    methods: {
        cancelMovements(movement_id){
            console.log('sdfsd' + movement_id)
            axios.post('api/deleteMovement', { movement_id}).then(res => {
                console.log(res.data)
                if(res.data.status === 'ok')
                {
                    console.log('delete ok')
                    location.reload();
                }
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                console.error(this.message)
            })
        },
        setPrice(move_id){
            console.log(move_id)
            this.editPrice2=true
        }
    // setOrderStatus(order_id, status){
    //     console.log('(cardMovement.vue) order_id: '+order_id + '. set Status to '+status)
    //     //this.dir = this.$route.params.dir
    //     //this.status = this.$route.params.status
    //
    //     axios.get('/api/setOrderStatus/'+ status +'/'+ order_id).then(res => {
    //         console.log(res.data)
    //        // this.$router.push({name: 'pageOrders', params: {status: status, dir: dir}});
    //        this.$router.push({name: 'home'});
    //     }).catch(err => {
    //         this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
    //     })
    // }
}
}
</script>

