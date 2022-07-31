<template>

    <div class="card card-style">
        <div class="content mb-0">
            <div>
                <div class="d-flex mb-0 pb-2">
                    <div>
                        <img src="images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130">
                    </div>
                    <div class="ms-3">
                        <h3 class="font-600">{{ movement.name }}
<!--                            <span class="font-300" style="margin-left: 15px">{{ movement.amount }} {{ movement.unit }}</span>-->
                        </h3>
<!--                        <h1 class="pt-3">$29.<sup>99</sup></h1>-->
                        <h4 class="pt-3 font-600">{{ movement.amount }} <sup>{{ movement.unit }}</sup></h4>
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
                        <a href="#" v-if="canPullGoods" data-menu="menu-setPrice" @click='this.movement_id = movement.id' class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark">Оприходовать</a>
<!--                        <a href="#" v-if="canMoveGoods"     @click.prevent="setOrderStatus(movement.order_id, 'canceled')" class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark">Отгрузить</a>-->


                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- menu-subscribe -->
    <div id="menu-setPrice" class="menu menu-box-bottom menu-box-detached rounded-m" data-menu-effect="menu-over" data-menu-height="200">
        <div class="menu-title mt-n1">
            <h1>Установить цену</h1>
            <a href="#" class="close-menu"><i class="fa fa-times"></i></a>
        </div>
        <div class="content mb-0 mt-2">
            <div class="divider mb-3"></div>
            <div class="input-style no-borders no-icon validate-field">
                <input type="text" class="form-control validate-text" id="form2a63" placeholder="0.00" v-model="price">
                <label for="form2a63" class="color-highlight">цена</label>
                <i class="fa fa-times disabled invalid color-red-dark"></i>
                <i class="fa fa-check disabled valid color-green-dark"></i>
                <em>(обязательно)</em>
            </div>
            <a href="#" @click.prevent="pullGoods(movement.id)" data-menu="menu-subscribe-confirm" class="btn btn-l mt-4 rounded-sm btn-full bg-blue-dark text-uppercase font-800">Установить цену</a>
        </div>
    </div>

</template>

<script>
export default {
    name: "cardMovement",
    data(){
        return {
            price: null
           //dir: null,
           // movement_id: null, // перед установкой цены
        }
    },
    props: [
        'movement',
        'status',
        'dir'
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
        canPullGoods(){
            if(this.dir == 'in' && this.status == 'opened' )
                return 1
        },

    },
  methods: {
        a(movement_id){
            console.log('-----')
        },
        pullGoods(movement_id){

            axios.post('/api/setPrice/', {
                movement_id,
                price: this.price
            }).then(res => {
                console.log('price is set')
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })

            axios.post('/api/goodsMovementPull/', {
                movement_id
            }).then(res => {
                console.log('movements approve')
                this.$router.push({name: 'home'});
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })
            console.log('-dd----'+movement_id+' '+this.price)
        },
    setOrderStatus(order_id, status){
        console.log('(cardMovement.vue) order_id: '+order_id + '. set Status to '+status)
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

<style scoped>

</style>
