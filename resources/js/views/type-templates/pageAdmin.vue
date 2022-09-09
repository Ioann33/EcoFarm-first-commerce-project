<template>
    <div class="page-content header-clear-medium m-2">
        <!-- ERROR -->  <error :message="message"></error>

        <div class="card card-style m-1"
             v-for="(log, index) in logs"
        >
            <div class="card-body" >
                <div class="d-flex">
                    <div class="align-self-start">
                        <h4 class="mb-0 font-15">{{log.log}}</h4>
                        <span class="badge bg-blue-dark  font-11 me-1">{{log.event}} </span>
                    </div>
                    <div class="align-self-start ms-auto ps-3">
						<span class="icon icon-xxs rounded-xl bg-white color-brown-dark">
							<i class="fa fa-graduation-cap color-green-dark font-11"></i>
						</span>
                    </div>
                </div>
                <div class="divider mt-2 mb-2"></div>
                <div class="d-flex">
                    <div class="align-self-center">
                        <span class="font-12 color-theme opacity-70 font-500"><i class="far fa-clock font-11 pe-1"></i> {{log.date}}</span>
                    </div>
                    <div class="align-self-center ms-auto">
                        <span class="font-12 color-theme opacity-30 font-500"> # {{log.id}}</span>
                    </div>
                </div>
            </div>
            <div class="card-overlay bg-teal-dark opacity-40"></div>
        </div>

<!--        <li-->
<!--            v-for="(log, index) in logs"-->
<!--        >-->
<!--            <span class="badge bg-gray-light color-black opacity-20 font-10 me-1 p-1"># {{log.id}}</span>-->
<!--            <span class="badge bg-gray-light color-gray-dark font-10 me-1">{{log.date}} </span>-->
<!--            <span class="badge bg-blue-dark  font-11 me-1">{{log.event}} </span>-->

<!--             <br> {{log.log}}-->
<!--            <hr>-->
<!--        </li>-->
    </div>

</template>

<!---->
<!--<span class="badge bg-gray-light color-gray-dark font-10 me-1"># {{log.date_created}} </span>-->
<!--<span class="badge bg-gray-light color-gray-dark font-10 me-1"># {{log.date_accepted}} </span>-->

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
            this.my_storage_id   = localStorage.getItem('my_storage_id')
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

            // axios.get('/api/getAdvancedLogs?limit=100').then(res => {
            //     console.log('logs:')
            //
            //     this.logs = res.data.data
            //     console.log(this.logs)
            //
            // }).catch(err => {
            //     this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            //     console.error (' [serv] '+this.message)
            // })

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
