<template>
    <button :value="modelValue" @click="createPost" class="btn">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        name: "my-button",
        props: {
            modelValue: [Object]
        },
        methods: {
            createPost(event){
                alert(this.modelValue)
                this.$emit('create:modelValue', event.target.value)
            }
        }
    }
</script>

<style scoped>
    .btn{

        padding: 10px 15px;
        background: none;
        color: teal;
        border: 1px solid teal;
        cursor: pointer;
    }
</style>
