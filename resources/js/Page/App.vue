<template>
    <router-view></router-view>
</template>

<script>
import 'vue-select/dist/vue-select.css';

export default {
    name: "App",
    mounted() {
        console.log('     Component App mounted.')
    }
}
</script>

<style>
    .vs__dropdown-toggle {
        border-radius: 10px;
    }
    .vs__selected-options > input, .vs__selected-options > input:focus {
        padding: 12px 6px;
        border: none;
    }
</style>

