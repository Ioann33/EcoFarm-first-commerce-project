<template>
    <div class="card card-style p-0 pt-0 mt-0" v-for="(item, index) in movements" :key="index">

        <div class="row m-0" >
            <div class="col-9">
                <div class="row mb-0">
                    <div class="col-12 pt-2 pb-2" style="border-bottom: solid 1px #f2f2f7;">
                        <div><b class="p-3">Продажа </b> {{ item.goods_name }}</div>
<!--                        <div><b class="p-3">Количество</b> {{ item.amount }} {{ item.unit}}</div>-->
<!--                        <div><b class="p-3">Цена за еденицу</b> {{ item.stock_price }} <sup class="opacity-50">₴</sup> </div>-->
                        <div><b class="p-3">Цена изготовления</b> {{ item.amount }} {{ item.unit}} * {{ item.stock_price }} <sup class="opacity-50">₴</sup> =  {{ this.myRound(item.stock_price, item.amount) }} <sup class="opacity-50">₴</sup></div>
                        <div><b class="p-3">Цена продажи</b> {{ item.amount }} {{ item.unit}} * {{ (item.size_pay/item.amount).toFixed(2)}} <sup class="opacity-50">₴</sup>  = {{ item.size_pay }} <sup class="opacity-50">₴</sup></div>
                        <div><b class="p-3">Прибыль</b> {{(item.size_pay - this.myRound(item.stock_price, item.amount)).toFixed(2) }} <sup class="opacity-50">₴</sup> </div>
                    </div>
                    <div class="col-12 pt-2 pb-2" v-if="item.storage_name_to">
                        ➠
                        <span class="opacity-30">{{ item.user_name_accepted }}</span>
                    </div>
                </div>
            </div>
            <div class="col-3 d-flex align-items-center justify-content-center">
                <div class="icon icon-m rounded-s shadow-l me-1 bg-twitter">
                    <i class="fas fa-cash-register font-25 bg-green-dark rounded-s "></i>
                </div>

            </div>
            <div style="width: 100%; height: 1px; background-image: linear-gradient(to right, #A0D468, #8CC152)"></div>
            <div style="text-align: right;">{{ item.date }}</div>
        </div>

    </div>




    <div style="margin-top: 20px; margin-right: 30px; padding: 20px; float: right; font-size: 1.1rem">
        <div><b>за период: </b> c {{this.df}} по {{this.dt}}</div>
        <div><b>выручка: </b> {{this.resIncoming.toFixed(2)}} <sup class="opacity-50">₴</sup></div>
        <div><b>себестоимость: </b> {{ this.resSelfCost}} <sup class="opacity-50">₴</sup></div>
        <div><b>прибыль: </b> {{(this.resIncoming - this.resSelfCost).toFixed(2)}} <sup class="opacity-50">₴</sup></div>
    </div>

</template>

<script>

    export default {
        name: "listSales",
        data() {
            return {
                movements: [],
                my_storage_id : '',
                resIncoming : 0,
                resSelfCost : 0,
                df : '2022-12-01',
                dt : '2022-12-20',
            }
        },
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')
        },
        mounted() {
            this.getListSales()

        },
        methods: {
            myRound(first, second){

                let res = first * second
                return res.toFixed(2)

            },
            countMoney(transaction){
                transaction.forEach(el =>{
                    this.resSelfCost += Number(this.myRound(el.stock_price, el.amount))
                    this.resIncoming += Number(el.size_pay)
                })
            },
            getListSales(){
                axios.get(`/api/getListSales?df=${this.df}&dt=${this.dt}&storage_id=${this.my_storage_id}`).then(res => {
                    // this.movements = res.data.data.splice(0,100);
                    this.movements = res.data;
                    this.countMoney(res.data)
                    console.log(' from API: movements:')
                    console.log(this.movements)
                }).catch(e => {
                    console.log(e)
                });
            }
        },
        watch: {

        }
    }
</script>

<style scoped>

</style>
