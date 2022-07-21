<template>
    <div>
        <button v-on:click="addLike">like</button>
        <button @click="addDislike">dislike</button>
        <div>Amount likes: <h2>{{ likes }}</h2></div>
        <div>Amount dislikes: <h2>{{ dislikes }}</h2></div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                likes: 0,
                dislikes: 0,
            }
        },
        methods: {
            addLike(){
                this.likes+=1;
            },
            addDislike(){
                this.dislikes+=1;
            }
        }
    }
</script>

<style scoped>

</style>
