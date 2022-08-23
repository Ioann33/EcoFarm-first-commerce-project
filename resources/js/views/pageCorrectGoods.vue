<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">


            <div class="input-style input-style-always-active has-borders no-icon">
                <!--                            <label for="storage-list-from" class="color-blue-dark">Товар</label>-->
                <select id="storage-list-from"  v-model="selected_storage"  class="form-control">
                    <option value="default" selected>выбрать склад</option>
                    <option
                        v-for="(storage, index) in list_storage"
                        v-bind:value="storage.id"
                    >
                        {{ storage.name }}
                    </option>

                </select>
                <div v-if="loading_balance_from" class="spinner-border text-light select-input-spinner" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <span><i class="fa fa-chevron-down"></i></span>
                <i class="fa fa-check disabled valid color-green-dark"></i>
                <em></em>
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
        name: "pagePreSale",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },
        data(){
            return {
                list_storage: [],
                selected_storage: ''
            }
        },
        computed: {},
        mounted() {

            const res = axios.get(`/api/getListStorages/`)
            if(!res.data)   {   console.error('Unfortunately some error')   }

            this.list_storage = res.data.data;

            console.log('list_storage:')
            console.log(this.list_storage)
        },
        updated() {
        },
        methods: {

        }
    }
</script>

<style scoped>

</style>
