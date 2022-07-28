<template>
    <div class="ms-3 me-3 mb-5 alert alert-small rounded-s shadow-xl bg-red-dark" role="alert" v-if="message">
        <span><i class="fa fa-times"></i></span>
        <strong> {{ message }} </strong>
        <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert" aria-label="Close">&times;</button>
    </div>
</template>

<script>
export default {
    name: "Error",
    props:[
        'message'
    ]
}
</script>

<style scoped>

</style>
