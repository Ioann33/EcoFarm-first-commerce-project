<script setup>
import {useStorage} from "../stores/storages";


const Storage = useStorage()

// {{ Storage.store_message }}

// import { storeToRefs } from 'pinia'
// const { store_message } = storeToRefs(useStorage())
// {{ store_message }}

</script>

<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

{{ test }}

            <div  v-for="(storage_id, index) in listStorage">
<!--                    index:{{ index }} - storage_id:{{storage_id['storage_id']}}-->
                <StorageButton
                    :storage_name="storage_id['name']"
                    :storage_id="storage_id['storage_id']"
                >
                </StorageButton>
            </div>

        </div>

        <nav-bar-menu></nav-bar-menu>

    </div>
</template>

<script>
import headBar from "../components/headBar";
import NavBar from "../Components/NavBar";
import NavBarMenu from "../Components/NavBarMenu";
import StorageButton from "../Components/StorageButton";

export default {
    name: "SelectStorage",
    components:{
        headBar, NavBar, NavBarMenu,
        StorageButton
    },
    data(){
        return {
            listStorage: null,
            test: null
        }
    },
    mounted() {
        //console.log('Component views/Home mounted....')
        localStorage.setItem('title','Выбор склада');
        this.getMyStorages()

        //console.log('Component views/Home mounted......done!')
    },
    updated() {
        update_template()
    },
    methods: {
        getMyStorages() {
            axios.get('/api/getMyStorage').then(res => {
                this.listStorage = res.data.data
                // console.log(this.listStorage)
                // console.log(this.listStorage.length)
                if (this.listStorage.length > 1) {
                    console.log('need select Storage')
                } else {
                    console.log('save to Localstorage')
                    localStorage.setItem('my_storage_id', a[0]['storage_id']);

                    this.$router.push({name: 'home'});
                }


            }).catch(err => {
                console.log(err.response)
                console.log(err.response.data.message)
                console.log(err.response.status)
                this.test = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })
        },

    }

}
</script>


