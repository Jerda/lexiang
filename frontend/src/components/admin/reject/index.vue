<template>
    <el-container v-loading="loading"
                  element-loading-text="拼命加载中"
                  element-loading-spinner="el-icon-loading"
                  element-loading-background="rgba(0, 0, 0, 0.8)">
        <el-main>
            <el-row :gutter="20">
                <el-col style="width: 145px">
                    <el-input
                            size="small"
                            placeholder="请输入员工名称"
                            clearable>
                    </el-input>
                </el-col>
                <el-col :span="4">
                    <el-button type="primary" size="small" icon="el-icon-search" @click="">搜索</el-button>
                </el-col>
            </el-row>
            <el-row>
                <el-table
                        :data="rejects"
                        style="width: 100%">
                    <el-table-column
                            label="员工姓名"
                            width="180">
                    </el-table-column>
                    <el-table-column
                            label="手机号">
                    </el-table-column>
                    <el-table-column
                            label="所属企业">
                    </el-table-column>
                    <el-table-column
                            label="关注人数">
                    </el-table-column>
                    <el-table-column
                            label="入驻时间">
                    </el-table-column>
                    <el-table-column
                            label="操作">
                        <template slot-scope="scope">
                            <el-button type="text" size="small" @click="">编辑</el-button>
                        </template>

                    </el-table-column>
                </el-table>
            </el-row>
            <el-row>
                <paginate :total="paginate_total" @change=""></paginate>
            </el-row>
        </el-main>
    </el-container>
</template>

<script>
    //    import UserDetail from "../user/detail.vue"
    import paginate from "../modules/paginate.vue"
    import { Container, Main, Row, Col, Table, TableColumn,
        Button, Input, Breadcrumb, BreadcrumbItem, DatePicker, Select, Option } from 'element-ui'

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
            'el-date-picker': DatePicker,
            'el-select': Select,
            'el-option': Option
        },
        data() {
            return {
                loading: false,
                paginate_total: 0,
                current_page: 1,
                rejects: [],
                search: {
                    name: '',
                },
            }
        },
        methods: {

        },
        mounted: function() {

        }
    }
</script>

<style scoped>
    .el-row {
        margin-top: 15px
    }
</style>
