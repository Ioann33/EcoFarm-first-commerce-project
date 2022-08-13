<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->  <error :message="message"></error>


            <div class="card card-style">
                <div class="content-boxed bg-magenta-dark mb-1 pb-3 text-center">
                    <h4 class="color-white">Списание</h4>
                </div>
                <div class="content mb-0">



                    <div class="input-style input-style-always-active has-borders no-icon">
                        <label for="f7" class="color-blue-dark">категория</label>
                        <select id="f7" class="form-control">
                            <option value="default" disabled selected>выбрать категорию</option>

                            <option
                                v-for="(category, index) in categories"
                                v-bind:value="category.id"
                                v-bind:selected="category.selected"
                            >
                                {{ category.name }}
                            </option>

                        </select>
                        <span><i class="fa fa-chevron-down"></i></span>
                        <i class="fa fa-check disabled valid color-green-dark"></i>
                        <em></em>
                    </div>



                    <div class="input-style input-style-always-active has-borders no-icon">
                        <label for="f6" class="color-blue-dark">на склад</label>
                        <select id="f6" v-model="selected_storage_id" class="form-control">
                            <option value="default" disabled  selected>выбрать склад</option>

                            <option
                                v-for="(storage, index) in listStorage"
                                v-bind:value="storage.id"
                            >
                                {{ storage.name }}
                            </option>

                        </select>
                        <span><i class="fa fa-chevron-down"></i></span>
                        <i class="fa fa-check disabled valid color-green-dark"></i>
                        <em></em>
                    </div>




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

                <a @click="doSpend" v-if="selected_storage_id!='default'" href="#" class="btn btn-m btn-full shadow-bg shadow-bg-m mb-3 m-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark btn-icon text-start">
                    <i class="fa fa-check font-15 text-center"></i>
                    Списать
                    <span class="badge bg-white color-black float-end ms-4 me-n2 mt-1" v-if="size_pay">{{ size_pay}} ₴  </span>
                </a>


            </div>


        </div>

        <nav-bar-menu></nav-bar-menu>
    </div>
</template>

<script>
    import headBar from "../Components/HeadBar";
    import NavBar from "../Components/NavBar";
    import NavBarMenu from "../Components/NavBarMenu";
    import error from "../Components/Error";
    import TitlePage from "../Components/Title";
    export default {
        name: "Spend",
        components:{
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },

        data() {
            return {
                message: '',
                listStorage: [],
                loading_goods: false,
                selected_storage_id: 'default',
                size_pay: '',
                comment: '',
                categories: [
                    {'id': 100, 'name': 'Зарплата', type:'salary', 'selected': 0},
                    {'id': 500, 'name': 'Не профильные затраты', type:'non_profit', 'selected': 0},
                    {'id': 600, 'name': 'Капитальные затраты', type:'capital', 'selected': 0},
                ]
            }
        },
        computed: {
        },
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id');
            this.permitTypeStorage = ['sale', 'buy', 'storage', 'services', 'finance', 'cook', 'grow']
        },
        async mounted() {
            await axios.get('/api/getListStorages').then(res => {
                this.listStorage = res.data.data.filter(el => this.permitTypeStorage.includes(el.type))
                update_template()
            }).catch(err => {
                this.message = 'Error: (' + err.response.status + '): ' + err.response.data.message;
                console.log(this.message)
            })

            // на основании входящего параметра при переходе на этустраницу - будет выбираться сразу: зарплата, кап затраты или не профильные
            this.categories.forEach(el => {
                if(el.type == this.$route.params.type) {
                    el.selected = 1
                }
            })
            update_template()
        },
        methods: {
            doSpend(){
                console.log('списать деньги')
                axios.post('/api/doSpends',{
                    storage_id: this.my_storage_id,
                    size_pay: -this.size_pay,   // списания со знаком минус
                    comment: this.comment,
                    category: 100,  // 100 - salary
                    param_id: this.selected_storage_id
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
