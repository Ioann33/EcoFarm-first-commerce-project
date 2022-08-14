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
                              :value="type" @getSelected="changeType" :defaultOption="'выбрать тип'" :keyOfValue="'id'">
                </select-input>
                <button :disabled="disabled" class="btn shadow-bg shadow-bg-m btn-m btn-full rounded-s text-uppercase font-900 shadow-s bg-green-dark" @click="addStorage">Добавить</button>
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
                ]
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
        },
        updated() {
        },
        methods: {
            changeType(value){
              this.type = value;
            },
            addStorage(){
                const res = axios.post('/api/addStorage', {
                    name: this.name,
                    type: this.login,
                }).then(res => {
                    if(res.data.status === 'ok'){
                        console.log('Склад добавлен')
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
