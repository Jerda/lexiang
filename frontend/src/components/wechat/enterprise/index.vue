<template>
    <div>
        <x-header :title="top_title" :isBack="true"></x-header>
        <loading :show="loadings"></loading>
        <div v-if="!loadings">
            <div v-if="have_company">
                <group class="change_margin_top">
                    <cell title="企业信息" is-link link="/enterprise_info" class="change_height_spec">
                        <img slot="icon" style="display:block;margin-right:5px;" src="../imgs/message.png">
                    </cell>
                    <cell v-if="is_admin" title="员工管理" is-link link="/worker_index" class="change_height_spec">
                        <img slot="icon" style="display:block;margin-right:5px;" src="../imgs/label.png">
                    </cell>
                    <cell v-if="is_admin" title="企业管理员" is-link link="/admin" class="change_height_spec">
                        <img slot="icon" style="display:block;margin-right:5px;" src="../imgs/headlines.png">
                    </cell>
                </group>
            </div>
            <div v-else class="change_error_mes">
                <img slot="icon" style="display:block;margin-right:5px;" src="../imgs/error.png" class="change_error">
                无企业数据
            </div>
        </div>
    </div>
</template>

<script>
    import { Group, Cell, XHeader, Loading} from 'vux'

    export default {
        components: {
            Group,
            Cell,
            XHeader,
            Loading
        },
        data() {
            return {
                top_title: '我的企业',
                have_company:false,
                is_admin:false,
                loadings:true
            }
        },
        methods: {
            getInfo() {
                axios.post('api/user/getEnterprise').then(response => {
                    if(response.data.data.id){
                        this.have_company = true
                    }
                    this.loadings = false
                }).catch(error => {
                    this.loadings = false
                })
            },
            getAdmin() {
                axios.post('api/user/isAdmin').then(response => {
                    if(response.data.data.id){
                        this.is_admin = true
                    }
                }).catch(error => {

                })
            }
        },
        mounted() {
            this.getInfo()
            this.getAdmin()
        }
    }
</script>

<style scoped>

    .change_margin_top{
        margin-top:-20px;
    }
    .change_height_spec{
        height:7vh;
    }
    .change_error{
        margin-left:2vw;
        margin-top:15vh;
    }
    .change_error_mes{
        margin-left:40vw;
    }
</style>
