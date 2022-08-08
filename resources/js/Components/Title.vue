<template>

    <div class="content-boxed bg-blue-dark mb-1 pb-3 text-center">
        <h4 class="color-white">{{ title_main }}</h4>
    </div>

</template>

<script>
export default {
    name: "Title",
    props: [
        'title_main'
    ]
}
</script>

<style scoped>

</style>
