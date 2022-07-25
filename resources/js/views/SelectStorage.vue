<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">


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

        }
    },
    mounted() {
        //console.log('Component views/Home mounted....')
        this.getMyStorages()

        //console.log('Component views/Home mounted......done!')
    },
    updated() {
        console.log('updated')
        init_template2()
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

                // a.forEach(function(item, i, arr){
                //     console.log( i + ": " + item['storage_id'] + " (массив:" + arr + ")" );
                // })

            })
        },

    }

}
</script>


