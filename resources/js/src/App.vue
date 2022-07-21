<template>
    <div class="main">
        <my-dialog v-bind:show="false">
            <post-form v-on:create="createPost"/>
        </my-dialog>

        <post-list
            v-bind:posts="posts"
            v-on:remove="removePost"
        />
    </div>
</template>

<script>
    import PostForm from "./components/PostForm";
    import PostList from "./components/PostList";
    import MyDialog from "./components/UI/MyDialog";
    export default {
        components: {
            MyDialog,
            PostList, PostForm
        },
        data(){
            return{
                posts: [
                    {id: 1, title: 'Vue+Laravel', body: 'Some description text'},
                    {id: 2, title: 'Vue+Laravel  2', body: 'Some description text 2'},
                    {id: 3, title: 'Vue+Laravel 3', body: 'Some description text 3'},
                ],
            }
        },
        methods: {
            createPost(post){
                this.posts.push(post)
            },
            removePost(post){
                this.posts = this.posts.filter(p => p.id !== post.id)
            }
        }
    }
</script>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
.main {
    padding: 10px;
}
</style>
