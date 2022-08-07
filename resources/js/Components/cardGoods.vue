<template>

    <div class="card card-style">
        <div class="content mb-0">
            <div>
                <div class="d-flex mb-2 pb-2">
                    <div>
                        <img src="images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130">
                    </div>
                    <div class="ms-3">
                        <h3 class="font-600">{{ goods.name }}
                            <!--                            <span class="font-300" style="margin-left: 15px">{{ goods.amount }} {{ goods.unit }}</span>-->
                        </h3>
                        <!--                        <h1 class="pt-3">$29.<sup>99</sup></h1>-->
                        <h4 class="pt-3 font-600">{{ goods.amount }} <sup>{{ goods.unit }}</sup></h4>
                        <p></p>

                    </div>
                </div>


            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "cardGoods",
    props: [
        'goods',

    ],
  methods: {
      acceptMovement(movement_id){
        console.log(movement_id)
        axios.post('/api/goodsMovementPull', {
            movement_id: movement_id
        }).then(res => {
            console.log(res.data)
        }).catch(err => {
            console.log(err.response)
            console.log(err.response.data.message)
            console.log(err.response.status)
            this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
        })
    }
}
}
</script>

<style scoped>

</style>
