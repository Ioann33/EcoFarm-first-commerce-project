<template>

    <!--    <div id="footer-bar" class="footer-bar-1">-->
    <!--        &lt;!&ndash;<a @click="logout" href="#"><i class="fa fa-home"></i><span>Главная</span></a>&ndash;&gt;-->
    <!--        <router-link :to="{name: 'home2'}"><i class="fa fa-home"></i><span>Главная</span></router-link>-->
    <!--        <router-link :to="{name: 'prices'}"><i class="fa fa-star"></i><span>Тарифы</span></router-link>-->


    <!--        <a href="#" class="active-nav"><i class="fa fa-heart"></i><span>Pages</span></a>-->
    <!--        &lt;!&ndash;<a href="#"><i class="fa fa-hand-sparkles"></i><span>Новости и Акции</span></a>&ndash;&gt;-->
    <!--        <router-link to="/news"><i class="fa fa-hand-sparkles"></i><span>Информация</span></router-link>-->
    <!--        <a href="" data-menu="menu-settings"><i class="fa fa-cog"></i><span>Настройки</span></a>-->
    <!--    </div>-->

<!--    <div id="footer-bar" class="footer-bar-1">-->
    <div id="footer-bar" class="footer-bar-1 footer-bar-white bg-dark-dark gradient-green">
        <router-link :to="{name: 'home'}"><i class="fa fa-home"></i><span>Главная</span></router-link>


            <router-link v-if="isCook"      :to="{name: 'makeProducts'}"><i class="fa fa-hat-hard "></i><span>приготовить</span></router-link>
            <router-link v-if="isFinance"   :to="{name: 'FinanceDashboard'}"><i class="fa fa-hat-hard "></i><span>Статистика</span></router-link>
            <router-link v-if="isMoveGoods" :to="{name: 'MoveGoods'}"><i class="fa">  </i><span>передать</span></router-link>
            <router-link v-if="isListGoods" :to="{name: 'pageListGoods', params: {type: 'available'}}"><i class="fa fa-search"></i><span>товары на складе</span></router-link>
            <router-link v-if="isListGoodsOnStorages"  :to="{name: 'pageListGoodsOnStorages'}"><i class="fa fa-search"></i><span>товары на складах</span></router-link>


        <router-link v-if="isSelectStorage" :to="{name: 'selectStorage'}"><i class="fa-fw select-all fas"></i><span>Выбор склада</span></router-link>
        <a href="#" data-menu="menu-settings"><i class="fa fa-cog"></i><span>Настройки</span></a>
    </div>

</template>

<script>
export default {
    name: "navBar",
    components: {
    },
    date(){
       return {
           my_storage_type: '',
       }
    },
    beforeMount() {
        this.pagesListGoods = ['sale', 'buy', 'storage', 'creditor', 'cook']
        this.pagesMoveGoods = ['sale', 'buy', 'grow', 'cook'];
        this.pagesCook = ['cook'];
        this.pagesFinance = ['finance'];
        this.pagesSelectStorage = ['sale', 'buy', 'storage', 'creditor', 'debtor'];
        this.pagesListGoodsOnStorages = [ 'storage'];

        this.my_storage_id = localStorage.getItem('my_storage_id')
        this.my_storage_name = localStorage.getItem('my_storage_name')
        this.my_storage_type = localStorage.getItem('my_storage_type')
    },
    mounted() {

    },
    computed: {
        isFinance(){
            if(this.pagesFinance.includes(this.my_storage_type))
                return 1
        },
        isCook(){
            if(this.pagesCook.includes(this.my_storage_type))
                return 1
        },
        isMoveGoods(){
            if(this.pagesMoveGoods.includes(this.my_storage_type))
                return 1
        },
        isListGoods(){
            if(this.pagesListGoods.includes(this.my_storage_type))
                return 1
        },
        isListGoodsOnStorages(){
            if(this.pagesListGoodsOnStorages.includes(this.my_storage_type))
                return 1
        },
        isSelectStorage(){
            if(this.pagesSelectStorage.includes(this.my_storage_type))
                return 1
        }
    }

}
</script>


