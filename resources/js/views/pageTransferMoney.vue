<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->  <error :message="message"></error>

            <div class="card card-style p-4">
                <h3 class="card-title text-center">Передать деньги</h3>
                <div class="row mb-0">
                    <div class="col-7 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
<!--                            <label for="storage-list-from" class="color-blue-dark">Товар</label>-->
                            <select id="storage-list-from"  v-model="storage_from.id" @change="selectStorageFrom()" class="form-control">
                                <option value="default" selected>выбрать склад</option>
                                <option
                                    v-for="(storage, index) in list_storage"
                                    v-bind:value="storage.id"
                                >
                                    {{ storage.name }}, {{ storage.balance }} грн
                                </option>

                            </select>
                            <div v-if="loading_balance_from" class="spinner-border text-light select-input-spinner" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <span><i class="fa fa-chevron-down"></i></span>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>

                    </div>
                    <div class="col-5 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" :disabled="true" class="form-control focus-color focus-blue validate-name "
                                   id="f15"
                                   v-model="storage_from.balance"
                            >
                            <!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                        </div>
                    </div>
                </div>
                <div class="row mb-0 d-flex justify-content-center">
                    <div class="col-5 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" class="form-control focus-color focus-blue validate-name "
                                   :id="'amount-from-' + storage_id"
                                   @input="checkAmount()"
                                   v-model="amount"
                            >
                            <!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                        </div>
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-7 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <label for="storage-list-to" class="color-blue-dark">На склад</label>
                            <select id="storage-list-to" v-model="storage_to.id" @change="getMoney(storage_to.id, true)" class="form-control">
                                <option value="default" selected>выбрать склад</option>
                                <option
                                    v-for="(storage, index) in list_storage"
                                    v-bind:value="storage.id"
                                >
                                    {{ storage.name }}, {{ storage.balance }} грн
                                </option>

                            </select>
                            <div v-if="loading_balance_to" class="spinner-border text-light select-input-spinner" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <span><i class="fa fa-chevron-down"></i></span>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>

                    </div>
                    <div class="col-5 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" :disabled="true" class="form-control focus-color focus-blue validate-name "
                                   id="storage-to-balance"
                                   v-model="storage_to.balance"
                            >
                            <!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                        </div>
                    </div>
                </div>
                <textarea name="transfer-money-comment"
                          v-model="comment"
                          class="p-2 mb-3"
                          id=""
                          cols="30"
                          rows="3"
                          maxlength="50"
                          style="resize: none; border-radius: 10px;"
                          placeholder="Комментарий">
                </textarea>

                <button type="button" class="btn btn-lg btn-default" @click="doTransfer">Передать</button>
            </div>





        </div>

        <nav-bar-menu></nav-bar-menu>
    </div>
</template>

<script>
    import headBar from "../Components/HeadBar";
    import NavBar from "../Components/NavBar";
    import NavBarMenu from "../Components/NavBarMenu";
    import error from "../Components/Error";

    export default {
        name: "pageTransferMoney",
        components:{
            error,
            headBar, NavBar, NavBarMenu,
        },
        data(){
            return {
                message: '',
                balance: 0,
                amount: '',
                list_storage: [],
                storage_from: {
                    id: 'default',
                    balance: 0
                },
                storage_to: {
                    id: 'default',
                    balance: 0
                },
                comment: '',

                // just helper variables
                loading_balance_from: false,
                loading_balance_to: false,
            }
        },
        beforeMount() {
            this.storage_id = localStorage.getItem('my_storage_id');
        },
        async  mounted() {

          await this.getMoney(this.storage_id);
          await this.getStorageList();
        },
        methods: {
            selectStorageFrom(){
                if(this.storage_from.id === 'default') {
                    this.storage_from.balance = 0;
                    return;
                }
                const selected = this.list_storage.find(el => el.id === this.storage_from.id );
                this.storage_from.balance = selected.balance;
            },
            async getMoney(storage_id, storage_to = false){
                if(storage_to){
                    this.loading_balance_to = true;
                } else {
                    this.loading_balance_from = true;
                }
                const res = await axios.get(`/api/getFinance/${storage_id}/balance`);
                if(storage_to){
                    this.loading_balance_to = false;
                } else {
                    this.loading_balance_from = false;
                }
                if(!res.data){
                    console.log('Unfortunately some error')
                }
                if(storage_to) {
                    this.storage_to.balance += Number.parseFloat(res.data.balance);
                } else {
                    this.balance = res.data.balance;
                }
                console.log(this.storage_to.balance)
            },
            async getStorageList(){
                const res = await axios.get(`/api/getListStorages/`)
                if(!res.data)   {   console.error('Unfortunately some error')   }

                this.list_storage = res.data.data;
                this.list_storage.forEach(function (storage, i){

                    // получить для каждого департамента Баланс
                    axios.get('/api/getFinance/'+ storage.id+"/balance").then(res => {
                        storage.balance = res.data.balance
                        // console.log('balance('+storage.id+'): '+ storage.balance)
                    }).catch(err => {
                        this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    })
                })

                console.log('list_storage:')
                console.log(this.list_storage)
            },
            async doTransfer(){
                const res = await axios.post(`/api/doTransferMoney`, {
                    storage_id_from: this.storage_from.id,
                    storage_id_to: this.storage_to.id,
                    amount: this.amount,
                    comment: this.comment,
                });
                if(!res.data){
                    console.log('Unfortunately some error')
                }
                this.$router.push({name: 'home'});
            },
            checkAmount(){
                const selected_storage_to = this.list_storage.find(el => el.id === this.storage_to.id);
                if(this.amount > this.storage_from.balance){
                    this.amount = Number.parseFloat(this.storage_from.balance);
                    if(this.storage_to.id === 'default'){
                        this.storage_to.balance = this.amount;
                    } else {
                        this.storage_to.balance = Number.parseFloat(selected_storage_to.balance) + this.amount;
                    }
                    return;
                }
                if(this.storage_to.id === 'default'){
                    this.storage_to.balance = this.amount;
                } else {
                    this.storage_to.balance = Number.parseFloat(selected_storage_to.balance) + this.amount;
                }
            }
        }
    }
</script>

<style>
    .btn-default {
        background-color: #A0D468;
        border-radius: 10px;
        color: #fff;
    }
    .btn-default:hover {
        color: #fff;
    }
    .select-input-spinner{
        position: absolute;
        right: 35px;
        top: 10px;
    }

</style>
