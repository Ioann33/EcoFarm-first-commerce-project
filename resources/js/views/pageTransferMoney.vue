<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->  <error :message="message"></error>

            <div class="card card-style p-4">
                <h3 class="card-title text-center">Передать деньги</h3>
                <div class="row mb-0">
                    <div class="col-10 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
<!--                            <label for="storage-list-from" class="color-blue-dark">Товар</label>-->
                            <select id="storage-list-from"  v-model="storage_id" class="form-control">
                                <option value="default" selected>выбрать склад</option>
                                <option
                                    v-for="(storage, index) in list_storage"
                                    v-bind:value="storage.id"
                                >
                                    {{ storage.name }}
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
                    <div class="col-2 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" :disabled="true" class="form-control focus-color focus-blue validate-name "
                                   id="f15"
                                   v-model="balance"
                            >
                            <!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                        </div>
                    </div>
                </div>
                <div class="row mb-0 d-flex justify-content-center">
                    <div class="col-3 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" class="form-control focus-color focus-blue validate-name "
                                   :id="'amount-from-' + storage_id"
                                   @change="checkAmount()"
                                   v-model="amount"
                            >
                            <!--                                <label for="f1" class="color-blue-dark">кол-во</label>-->
                            <i class="fa fa-times disabled invalid color-red-dark"></i>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                        </div>
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-10 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <label for="storage-list-to" class="color-blue-dark">На склад</label>
                            <select id="storage-list-to" v-model="storage_to.id" @change="getMoney(storage_to.id, true)" class="form-control">
                                <option value="default" selected>выбрать склад</option>
                                <option
                                    v-for="(storage, index) in list_storage"
                                    v-bind:value="storage.id"
                                >
                                    {{ storage.name }}
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
                    <div class="col-2 p-1">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <input type="number" :disabled="true" class="form-control focus-color focus-blue validate-name "
                                   id="storage-to-balance"
                                   v-model="balance"
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
    import headBar from "../components/headBar";
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
            async getMoney(storage_id, storage_to = false){
                if(storage_to){
                    this.loading_balance_to = true;
                } else {
                    this.loading_balance_from = true;
                }
                const res = await axios.get(`/api/getFinance/${storage_id}`);
                if(storage_to){
                    this.loading_balance_to = false;
                } else {
                    this.loading_balance_from = false;
                }
                if(!res.data){
                    console.log('Unfortunately some error')
                }
                if(storage_to) {
                    this.storage_to.balance = res.data.balance;
                } else {
                    this.balance = res.data.balance;
                }
                console.log(this.storage_to.balance)
            },
            async getStorageList(){
                const res = await axios.get(`/api/getListStorages/`);
                if(!res.data){
                    console.log('Unfortunately some error')
                }
                this.list_storage = res.data.data;
                this.list_storage.forEach(function (storage, i){
                    console.log(storage)
                    console.log(storage.id +' = '+i)

                    // получить для каждого департамента Баланс
                    axios.get(`/api/getFinance/` + storage.id + '/balance');
                    if(!res.data){
                        console.log('Unfortunately some error')
                    }
                    console.log('balance='.res.data.balance)
                })

                console.log('list_storage:')
                console.log(this.list_storage)
            },
            async doTransfer(){
                const res = await axios.post(`/api/doTransferMoney`, {
                    storage_id_from: this.storage_id,
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
                if(this.amount > this.balance){
                    this.amount = this.balance;
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
