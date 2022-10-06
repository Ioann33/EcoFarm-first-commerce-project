<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->  <error  :message="message"></error>

{{this.$route.params.type}}
            <!--
                @focus="$event.target.select()"
            -->
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
    name: "pageListMoney",
    components: {
        error,
        TitlePage,
        headBar, NavBar, NavBarMenu,
    },
    data(){
        return {
            message: '',
            toast_message: '',
        }
    },
    computed: {},
    mounted() {

    },
}
</script>

<style scoped>

</style>
