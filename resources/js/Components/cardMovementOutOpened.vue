<template>
    <router-link :to="{name: 'pageMovements', params: { dir: 'out', status: 'opened' }}" v-if="count_movement_out_opened">
    <div class="card card-style mx-0 mb-3">
        <div class="p-3 bg-blue-dark">
            <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">на
                рассмотрении</h4>
            <h1 class="font-700 font-34  opacity-60 mb-0 text-center">
                {{count_movement_out_opened}}</h1>
        </div>
    </div>
    </router-link>
</template>

<script>
export default {
    name: "cardMovementOutOpened",
    data(){
        return {
            count_movement_out_opened: -0,
        }
    },
    props:[
        'storage_id'
    ],
    mounted() {
        // получить ОТКРЫТЫЕ ИСХОДЯЩИЕ заявки на ПЕРЕДАЧУ товара  (нужно только количество, для отображения на главной странице)
        axios.get('/api/getMovement/out/opened/'+ this.storage_id).then(res => {
            this.count_movement_out_opened = res.data.data.length
        }).catch(err => {
            this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
        })

    }
}
</script>
