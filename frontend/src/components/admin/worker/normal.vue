<template>
    <el-container>
        <el-main>
            <el-row id="enterprise_name">
                <el-col :span="15">
                    <span style="color: #b1adad">{{ enterprise.name }} 员工列表</span>
                </el-col>
            </el-row>
            <el-row :gutter="20">
                <el-col style="width: 145px">
                    <el-input
                            v-model="search.name"
                            size="small"
                            placeholder="请输入员工名称"
                            clearable>
                    </el-input>
                </el-col>
                <el-col style="width: 170px">
                    <el-input
                            v-model="search.mobile"
                            size="small"
                            placeholder="请输入手机号"
                            clearable>
                    </el-input>
                </el-col>
                <el-col style="width: 70px">
                    <el-button type="primary" size="small" icon="el-icon-search" @click="getWorkers">搜索</el-button>
                </el-col>
                <el-col :span="1" style="margin-left: 10px">
                    <el-button size="small" @click="$router.go(-1)">返回上一级</el-button>
                </el-col>
            </el-row>
            <el-row>
                <el-table
                        v-loading="loading.table"
                        element-loading-text="加载中"
                        element-loading-spinner="el-icon-loading"
                        :data="workers"
                        size="mini"
                        style="width: 100%">
                    <el-table-column
                            prop="name"
                            label="员工姓名"
                            width="180">
                    </el-table-column>
                    <el-table-column
                            prop="mobile"
                            label="手机号">
                    </el-table-column>
                    <el-table-column
                            label="性别"
                            prop="sex"
                            width="180">
                        <template slot-scope="scope">
                            <span v-if="scope.row.wechat.sex == 1">男</span>
                            <span v-else>女</span>
                        </template>
                    </el-table-column>
                    <el-table-column
                            prop="age"
                            label="年龄"
                            width="180">
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
                            prop="created_at"
                            label="入驻时间">
                    </el-table-column>
                    <el-table-column
                            label="操作">
                        <template slot-scope="scope">
                            <el-button type="text" size="small" @click="showUserDetail(scope.row)">查看</el-button>
                            <el-button type="text" size="small" @click="removeWorker(scope.row.id)">移除员工</el-button>
                            <el-button type="text" size="small" @click="healthHistory(scope.row.id)">健康数据</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-row>
            <el-row>
                <paginate :total="paginate_total" @change="getWorkers"></paginate>
            </el-row>

            <UserDetail :data="userDetail.data" :show="userDetail.show"
                        @close="userDetail.show = false;" @modify="modifyUser"></UserDetail>

            <concern :show="concern.show" :data_user_id="concern.user_id" @close="concern.show = false"></concern>
        </el-main>
    </el-container>
</template>

<script>
    import UserDetail from "../user/detail.vue"
    import paginate from "../modules/paginate.vue"
    import concern from '../user/concern_dialog.vue'
    import { Container, Main, Row, Col, Table, TableColumn,
        Button, Input, Breadcrumb, BreadcrumbItem, DatePicker, Select, Option, MessageBox } from 'element-ui'

    export default {
        components: {
            MessageBox,
            UserDetail,
            concern,
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
            'el-date-picker': DatePicker,
            'el-select': Select,
            'el-option': Option
        },
        data() {
            return {
                loading: {
                    base: true,
                    table: true
                },
                paginate_total: 0,
                current_page: 1,
                enterprise: {},
                workers: [],
                search: {
                    name: '',
                    mobile: '',
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
            getWorkers(page) {
                this.loading.table = true
                let enterprise_id = this.$route.params.enterprise_id
                axios.post('api/worker/all', {page:page, limit: this.$limit, enterprise_id: enterprise_id, search: this.search})
                    .then(response => {
                        this.enterprise = response.data.data
                        this.workers = response.data.data.data
                        this.paginate_total = response.data.data.total
                        this.current_page = response.data.data.current_page
                        this.loading.table = false
                });
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
            removeWorker(user_id) {
                MessageBox.confirm('确定要移除员工？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.loading.table = true
                    axios.post('api/enterprise/removeWorker', {user_id: user_id}).then(response => {
                        this.$message.success(response.data.message)
                        this.getWorkers(this.current_page)
                    })
                }).catch(() => {})
            },
            healthHistory(user_id) {
                this.$router.push({name:'user_report', params: {user_id: user_id}})
            }
        },
        mounted() {
            this.getWorkers(this.current_page)
        }
    }
</script>

<style scoped>
    #enterprise_name {
        margin-top: -5px;
    }
</style>
