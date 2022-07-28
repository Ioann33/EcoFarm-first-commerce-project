<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

            {{message}}

            <div class="card card-style bg-green-dark shadow-bg shadow-bg-l">
                <p class="content color-white mb-4">
                    Передать продукцию
                </p>
            </div>

            {{selected_goods_id}} - {{goods_amount}}

            <div class="card card-style">
                <div class="content mb-0">

                    <div class="row mb-0">

                        <div class="col-6">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <label for="f6" class="color-blue-dark">Выбрать продукцию для заказа</label>
                                <select id="f6" v-model="selected_goods_id">
                                    <option value="default" disabled selected>продукция</option>

                                    <option
                                        v-for="(goods, index) in listGoods"
                                        v-bind:value="goods.goods_id"
                                    >
                                        {{ goods.name }} ({{ goods.goods_id }})
                                    </option>

                                </select>
                                <span><i class="fa fa-chevron-down"></i></span>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em></em>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <input type="number" class="form-control focus-color focus-blue validate-name "
                                       id="f1"
                                       v-model="goods_amount"
                                >
                                <label for="f1" class="color-blue-dark">кол-во</label>
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                                <em>0</em>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="input-style input-style-always-active has-borders no-icon">
                                <input type="number" disabled class="form-control focus-color focus-blue validate-name text-center"
                                       id="f1"
                                       v-bind:value="selected"
                                       v-bind:placeholder="selected"
                                >
                                <label for="f1" class="color-blue-dark" style="background: transparent;">ед.изм</label>
                                <i class="fa fa-times disabled invalid color-red-dark"></i>
                                <i class="fa fa-check disabled valid color-green-dark"></i>
                            </div>
                        </div>

                    </div>

                    <a @click.prevent="makeOrder" href="#" class="btn btn-xxl  shadow-bg shadow-bg-m btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-blue-dark">
                        Передать на склад
                    </a>
                </div>
            </div>

        </div>

        <nav-bar-menu></nav-bar-menu>

    </div>
</template>

<script>
import headBar from "../components/headBar";
import NavBar from "../Components/NavBar";
import NavBarMenu from "../Components/NavBarMenu";
import StorageButton from "../Components/StorageButton";

export default {
    name: "SelectStorage",
    components:{
        headBar, NavBar, NavBarMenu,
        StorageButton
    },
    data(){
        return {
            listGoods: null,
            storage_id: null,
            message: null,
            selected: null,
            selected_goods_id: null,
            storage_id_to: null,
            goods_amount: 0 // количество товара

        }
    },
    mounted() {
        //console.log('Component views/Home mounted....')
        this.storage_id = localStorage.getItem('my_storage_id')

        this.getStorageGoodsAllowed(this.storage_id)

        //console.log('Component views/Home mounted......done!')
    },
    updated() {
        console.log('updated')
        init_template2()
    },
    methods: {
        getStorageGoodsAllowed(storage_id) {
            axios.get('/api/getStorageGoodsAllowed/'+storage_id).then(res => {
                this.listGoods = res.data.data
                console.log(this.listGoods)
            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                console.log(this.message)
            })
        },
        makeOrder(){
            axios.get('/api/getMainStorage/').then(res => {
                this.storage_id_to = res.data.storage_id
                console.log(this.storage_id_to)

                console.log('good_id:' + this.selected_goods_id +
                    ', storage_to: '+ this.storage_id_to +
                    ', storage_from: '+ localStorage.getItem('my_storage_id')+
                    ', amount: '+ this.goods_amount
                )

                axios.post('/api/createOrder/',{
                    amount: this.goods_amount,
                    storage_id_to: this.storage_id_to,
                    storage_id_from: localStorage.getItem('my_storage_id'),
                    goods_id: this.selected_goods_id

                }).then(res => {
                    if(res.data.status=='ok') {
                        console.log('createOrder - ok')
                        this.$router.push({name: 'home'});
                    }
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.log(this.message)
                })


            }).catch(err => {
                this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                console.log(this.message)
            })
        }

    }

}
</script>


