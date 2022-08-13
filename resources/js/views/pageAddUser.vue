<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

            <div class="card card-style p-4 pt-3 mt-3">
                <div class="input-style has-borders no-icon validate-field mb-4">
                    <input v-model="name" type="text" class="form-control validate-text" id="form1" placeholder="Имя пользователя">
                    <label for="form1" class="color-highlight">Имя пользователя</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>
                <div class="input-style has-borders no-icon validate-field mb-4">
                    <input v-model="login" type="text" class="form-control validate-text" id="form2" placeholder="Логин">
                    <label for="form2" class="color-highlight">Логин</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>
                <div class="input-style has-borders no-icon validate-field mb-4">
                    <input v-model="password" type="password" class="form-control validate-text" id="form3" placeholder="Пароль">
                    <label for="form3" class="color-highlight">Пароль</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>
                <button class="btn shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-green-dark" @click="addUser">Добавить</button>
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
                password: ''
            }
        },
        computed: {},
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
