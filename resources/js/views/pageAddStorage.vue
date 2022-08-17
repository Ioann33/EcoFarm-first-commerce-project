<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

            <title-page title_main="Добавить новый склад"></title-page>

            <div class="card card-style p-4 mt-3">
                <div class="input-style has-borders no-icon validate-field mb-4">
                    <input v-model="name" type="text" class="form-control validate-text" id="form1" placeholder="Название склада">
                    <label for="form1" class="color-highlight">Название склада</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>
                <select-input :data="types"
                              :label="'Тип склада'"
                              :value="type"
                              @getSelected="changeType"
                              :defaultOption="'выбрать тип'"
                              :keyOfValue="'id'">
                </select-input>
                <button :disabled="disabled" class="btn shadow-bg shadow-bg-m btn-m btn-full rounded-s text-uppercase font-900 shadow-s bg-green-dark" @click="addStorage">Добавить</button>
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
    import SelectInput from "../Components/SelectInput";

    export default {
        name: "pageAddUser",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu, SelectInput
        },
        data(){
            return {
                name: '',
                type: 'default',
                types: [
                    {id: 1, name: 'buy'},
                    {id: 2, name: 'cook'},
                    {id: 3, name: 'creditor'},
                    {id: 4, name: 'finance'},
                    {id: 5, name: 'grow'},
                    {id: 6, name: 'sale'},
                    {id: 7, name: 'services'},
                    {id: 8, name: 'storage'},
                ],
                status: '',
                message2: ''
            }
        },
        computed: {
            disabled(){
                if(this.type !== 'default' && this.name.length >= 2){
                    return false;
                }
                return true;
            }
        },
        mounted() {
            console.log(this.types.filter(el => el.id==3)[0]['name'])

        },
        updated() {
        },
        methods: {
            changeType(value){
                this.type = value
            },
            addStorage(){
                const res = axios.post('/api/addStorage', {
                    name: this.name,
                    type: this.types.filter(el => el.id == this.type)[0]['name'],
                }).then(res => {
                    if(res.data.status === 'ok'){
                        console.log('Склад добавлен')
                        console.log('  [serv-ok] ' + res.data.message)


                        this.status = true;
                        this.message2 = 'Пользователь добавлен';
                        setTimeout(() => {
                            this.message2 = '';
                            this.$router.push({name: 'home'});
                        }, 1000)
                    }
                }).catch(e => {
                    console.error(e)
                })
            }
        }
    }
</script>

<style scoped>

</style>
