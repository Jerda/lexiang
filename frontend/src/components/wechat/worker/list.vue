<template>
    <div v-show="isShow">
        <search
                :results="workers"
                v-model="search.data"
                position="absolute"
                placeholder="搜索员工(员工名)"
                :auto-fixed="search.auto_fixed"
                @on-submit="searchWorker"
                @on-cancel="clearSearch"
                ref="search">
        </search>

        <loading :show="loadings"></loading>
        <div v-if="!loadings">
            <group class="change_margin_top">
                <cell v-for="(worker,index) in workers" :title="worker.name" is-link
                      @click.native="toUserHealth(worker.id)" :key="index" class="change_height"></cell>
            </group>
        </div>
    </div>
</template>

<script>
    import { Search, Group, Cell, Loading} from 'vux'

    export default {
        components: {
            Search, Group, Cell, Loading
        },
        props: ['show'],
        data() {
            return {
                isShow: this.show,
                workers: [],
                search: {
                    data: '',
                    auto_fixed: false
                },
                loadings:true
            }
        },
        methods: {
            getWorkers() {

                axios.post('api/worker/all', {enterprise_id: 1}).then(response => {
                    this.workers = response.data.data.data
                    this.loadings = false
                }).catch(error => {
                    this.loadings = false
                })
            },
            searchWorker(name) {
                this.loadings = true;
                axios.post('api/worker/getByName', {enterprise_id: 1, name: name}).then(response => {
                    this.workers = response.data.data
                    this.loadings = false
                }).catch(error => {
                    this.loadings = false;
                })
            },
            toUserHealth(user_id) {
                this.$router.push('/health/' + user_id)
            },
            clearSearch() {
                this.getWorkers()
            }
        },
        mounted() {
            this.getWorkers()
        },
        watch: {
            show () {
                this.isShow = this.show
            },
        }
    }
</script>

<style>

    .change_height{
        height:5vh;
    }
    .change_margin_top{
        margin-top:-20px;
    }
</style>
