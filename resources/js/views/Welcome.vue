<template>
    <div id="page">
        <div class="page-content pb-0">

            <div data-card-height="cover" class="card">
                <div class="card-top notch-clear">
                    <a href="#" data-back-button class="me-auto icon icon-m"><i class="font-14 fa fa-arrow-left color-white"></i></a>
                </div>
                <div class="card-center bg-white rounded-m mx-3">
                    <div class="p-4">
                        <h1 class="text-center font-800 font-40 mb-1">Вход</h1>
                        <p class="color-highlight text-center font-12">в личный кабинет</p>

                        <div class="input-style no-borders has-icon validate-field">
                            <i class="fa fa-user"></i>
                            <input v-model="user_login" type="name" class="form-control validate-name" id="form1a" placeholder="Логин">
                            <label for="form1a" class="color-blue-dark font-10 mt-1">Логин</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>(обязательно)</em>
                        </div>

                        <div class="input-style no-borders has-icon validate-field mt-4">
                            <i class="fa fa-lock"></i>
                            <input
                                v-model="user_password"
                                v-on:keydown.enter="login"
                                type="password"
                                class="form-control validate-password"
                                id="form3a"
                                placeholder="Пароль"
                            >
                            <label for="form3a" class="color-blue-dark font-10 mt-1">Пароль</label>
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>(обязательно)</em>
                        </div>

                        <div class="d-flex mt-4 mb-4">
<!--                            <div class="w-50 font-11 pb-2 color text-start"><a href="#">ссылка куда то</a></div>-->
<!--                            <div class="w-50 font-11 pb-2 text-end"><a href="#">Забыл пароль</a></div>-->
                        </div>
                        <a
                            @click.prevent="login"
                            href="#" class="back-button btn btn-full btn-m shadow-large rounded-sm text-uppercase font-900 bg-highlight">Вход</a>
                    </div>
                </div>
                <div v-if="error" class="mt-5 mx-3 alert alert-small rounded-s shadow-xl bg-red-dark login-error" role="alert">
                    <span><i class="fa fa-times"></i></span>
                    <strong>{{ message_error ? message_error : 'Unfortunately error. Please try again!'}}</strong>
                    <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert" aria-label="Close">×</button>
                </div>
                <div class="card-overlay-infinite preload-img" data-src="images/pictures/_bg-infinite.jpg"></div>
            </div>
        </div>

    </div>
</template>

<script>
import moment from 'moment'


export default {
    name: "Welcome",
    data() {
        return {
            user_login: '',
            user_password: '',
            error: false,
            message_error: ''
        }
    },
    methods: {
        login(){
            this.error = false;
            this.message_error = '';
            console.log('Auth...');
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/login', {
                    login: this.user_login.toLowerCase(),
                    password: this.user_password
                })
                    .then(r => {
                        console.log('Auth...ok:');
                        console.log(r);


                        // установлю диапазон даты для формирования фин отчености
                        var df = moment().weekday(1).hour(8).minute(0).second(0).format("YYYY-MM-DD hh:mm:ss")
                        var dt = moment().add(3,'day').hour(0).minute(0).second(0).format("YYYY-MM-DD 00:mm:ss")
                        localStorage.setItem('df', df)
                        localStorage.setItem('dt', dt)




                        localStorage.setItem('x_xsrf_token', r.config.headers['X-XSRF-TOKEN']);

                        axios.get('/api/getMainStorage/').then(res => {
                            localStorage.setItem('main_storage_id', res.data.storage_id)
                        })

                        axios.get('/api/getListMyStorages').then(res => {

                            if(res.data.data.length>1)
                            {
                                console.log('Redirect to /selectSorage')

                                this.$router.push({name: 'selectStorage'});
                                //location.reload();
                            }
                            else
                            {
                                console.log('save to Localstorage. and goto HOME for type('+res.data.data[0].type+')')
                                console.log(res.data.data[0])

                                localStorage.setItem('my_storage_type', res.data.data[0].type)
                                localStorage.setItem('my_storage_id', res.data.data[0].storage_id)
                                localStorage.setItem('my_storage_name', res.data.data[0].name)
                                // localStorage.setItem('df', res.data.data[0].name)
                                // localStorage.setItem('dt', res.data.data[0].name)


                                this.$router.push({name: 'home'});

                                // axios.get('/api/getStorageProp/' + res.data.data.storage_id).then(res => {
                                //     this.storage_prop = res.data.data
                                //     console.log(this.storage_prop)
                                //     localStorage.setItem('storage_name', this.storage_prop[0]['name'])
                                //     // localStorage.setItem('money_in', this.storage_prop[0]['money_in'])
                                //     // localStorage.setItem('money_out', this.storage_prop[0]['money_out'])
                                //     // localStorage.setItem('move_in', this.storage_prop[0]['money_in'])
                                //     // localStorage.setItem('move_out', this.storage_prop[0]['money_out'])
                                //     // localStorage.setItem('order_in', this.storage_prop[0]['money_in'])
                                //     // localStorage.setItem('order_out', this.storage_prop[0]['money_out'])
                                //     localStorage.setItem('my_storage_type', this.storage_prop[0]['type'])
                                //     localStorage.setItem('my_storage_id', res.data.data.storage_id);
                                //
                                //     //location.reload();
                                // })

                            }

                        })

                        // this.$router.push({name: 'home'});
                        // location.reload();
                    })
                    .catch(err => {
                        console.log('Auth...no');
                        this.message_error = "Проверьте Логин и пароль"
                        this.error = true
                        console.error(err.response.data)
                        console.error(' [serv-error] ' + err.response.data.message)
                    })
            })
                .catch(err => {
                    console.log('Auth...no')
                    this.message_error = err.response.data
                    this.error = true
                    console.error(err.response.data)
                });

        }
    },
    mounted() {
        console.log('Component Welcome mounted.')
        update_template()
    },
    updated(){
        update_template()
    }

}
</script>

<style>
    .login-error {
        z-index: 10;
        overflow: hidden;
    }
</style>
