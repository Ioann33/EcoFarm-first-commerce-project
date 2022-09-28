<template>
    <div class="ms-3 me-3 alert p-0 alert-small rounded-s shadow-xl custom-modal" role="alert">
        <div class="modal-header bg-blue-dark position-relative">
            <span><i class="fa fa-cog"></i></span>
            <strong>{{ title }}</strong>
            <button @click="this.$emit('close', true)" type="button" class="color-white opacity-60 font-16">×</button>
        </div>
        <div class="modal-body" :class="loading ? 'text-center' : ''">
            <slot name="data"></slot>
            <div v-if="loading" class="spinner-border text-success" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Modal",
        props: {
            title: {
                type: String,
                default: 'Default title'
            },
            loading: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {

            }
        },
        computed: {},
        mounted() {
        },
        methods: {

        },
        watch: {}
    }
</script>

<style>
    .modal-header {
        border-radius: 8px 8px 0 0;
    }
    .custom-modal {
        position: fixed;
        top: 66px;
        left: 0;
        right: 0;
        z-index: 10;
        background-color: white;
    }
    .modal-body {
        max-height: calc(100vh - 206px);
        overflow: scroll;
    }
</style>
