<template>
    <div class="page-content header-clear-medium">
        <!-- ERROR -->  <error :message="message"></error>
       dffdg

fddfddfdfg
    </div>
</template>

<script>
    import Error from "../../Components/Error";

    export default {
        name: "pageStorage",
        components: {
            Error,
        },
        data() {
            return {
                message: '',

            }
        },
        computed: {

        },
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')
        },
        mounted() {
            console.log('     Component views/Admin mounted....')

            console.log('     Component views/Admin mounted......done!')
            update_template()
        },
        updated() {
            update_template()
        },
        methods: {

        }
    }
</script>

<style scoped>

</style>
