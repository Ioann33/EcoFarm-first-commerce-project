<template>
    <h1>
        Laravel 9 vite with vue 3
    </h1>
    <router-link :to="{ name: 'home' }">details</router-link>
    <router-view></router-view>
</template>

<script>
export default {
    name: "app"
}
</script>
