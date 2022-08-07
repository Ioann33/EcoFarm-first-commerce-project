<template>
    <a href="#" @click.prevent="changeStorage(storage_id, storage_name)">
    <div class="card card-style bg-20" data-card-height="175">
        <div class="card-center">
            <p class="text-center mb-0">
                <i class="fa fa-eye color-blue-dark fa-3x mt-3"></i>
            </p>
            <h1 class="color-white text-center mb-n1">{{ storage_name }}  <sup class="opacity-20 font-400">{{ storage_id}}</sup></h1>
<!--            <p class="color-white text-center opacity-60 ">A beautiful sunset at the docks!</p>-->
        </div>
        <div class="card-overlay bg-black opacity-80"></div>
    </div>
    </a>
</template>

<script>
//import {store} from "../stores/store";

export default {
    name: "StorageButton",
    props: [
        'storage_name',
        'storage_id'
    ],
    data(){
        return {
            storage_prop: null
        }
    },
    methods: {
        changeStorage(storage_id, storage_name){
            console.log('Set MyStorage to: '+storage_id)
            localStorage.setItem('my_storage_id', storage_id);
            localStorage.setItem('my_storage_name', storage_name);
            localStorage.setItem('title', storage_name);
            //store.setTitle(storage_name)


            axios.get('/api/getStorageProp/'+storage_id).then(res => {
                this.storage_prop = res.data.data
                console.log(this.storage_prop)
                localStorage.setItem('storage_name', this.storage_prop[0]['name'])
                localStorage.setItem('money_in', this.storage_prop[0]['money_in'])
                localStorage.setItem('money_out', this.storage_prop[0]['money_out'])
                localStorage.setItem('move_in', this.storage_prop[0]['money_in'])
                localStorage.setItem('move_out', this.storage_prop[0]['money_out'])
                localStorage.setItem('order_in', this.storage_prop[0]['money_in'])
                localStorage.setItem('order_out', this.storage_prop[0]['money_out'])
                localStorage.setItem('type', this.storage_prop[0]['type'])
            })

             this.$router.push({name: 'home'});
            //this.$router.push({name: this.storage_prop[0]['type']});
        }
    }
}
</script>
