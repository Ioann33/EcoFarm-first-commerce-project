<template>
    <div v-if="posts.length>0">
        <h3>Users list</h3>
        <post-item
            v-for="post in posts"
            v-bind:post="post"
            v-bind:key="post.id"
            v-on:remove="$emit('remove', post)"
        />
    </div>
    <h2 v-else style="color: #ed000f">User list is empty</h2>
</template>

<script>
    import PostItem from "./PostItem";
    export default {
        components: {PostItem},
        props: {
            posts: {
                type: Array,
                required: true,
            }
        }
    }
</script>

<style scoped>

</style>
