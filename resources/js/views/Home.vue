<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

<div class="page-content header-clear-medium">




    <div v-if="money_in">money in</div>
    <div v-if="money_out">money out</div>

    <div v-if="order_in">order in</div>
    <div v-if="order_out">order out</div>

    <div v-if="move_in">move in</div>
    <div v-if="move_out">move out</div>






</div>
        <nav-bar-menu></nav-bar-menu>

    </div>
</template>

<script>
import headBar from "../components/headBar";
import NavBar from "../Components/NavBar";
import NavBarMenu from "../Components/NavBarMenu";

export default {
    name: "Home",
    components:{
        headBar, NavBar, NavBarMenu,
    },
    data(){
        return {
            ddd: 'Test name button',
            order_in: false,
            order_out: false,
            move_in: false,
            move_out: false,
            money_in: false,
            money_out: false,
            storage_id: null

        }
    },
    mounted() {
        console.log('Component views/Home mounted....')

        this.storage_id = localStorage.getItem('my_storage_id');
        console.log('show my_storage_id: ' + this.storage_id)

        if(this.storage_id == null) {
            this.$router.push({name: 'selectStorage'});
        }

        this.order_in = localStorage.getItem('order_in');
        this.order_out = localStorage.getItem('order_out');
        this.money_in = localStorage.getItem('money_in');
        this.money_out = localStorage.getItem('money_out');
        this.move_in = localStorage.getItem('move_in');
        this.move_out = localStorage.getItem('move_out');


        // document.onreadystatechange = () => {
        //     if (document.readyState == "complete") {
        //         console.log('onreadystatechange=complete in view/Home')
        //     }
        // }

        // this.$nextTick(function () {
        //     console.log('$nextTick')
        // })

        console.log('Component views/Home mounted......done!')
    },
    updated() {
        console.log('updated')
        init_template2()
    },
    methods: {

    }

}
</script>


