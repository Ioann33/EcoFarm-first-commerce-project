<template>
    <div class="page-content header-clear-medium">
        <!-- ERROR -->
        <error :message="message"></error>


        <card-balance
            :balance="balance"
            :my_storage_name="my_storage_name"
        >
        </card-balance>

    </div>
</template>

<script>
    import Error from "../../Components/Error";
    import CardBalance from "../../Components/cardBalance";

    export default {
        name: "pageProduce",
        components: {
            Error,
            CardBalance
        },
        data() {
            return {
                balance: 0,
                my_storage_name: '',
                message: ''
            }
        },
        computed: {},
        mounted() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.my_storage_name = localStorage.getItem('my_storage_name')


        },
        methods: {

        }

    }
</script>

<style scoped>

</style>
