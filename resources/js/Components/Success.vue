<template>
    <div class="ms-3 me-3 alert alert-small rounded-s shadow-xl bg-green-dark" role="alert" style="position: fixed; top: 72px; left: 0; right: 0;">
        <span><i class="fa fa-check"></i></span>
        <strong>{{success}}</strong>
        <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert" aria-label="Close">×</button>
    </div>
</template>

<script>
    export default {
        name: "Success",
        props: {
            success: {
                type: String
            }
        }
    }
</script>

<style scoped>

</style>
