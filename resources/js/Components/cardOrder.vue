<template>

    <div class="card card-style">
        <div class="content mb-0">
            <div>
                <div class="d-flex mb-0 pb-2">
                    <div>
                        <img src="images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130">
                    </div>
                    <div class="ms-3">
                        <h3 class="font-600">{{ order.name }}
<!--                            <span class="font-300" style="margin-left: 15px">{{ order.amount }} {{ order.unit }}</span>-->
                        </h3>
<!--                        <h1 class="pt-3">$29.<sup>99</sup></h1>-->
                        <h4 class="pt-3 font-600">{{ order.amount }} <sup>{{ order.unit }}</sup></h4>
<p></p>
                        Заказ в <i class="fa-fw select-all fas "></i> {{ order.storage_name }}
                    </div>
                    <div class="ms-auto opacity-40 font-11">#{{ order.order_id }}</div>
                </div>

                <div class="row mb-0 font-10 text-start color-dark-light">
                    <div class="col-6 pe-1">
                        <div><i class="fa fa-pencil-alt pe-2"></i>Создал</div>
                        <div><i class="fa fa-user pe-2"></i><b>({{ order.storage_name }})</b>  {{ order.user_name_created }}</div>
                        <div><i class="fa fa-clock pe-2"></i>{{ order.date_created}}</div>
                    </div>
                    <div class="col-6 pe-1 text-end">
                        <div v-if="status=='canceled'">Отменил <i class="fa fa-cancel pe-2 color-red-dark"></i></div>
                        <div v-if="status=='progress'">Взял в работу <i class="fa fa-handshake pe-2 color-yellow-dark"></i></div>

                        <div v-if="status=='canceled'">{{ order.user_name_handler}}  <i class="fa fa-user pe-2"></i></div>
                        <div v-if="status=='progress'">{{ order.user_name_handler}}  <i class="fa fa-user pe-2"></i></div>

                        <div v-if="status=='canceled'">{{ order.date_status}}  <i class="fa fa-calendar pe-2"></i></div>
                        <div v-if="status=='progress'">{{ order.date_status}}  <i class="fa fa-calendar pe-2"></i></div>
                    </div>

                    <div class="col-12">
                        <a href="#" v-if="canReOrder"       class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-magenta-dark">передать в другой отдел</a>
                    </div>
                    <div class="col-6 pe-1">
                        <a href="#" v-if="canCancel"        @click.prevent="setOrderStatus(order.order_id, 'canceled')" class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-red-dark">Отменить</a>


                    </div>
                    <div class="col-6 pe-1">
                        <a href="#" v-if="canGetToProgress" @click.prevent="setOrderStatus(order.order_id, 'progress')" class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-yellow-dark">В работу</a>
                        <router-link :to="{name: 'makeMoveGoods', params: {order_id: order.order_id}}" v-if="canMoveGoods"  class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark">Отгрузить</router-link>
                    </div>

                </div>
            </div>
        </div>
    </div>

<!--    <a href="#" class="card card-style mt-1 mb-2">-->
<!--        <div class="content ">-->
<!--            <div class="row mb-0">-->
<!--                <div class="col-3 pe-1"><img src="/images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130"></div>-->
<!--                <div class="col-1 pe-1"></div>-->
<!--                <div class="col-8 pe-1 ps-1">-->


<!--                    <div class="row mb-0">-->
<!--                        <div class="col-8">-->
<!--                            <h3 class="font-600">{{ order.name }} <span class="font-300" style="margin-left: 15px">{{ order.amount }} {{ order.unit }}</span> </h3>-->
<!--&lt;!&ndash;                            <h1 class="pt-1 pb-0" v-if="order.status == 'canceled'" >$29.<sup>99</sup></h1>&ndash;&gt;-->
<!--&lt;!&ndash;                            <h1 class="pt-1 pb-0 color-white" v-else>.</h1>&ndash;&gt;-->
<!--                        </div>-->
<!--                        <div class="col-4 opacity-40 text-end">#{{ order.order_id }}</div>-->
<!--                    </div>-->
<!--                    <div class="row mb-5">-->
<!--                        <div class="col-12">-->


<!--                            &lt;!&ndash;<div class="row mb-n5 mt-2 color-theme" v-if="canGetToProgress">-->
<!--                                <a href="#" @click.prevent="setOrderStatus(order.order_id, 'progress')" class="btn shadow-bg shadow-bg-m btn-m  mb-1 rounded-s text-uppercase font-900 shadow-s bg-yellow-dark color-black ">-->
<!--                                    <h4 class="font-900 text-uppercase opacity-60">взять в работу</h4></a>-->
<!--                            </div>-->


<!--                            <div class="row mb-n5 mt-5 color-theme" v-if="canCancel">-->
<!--                                <a href="#" @click.prevent="setOrderStatus(order.order_id, 'canceled')" class="btn shadow-bg shadow-bg-m btn-m  mb-1  rounded-s text-uppercase font-900 shadow-s bg-red-dark color-black ">-->
<!--                                    <h4 class="font-900 text-uppercase opacity-60">отменить</h4></a>-->
<!--                            </div>-->


<!--                            <div class="row">-->
<!--                                <div class="col-6">-->
<!--                                    <a href="#" v-if="canNewOrder" @click.prevent="setOrderStatus(order.order_id, 'canceled')" class="btn btn-m btn-full mb-1 rounded-xl text-uppercase font-900 shadow-s bg-teal-dark">-->
<!--                                        <h4 class="font-900 text-uppercase opacity-60">сформировать заявку в другой отдел</h4></a>-->

<!--                                </div>-->
<!--                                <div class="col-6">-->
<!--                                    <a href="#" v-if="canMoveGoods" @click.prevent="setOrderStatus(order.order_id, 'canceled')" class="btn btn-m btn-full mb-1 rounded-xl text-uppercase font-900 shadow-s bg-teal-dark">-->
<!--                                        <h4 class="font-900 text-uppercase opacity-60">сформировать заявку в другой отдел</h4></a>-->

<!--                                </div>-->
<!--                            </div>-->




<!--                            <div class="row mb-n5 mt-5 color-theme" v-if="canNewOrder">-->
<!--                                <a href="#" @click.prevent="setOrderStatus(order.order_id, 'canceled')" class="btn btn-m btn-full mb-1 rounded-xl text-uppercase font-900 shadow-s bg-teal-dark">-->
<!--                                    <h4 class="font-900 text-uppercase opacity-60">сформировать заявку в другой отдел</h4></a>-->
<!--                            </div>-->

<!--                            <div class="row mb-n5 mt-5 color-theme" v-if="canMoveGoods">-->
<!--                                <a href="#" @click.prevent="setOrderStatus(order.order_id, 'canceled')" class="btn shadow-bg shadow-bg-m btn-m  mb-1 rounded-s text-uppercase font-900 shadow-s bg-red-dark color-black ">-->
<!--                                    <h4 class="font-900 text-uppercase opacity-60">отгрузить продукцию</h4></a>-->
<!--                            </div>-->
<!--&ndash;&gt;-->
<!--                        </div>-->

<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->

<!--            <div class="row mb-0 font-10 text-start color-dark-light">-->
<!--                <div class="col-6 pe-1">-->
<!--                    <div><i class="fa fa-pencil-alt pe-2"></i>Создал</div>-->
<!--                    <div><i class="fa fa-user pe-2"></i><b>({{ order.storage_name }})</b>  {{ order.user_name_created }}</div>-->
<!--                    <div><i class="fa fa-clock pe-2"></i>{{ order.date_created}}</div>-->
<!--                </div>-->
<!--                <div class="col-6 pe-1 text-end">-->
<!--                    <div v-if="status=='canceled'">Отменил <i class="fa fa-cancel pe-2 color-red-dark"></i></div>-->
<!--                    <div v-if="status=='progress'">Взял в работу <i class="fa fa-handshake pe-2 color-yellow-dark"></i></div>-->

<!--                    <div v-if="status=='canceled'">{{ order.user_name_handler}}  <i class="fa fa-user pe-2"></i></div>-->
<!--                    <div v-if="status=='progress'">{{ order.user_name_handler}}  <i class="fa fa-user pe-2"></i></div>-->

<!--                    <div v-if="status=='canceled'">{{ order.date_status}}  <i class="fa fa-calendar pe-2"></i></div>-->
<!--                    <div v-if="status=='progress'">{{ order.date_status}}  <i class="fa fa-calendar pe-2"></i></div>-->
<!--                </div>-->

<!--                <div class="col-12">-->
<!--                    <a href="#" v-if="canReOrder"       class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-magenta-dark">передать в другой отдел</a>-->
<!--                </div>-->
<!--                <div class="col-6 pe-1">-->
<!--                    <a href="#" v-if="canCancel"        @click.prevent="setOrderStatus(order.order_id, 'canceled')" class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-red-dark">Отменить</a>-->


<!--                </div>-->
<!--                <div class="col-6 pe-1">-->
<!--                    <a href="#" v-if="canGetToProgress" @click.prevent="setOrderStatus(order.order_id, 'progress')" class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-yellow-dark">В работу</a>-->
<!--                    <a href="#" v-if="canMoveGoods"     @click.prevent="setOrderStatus(order.order_id, 'canceled')" class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark">Отгрузить</a>-->
<!--                </div>-->

<!--            </div>-->



<!--        </div>-->
<!--    </a>-->
</template>

<script>
export default {
    name: "cardOrder",
    data(){
        return {
           //dir: null,
            storage_id: null,
            goods_available: null,
            goods_enough: 'no'
        }
    },
    props: [
        'order',
        'status',
        'dir'
    ],
    mounted(){
        this.storage_id = localStorage.getItem('my_storage_id')

        axios.get('/api/getStorageGoods/available/'+ this.storage_id +'/'+ this.order.goods_id).then(res => {
            // console.log(res.data.data)
            this.goods_available = res.data.data[0].amount;
            if((this.goods_available - this.order.amount) >0)
                this.goods_enough = 'yes'
            else
                this.goods_enough = 'no'
    // console.log('можно отгружать. есть товар на складе ')
    //         else
    // console.log('нет товара на складе')
    //         // console.log('avail: '+ this.order.goods_id +'('+  res.data.data.goods_id +'): '+ this.goods_available +' kg')
        }).catch(err => {
            this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
        })
    },
    computed:{
        canCancel(){
            //if( localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id')
            if(
                (this.status == 'opened' &&
                    localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id'))
                ||
                (
                    this.status == 'progress' &&
                    localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id')
                )
            )
                return 1

            // если на деапртамент прилетела заявка(in). текущий статус - в работе(progress)
            if(this.dir == 'in' && this.status == 'progress')
                return 1
        },
        canGetToProgress(){

            // для Главного склада
            if( ( this.status == 'canceled' || this.status == 'opened') &&
                localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id'))
            return 1

            // если на деапртамент прилетела заявка(in). текущий статус - открыт(opened)
            if(this.dir == 'in' && this.status == 'opened')
                return 1
        },
        canNewOrder(){
            if(
                (this.status == 'opened' || this.status == 'progress') &&
                    localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id')

            )
                return 1
        },
        canMoveGoods(){

if(localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id'))
{
    if((this.status == 'opened' || this.status == 'progress') && this.goods_enough=="yes")
        return 1
} else {
    if(this.dir == 'in' && this.status == 'progress')
        return 1
}

            // if(
            //     (this.status == 'opened' || this.status == 'progress')
            //     && localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id')
            //     // && this.goods_enough == 'yes'
            // )
            //     return 1
            //
            // if(
            //     this.dir == 'in'
            //     && this.status == 'progress'
            //     // && this.goods_enough == 'yes'
            // )
            //     return 1


        },
        canReOrder(){
            if(
                (this.status == 'opened' || this.status == 'progress') &&
                localStorage.getItem('my_storage_id') == localStorage.getItem('main_storage_id')

            )
                return 1
        }
    },
    methods: {
        setOrderStatus(order_id, status){
        console.log('(cardOrder.vue) order_id: '+order_id + '. set Status to '+status)
        //this.dir = this.$route.params.dir
        //this.status = this.$route.params.status

        axios.get('/api/setOrderStatus/'+ status +'/'+ order_id).then(res => {
            console.log(res.data)
           // this.$router.push({name: 'pageOrders', params: {status: status, dir: dir}});
           this.$router.push({name: 'home'});
        }).catch(err => {
            this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
        })
    }
}
}
</script>
