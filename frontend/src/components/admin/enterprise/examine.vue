<template>
    <el-container>
        <el-main>
            <el-row :gutter="20">
                <el-col style="width:170px">
                    <el-input
                            v-model="search.name"
                            size="small"
                            placeholder="请输入企业名称"
                            clearable>
                    </el-input>
                </el-col>
                <el-col style="width:140px">
                    <el-input
                            v-model="search.legal_person"
                            size="small"
                            placeholder="请输入法人"
                            clearable>
                    </el-input>
                </el-col>
                <el-col style="width: 170px">
                    <enterprise-type-selector @change="(val) => {search.enterprise_type_id = val}"></enterprise-type-selector>
                </el-col>
                <el-col style="width: 80px">
                    <el-button type="primary" size="small" icon="el-icon-search" @click="getEnterprise(current_page)">搜索</el-button>
                </el-col>
            </el-row>
            <el-row>
                <el-table
                        v-loading="loading.table"
                        element-loading-text="请等待"
                        element-loading-spinner="el-icon-loading"
                        :data="enterprises"
                        size="mini"
                        style="width: 100%">
                    <el-table-column
                            prop="name"
                            label="企业名称"
                            width="180">
                    </el-table-column>
                    <el-table-column
                            prop="legal_person"
                            label="企业法人">
                    </el-table-column>
                    <el-table-column
                            prop="linker"
                            label="联系人">
                    </el-table-column>
                    <el-table-column
                            prop="linker_mobile"
                            label="联系人手机号">
                    </el-table-column>
                    <el-table-column
                            prop="enterprise_type"
                            label="企业类型">
                    </el-table-column>
                    <el-table-column
                            label="地址">
                    </el-table-column>
                    <el-table-column
                            prop="created_at"
                            label="申请时间">
                    </el-table-column>
                    <el-table-column
                            prop="status"
                            label="状态"
                            width="70px">
                        <template slot-scope="scope">
                            <span v-if="scope.row.status == 0" class="el-dropdown-link" style="color: orange">
                                申请
                            </span>
                            <span v-else-if="scope.row.status == 2" class="el-dropdown-link" style="color: orange">
                                被驳回
                            </span>
                            <!--<status :status="scope.row.status" :id="scope.row.id"></status>-->
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="操作">
                        <template slot-scope="scope">
                            <el-button type="text" size="small" @click="edit(scope.row.id)">查看</el-button>
                            <el-dropdown id="dropdown" size="small">
                                <span class="el-dropdown-link">
                                   审批<i class="el-icon-arrow-down el-icon--right"></i>
                                </span>
                                <el-dropdown-menu slot="dropdown">
                                    <el-dropdown-item @click.native="agree(scope.row.id)">通过</el-dropdown-item>
                                    <el-dropdown-item @click.native="showReject(scope.row)">驳回</el-dropdown-item>
                                </el-dropdown-menu>
                            </el-dropdown>
                            <el-button v-if="scope.row.status == 2" type="text" size="small" @click="showRejectList(scope.row)">驳回记录</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-row>
            <el-row>
                <paginate :total="paginate_total" @change="getEnterprise"></paginate>
            </el-row>

            <reject-dialog :show="rejectDialog.show"
                           :data="rejectDialog.data"
                           @reject="reject"
                           @close="rejectDialog.show = false"></reject-dialog>

            <reject-list :show="rejectListDialog.show"
                         :data="rejectListDialog.data"
                         :url="rejectListDialog.url"
                         @close="rejectListDialog.show = false"></reject-list>
        </el-main>
    </el-container>
</template>

<script>
    import paginate from "../modules/paginate.vue"
    import EnterpriseTypeSelector from './enterprise_type_selector'
    import RejectDialog from '../modules/reject/index'
    import Status from '../modules/status'
    import RejectList from '../modules/reject/reject_list'
    import {
        Container, Main, Row, Col, Table, TableColumn,Dropdown, DropdownMenu, DropdownItem,
        Button, Input, Breadcrumb, BreadcrumbItem, DatePicker, Select, Option, MessageBox
    } from 'element-ui'

    export default {
        components: {
            Status,
            RejectDialog,
            RejectList,
            paginate,
            EnterpriseTypeSelector,
            MessageBox,
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
            'el-dropdown-item': DropdownItem,
        },
        data() {
            return {
                loading: {
                    table: true
                },
                paginate_total: 0,
                current_page: 1,
                enterprises: [],
                search: {
                    name: '',
                    legal_person: '',
                    enterprise_type_id: '',
                },
                rejectDialog: {
                    show: false,
                    data: {}
                },
                rejectListDialog: {
                    show: false,
                    data: {},
                    url: 'api/enterprise/rejectList'
                }
            }
        },
        methods: {
            getEnterprise(page) {
                this.loading.table = true
                axios.post('api/enterprise/waitExamine', {page:page, limit: this.$limit, search: this.search}).then(response => {
                    this.enterprises = response.data.data.data
                    this.paginate_total = response.data.data.total
                    this.current_page = response.data.data.current_page
                    this.loading.table = false
                });
            },
            edit(enterprise_id) {
                this.$router.push({name: 'enterprise_add', params: {id: enterprise_id}})
            },
            agree(enterprise_id) {
                MessageBox.confirm('确定通过审批？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post('api/enterprise/agree', {enterprise_id: enterprise_id}).then(response => {
                        this.$message.success(response.data.message)
                        this.getEnterprise(this.current_page)
                    });
                }).catch(() => {})
            },
            showReject(enterprise) {
                this.rejectDialog.data = enterprise
                this.rejectDialog.show = true
            },
            reject(data) {
                axios.post('api/enterprise/reject', {content: data.content, enterprise_id: data.model_id})
                    .then(response => {
                        this.$message.success(response.data.message)
                })
            },
            showRejectList(enterprise) {
                this.rejectListDialog.data = enterprise
                this.rejectListDialog.show = true
            }
        },
        mounted: function () {
            this.getEnterprise(this.current_page)
        }
    }
</script>

<style scoped>
    .el-row {
        margin-top: 15px
    }
    #dropdown {
        font-size: 11px;
        color: #409EFF;
    }
</style>
