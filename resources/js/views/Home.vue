<script setup>

</script>

<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <component :is="typeTemplate"></component>

        <nav-bar-menu></nav-bar-menu>

    </div>
</template>

<script>
import headBar from "../Components/HeadBar";
import NavBar from "../Components/NavBar";
import NavBarMenu from "../Components/NavBarMenu";
import error from "../Components/Error";

import pageStorage from "./type-templates/pageStorage";
import pageCook from "./type-templates/pageCook";
import pageGrow from "./type-templates/pageGrow";
import pageFinance from "./type-templates/pageFinance";
import pageCreditor from "./type-templates/pageCreditor";
import pageServices from "./type-templates/pageServices";
import pageSale from "./type-templates/pageSale";
import pageBuy from "./type-templates/pageBuy";
import pageAdmin from "./type-templates/pageAdmin";

export default {
    name: "Home",
    components:{
        error,
        headBar, NavBar, NavBarMenu,

        // types
        pageStorage, pageCook, pageGrow, pageFinance, pageCreditor, pageServices,  pageSale, pageBuy, pageAdmin
    },
    data(){
        return {
            message: ''
        }
    },
    mounted() {
        update_template()
    },
    computed: {
        typeTemplate(){
          const type = localStorage.getItem('my_storage_type');
          if(!type){
              return false;
          }
            console.log('page-'+type)
          return `page-${type}`;
        },
    },
    methods: {
    },
    updated() {
        update_template()
    }
}
</script>


