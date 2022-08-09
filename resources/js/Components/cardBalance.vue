<template>

    <div data-card-height="100" style="height: 150px" class="card card-style rounded-m shadow-xl preload-img"
         data-src="images/teplitsa.webp">
        <div class="card-top mt-2 ms-3">
            <h1 class="color-white mb-0 mb-n2 font-22">{{ my_storage_name }} </h1>
            <p class="bottom-0 color-white opacity-50 under-heading font-11 font-700">{{my_storage_name}}</p>
        </div>
        <div class="card-top text-center mt-4">
            <h1 class="color-white fa-4x">{{ balance }} ₴ </h1>
            <p class="color-white opacity-70 font-11 mb-n5">Баланс наличка</p>
        </div>

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
            balance: -0
        }
    },
    props: [
        'storage_id'
    ],
    mounted() {
        console.log('[cardBalance]     load cardBalance for storage_id: '+this.storage_id)
        this.getBalance(this.storage_id)
    },
    methods: {
        getBalance(storage_id){
            axios.get('/api/getFinance/'+ storage_id).then(res => {
                this.balance=res.data.balance;
                console.log("[cardBalance]     balace ("+storage_id+") = " + this.balance)
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })

        }
    }
}
</script>

