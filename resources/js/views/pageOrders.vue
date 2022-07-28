<template>
    <div id="page">

        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">

orders {{ $route.params.type }}

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
    name: "ShowOrders",
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
            goods_amount: 1 // количество товара

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


