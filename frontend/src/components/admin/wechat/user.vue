<template>
    <el-container>
        <el-main>
            <el-row :gutter="20">
                <el-col style="width: 170px">
                    <el-input
                            size="small"
                            placeholder="请输入昵称"
                            v-model="search_data.nickname"
                            clearable>
                    </el-input>
                </el-col>
                <el-col style="width: 170px">
                    <el-input
                            size="small"
                            placeholder="请输入姓名"
                            v-model="search_data.name"
                            clearable>
                    </el-input>
                </el-col>
                <el-col style="width: 110px">
                    <el-button type="primary" size="small" @click="synchronizeUsers">同步微信用户</el-button>
                </el-col>
                <el-col style="width: 80px">
                    <el-button type="primary" size="small" icon="el-icon-search" @click="getUsers">搜索</el-button>
                </el-col>
            </el-row>
            <el-row>
                <el-table
                        v-loading="loading.table"
                        element-loading-text="请等待"
                        element-loading-spinner="el-icon-loading"
                        :data="users"
                        size="mini"
                        style="width: 100%">
                    <el-table-column
                            label="头像"
                            width="180">
                        <template slot-scope="scope">
                            <img :src="scope.row.wechat.avatar" alt=""  width="40" height="40">
                        </template>
                    </el-table-column>
                    <el-table-column
                            prop="wechat.nickname"
                            label="昵称"
                            width="180">
                    </el-table-column>
                    <el-table-column
                            prop="name"
                            label="姓名">
                    </el-table-column>
                    <el-table-column
                            prop="mobile"
                            label="手机号">
                    </el-table-column>
                    <el-table-column
                            prop="wechat.sex"
                            label="性别">
                        <template slot-scope="scope">
                            <span v-if="scope.row.wechat.sex == 0">女</span>
                            <span v-else>男</span>
                        </template>
                    </el-table-column>
                    <el-table-column
                            prop="wechat.city"
                            label="城市">
                    </el-table-column>
                    <el-table-column
                            prop="wechat.subscribe_time"
                            label="关注时间">
                    </el-table-column>
                    <!--<el-table-column
                            label="操作">
                        <template slot-scope="scope">
                            <el-button type="text" size="small" @click="showUserDetail(scope.row.id)">编辑</el-button>
                            &lt;!&ndash;<el-button type="text" size="small" @click="block(scope.row.id)">拉黑用户</el-button>&ndash;&gt;
                        </template>
                    </el-table-column>-->
                </el-table>
            </el-row>
            <el-row>
                <paginate :total="paginate_total" @change="getUsers"></paginate>
            </el-row>

            <!--<UserDetail :user_data="userDetail.data" :show="userDetail.show"
                        @close="userDetail.show = false;" @modify="modifyUser"></UserDetail>-->
        </el-main>
    </el-container>
</template>

<script>
//    import UserDetail from "../user/detail.vue"
    import paginate from "../modules/paginate.vue"
    import { Container, Main, Row, Col, Table, TableColumn,
        Button, Input, Breadcrumb, BreadcrumbItem, DatePicker } from 'element-ui'

    export default {
        components: {
//            UserDetail,
            paginate,
            'el-container': Container,
            'el-main': Main,
            'el-col': Col,
            'el-row': Row,
            'el-table': Table,
            'el-table-column': TableColumn,
            'el-button': Button,
            'el-input': Input,
            'el-breadcrumb': Breadcrumb,
            'el-breadcrumb-item': BreadcrumbItem,
            'el-date-picker': DatePicker
        },
        data() {
            return {
                loading: {
                    table: true
                },
                paginate_total: 0,
                current_page: 1,
                users: [],
                search_data: {
                    nickname: '',
                    name: '',
                    subscribe_time: '',
                },
                userDetail: {
                    data: '',
                    show: false,
                },
            }
        },
        methods: {
            synchronizeUsers: function() {
                this.loading.table = true
                axios.post('/api/wechat/user/synchronizeUsers').then(response => {
                    this.loading.table = false
                    this.$message.success(response.data.message)
                    this.getUsers(this.current_page)
                }).catch(error => {
                    this.loading.table = false
                    this.$message.error(error.response.data.message)
                });
            },
            getUsers: function(page) {
                axios.post('/api/wechat/user/getWechatUsers', {page:page, limit: this.$limit, search: this.search_data}).then(response => {
                    this.users = response.data.data.data
                    this.paginate_total = response.data.data.total
                    this.current_page = response.data.data.current_page
                    this.loading.table = false
                }).catch(error => {
                    this.$message.error(error.response.data.message)
                });
            },
            search: function() {
                this.current_page = 1
                this.getUsers(this.current_page)
            },
            showUserDetail: function(user_id) {
                axios.post('/api/user/get', {user_id:user_id}).then(response => {
                    this.userDetail.data = response.data.data
                    this.userDetail.show = true
                }).catch(error => {
                    this.$message.error(error.response.data.message)
                });
            },
            modifyUser: function() {
                this.getUsers(this.current_page)
            },
            /**
             * 拉黑用户
             */
            block: function(user_id) {
                axios.post('api/wechat/user/block', {user_id: user_id}).then(response => {
                    this.$message.success(response.data.message)
                }).catch(error => {
                    this.$message.error(error.response.data.message)
                });
            }
        },
        mounted: function() {
            this.$nextTick(function() {
                this.getUsers(this.current_page)
            });
        }
    }
</script>

<style scoped>
    .el-row {
        margin-top: 15px
    }
</style>
