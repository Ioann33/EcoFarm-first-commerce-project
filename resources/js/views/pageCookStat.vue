<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <div class="card card-style p-4 pt-3 mt-3 content">

                <div class=" mb-0">
                    <table class="table text-center rounded-sm shadow-l" style="overflow: hidden;"> <!-- table-borderless -->
                        <thead>
                            <tr class="bg-grass-light">
                                <th></th>
                                <th></th>
                                <th scope="col" class="color-black opacity-50" colspan="3">Инфо</th>
                            </tr>
                            <tr class="bg-grass-light">
                                <th scope="col" class="color-black opacity-50">Готовая прод.</th>
                                <th scope="col" class="color-black opacity-50">К</th>
                                <th scope="col" class="color-black opacity-50">И</th>
                                <th scope="col" class="color-black opacity-50">СС</th>
                                <th scope="col" class="color-black opacity-50">S</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="goods in listMovements">
                                <tr>
                                    <th scope="row" rowspan="2" style="vertical-align: middle;">{{ goods.goods_name }}</th>
                                    <td rowspan="2" style="vertical-align: middle;">{{ goods.amount }}</td>
                                    <td>{{ goods.production }}</td>
                                    <td>{{ goods.cost }}</td>
                                    <td>{{ goods.sum }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ goods.totalProduction }}</b></td>
                                    <td><b>{{ goods.totalCost ? goods.totalCost.toFixed(2) : '' }}</b></td>
                                    <td><b>{{ goods.totalSum}}</b></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

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
    import TitlePage from "../Components/Title";

    export default {
        name: "pagePreSale",
        components: {
            error,
            TitlePage,
            headBar, NavBar, NavBarMenu,
        },
        data(){
            return {
                listMovements: [],
                df: '2022-06-01 00:00:00',
                dt: '2022-09-05 00:00:00'
            }
        },
        computed: {},
        beforeMount() {
            this.my_storage_id = localStorage.getItem('my_storage_id')
        },
        mounted() {
            axios.get('api/getListGoodsMovements/'+this.my_storage_id+'/'+this.df+'/'+this.dt)
                .then(res => {
                    console.log(res.data)

                    this.listMovements = res.data.data.filter(el => el.category === 'move' && el.link_id !== null ).map(item => {
                        return {
                            goods_name: 'Товар ' + item.goods_id, // Змінити назву товару, коли буде в API goods_id
                            amount: Number.parseFloat(item.amount),
                            production: Number.parseFloat(item.price),
                            totalProduction: Number.parseFloat(item.amount) * Number.parseFloat(item.price),
                            cost: '',
                            totalCost: '',
                            sum: '',
                            totalSum: '',
                            link_id: item.link_id
                        }
                    })

                    this.listMovements.forEach((el, index) => {
                        this.getMovementInfo(el.link_id, index);
                    })

                }).catch(err => {
                    this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                    console.error(this.message)
                })
        },
        updated() {
        },
        methods: {
            getMovementInfo(id, index){
                axios.get('api/getMovementInfo/'+id)
                    .then(res => {

                        this.listMovements[index].goods_name = res.data.data.goods_name;
                        this.listMovements[index].cost = Number.parseFloat(res.data.data.price);
                        this.listMovements[index].sum = this.listMovements[index].production + this.listMovements[index].cost;
                        this.listMovements[index].totalCost = this.listMovements[index].amount * this.listMovements[index].cost;
                        this.listMovements[index].totalSum = this.listMovements[index].amount * this.listMovements[index].sum;


                    }).catch(err => {
                        this.message = 'Error: ('+err.response.status+'): '+err.response.data.message;
                        console.error(this.message)
                    })
            }
        }
    }
</script>

<style scoped>

</style>
