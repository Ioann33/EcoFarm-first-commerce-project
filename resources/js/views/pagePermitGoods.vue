<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->  <error  :message="message"></error>

            <title-page title_main="Разрешить выбранный товар"></title-page>

            <div class="card card-style p-4 pt-3 mt-3">

<!--                <div class="row mb-0">-->
<!--                    <select-input :data="available_goods"-->
<!--                                  :label="'Весь список товаров'"-->
<!--                                  :value="selected_goods"-->
<!--                                  :loading="loading_goods" @getSelected="changeGoods" :defaultOption="'выбрать продукт'">-->
<!--                    </select-input>-->
<!--                </div>-->
                <span>Весь список товаров</span>
                <v-select :options="available_goods"
                          :value="'id'"
                          :label="'name'"
                          :placeholder="'выбрать продукт'"
                          :map-keydown="handlers"
                          @option:selected="changeGoods"
                          @search="searchGoods"
                          v-model="selected_goods_name"
                >
                </v-select>
                <div v-if="loading_storages_goods" class="spinner-border spinner-loading_storages_goods text-light" role="status">
                    <span class="sr-only">Loading...</span>
                </div>

                <div class="row mb-0" v-for="(elem, index) in storages" :key="elem.id">
                    <div class="col-9">
                        {{ elem.name }}
                    </div>
                    <div class="col-3">
                        <div class="custom-control ios-switch ios-switch-icon">
                            <input v-model="elem.allowed" type="checkbox" class="ios-input" @change="changeGoodsAllow(elem.id, elem.allowed)" :disabled="loading_storages_goods" :id="'toggle-id-'+index">
                            <label class="custom-control-label" :for="'toggle-id-'+index"></label>
                            <i class="fa fa-check font-11 color-white"></i>
                            <i class="fa fa-times font-11 color-white"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div v-if="message2" class="alert me-3 ms-3 rounded-s alert-save-goods-custom" :class="status ? 'bg-green-dark' : 'bg-red-dark'" role="alert">
            <span class="alert-icon"><i class="fa font-18" :class="status ? 'fa-check' : 'fa-times-circle'"></i></span>
            <h4 class="text-uppercase color-white">{{ status ? 'ОК' : 'ERROR' }} - {{ message2 }}</h4>
<!--            <strong class="alert-icon-text">{{ message2 }}</strong>-->
<!--            <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert" aria-label="Close">×</button>-->
        </div>
        <nav-bar-menu></nav-bar-menu>
    </div>
</template>

<script>
    import headBar from "../Components/HeadBar";
    import NavBar from "../Components/NavBar";
    import NavBarMenu from "../Components/NavBarMenu";
    import error from "../Components/Error";
    import TitlePage from "../Components/Title";
    import SelectInput from "../Components/SelectInput";
    import vSelect from "vue-select"

    export default {
        name: "pagePermitGoods",
        components:{
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
            SelectInput, vSelect
        },
        data(){
            return {
                status: '',
                message: '',
                message2: '',
                loading_goods: false,
                loading_storages_goods: false,
                selected_goods: 'default',
                available_goods: [],
                storages: [],
                selected_goods_name: ''
            }
        },
        computed: {},
        updated() {
        },
        mounted() {
            // this.getStorageGoods()
            this.getListStorages()
            console.log(this.$route.params)
            if (this.$route.params.goods_id > 0) {
                console.log('> landing with goods_id: '+this.$route.params.goods_id)
                this.changeGoods({id: this.$route.params.goods_id})
                this.searchGoods(this.$route.params.goods_name)
                this.selected_goods_name = this.$route.params.goods_name
            }
        },
        methods: {
            handlers: (map, vm) => ({
                ...map,
                13: (e) => {
                    e.preventDefault()
                    vm.deselect()
                    vm.onSearchBlur()
                },
            }),
            async getStorageGoods(){
                this.loading_goods = true;
                const res = await axios.get(`/api/getListGoods`);
                this.loading_goods = false;
                if(!res.data){
                    console.log('Error get search')
                    return ;
                }
                this.available_goods = res.data.data;
                console.log(res.data)
            },
            getListStorages(){
                const res = axios.get('/api/getListStorages').then(res => {
                    this.storages = res.data.data.map(el => { return {...el, allowed: false} } );
                })
            },
            getListStoragesGoodsPermit(goods_id) {
                this.loading_storages_goods = true;
                const res = axios.get(`/api/getListStoragesGoodsPermit/${goods_id}`).then(res => {
                    res.data.data.forEach(el => {
                        const current = this.storages.find(item => item.id === el.storage_id)
                        if(current) {
                            current.allowed = el.allowed;
                        }
                    });
                    this.loading_storages_goods = false;
                })
            },
            changeGoods(value){
                this.selected_goods = value.id;
                this.getListStoragesGoodsPermit(value.id)
            },
            searchGoods(value){
                if(!value) return;
                axios.get('/api/searchGoods/'+value.toLowerCase()).then(res => {
                    this.available_goods = res.data;
                }).catch(e => {
                    this.message = e.response.data.message
                   console.log(e)
                });
            },
            async changeGoodsAllow(id, value){
                if(this.selected_goods === 'default') return;
                await axios.post('/api/setGoodsPermit', {
                    goods_id: this.selected_goods,
                    storage_id: id,
                    allowed: value
                }).then(res => {
                    this.status = true;
                    this.message2 = 'Сохранено';

                }).catch(e => {
                    this.status = false;
                    this.message = e;
                })
                setTimeout(() => this.message2 = '', 1000)
            }
        }
    }
</script>

<style>
    .vs__selected-options > input, .vs__selected-options > input:focus {
        padding: 12px 6px;
        border: none;
    }
    .spinner-loading_storages_goods {
        position: absolute;
        left: calc(50% - 16px);
        top: 90px;
    }
    .alert-save-goods-custom {
        position: fixed;
        bottom: 55px;
        left: 0;
        right: 0;
    }
</style>
