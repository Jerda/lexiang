<template>
    <el-container v-loading="loading"
                  element-loading-text="拼命加载中"
                  element-loading-spinner="el-icon-loading"
                  element-loading-background="rgba(0, 0, 0, 0.8)">
        <el-main>
            <el-row :gutter="20">
                <el-col :span="3">
                    <el-input
                            size="small"
                            placeholder="请输入昵称"
                            clearable>
                    </el-input>
                </el-col>
                <el-col :span="3">
                    <el-input
                            size="small"
                            placeholder="请输入姓名"
                            clearable>
                    </el-input>
                </el-col>
                <el-col :span="4">
                    <el-button type="primary" size="small" icon="el-icon-search" >搜索</el-button>
                    <el-button type="primary" size="small" @click="dialog.add = true">创建客户</el-button>
                </el-col>
            </el-row>
            <el-row>
                <el-table
                        :data="services"
                        style="width: 100%">
                    <el-table-column
                            prop="kf_id"
                            label="客服ID"
                            width="180">
                    </el-table-column>
                    <el-table-column
                            prop="kf_headimgurl"
                            label="客服头像"
                            width="180">
                    </el-table-column>
                    <el-table-column
                            prop="kf_account"
                            label="客服账号"
                            width="180">
                    </el-table-column>
                    <el-table-column
                            prop="kf_nick"
                            label="客服昵称">
                    </el-table-column>
                    <el-table-column
                            label="操作">
                        <template slot-scope="scope">
                            <el-button type="text" size="small" @click="">编辑</el-button>
                            <el-button type="text" size="small" @click="invite(scope.row.kf_account)">绑定微信号</el-button>
                            <!--<el-button type="text" size="small" @click="block(scope.row.id)">拉黑用户</el-button>-->
                        </template>
                    </el-table-column>
                </el-table>
            </el-row>
            <el-row>
                <!--<paginate :total="paginate_total" @change=""></paginate>-->
            </el-row>

            <Add :show="dialog.add" @close="dialog.add = false"></Add>
            <!--<UserDetail :user_data="userDetail.data" :show="userDetail.show"
                        @close="userDetail.show = false;" @modify="modifyUser"></UserDetail>-->
        </el-main>
    </el-container>
</template>

<script>
    //    import UserDetail from "../user/detail.vue"
//    import paginate from "../modules/paginate.vue"
    import Add from "./add.vue"
    import { Container, Main, Row, Col, Table, TableColumn,
        Button, Input, Breadcrumb, BreadcrumbItem, DatePicker } from 'element-ui'

    export default {
        components: {
//            UserDetail,
//            paginate,
            Add,
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
                loading: false,
                dialog: {
                    add: false,
                },
                services: []
            }
        },
        methods: {
            getServices: function() {
                axios.post('api/wechat/service/services').then(response => {
                    this.services = response.data.data;
                });
            },
            /**
             * 绑定微信号
             */
            invite: function(account) {
                axios.post('api/wechat/service/invite', {account: account})
            }

        },
        mounted: function() {
            this.getServices();
        }
    }
</script>

<style scoped>
    .el-row {
        margin-top: 15px
    }
</style>