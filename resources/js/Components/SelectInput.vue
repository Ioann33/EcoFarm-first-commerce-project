<template>
    <div class="p-1" :class="{class: true}">
        <div class="input-style input-style-always-active has-borders no-icon">
            <label v-if="label" for="storage-list-from" class="color-blue-dark">{{label}}</label>
            <select id="storage-list-from" :disabled="disabled" v-model="selected_goods" class="form-control">
                <option value="default" selected>{{ defaultOption }}</option>
                <option
                    v-for="(item, index) in data"
                    v-bind:value="item[keyOfValue]"
                >
                    {{ item.name }}
                </option>

            </select>
            <div v-if="loading" class="spinner-border text-light select-input-spinner" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span><i class="fa fa-chevron-down"></i></span>
            <i class="fa fa-check disabled valid color-green-dark"></i>
            <em></em>
        </div>

    </div>
</template>

<script>
    export default {
        name: "SelectInput",
        emits: ['getSelected'],
        props: {
            label: {
              type: String,
              default: ''
            },
            class: {
                type: String,
                default: 'col-12'
            },
            data: {
                type: Array,
                default: []
            },
            value: {
              required: true
            },
            loading: {
                type: Boolean,
                default: false
            },
            defaultOption: {
                type: String,
                default: 'выбрать'
            },
            disabled: {
                type: Boolean,
                default: false
            },
            keyOfValue: {
                type: String,
                default: 'goods_id'
            }
        },
        data() {
            return {
            }
        },
        computed: {
            selected_goods: {
                get() { return this.value },
                set(value) { this.$emit('getSelected', value) }
            }
        },
        methods: {

        },

    }
</script>

<style>
    .select-input-spinner{
        position: absolute;
        right: 35px;
        top: 10px;
    }
</style>
