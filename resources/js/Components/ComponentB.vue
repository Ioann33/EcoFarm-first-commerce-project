<script setup>
import {store } from "../stores/storeAB";
</script>

<template>
B: {{ store.count }}
    <button @click="store.increment()">++</button>
    <br>
</template>

<script>
export default {
    name: "ComponentB"
}
</script>


