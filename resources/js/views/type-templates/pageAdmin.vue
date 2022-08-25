<template>
    <div class="page-content header-clear-medium m-3">
        <!-- ERROR -->  <error :message="message"></error>
        <li
            v-for="(log, index) in logs"
        >
            #{{log.id}} {{log.date}} <b>{{log.event}}</b> <br> {{log.log}}
            <hr>
        </li>
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
                logs: []

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


            axios.get('/api/getLogs/all').then(res => {
                console.log('logs:')

                this.logs = res.data
                console.log(this.logs)

            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                console.error (' [serv] '+this.message)
            })

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
