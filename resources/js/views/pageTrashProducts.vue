<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <error :message="error"></error>
            <div class="card card-style p-4">
                <div class="row mb-0">
                    <div class="col-12 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <label for="storage-list" class="color-blue-dark">Склад</label>
                            <select id="storage-list" v-model="selected_storage_id" @change="getStorageGoods(selected_storage_id)" class="form-control">
                                <option value="default" selected>выбрать склад</option>
                                <option
                                    v-for="(storage, index) in storageList"
                                    v-bind:value="storage.id"
                                >
                                    {{ storage.name }}
                                </option>

                            </select>
                            <div v-if="loading_storage" class="spinner-border text-light select-input-spinner" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <span><i class="fa fa-chevron-down"></i></span>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
                </div>

                <div class="row mb-0">
<!--   выбор товара   -->
                    <div class="col-7 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <label for="prod_2" class="color-blue-dark">Товар</label>
                            <select id="prod_2" v-model="selected_goods_id" @change="changeGoods" class="form-control">
                                <option value="default" selected>выбрать</option>
                                <option
                                    v-for="(goods, index) in availableGoods"
                                    v-bind:value="goods.goods_id"
                                >
                                    {{ goods.name }}, {{ goods.amount }} {{ goods.unit }}
                                </option>

                            </select>
                            <div v-if="loading_goods" class="spinner-border text-light select-input-spinner" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <span><i class="fa fa-chevron-down"></i></span>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
<!--   количество   -->
                    <div class="col-3 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" :disabled="max_amount === 0" class="form-control focus-color focus-blue validate-name "
                                   id="f1"
                                   v-model="amount"
                                   @input="checkAmount"
                            >
                            <!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em>{{ max_amount }}</em>
                        </div>
                    </div>

                    <div class="col-2 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" disabled class="form-control focus-color focus-blue validate-name text-center"
                                   :placeholder="unit"
                            >

                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                        </div>
                    </div>
                </div>

                <button style="background-color: #8CC152; color: #fff;" class="btn" @click="doTrash">Утилизировать</button>

            </div>
        </div>

        <nav-bar-menu></nav-bar-menu>
    </div>
</template>

<script>
    import headBar from "../components/headBar";
    import NavBar from "../Components/NavBar";
    import NavBarMenu from "../Components/NavBarMenu";
    import error from "../Components/Error";

    export default {
        name: "pageUtilizationProducts",
        components:{
            error,
            headBar, NavBar, NavBarMenu,
        },
        data(){
            return {
                loading_storage: false,
                loading_goods: false,
                storageList: [],
                selected_storage_id: 'default',
                availableGoods: [],
                selected_goods_id: 'default',
                max_amount: 0,
                unit: 'кг',
                amount: '',
                error: ''
            }
        },
        async mounted() {
            await this.getStorageList();
        },
        methods: {
            checkAmount(){
              if(this.amount > this.max_amount){
                  this.amount = this.max_amount;
              }
            },
            changeGoods(){
                const selected = this.availableGoods.find(el => el.goods_id === this.selected_goods_id);
                this.max_amount = selected.amount;
                this.unit = selected.unit;
            },
            async getStorageList(){
                this.loading_storage = true;
                const res = await axios.get('/api/getListStorages');
                this.loading_storage = false;
                if(!res.data){
                    return ;
                }

                this.storageList = res.data.data;
                console.table(this.storageList)
            },
            async getStorageGoods(id){
                if(!Number.isInteger(id)) return ;
                this.loading_goods = true;
                const res = await axios.get(`/api/getStorageGoods/available/${id}/all`);
                this.loading_goods = false;
                if(!res.data){
                    return ;
                }
                this.availableGoods = res.data.data.filter(el => el.amount>0);
            },
            async doTrash(){
                const res = await axios.post(`/api/doTrash`, {
                    storage_id: this.selected_storage_id,
                    goods_id: this.selected_goods_id,
                    amount: this.amount
                });

                if(!res.data){
                    console.error('Some error in trash')
                    return;
                }
                console.log('Success')
                this.$router.push({name: 'home'});
            }
        }
    }
</script>

<style>
    .select-input-spinner{
        position: absolute;
        right: 35px;
        top: 10px;
    }
</style>
