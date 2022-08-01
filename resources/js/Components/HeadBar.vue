<template>
    <div>


<!--        <div class="header header-demo header-logo-app mb-3">-->
<!--        <div class="header header-fixed header-logo-center mb-4">-->
<!--            <router-link :to="{name: 'home'}" class="header-title">HomeNet</router-link>-->
<!--            <a href="#" data-back-button class="header-icon header-icon-1"><i class="fas fa-arrow-left"></i></a>-->



<!--            <a href="#" class="header-icon header-icon-2">-->
<!--                <i class="fas fa-envelope"></i>-->
<!--                <span class="badge bg-green-dark">2</span>-->
<!--            </a>-->

<!--            <a href="#" class="header-icon header-icon-3">-->
<!--                <i class="fas fa-envelope"></i>-->
<!--                <span class="badge bg-green-dark">3</span>-->
<!--            </a>-->

<!--            <a href="#" class="header-icon header-icon-4" data-toggle-theme>-->
<!--                <i class="fas fa-lightbulb"></i></a>-->

<!--        </div>-->

        <div style="z-index:97!important" class="header gradient-green header-logo-center mb-3">
            <router-link :to="{name: 'home'}" class="header-title color-white">{{ title }}</router-link>
            <router-link :to="{name: 'home'}" class="header-icon color-white header-icon-1"><i class="fas fa-arrow-left"></i></router-link>

                        <a href="#" class="header-icon header-icon-2">
                            <i class="fas fa-envelope"></i>
                            <span class="badge bg-red-dark">2</span>
                        </a>
            <a href="#" class="header-icon color-white header-icon-3"><i class="fas fa-heart"></i></a>
            <a href="#" class="header-icon color-white header-icon-4"><i class="fas fa-share-alt"></i></a>
        </div>
    </div>
</template>

<script>
export default {
    name: "headBar",
    data(){
        return {
            title: 'storage_name'
        }
    },
    mounted() {
        //console.log('Component headBar mounted')
        this.title = localStorage.getItem('title')
    }
}
</script>

<style scoped>

</style>
