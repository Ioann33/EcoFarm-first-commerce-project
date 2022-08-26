<template>
    <div class="card card-style p-4 pt-0 mt-0" v-for="(item, index) in movements" :key="index">

        <div class="row" >
            <div class="col-9">
                <div class="row mb-0">
                    <div class="col-12 pt-2 pb-2" style="border-bottom: solid 1px #f2f2f7;">
                        <span class="opacity-20">От </span>
                        {{ item.storage_name_from }} <span class="opacity-30"> {{ item.user_name_created }}</span>

                        <!--                                <span class="opacity-20">для </span>-->
                        <!--                                {{ item.storage_name_to }} <span class="opacity-20">{{ item.user_name_accepted }}</span>-->
                    </div>
                    <div class="col-12 pt-2 pb-2" style="border-bottom: solid 1px #f2f2f7;">{{ item.goods_name }} {{ item.amount }} {{ item.goods_unit }}</div>
                    <div class="col-12 pt-2 pb-2">
                        ➠ <span class="opacity-20">для </span>
                        {{ item.storage_name_to }} <span class="opacity-30">{{ item.user_name_accepted }}</span>
                    </div>
                </div>
            </div>
            <div class="col-3 d-flex align-items-center justify-content-center">
                <div class="icon icon-m rounded-md shadow-l me-1 bg-twitter"><i class="fa-solid fa-dolly"></i></div>
            </div>
            <div style="width: 100%; height: 2px; background-image: linear-gradient(to right, #A0D468, #8CC152)"></div>
            <div style="text-align: right;">{{ item.date_accepted }}</div>
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
        mounted() {
        },
        methods: {
            getListGoodsMovements(){
                axios.get(`/api/getListGoodsMovements/${this.storage_id}/2022-08-01/2022-09-01`).then(res => {
                    this.movements = res.data.data;
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
