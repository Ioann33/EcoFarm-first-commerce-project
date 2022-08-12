<template>

    <div data-card-height="120" style="height: 150px" class="card card-style rounded-m shadow-xl preload-img"
         data-src="images/teplitsa.webp">
        <div class="card-top mt-2 ms-3" style="text-align: left !important;">
            <h1 class="color-white mb-0 mb-n2 font-22">{{ my_storage_name }}</h1>
            <p class="bottom-0 color-white opacity-50 under-heading font-11 font-700">{{my_storage_type}} {{my_storage_id}}</p>
        </div>
        <div class="card-top text-center mt-5">
            <h1 class="color-white fa-4x">{{ finance.balance }} ₴ </h1>
            <p class="color-white opacity-70 font-11 mb-n5">Баланс наличка</p>
        </div>

<!--        <div class="card-top mt-2 ms-3 text-center">-->
<!--            <h1 class="color-white mb-0 mb-n2 font-22"> cener text </h1>-->
<!--            <p class="bottom-0 color-white opacity-50 under-heading font-11 font-700"> center small text </p>-->
<!--        </div>-->

        <div class="card-top mt-2 ms-3 me-2" style="text-align: right !important;">

            <h1 class="color-white me-2 mb-0 mb-n2 font-22 font-500"> {{finance.sales_today }} </h1>
            <p class="bottom-0 color-white opacity-50 under-heading font-10 font-500">продано сегодня</p>
        </div>

        <div class="card-bottom">
            <h1 class="text-end me-3 font-22 font-500  color-white mb-0"> {{ finance.sales_week }} </h1>

            <p class="text-end me-2 font-10 font-500 opacity-50 color-white mb-2"> продано на неделе </p>
<!--            <h1 class="color-white mb-0 mb-n2 font-22">22</h1>-->
<!--            <p class="bottom-0 color-white opacity-50 under-heading font-11 font-700">продано сегодня</p>-->
        </div>


        <div class="card-bottom">
<!--    <p class="ms-3 font-10 font-500 opacity-50 color-white mb-2">Exp: 10/22</p>-->
</div>
<!--        <div class="card-bottom">-->
<!--            <p class="text-end me-3 font-10 font-500 opacity-50 color-white mb-2"><i class="fab fa-cc-visa font-20 rotate-90"></i></p>-->
<!--        </div>-->

        <div class="card-overlay bg-black opacity-40"></div>
    </div>


</template>

<script>
export default {
    name: "cardBalance",
    data(){
        return{
            my_storage_id: null,
            my_storage_name: '',
            my_storage_type: '',
            finance: []
        }
    },
    props: [
        'storage_id'
    ],
    beforeMount() {
        this.my_storage_id = localStorage.getItem('my_storage_id')
        this.my_storage_name = localStorage.getItem('my_storage_name')
        this.my_storage_type = localStorage.getItem('type')
    },
    mounted() {
        // console.log('[cardBalance]     load cardBalance for storage_id: '+this.storage_id)
        this.getBalance(this.storage_id)
    },
    methods: {
        getBalance(storage_id){
            axios.get('/api/getFinance/'+ storage_id).then(res => {
                this.finance = res.data;
                console.log("[cardBalance]: ")
                console.table(this.finance)
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })

        }
    }
}
</script>

