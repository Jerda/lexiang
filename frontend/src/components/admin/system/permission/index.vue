<template>
    <div>
        <el-row>
            <el-button size="small" type="primary" @click="addPermission.dialogShow = true">添加权限</el-button>
            <el-button size="small" type="primary" @click="addPermissionGroup.dialogShow = true">权限分组</el-button>
        </el-row>
        <el-row>
            <el-table
                    v-loading="loading.table"
                    element-loading-text="请等待"
                    element-loading-spinner="el-icon-loading"
                    :data="permissions"
                    size="mini"
                    style="width: 100%">
                <el-table-column type="expand">
                    <template slot-scope="props">
                        <el-table :data="props.row.data" size="mini">
                            <el-table-column
                                    prop="cn.name"
                                    label="权限名称">
                            </el-table-column>
                            <el-table-column
                                    prop="name"
                                    label="路由名">
                            </el-table-column>
                            <el-table-column
                                    prop="guard_name"
                                    label="guard_name">
                            </el-table-column>
                            <el-table-column
                                    label="操作">
                                <template slot-scope="scope">
                                    <el-button type="text" size="small" @click="editPermission(scope.row)">编辑</el-button>
                                    <el-button type="text" size="small" @click="del(scope.row.id)">删除</el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </template>
                </el-table-column>
                <el-table-column
                        prop="name"
                        label="权限分组">
                </el-table-column>
            </el-table>
        </el-row>

        <add-permission :show="addPermission.dialogShow"
                        :data="addPermission.data"
                        @close="addPermissionDialogClose"></add-permission>
        <add-permission-group :show="addPermissionGroup.dialogShow"
                              @close="addPermissionGroupDialogClose"></add-permission-group>
    </div>
</template>

<script>
    import { Row, Table, TableColumn, Button } from 'element-ui'
    import AddPermission from './add'
    import AddPermissionGroup from './add_group'

    export default {
        components: {
            'el-row': Row,
            'el-table': Table,
            'el-table-column': TableColumn,
            'el-button': Button,
            AddPermission,
            AddPermissionGroup
        },
        data() {
            return {
                loading: {
                    table: true,
                },
                permissions: [],
                addPermission: {
                    dialogShow: false,
                    data: ''
                },
                addPermissionGroup: {
                    dialogShow: false
                }
            }
        },
        methods: {
            getPermissions() {
                axios.post('api/system/power/getPermissions').then(response => {
                   this.permissions = response.data.data
                   this.loading.table = false
                });
            },
            //添加权限dialog关闭后重新获取权限
            addPermissionDialogClose() {
                this.getPermissions()
                this.addPermission.dialogShow = false
            },
            addPermissionGroupDialogClose() {
                this.getPermissions()
                this.addPermissionGroup.dialogShow = false
            },
            del(id) {
                axios.post('api/system/power/delPermission', {id: id}).then(response => {
                    this.$message.success(response.data.message)
                    this.getPermissions()
                });
            },
            editPermission(permission) {
                this.addPermission.data = permission
                this.addPermission.dialogShow = true
            }
        },
        mounted() {
            this.getPermissions()
        }
    }
</script>
