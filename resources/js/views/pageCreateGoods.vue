<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium text-center">
            <!-- ERROR -->  <error :message="message"></error>

            <title-page title_main="Создать продукт, товар"></title-page>

            <div class="card card-style overflow-visible p-4 pt-3 mt-3">

                создать продукт
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
    import TitlePage from "../Components/Title";
    export default {
        name: "pageSaleProducts",
        components:{
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },

        data() {
            return {
                message: '',
                loading_goods: false,
                available_goods: [],
                sale_goods: [{
                    goods_id: 'default',
                    amount: 0,
                    max_amount: 0,
                    unit: 'кг',
                    price: 5,
                    total: 0
                }]
            }
        },
        computed: {
            total() {
                let total = 0;
                this.sale_goods.forEach(el => {
                    total += el.total;
                })
                return total;
            },
            selected_goods(){
                let arr = [];
                this.sale_goods.forEach(el => {
                    arr.push(el.goods_id);
                })
                return arr;
            }
        },
        async mounted() {
            this.my_storage_id = localStorage.getItem('my_storage_id');
            await this.getStorageGoods(this.my_storage_id);

        },
        updated() {
            update_template()
        },
        methods: {
            selectedGoods(id, index){
                if(!Number.isInteger(id)) return ;

                const selected = this.available_goods.find(el => el.goods_id === id);
                this.sale_goods[index].max_amount = selected.amount;
                this.sale_goods[index].amount = 0;
                this.sale_goods[index].unit = selected.unit;
                this.sale_goods[index].price = selected.price;

            },
            async getStorageGoods(id){
                this.loading_goods = true;
                const res = await axios.get(`/api/getStorageGoods/available/${id}/all`);
                this.loading_goods = false;
                if(!res.data){
                    return ;
                }
                this.available_goods = res.data.data.filter(el => el.amount >0);
            },
            checkAmount(i){
                if(this.sale_goods[i].amount > this.sale_goods[i].max_amount){
                    this.sale_goods[i].amount = this.sale_goods[i].max_amount;
                }
                this.sale_goods[i].total = this.sale_goods[i].amount * this.sale_goods[i].price;
            },
            addGoods(){
                this.sale_goods.push({
                    goods_id: 'default',
                    amount: 0,
                    max_amount: 0,
                    unit: 'кг',
                    price: 5,
                    total: 0
                });
            },
            async saleProducts(){
                let sales = this.sale_goods.map(el => {
                    if(Number.isInteger(el.goods_id)){
                        return {
                            storage_id: this.my_storage_id,
                            goods_id: el.goods_id,
                            amount: el.amount,
                            price: el.price
                        }
                    }
                });
                console.log(sales)
                console.log('>>> продажа товара: ')
                console.log(sales)
                console.dir(sales)

                axios.post('/api/doSale', {
                    sales: sales
                }).then(res => {
                    console.log('<<< товар продан')


                    this.$router.push({name: 'home'});
                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                })

                // const res = await axios.post(`/api/doSale`, {
                //     prepareData
                // });
                // if(!res.data){
                //     console.log('Some error');
                // }
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
    .add-goods-btn{
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translate(-50%, 50%);
    }
</style>
