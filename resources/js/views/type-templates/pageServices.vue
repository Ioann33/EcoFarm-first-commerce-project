<template>
    <div class="page-content header-clear-medium">
        <!-- ERROR -->  <error  :message="message"></error>

продать услугу (выбрать) <br>
        <select>
            <option>баня</option>
            <option>беседка</option>
            <option>рыбалка</option>
        </select><br>
        цена: <input> <br>
        <button class="btn bg-green-dark">продать</button>

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

            }
        },
        computed: {},
        mounted() {
        },
        methods: {

        }

    }
</script>

<style scoped>

</style>
