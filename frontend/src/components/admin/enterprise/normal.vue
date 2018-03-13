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
                    <el-button type="primary" size="small" icon="el-icon-search" @click="getEnterprises(current_page)">搜索</el-button>
                </el-col>
                <el-col style="width: 80px">
                    <el-button type="primary" size="small" @click="addEnterprise">注册企业</el-button>
                </el-col>
            </el-row>
            <el-row>
                <el-table
                        :data="enterprises"
                        size="mini"
                        style="width: 100%"
                        v-loading="loading.table"
                        element-loading-text="请等待"
                        element-loading-spinner="el-icon-loading">
                    <el-table-column
                            prop="name"
                            label="企业名称">
                    </el-table-column>
                    <el-table-column
                            prop="legal_person"
                            width="70"
                            label="企业法人">
                    </el-table-column>
                    <el-table-column
                            prop="linker"
                            width="70"
                            label="联系人">
                    </el-table-column>
                    <el-table-column
                            prop="linker_mobile"
                            width="120"
                            label="联系人手机号">
                    </el-table-column>
                    <el-table-column
                            prop="enterprise_type"
                            label="企业类型">
                    </el-table-column>
                    <el-table-column
                            label="地址">
                        <template slot-scope="scope">
                            <span>{{ scope.row.province }}{{ scope.row.city }}{{ scope.row.district }}</span>
                            <el-tooltip placement="top">
                                <div slot="content">
                                    {{ scope.row.province }}{{ scope.row.city }}{{ scope.row.district }}
                                    <br>
                                    {{ scope.row.address }}
                                </div>
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
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
                            <status :status="scope.row.status" :id="scope.row.id" @modify="modifyStatus"></status>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="操作">
                        <template slot-scope="scope">
                            <el-button type="text" size="small" @click="showWorker(scope.row.id)">查看员工</el-button>
                            <el-button type="text" size="small" @click="edit(scope.row.id)">查看</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-row>
            <el-row>
                <paginate :total="paginate_total" @change="getEnterprises"></paginate>
            </el-row>
        </el-main>
    </el-container>
</template>

<script>
    import paginate from "../modules/paginate"
    import EnterpriseTypeSelector from './enterprise_type_selector'
    import Status from '../modules/status'
    import { Container, Main, Row, Col, Table, TableColumn, Tooltip,
        Button, Input, Breadcrumb, BreadcrumbItem, DatePicker, Select, Option } from 'element-ui'

    export default {
        name: 'normal_enterprise',
        components: {
            Status,
            paginate,
            EnterpriseTypeSelector,
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
            'el-tooltip': Tooltip,
        },
        data() {
            return {
                loading: {
                    base: true,
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
                options: {
                    enterprise_type: []
                }

            }
        },
        methods: {
            /**
             * 跳转添加企业页面
             */
            addEnterprise() {
                this.$router.push({name: 'enterprise_add'});
            },
            getEnterprises(page) {
                this.loading.table = true
                axios.post('api/enterprise/normal', {page:page, limit: this.$limit, search: this.search}).then(response => {
                    this.enterprises = response.data.data.data
                    this.paginate_total = response.data.data.total
                    this.current_page = response.data.data.current_page
                    this.loading.table = false
                })
            },
            edit(enterprise_id) {
                this.$router.push({name: 'enterprise_add', params: {id: enterprise_id}})
            },
            /*
            修改企业状态
             */
            modifyStatus(data) {
                this.loading.table = true
                axios.post('api/enterprise/modifyStatus', {enterprise_id: data.enterprise_id, status: data.status}).then(response => {
                    this.$message.success(response.data.message)
                    this.getEnterprises(this.current_page)
                    this.loading.table = false
                });
            },
            showWorker(enterprise_id) {
                this.$router.push({name: 'worker_index', params: {enterprise_id: enterprise_id}})
            }
        },
        mounted: function() {
            this.getEnterprises(this.current_page)
        }
    }
</script>

<style scoped>
    .el-row {
        margin-top: 15px
    }
</style>
