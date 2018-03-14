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
            <div v-else>
                <search
                        v-model="search.data"
                        position="absolute"
                        auto-scroll-to-top
                        :auto-fixed='search.auto_fixed'
                        placeholder="搜索企业"
                        @on-submit="searchEnterprise"
                        @on-cancel="search.result = []"
                        ref="search" class="change_margin"></search>
                <group>
                    <cell v-for="(item,args) in search.result" :title="item.name" :key="args" class="change_height_">
                        <x-button style="height:5vh;line-height:25%;background:rgb(38,104,255);color:white;"
                                  @click.native="applyJoinEnterprise(item.id)">
                            申请加入
                        </x-button>
                    </cell>
                </group>
                <div class="change_error_mes" v-if="search.result == ''">
                    <img slot="icon" style="display:block;margin-right:5px;" src="../imgs/error.png" class="change_error">
                    <p style="margin-left: -20px">{{ info }}</p>
                    <a link="" style="margin-left: -5px">查看驳回信息</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Group, Cell, XHeader, Loading, Search, XButton} from 'vux'

    export default {
        components: {
            Group, Cell, XHeader, Loading, Search, XButton
        },
        data() {
            return {
                top_title: '我的企业',
                have_company:false,
                is_admin:false,
                loadings:true,
                search: {
                    auto_fixed: false,
                    data: '',
                    result: []
                },
                info: ''
            }
        },
        methods: {
            getInfo() {
                axios.post('api/user/info').then(response => {
                    if (response.data.data.status == 4) {
                        this.info = '您的申请还未通过'
                    } else if (response.data.data.status == 5) {
                        this.info = '您的申请已被驳回'
                    } else if (response.data.data.status == 3){
                        this.info = '您的账号已被企业禁用'
                    } else if (response.data.data.status == 2) {
                        this.have_company = true
                    } else {
                        this.info = '你还没有加入企业'
                    }
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
            },
            searchEnterprise() {
                axios.post('api/enterprise/getByName').then(response => {
                    this.search.result = response.data.data
                })
            },
            applyJoinEnterprise(enterprise_id) {
                axios.post('api/user/applyJoinEnterprise', {enterprise_id: enterprise_id}).then(response => {
                    this.$vux.alert.show({
                        content: response.data.message
                    })
                    this.$router.push('user_index')
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
