<template>
    <div>
        <el-row>
            <el-button size="small" type="primary" @click="edit('')">添加角色</el-button>
        </el-row>
        <el-row>
            <el-table
                    v-loading="loading.table"
                    element-loading-text="请等待"
                    element-loading-spinner="el-icon-loading"
                    :data="roles"
                    size="mini"
                    style="width: 100%">
                <el-table-column
                        prop="name"
                        label="角色名称"
                        width="180">
                </el-table-column>
                <el-table-column
                        prop="guard_name"
                        label="guard_name"
                        width="180">
                </el-table-column>
                <el-table-column
                        label="操作">
                    <template slot-scope="scope">
                        <el-button type="text" size="small"
                                   @click="edit(scope.row.id)">编辑</el-button>
                        <el-button type="text" size="small"
                                   @click="editPermissions(scope.row)">权限</el-button>
                        <el-button type="text" size="small" @click="del(scope.row.id)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-row>

        <add-role :show="addRole.dialogShow" :role_id="addRole.data.role_id" @close="addRoleDialogClose"></add-role>
        <edit-permission :show="editPermission.dialogShow"
                         :role="editPermission.role"
                         @close="editPermission.dialogShow = false"></edit-permission>
    </div>
</template>

<script>
    import { Row, Table, TableColumn, Button } from 'element-ui'
    import AddRole from './add'
    import EditPermission from './edit_permission'

    export default {
        components: {
            'el-row': Row,
            'el-table': Table,
            'el-table-column': TableColumn,
            'el-button': Button,
            AddRole,
            EditPermission
        },
        data() {
            return {
                loading: {
                    table: true,
                },
                addRole: {
                    dialogShow: false,
                    data: {
                        role_id: ''
                    }
                },
                editPermission: {
                    dialogShow: false,
                    role: '',
                },
                roles: []
            }
        },
        methods: {
            //获取所有角色
            getRoles() {
                axios.post('api/system/power/getRoles').then(response => {
                    this.roles = response.data.data
                    this.loading.table = false
                });
            },
            //添加角色dialog关闭后重新获取角色
            addRoleDialogClose() {
                this.getRoles()
                this.addRole.dialogShow = false
            },
            del(id) {
                axios.post('api/system/power/delRole', {id: id}).then(response => {
                    this.$message.success(response.data.message)
                    this.getRoles()
                })
            },
            /*
            编辑权限
             */
            editPermissions(role) {
                this.editPermission.dialogShow = true
                this.editPermission.role = role
            },
            edit(role_id) {
                this.addRole.dialogShow = true
                this.addRole.data.role_id = role_id
            }
        },
        mounted() {
            this.getRoles();
        }

    }
</script>
