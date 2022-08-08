<script setup>
// import {useStorage} from "../stores/storages";
//
//
// const Storage = useStorage()
// Storage.getMyStorages()
// {{ Storage.store_message }}

// import { storeToRefs } from 'pinia'
// const { store_message } = storeToRefs(useStorage())
// {{ store_message }}
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
import headBar from "../components/headBar";
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

export default {
    name: "Home",
    components:{
        error,
        headBar, NavBar, NavBarMenu,

        // types
        pageStorage, pageCook, pageGrow, pageFinance, pageCreditor, pageServices,  pageSale, pageBuy
    },
    data(){
        return {

        }
    },
    mounted() {},
    computed: {
        typeTemplate(){
          const type = localStorage.getItem('type');
          if(!type){
              return false;
          }
            console.log('page-'+type)
          return `page-${type}`;
        },
    },
    methods: {

    }
}
</script>


