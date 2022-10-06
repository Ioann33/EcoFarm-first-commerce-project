<template>
    <div id="page">
        <head-bar></head-bar>
        <nav-bar></nav-bar>

        <div class="page-content header-clear-medium">
            <!-- ERROR -->  <error  :message="message"></error>

            <title-page :title_main="this.$route.params.type"></title-page>

            <div class="card card-style overflow-visible p-4 pt-3 mt-3">
                <div class="row mb-0">
                    <div class="col-6">
                        <div class="input-style has-borders no-icon mb-4 input-style-active">
                            <input type="date" v-model="df" :max="dt" min="2000-01-01" class="form-control validate-text" id="form6" placeholder="from" @blur="getMoneyRequest">
                            <label for="form6" class="color-highlight">From</label>
                            <i class="fa fa-check valid me-4 pe-3 font-12 color-green-dark"></i>
                            <i class="fa fa-check disabled invalid me-4 pe-3 font-12 color-red-dark"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-style has-borders no-icon mb-4 input-style-active">
                            <input type="date" v-model="dt" :max="today" :min="df" class="form-control validate-text" id="form7" placeholder="to" @blur="getMoneyRequest">
                            <label for="form7" class="color-highlight">To</label>
                            <i class="fa fa-check valid me-4 pe-3 font-12 color-green-dark"></i>
                            <i class="fa fa-check disabled invalid me-4 pe-3 font-12 color-red-dark"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <label for="f7" class="color-blue-dark">категория</label>
                            <select id="f7" class="form-control" v-model="selectedCategory" @change="getMoneyRequest">
                                <option value="default" disabled selected>выбрать</option>
                                <!--                                v-bind:selected="category.selected"-->
                                <option
                                    v-for="(category, index) in categories"
                                    v-bind:value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                            <span><i class="fa fa-chevron-down"></i></span>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-style input-style-always-active has-borders no-icon">
                            <label for="f8" class="color-blue-dark">склад</label>
                            <select id="f8" class="form-control" v-model="selectedStorage" @change="getMoneyRequest">
                                <option value="all" selected>все</option>
                                <!--                                v-bind:selected="category.selected"-->
                                <option
                                    v-for="(category, index) in storages"
                                    v-bind:value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                            <span><i class="fa fa-chevron-down"></i></span>
                            <i class="fa fa-check disabled valid color-green-dark"></i>
                            <em></em>
                        </div>
                    </div>
                    <div v-if="loading" class="spinner-border text-light position-absolute" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="card card-style overflow-visible p-4 pt-3 mt-3">
                <p v-if="Array.isArray(list) && list.length === 0" class="text-center">К сожалению, информации не найдено</p>
                <table v-if="list.length" class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Сумма</th>
                        <th scope="col"><i class="fa-solid fa-user"></i></th>
                        <th scope="col">Описание</th>
                        <th scope="col">Дата</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in list">
                        <th scope="row">{{ item.id }}</th>
                        <td>{{ item.size_pay }}</td>
                        <td>{{ item.user_id }}</td>
                        <td>{{ item.description }}</td>
                        <td>{{ item.date }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!--
                @focus="$event.target.select()"
            -->
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
    name: "pageListMoney",
    components: {
        error,
        TitlePage,
        headBar, NavBar, NavBarMenu
    },
    data(){
        return {
            categories: [
                {name: 'Зарплата', id: 100},
                {name: 'Не профильные затраты', id: 500},
                {name: 'Капитальные затраты', id: 600},
            ],
            selectedCategory: 100,
            storages: [],
            selectedStorage: 'all',
            today: (new Date()).toISOString().split('T')[0],
            df: '',
            dt: (new Date()).toISOString().split('T')[0],
            message: '',
            toast_message: '',
            loading: false,
            list: ''
        }
    },
    computed: {},
    mounted() {
        // По дефолту дата за последнюю неделю
        const current = (new Date()).getTime();
        const weekAgo = (new Date()).setTime(current - (24*60*60*1000*7));
        this.df = (new Date(weekAgo)).toISOString().split('T')[0];

        let df = localStorage.getItem('df');
        let dt = localStorage.getItem('dt');
        if(df) this.df = df;
        if(dt) this.dt = dt;

        this.getListStorages();
        this.getMoneyRequest();
    },
    methods: {
        getListStorages(){
            axios.get('/api/getListStorages').then(res => {
                this.storages = res.data.data;
            }).catch(e => {
               console.log(e)
            });
        },
        getMoneyRequest(){
            this.loading = true;

            axios.get(`/api/getMoneyByCategoryOnStorage/list/${this.selectedStorage}/${this.selectedCategory}/all/${this.df} 00:00:00/${this.dt} 00:00:00`)
                .then(res => {
                    this.list = res.data.sum;
                    console.log(res.data)
                    this.loading = false;
            }).catch(e => {
                console.log(e)
            });
        }
    }
}
</script>

<style scoped>
    input[type=date] {
        width: 100%;
    }
    .spinner-border {
        bottom: 5px;
        left: calc(50% - 1rem);
    }
</style>
