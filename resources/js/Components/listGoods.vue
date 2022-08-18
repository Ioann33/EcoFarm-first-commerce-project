<template>
    <div class="content mb-0">
        <div class="d-flex mb-0" v-for="(goods, index) in listGoods" :key="goods.id">
            <div class="align-self-center">{{ goods.name }}</div>
            <div class="ms-auto align-self-center"><h4 class="pt-3 font-600">{{ goods.amount }} <sup class="font-400">{{ goods.unit }}</sup></h4>   </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "listGoods",
    data(){
        return{
            listGoods: [],
        }
    },
    props: [
        'storage_id'
    ],
    async mounted() {
        // Получить список продукции на складе
         await axios.get('/api/getStorageGoods/available/' + this.storage_id+'/all').then(res => {
            this.listGoods = res.data.data.filter(el => el.amount >0)
        }).catch(err => {
            console.error(this.message)
        })

    },
    updated() {
        update_template()
    }
}
</script>

