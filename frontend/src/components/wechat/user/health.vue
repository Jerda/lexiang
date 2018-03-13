<template>
    <div>
        <x-header title="健康历史"></x-header>
        <loading :show="loadings"></loading>
        <div v-if="!loadings">
            <group v-if="healths.length != 0" class="change_margin_top">
                <cell v-for="(health,index) in healths" :title="health.created_at" is-link
                      @click.native="toHealthDetail(health.id)" :key="index" class="height_change"></cell>
            </group>
            <div v-else class="change_error_mes">
                <img slot="icon" style="display:block;margin-right:5px;" src="../imgs/error.png" class="change_error">
            无健康数据</div>
        </div>
        <Nav :healthActive="true"></Nav>
    </div>
</template>

<script>
    import Nav from '../common/user_nav'
    import { Group, Cell, XHeader, Icon, Loading} from 'vux'

    export default {
        components: {
            Nav,
            Group,
            Cell,
            XHeader,
            Icon,
            Loading
        },
        data() {
            return {
                healths: [],
                user_id: '',
                loadings:true
            }
        },
        methods: {
            getHealthHistory() {
                axios.post('api/health/userList', {user_id: this.$route.params.user_id}).then(response => {
                    this.healths = response.data.data
                    this.loadings = false
                    console.log(this.healths)
                }).catch(error => {
                    this.loadings = false
                })
            },
            toHealthDetail(id) {
                this.$router.push('/health_detail/' + id)
            }
        },
        mounted() {
            this.getHealthHistory()
        }
    }
</script>

<style>
    .height_change{
        height:5vh;
    }
    .change_margin_top{
        margin-top:-20px;
    }
    .change_error{
        margin-left:2vw;
        margin-top:15vh;
    }
    .change_error_mes{
        margin-left:40vw;
    }
</style>
