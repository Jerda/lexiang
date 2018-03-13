<template>
    <div>
        <el-row>
            <el-button type="primary" size="mini" @click="addDialogShow">添加管理员</el-button>
        </el-row>
        <el-row>
            <el-table
                    v-loading="loading.table"
                    element-loading-text="请等待"
                    element-loading-spinner="el-icon-loading"
                    :data="admins"
                    size="mini"
                    style="width: 100%">
                <el-table-column
                        prop="username"
                        label="账号名称"
                        width="180">
                </el-table-column>
                <el-table-column
                        label="角色">
                    <template slot-scope="scope">
                        <div style="float: left;margin-left: 2px" v-for="role in scope.row.roles">
                            <el-tag type="info" size="mini">{{ role.name }}</el-tag>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column
                        label="操作">
                    <template slot-scope="scope">
                        <el-button type="text" size="small"
                                   @click="edit(scope.row.id)">编辑</el-button>
                        <el-button type="text" size="small" @click="del(scope.row.id)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-row>
        <add :show="addDialog.show" :data="addDialog.data" @close="addDialogClose"></add>
    </div>
</template>

<script>
    import {Row, Col, Table, TableColumn, Button, Tag} from 'element-ui'
    import Add from './add'

    export default {
        components: {
            'el-row': Row,
            'el-cole': Col,
            'el-table': Table,
            'el-table-column': TableColumn,
            'el-button': Button,
            'el-tag': Tag,
            Add
        },
        data() {
            return {
                loading: {
                    table: true,
                },
                admins: [],
                addDialog: {
                    show: false,
                    data: ''
                },
            }
        },
        methods: {
            getAdmins() {
                axios.post('api/system/admins/getAll').then(response => {
                    this.admins = response.data.data
                    this.loading.table = false
                })
            },
            edit(user_id) {
                this.addDialog.data = user_id
                this.addDialog.show = true
            },
            addDialogShow() {
                this.addDialog.show = true
                this.addDialog.data = ''
            },
            addDialogClose() {
                this.getAdmins()
                this.addDialog.show = false
            },
            del(id) {
                axios.post('api/system/admins/delete', {user_id: id}).then(response => {
                    this.$message.success(response.data.message)
                    this.getAdmins()
                }).catch(error => {
                    this.$message.error(error.response.data.message)
                })
            }
        },
        mounted() {
            this.getAdmins();
        }
    }
</script>
