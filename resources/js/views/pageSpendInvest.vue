<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->  <error :message="message"></error>


            <div v-if="error=='0'" class="card card-style">


                <div  v-if="this.$route.params.type == 'out'" class="content-boxed bg-red-dark mb-1 pb-3 text-center">
                    <h1 class="color-white">Вывод средств/инвестиций</h1>
                </div>
                <div v-else class="content-boxed bg-green-dark mb-1 pb-3 text-center">
                    <h1 class="color-white">Инвестировать в компанию</h1>
                </div>


                <div class="content mb-0">


                    <div class="input-style has-borders has-icon validate-field mb-4">
                        <i class="text-black-50 font-29 text-left " style="top: 20px; padding-left: 7px  !important;">₴</i>
                        <input type="number" class="form-control validate-number" id="form111" placeholder="Сумма 0.00 грн" v-model="size_pay">
                        <label for="form111" class="color-blue-dark">Сумма</label>
                        <i class="fa fa-times disabled invalid color-red-dark"></i>
                        <i class="fa fa-check disabled valid color-green-dark"></i>
                        <em>(обязательно)</em>
                    </div>



                    <div class="input-style has-borders no-icon validate-field mb-4">
                        <input type="text" class="form-control validate-text" id="form2" placeholder="коментарий" v-model="comment">
                        <label for="form2" class="color-blue-dark">коментарий</label>
                        <i class="fa fa-times disabled invalid color-red-dark"></i>
                        <i class="fa fa-check disabled valid color-green-dark"></i>
                        <em>(не обязательно)</em>
                    </div>

                </div>

                <div v-if="size_pay>0">
                <a v-if="this.$route.params.type == 'out'" @click="doSpend"  href="#" class="btn btn-m btn-full shadow-bg shadow-bg-m mb-3 m-3 rounded-s text-uppercase font-900 shadow-s bg-red-dark btn-icon text-start">
                    <i class="fa fa-check font-15 text-center"></i>
                    Вывести средства
                    <span class="badge bg-white color-black float-end ms-4 me-n2 mt-1" v-if="size_pay">{{ size_pay}} ₴  </span>
                </a>

                <a v-else @click="doSpend"  href="#" class="btn btn-m btn-full shadow-bg shadow-bg-m mb-3 m-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark btn-icon text-start">
                    <i class="fa fa-check font-15 text-center"></i>
                    Инвестировать
                    <span class="badge bg-white color-black float-end ms-4 me-n2 mt-1" v-if="size_pay">{{ size_pay}} ₴  </span>
                </a>
                </div>

            </div>


        </div>

        <nav-bar-menu></nav-bar-menu>
    </div>
</template>

<script>
    import headBar from "../components/headBar";
    import NavBar from "../Components/NavBar";
    import NavBarMenu from "../Components/NavBarMenu";
    import error from "../Components/Error";
    import TitlePage from "../Components/Title";
    export default {
        name: "SpendInvest",
        components:{
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },

        data() {
            return {
                message: '',

                loading_goods: false,
                selected_storage_id: 1,
                size_pay: '',
                comment: '',
                error: 0
            }
        },
        computed: {
        },
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id');

        },
        mounted() {
            if(this.$route.params.type !== 'out' && this.$route.params.type !== 'in') {
                this.message = 'не верный параметр в маршруте: ' + this.$route.params.type
                this.error = 1
            }
            update_template()
        },
        methods: {
            doSpend(){
                console.log('investing' + this.$route.params.type)
                if(this.$route.params.type == 'out') {
                    this.category = 900
                    this.size_pay = -1*Math.abs(this.size_pay)
                }
                else if(this.$route.params.type == 'in')
                {
                    this.category = 901
                    this.size_pay = Math.abs(this.size_pay)
                }


                axios.post('/api/doSpends',{
                    storage_id: this.my_storage_id,
                    size_pay: this.size_pay,
                    comment: this.comment,
                    category: this.category,
                    param_id: this.my_storage_id
                }).then(res => {
                    if(res.data.status === 'ok') {
                        console.log('[server]: '+ res.data.message)
                        this.$router.push({name: 'home'});
                    }else {
                        console.error(res.data.message)
                        this.message = res.data.message
                    }
                }).catch(err => {
                    console.error(this.message)
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                })
            }
        },
        updated() {
            update_template()
        },

    }
</script>
