<template>
    <div class="card card-style p-0 pt-0 mt-0" v-for="(item, index) in movements" :key="index">

        <div class="row m-0" >
            <div class="col-9">
                <div class="row mb-0">
                    <div class="col-12 pt-2 pb-2" v-if="item.storage_name_from" style="border-bottom: solid 1px #f2f2f7;">
                        <span class="opacity-20">От </span>
                        {{ item.storage_name_from }} <span class="opacity-30"> {{ item.user_name_created }}</span>

                        <!--                                <span class="opacity-20">для </span>-->
                        <!--                                {{ item.storage_name_to }} <span class="opacity-20">{{ item.user_name_accepted }}</span>-->
                    </div>
                    <div class="col-12 pt-2 pb-2" style="border-bottom: solid 1px #f2f2f7;">
                        <b class="p-3">{{ item.goods_name }}</b> {{ item.amount }} <sup class="opacity-50">{{ item.goods_unit }}</sup>
                        <span v-if="item.price" class="p-3">{{item.price}} <sup class="opacity-50">₴</sup></span>
                    </div>
                    <div class="col-12 pt-2 pb-2" v-if="item.storage_name_to">
                        ➠
                        {{ item.storage_name_to }} <span class="opacity-30">{{ item.user_name_accepted }}</span>
                    </div>
                </div>
            </div>
            <div class="col-3 d-flex align-items-center justify-content-center">
                <div class="icon icon-m rounded-s shadow-l me-1 bg-twitter">
<!--                    <i class="fa-solid fa-dolly font-20" v-if="item.category==='move'"></i>-->

                    <i class="fa-solid fa-right-from-bracket font-25 bg-orange-dark rounded-s" v-if="item.category==='move' && item.storage_id_from == my_storage_id"></i>
                    <i class="fa-solid fa-right-to-bracket font-25" v-if="item.category==='move' && item.storage_id_to == my_storage_id"></i>
                    <i class="fas fa-check-to-slot font-25 bg-green-dark rounded-s " v-if="item.category==='ready'"></i>
                    <i class="fas fa-cash-register font-25 bg-green-dark rounded-s " v-if="item.category==='sale'"></i>
                    <i class="fa-solid fa-recycle" v-if="item.category==='correct'"></i>
                    <i class="fa-solid fa-seedling font-25 bg-green-dark rounded-s" v-if="item.category==='grow'"></i>
                    <i class="fa-solid fa-dollar font-25 bg-green-dark rounded-s" v-if="item.category==='buy'"></i>
                    <i class="fas fa-boxes-alt font-25 bg-gray-dark rounded-s" v-if="item.category==='ingredients'"></i>



                </div>

            </div>
            <div style="width: 100%; height: 1px; background-image: linear-gradient(to right, #A0D468, #8CC152)"></div>
            <div style="text-align: right;">{{ item.date_accepted }}</div>
<!--            <span class="opacity-40"> {{ item.category}} </span>-->
        </div>

    </div>
</template>

<script>
//    import St from "../../../public/build/assets/Home.783acc47";

    export default {
        name: "listMovements",
        props: {
            storage_id: {
                type: [String, Number],
                 required: false
            },
            goods_id: {
                type: [String, Number],
                required: false
            }
        },
        data() {
            return {
                movements: [],
                my_storage_id : '',

            }
        },
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')
        },
        mounted() {
            if(this.storage_id !== undefined && this.storage_id !== '') {
                // console.log('storage=' + this.storage_id)
                this.getListGoodsMovements()
            }
            else if(this.goods_id !== undefined && this.goods_id !== 'default') {
                // console.log('goods=' + this.goods_id)
                this.getListGoodsMovementsOnStorages()
            }

        },
        methods: {
            getListGoodsMovements(){
                axios.get(`/api/getListGoodsMovements/${this.storage_id}/${this.storage_id}/all/2022-08-01/2022-11-01`).then(res => {
                    this.movements = res.data.data.splice(0,100);
                    console.log('storage: '+this.storage_id)
                    console.log(this.movements)
                }).catch(e => {
                    console.log(e)
                })
            },
            getListGoodsMovementsOnStorages(){
                axios.get(`/api/getListGoodsMovementsOnStorages/${this.goods_id}/2022-08-01/2022-11-01`).then(res => {
                    this.movements = res.data.data.splice(0,100);
                    console.log('goods: '+this.goods_id)
                    console.log(this.movements)
                }).catch(e => {
                    console.log(e)
                });
            }
        },
        watch: {
            storage_id(newVal){
                this.getListGoodsMovements();
                return newVal;
            },
            goods_id(newVal){
                this.getListGoodsMovementsOnStorages();
                return newVal;
            },
        }
    }
</script>

<style scoped>

</style>
