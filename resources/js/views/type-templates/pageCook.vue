<template>
    <div class="page-content header-clear-medium">
        <ul>товар отгрузить (на главный склад)</ul>
        <ul>баланс налички: {{balance}}грн</ul>
        <ul>продать товар доступный</ul>
        <ul>купить товар разрешенный</ul>
        <ul>Остатоки товара на складе</ul>
    </div>
</template>

<script>
    import Error from "../../Components/Error";

    export default {
        name: "pageProduce",
        components: {
            Error,
        },
        data() {
            return {
                my_storage_id: 0,
                balance: '0'

            }
        },
        computed: {},
        mounted() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
            this.getBalance()
            update_template()
        },
        updated() {
            update_template()
        },
        methods: {
            getBalance(){
                axios.get('/api/getFinance/'+ this.my_storage_id).then(res => {
                    this.balance=res.data.balance;
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
            })

            }

        }
    }
</script>

