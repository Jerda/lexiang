<template>
    <el-container>
        <el-main>
            <el-row :gutter="20">
                <el-col style="width: 170px">
                    <el-input
                            size="small"
                            placeholder="请输入姓名"
                            v-model="search_data.name"
                            clearable>
                    </el-input>
                </el-col>
                <el-col style="width: 170px">
                    <el-input
                            size="small"
                            placeholder="请输入手机号"
                            v-model="search_data.mobile"
                            clearable>
                    </el-input>
                </el-col>
                <!--<el-col style="width:370px">
                    <el-date-picker
                            size="small"
                            v-model="search_data.subscribe_time"
                            value-format="yyyy-MM-dd"
                            type="daterange"
                            range-separator="至"
                            start-placeholder="关注开始日期"
                            end-placeholder="关注结束日期">
                    </el-date-picker>
                </el-col>-->
                <el-col style="width: 80px">
                    <el-button type="primary" size="small" icon="el-icon-search" @click="getUsers">搜索</el-button>
                </el-col>
            </el-row>
            <el-row>
                <el-table
                        v-loading="loading.table"
                        element-loading-text="拼命加载中"
                        element-loading-spinner="el-icon-loading"
                        :data="users"
                        size="mini"
                        style="width: 100%">
                    <el-table-column
                            label="头像"
                            width="80">
                        <template slot-scope="scope">
                            <img :src="scope.row.wechat.avatar" alt=""  width="40" height="40">
                        </template>
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
                            <span v-if="scope.row.wechat.sex == 1">男</span>
                            <span v-else>女</span>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="年龄">
                        <template slot-scope="scope">
                            <div>{{scope.row.age}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="关注人数">
                        <template slot-scope="scope">
                            <button type="text" @click="showUserConcern(scope.row.id)">{{ scope.row.concern.length }}</button>
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
                    <el-table-column
                            label="状态">
                        <template slot-scope="scope">
                            <user-status :id="scope.row.id" :status="scope.row.status"
                                         @modify="modifyUserStatus"></user-status>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="操作">
                        <template slot-scope="scope">
                            <el-button type="text" size="small" @click="showUserDetail(scope.row)">查看</el-button>
                            <el-button type="text" size="small" @click="healthHistory(scope.row.id)">健康数据</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-row>
            <el-row>
                <paginate :total="paginate_total" @change="getUsers"></paginate>
            </el-row>

            <UserDetail :show="userDetail.show"
                        :data="userDetail.data"
                        @close="userDetail.show = false"></UserDetail>

            <concern :show="concern.show" :data_user_id="concern.user_id" @close="concern.show = false"></concern>
        </el-main>
    </el-container>
</template>

<script>
    import UserStatus from '../modules/user_status'
    import UserDetail from "./detail.vue"
    import concern from './concern_dialog.vue'
    import paginate from "../modules/paginate.vue"
    import { Container, Main, Row, Col, Table, TableColumn,
        Button, Input, Breadcrumb, BreadcrumbItem, DatePicker } from 'element-ui'

    export default {
        components: {
            concern,
            UserStatus,
            UserDetail,
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
                    mobile: '',
                    name: '',
                },
                userDetail: {
                    data: '',
                    show: false,
                },
                concern: {
                    show: false,
                    user_id: '',
                }
            }
        },
        methods: {
            getUsers(page) {
                axios.post('/api/user/all', {page:page, limit: this.$limit, search: this.search_data}).then(response => {
                    this.users = response.data.data.data
                    this.paginate_total = response.data.data.total
                    this.current_page = response.data.data.current_page
                    this.loading.table = false
                }).catch(error => {
                    this.$message.error(error.response.data.message)
                })
            },
            search() {
                this.current_page = 1
                this.getUsers(this.current_page)
            },
            showUserDetail(user) {
                this.userDetail.data = user
                this.userDetail.show = true
            },
            modifyUser() {
                this.getUsers(this.current_page)
            },
            showUserConcern(user_id) {
                this.concern.show = true
                this.concern.user_id = user_id
            },
            modifyUserStatus(data) {
                axios.post('api/user/modifyStatus', {user_id: data.user_id, status: data.status}).then(response => {
                    this.$message.success(response.data.message)
                    this.getUsers(this.current_page)
                })
            },
            healthHistory(user_id) {
                this.$router.push({name:'user_report', params: {user_id: user_id}});
            },
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
