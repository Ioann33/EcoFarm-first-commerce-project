<template>
    <form v-on:submit.prevent>
        <h4>Create note</h4>
        <input v-bind:value="post.title" v-on:change="inputTitle" type="text" class="input" placeholder="title">
        <input v-bind:value="post.body" v-on:change="inputNote" type="text" class="input" placeholder="description">
        <button class="btn" v-on:click="createPost">Create</button>
    </form>
</template>

<script>
    import MyButton from "./UI/MyButton";
    export default {
        name: "PostForm",
        components: {MyButton},
        data(){
            return {
                post: {
                    title: '',
                    body: '',
                }
            }
        },
        methods: {
            inputTitle(event){
                this.post.title = event.target.value;
            },
            inputNote(event){
                this.post.body = event.target.value;
            },
            createPost() {
                this.post.id = Date.now();
                this.$emit('create', this.post)
                this.post = {
                    title: '',
                    body: '',
                }
            }
        }
    }
</script>

<style>
    form{
        display: flex;
        flex-direction: column;
    }
    .input {
        width: 100%;
        border: 1px solid #9ccbed;
        padding: 10px 15px;
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .btn{
        align-self: flex-end;
        padding: 10px 15px;
        background: none;
        color: teal;
        border: 1px solid teal;
        cursor: pointer;
    }
</style>
