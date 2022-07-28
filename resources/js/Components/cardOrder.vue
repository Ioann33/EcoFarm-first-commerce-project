<template>

    <a href="#" class="card card-style">
        <div class="content mb-0">
            <div class="row mb-3">
                <div class="col-3"><img src="/images/food/full/1s.jpg" class="rounded-m shadow-xl" width="130"></div>
                <div class="col-1"></div>
                <div class="col-8">


                    <div class="row mb-4">
                        <div class="col-10">
                            <h3 class="font-600">{{ order.name }}, {{ order.amount }} {{ order.unit }}</h3>
<!--                            <h1 class="pt-1 pb-0" v-if="order.status == 'canceled'" >$29.<sup>99</sup></h1>-->
<!--                            <h1 class="pt-1 pb-0 color-white" v-else>.</h1>-->
                        </div>
                        <div class="col-2 opacity-40">#{{ order.order_id }}</div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="row mb-n2 color-theme">
                                <div class="col-6 font-10 text-start">
                                    <i class="fa fa-pencil-alt pe-2"></i>Создал
                                </div>
                                <div class="col-6 font-10 text-end" v-if="type=='canceled'">
                                 Отменил <i class="fa fa-cancel pe-2 color-red-dark"></i>
                                </div>
                                <div class="col-6 font-10 text-end" v-if="type=='progress'">
                                    Взял в работу <i class="fa fa-handshake pe-2 color-yellow-dark"></i>
                                </div>

                            </div>
                            <div class="row mb-n2 color-theme">
                                <div class="col-6 font-10 text-start">
                                    <i class="fa fa-user pe-2"></i>{{ order.user_name_created }}
                                </div>
                                <div class="col-6 font-10 text-end" v-if="type=='canceled'">
                                    {{ order.user_name_handler}}  <i class="fa fa-user pe-2"></i>
                                </div>
                                <div class="col-6 font-10 text-end" v-if="type=='progress'">
                                    {{ order.user_name_handler}}  <i class="fa fa-user pe-2"></i>
                                </div>
                            </div>
                            <div class="row mb-n2 color-theme">
                                <div class="col-6 font-10 text-start">
                                    <i class="fa fa-clock pe-2"></i>{{ order.date_created}}
                                </div>
                                <div class="col-6 font-10 text-end" v-if="type=='canceled'">
                                    {{ order.date_status}}  <span class="copyright-year"></span> <i class="fa fa-calendar pe-2"></i>
                                </div>
                                <div class="col-6 font-10 text-end" v-if="type=='progress'">
                                    {{ order.date_status}}  <i class="fa fa-calendar pe-2"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</template>

<script>
export default {
    name: "cardOrder",
    props: [
        'order',
        'type'
    ],

}
</script>

<style scoped>

</style>
