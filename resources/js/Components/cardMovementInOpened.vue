<template>

    <router-link :to="{name: 'pageMovements', params: { dir: 'in', status: 'opened' }}" v-if="count_movement_in_opened">
        <div class="card card-style mx-0 mb-3">
            <div class="p-3 bg-yellow-dark ">
                <i class="fa bg-yellow-light rounded-s">  </i>
                <h4 class="font-700 text-uppercase font-12 opacity-50 mt-n2">Принять </h4>
                <h1 class="font-700 font-34 opacity-60 mb-0 text-center">
                    {{count_movement_in_opened}}</h1>
            </div>
        </div>
    </router-link>

</template>

<script>
export default {
    name: "cardMovementOutOpened",
    data(){
        return {
            count_movement_in_opened: -0,
        }
    },
    props:[
        'storage_id'
    ],
    mounted() {
        // получить ОТКРЫТЫЕ ВХОДЯЩИЕ заявки на ПОЛУЧЕНИЕ товара (нужно только количество, для отображения на главной странице)
        axios.get('/api/getMovement/in/opened/'+ this.storage_id).then(res => {
            this.count_movement_in_opened = res.data.data.length
        }).catch(err => {
            this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
        })


    }
}
</script>
