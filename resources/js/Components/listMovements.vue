<template>
    <div class="card card-style p-4 pt-0 mt-0" v-for="(item, index) in movements" :key="index">

        <div class="row m-0" >
            <div class="col-9">
                <div class="row mb-0">
                    <div class="col-12 pt-2 pb-2" v-if="item.storage_name_from" style="border-bottom: solid 1px #f2f2f7;">
                        <span class="opacity-20">От </span>
                        {{ item.storage_name_from }} <span class="opacity-30"> {{ item.user_name_created }}</span>

                        <!--                                <span class="opacity-20">для </span>-->
                        <!--                                {{ item.storage_name_to }} <span class="opacity-20">{{ item.user_name_accepted }}</span>-->
                    </div>
                    <div class="col-12 pt-2 pb-2" style="border-bottom: solid 1px #f2f2f7;"><b>{{ item.goods_name }}</b> {{ item.amount }} <sup class="opacity-50">{{ item.goods_unit }}</sup></div>
                    <div class="col-12 pt-2 pb-2" v-if="item.storage_name_to">
                        ➠ <span class="opacity-20">для </span>
                        {{ item.storage_name_to }} <span class="opacity-30">{{ item.user_name_accepted }}</span>
                    </div>
                </div>
            </div>
            <div class="col-3 d-flex align-items-center justify-content-center">
                <div class="icon icon-m rounded-s shadow-l me-1 bg-twitter">
<!--                    <i class="fa-solid fa-dolly font-20" v-if="item.category==='move'"></i>-->
                    <i class="fa-solid fa-right-from-bracket font-20" v-if="item.category==='move' && item.storage_from === this.my_storage_id"></i>
                    <i class="fa-solid fa-right-to-bracket font-20" v-if="item.category==='move' && item.storage_to === this.my_storage_id"></i>
                    <i class="fas fa-box-archive font-20 bg-green-dark rounded-s " v-if="item.category==='ready'"></i>
                    <i class="fa-solid fa-recycle" v-if="item.category==='correct'"></i>
                    <i class="fas fa-boxes-alt font-20 bg-gray-dark rounded-s" v-if="item.category==='ingredients'"></i>



                </div>

            </div>
            <div style="width: 100%; height: 1px; background-image: linear-gradient(to right, #A0D468, #8CC152)"></div>
            <div style="text-align: right;">{{ item.date_accepted }}</div>
            <span class="opacity-40"> {{ item.category}} </span>
        </div>

    </div>
</template>

<script>
    export default {
        name: "listMovements",
        props: {
            storage_id: {
                type: [String, Number],
                required: true
            }
        },
        data() {
            return {
                movements: []
            }
        },
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')
            //this.storage_name = localStorage.getItem('my_storage_name');
        },
        mounted() {
        },
        methods: {
            getListGoodsMovements(){
                axios.get(`/api/getListGoodsMovements/${this.storage_id}/2022-08-01/2022-09-01`).then(res => {
                    this.movements = res.data.data.splice(0,30);
                    console.log(res.data)
                }).catch(e => {
                    console.log(e)
                });
            }
        },
        watch: {
            storage_id(newVal){
                this.getListGoodsMovements();
                return newVal;
            }
        }
    }
</script>

<style scoped>

</style>
