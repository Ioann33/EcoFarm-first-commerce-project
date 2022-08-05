<script setup>
import {store} from "../stores/storeAB";
</script>
<template>
A: {{ store.count }} - ({{ store.my_storage_id }})
    <button @click="store.getMyStorageName()">getName</button>
</template>

<script>
export default {
    name: "ComponentA"
}
</script>


