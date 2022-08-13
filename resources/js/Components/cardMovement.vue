<template>

    <div class="card card-style">
        <div class="content mb-0">
            <div>
                <div class="d-flex mb-0 pb-2">
                    <div>
                        <img src="images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130">
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

<!--                        @click.prevent="pullGoods(movement.order_id)"-->


<!--                        <div v-if="!this.clk">-->
<!--                            <div v-if="needEditPrice"><a href="#" @click.prevent="this.clk=true"> установить цену и оприходовать </a></div>-->
<!--                            <div v-else> только оприходовать</div>-->
<!--                        </div>-->
<!--                        <div v-else>-->
<!--                            <div class="input-style input-style-always-active has-borders no-icon">-->
<!--                                <div class="input-group input-group-lg">-->
<!--                                    <div class="input-group-prepend">-->
<!--                                        <span class="input-group-text" id="inputGroup-sizing-lg">₴</span>-->
<!--                                    </div>-->
<!--                                    <input type="number" class="form-control validate-text" id="form2a63"  placeholder="0.00" v-model="price">-->
<!--                                </div>-->
<!--                                <a href="#"   @click.prevent='' class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark">Установить цену и Оприходовать</a>-->

<!--                            </div>-->
<!--                        </div>-->


                        <a href="#" v-if="editPrice" data-menu="menu-setPrice" @click='this.$emit("getMovementId", movement.id)' class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark">Установить цену и Оприходовать</a>
                        <a href="#" v-else-if="this.dir==='in'" @click='this.$emit("getMovementId", movement.id)' class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark">Оприходовать</a>
<!--                        <a href="#" v-if="canMoveGoods"     @click.prevent="setOrderStatus(movement.order_id, 'canceled')" class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark">Отгрузить</a>-->


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
            clk: false
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

