<template>
    <div>
        <div class="post">
            <div>
                <div><strong>Title: </strong>{{ post.title }}</div>
                <div><strong>Description: </strong>{{ post.body }}</div>
            </div>
            <div class="post_btns">
                <button class="btn"
                    @click="$emit('remove', post)"
                >Delete</button>
            </div>
        </div>
    </div>
</template>

<script>

    import MyButton from "./UI/MyButton";
    export default {
        name: "PostItem",
        components: {MyButton},
        props: {
            post: {
                type: Object,
                required: true,
            }
        }
    }
</script>

<style scoped>
    .post{
        padding: 15px;
        margin-top: 10px;
        border: 2px solid #1ea832;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>
