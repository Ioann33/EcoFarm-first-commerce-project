<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->  <error :message="message"></error>
            <title-page title_main="Новый пользователь"></title-page>

            <div class="card card-style p-4 mt-3">
                <div class="input-style has-borders no-icon mb-4">
                    <input v-model="name" type="text" class="form-control" id="form1" placeholder="Имя пользователя">
                    <label for="form1" class="color-blue-dark">Имя пользователя</label>
                    <i class="fa fa-times invalid color-red-dark" :class="(isValidName || name === '') ? 'disabled' : ''"></i>
                    <i class="fa fa-check valid color-green-dark" :class="{disabled: !isValidName}"></i>
                    <em>(required)</em>
                </div>
                <div class="input-style has-borders no-icon mb-4">
                    <input v-model="login" type="text" class="form-control" id="form2" placeholder="Логин">
                    <label for="form2" class="color-blue-dark">Логин</label>
                    <i class="fa fa-times invalid color-red-dark" :class="(isValidLogin || login === '') ? 'disabled' : ''"></i>
                    <i class="fa fa-check valid color-green-dark" :class="{disabled: !isValidLogin}"></i>
                    <em>(required)</em>
                </div>
                <div class="input-style has-borders no-icon mb-4">
                    <input v-model="password" type="password" class="form-control" id="form3" placeholder="Пароль">
                    <label for="form3" class="color-blue-dark">Пароль</label>
                    <i class="fa fa-times invalid color-red-dark" :class="(isValidPassword || password === '') ? 'disabled' : ''"></i>
                    <i class="fa fa-check valid color-green-dark" :class="{disabled: !isValidPassword}"></i>
                    <em>(required)</em>
                </div>
                <button :disabled="!isValidName || !isValidLogin || !isValidPassword" class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark" @click="addUser">Добавить</button>
            </div>

        </div>
        <div v-if="message2" class="alert me-3 ms-3 rounded-s alert-save-goods-custom" :class="status ? 'bg-green-dark' : 'bg-red-dark'" role="alert">
            <span class="alert-icon"><i class="fa font-18" :class="status ? 'fa-check' : 'fa-times-circle'"></i></span>
            <h4 class="text-uppercase color-white">{{ status ? 'ОК' : 'ERROR' }} - {{ message2 }}</h4>
            <!--            <strong class="alert-icon-text">{{ message2 }}</strong>-->
            <!--            <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert" aria-label="Close">×</button>-->
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
        name: "pageAddUser",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },
        data(){
            return {
                message: '',
                message2:'',
                status: false,
                name: '',
                login: '',
                password: '',
                regex_name: /[\wа-я]+/ig,
                regex_login: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{3,}$/,
                regex_password: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{3,}$/,
            }
        },
        computed: {
            isValidName(){
                if(this.regex_name.test(this.name) && this.name.length >= 3) {
                    return true;
                }
                return false;
            },
            isValidLogin(){
                return this.regex_login.test(this.login);
            },
            isValidPassword(){
                return this.regex_password.test(this.password);
            },
        },
        mounted() {
        },
        updated() {
        },
        methods: {
            addUser(){
                const res = axios.post('/api/addUser', {
                    name: this.name,
                    login: this.login,
                    password: this.password,
                }).then(res => {
                    if(res.data.status === 'ok'){
                        console.log('Пользователь добавлен')
                        console.log('[serv-'+res.data.status+'] '+res.data.message)

                        this.status = true;
                        this.message2 = 'Пользователь добавлен';

                        setTimeout(() => {
                            this.message2 = '';
                            this.$router.push({name: 'home'});
                        }, 1000)

                    }
                }).catch(err => {
                    console.error(err.response.data.message)
                    this.status = false;
                    this.message = err.response.data.message;
                })
            }
        }
    }
</script>

<style scoped>

</style>
