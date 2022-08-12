<script setup>

</script>

<template>
    <div id="page">

        <head-bar></head-bar>

        <div class="page-content header-clear-medium">

            <!-- ERROR --> <error :message="message"></error>

            <div  v-for="(storage_id, index) in listStorage">
<!--                    index:{{ index }} - storage_id:{{storage_id['storage_id']}}-->
                <StorageButton
                    :storage_name="storage_id['name']"
                    :storage_id="storage_id['storage_id']"
                ></StorageButton>
            </div>

        </div>



    </div>
</template>

<script>
import headBar from "../components/headBar";
import error from "../Components/Error";
import StorageButton from "../Components/StorageButton";


export default {
    name: "SelectStorage",
    components:{
        headBar,
        error,
        // NavBar, NavBarMenu,
        StorageButton
    },
    data(){
        return {
            listStorage: null,
            message: null
        }
    },
    beforeMount() {
        localStorage.setItem('title', 'Выбор склада');
    },
    mounted() {

        axios.get('/api/getListMyStorages').then(res => {
            this.listStorage = res.data.data
            if (this.listStorage.length > 1) {
                console.log('need select Storage')
            } else {
                console.log('save to Localstorage')
                localStorage.setItem('my_storage_id', a[0]['storage_id']);

                //this.$router.push({name: 'home'});
            }


        }).catch(err => {
            this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
        })

        update_template()
    },
    updated() {
        update_template()
    },
    methods: {


    }

}
</script>


