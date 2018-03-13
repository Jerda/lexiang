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
                        element-loading-text="拼命加载中"
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
                            prop="status"
                            label="状态"
                            width="70px">
                        <template slot-scope="scope">
                            <span v-if="scope.row.status == 4" class="el-dropdown-link" style="color: orange">
                                申请
                            </span>
                            <span v-else-if="scope.row.status == 5" class="el-dropdown-link" style="color: orange">
                                被驳回
                            </span>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="操作">
                        <template slot-scope="scope">
                            <el-dropdown id="dropdown" size="small">
                                <span class="el-dropdown-link">
                                   审批<i class="el-icon-arrow-down el-icon--right"></i>
                                </span>
                                <el-dropdown-menu slot="dropdown">
                                    <el-dropdown-item @click.native="agree(scope.row.id)">通过</el-dropdown-item>
                                    <el-dropdown-item @click.native="showReject(scope.row)">驳回</el-dropdown-item>
                                </el-dropdown-menu>
                            </el-dropdown>
                            <!--<el-button type="text" size="small" @click="agree(scope.row.id)">通过</el-button>-->
                            <el-button type="text" size="small" @click="showUserDetail(scope.row)">查看</el-button>
                            <el-button v-if="scope.row.status == 5" type="text" size="small" @click="showRejectList(scope.row)">驳回记录</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-row>
            <el-row>
                <paginate :total="paginate_total" @change="getWorkers"></paginate>
            </el-row>

            <reject-dialog :show="rejectDialog.show"
                           :data="rejectDialog.data"
                           @reject="reject"
                           @close="rejectDialog.show = false"></reject-dialog>

            <reject-list :show="rejectListDialog.show"
                         :data="rejectListDialog.data"
                         :url="rejectListDialog.url"
                         @close="rejectListDialog.show = false"></reject-list>

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
    import RejectDialog from '../modules/reject/index'
    import RejectList from '../modules/reject/reject_list'
    import { Container, Main, Row, Col, Table, TableColumn, Dropdown, DropdownItem, DropdownMenu,
        Button, Input, Breadcrumb, BreadcrumbItem, DatePicker, Select, Option, MessageBox } from 'element-ui'

    export default {
        components: {
            MessageBox,
            UserDetail,
            concern,
            paginate,
            RejectDialog,
            RejectList,
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
            'el-option': Option,
            'el-dropdown': Dropdown,
            'el-dropdown-menu': DropdownMenu,
            'el-dropdown-item': DropdownItem
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
                },
                rejectDialog: {
                    show: false,
                    data: {}
                },
                rejectListDialog: {
                    show: false,
                    data: {},
                    url: 'api/worker/rejectList'
                }
            }
        },
        methods: {
            getWorkers(page) {
                this.loading.table = true
                let enterprise_id = this.$route.params.enterprise_id
                axios.post('api/worker/getWaitExamineWorker', {page:page, limit: this.$limit, enterprise_id: enterprise_id, search: this.search})
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
            agree(user_id) {
                MessageBox.confirm('确定通过员工申请？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.loading.table = true
                    axios.post('api/enterprise/agreeWorkerJoin', {user_id: user_id, enterprise_id: this.$route.params.enterprise_id})
                        .then(response => {
                            this.$message.success(response.data.message)
                            this.getWorkers(this.current_page)
                            this.$emit('modify')
                        })
                }).catch(() => {})
            },
            showReject(user) {
                this.rejectDialog.data = user
                this.rejectDialog.show = true
            },
            reject(data) {
                axios.post('api/worker/reject', {content: data.content, user_id: data.model_id})
                    .then(response => {
                        this.$message.success(response.data.message)
                    })
            },
            showRejectList(user) {
                this.rejectListDialog.data = user
                this.rejectListDialog.show = true
            }
        },
        mounted() {
            this.getWorkers(this.current_page);
        }
    }
</script>

<style scoped>
    #enterprise_name {
        margin-top: -5px;
    }
    #dropdown {
        font-size: 11px;
        color: #409EFF;
    }
</style>
