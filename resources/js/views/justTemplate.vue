<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->  <error  :message="message"></error>


<!--
    @focus="$event.target.select()"
-->
        </div>


        <nav-bar-menu></nav-bar-menu>
    </div>

    <!-- TOAST -->
    <div id="toast-successful" class="snackbar-toast bg-green-dark color-white" data-delay="1500" data-autohide="true"><i class="fa fa-check-circle me-3"></i>{{ this.toast_message}}</div>

</template>

<script>
    import headBar from "../Components/HeadBar";
    import NavBar from "../Components/NavBar";
    import NavBarMenu from "../Components/NavBarMenu";
    import error from "../Components/Error";
    import TitlePage from "../Components/Title";

    export default {
        name: "pagePreSale",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },
        data(){
            return {
                message: '',
                toast_message: '',
            }
        },
        computed: {},
        mounted() {
            this.toast('toast-successful', 'Test')
        },
        updated() {
        },
        methods: {
            toast(id, message){
                this.toast_message = message

                var notificationToast = new bootstrap.Toast(document.getElementById(id))
                notificationToast.show()
            },
            test(){
                axios.get('/api/getStorageGoods/allowed/'+this.my_storage_id+'/all')
                    .then(res => {
                        console.log(res.data.data)
                    })
                    .catch(err => {
                        this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                        console.error (' [serv] '+this.message)
                    })
            }

        }
    }
</script>

<style scoped>

</style>
