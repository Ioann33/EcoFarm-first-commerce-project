<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

            <title-page title_main="Новый пользователь"></title-page>

            <div class="card card-style p-4 mt-3">
                <div class="input-style has-borders no-icon mb-4">
                    <input v-model="name" type="text" class="form-control" id="form1" placeholder="Имя пользователя">
                    <label for="form1" class="color-highlight">Имя пользователя</label>
                    <i class="fa fa-times invalid color-red-dark" :class="(isValidName || name === '') ? 'disabled' : ''"></i>
                    <i class="fa fa-check valid color-green-dark" :class="{disabled: !isValidName}"></i>
                    <em>(required)</em>
                </div>
                <div class="input-style has-borders no-icon mb-4">
                    <input v-model="login" type="text" class="form-control" id="form2" placeholder="Логин">
                    <label for="form2" class="color-highlight">Логин</label>
                    <i class="fa fa-times invalid color-red-dark" :class="(isValidLogin || login === '') ? 'disabled' : ''"></i>
                    <i class="fa fa-check valid color-green-dark" :class="{disabled: !isValidLogin}"></i>
                    <em>(required)</em>
                </div>
                <div class="input-style has-borders no-icon mb-4">
                    <input v-model="password" type="password" class="form-control" id="form3" placeholder="Пароль">
                    <label for="form3" class="color-highlight">Пароль</label>
                    <i class="fa fa-times invalid color-red-dark" :class="(isValidPassword || password === '') ? 'disabled' : ''"></i>
                    <i class="fa fa-check valid color-green-dark" :class="{disabled: !isValidPassword}"></i>
                    <em>(required)</em>
                </div>
                <button :disabled="!isValidName || !isValidLogin || !isValidPassword" class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark" @click="addUser">Добавить</button>
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
        name: "pageAddUser",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },
        data(){
            return {
                name: '',
                login: '',
                password: '',
                regex_name: /[\wа-я]+/ig,
                regex_login: /^[\SA-Za-z0-9]{5,15}$/,
                regex_password: /[A-Za-z]{2}[A-Za-z]*[ ]?[A-Za-z]*/,
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
                if(this.regex_login.test(this.login) && this.login.length >=3){
                    return true;
                }
                return false;
            },
            isValidPassword(){
                if(this.regex_password.test(this.password) && this.password.length >=3){
                    return true;
                }
                return false;
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
                    }
                }).catch(e => {
                    console.log(e)
                })
            }
        }
    }
</script>

<style scoped>

</style>
