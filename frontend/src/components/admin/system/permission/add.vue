<template>
    <el-dialog title="添加权限" :visible.sync="dialogShow" width="250px">
        <el-form :model="form"
                 v-loading="loading"
                 element-loading-text="请等待"
                 element-loading-spinner="el-icon-loading">
            <el-form-item :label-width="formLabelWidth">
                <el-input placeholder="输入权限名称" v-model="form.cn.name" size="small"></el-input>
            </el-form-item>
            <el-form-item :label-width="formLabelWidth">
                <el-select v-model="form.group_id" placeholder="选择分组" size="small">
                    <el-option v-for="group in permission_groups"
                               :key="group.value"
                               :label="group.name"
                               :value="group.id"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item :label-width="formLabelWidth">
                <el-input placeholder="输入路由名" v-model="form.name" size="small"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="dialogShow = false" size="small">取 消</el-button>
            <el-button v-if="mode == 'add'" type="primary" @click="add" size="small">添 加</el-button>
            <el-button v-else type="primary" @click="add" size="small">修 改</el-button>
        </div>
    </el-dialog>
</template>

<script>
    import { Dialog, Input, Button, Form, FormItem, Select, Option } from 'element-ui'

    export default {
        components: {
            'el-dialog': Dialog,
            'el-input': Input,
            'el-button': Button,
            'el-form': Form,
            'el-form-item': FormItem,
            'el-select': Select,
            'el-option': Option
        },
        props: ['show', 'data'],
        data() {
            return {
                loading: true,
                dialogShow: false,
                formLabelWidth: '10px',
                mode: 'add',
                form: {
                    name: '',
                    group_id: '',
                    cn: {
                        name: ''
                    }
                },
                permission_groups: []
            }
        },
        methods: {
            getPermissionGroups() {
                axios.post('api/system/power/getPermissionGroups').then(response => {
                    this.permission_groups = response.data.data
                    console.log(this.mode)
                    if (this.mode == 'add') {
                        this.loading = false
                    }
                    if (this.permission_groups.length == 0) { //当前无权限分组
                        this.$message.error('当前还没有权限分组，请先创建权限分组')
                        this.dialogShow = false
                    }
                })
            },
            add() {
                axios.post('api/system/power/addPermission', this.form).then(response => {
                    // this.form = {};
                    this.$message.success(response.data.message)
                    this.dialogShow = false
                }).catch(error => {
                    this.$message.error(error.response.data.message)
                });
            },
            getGroupByPermissionID(permission_id) {
                axios.post('api/system/power/getGroupByPermissionID', {permission_id: permission_id}).then(response => {
                    this.form.group_id = response.data.data
                    this.loading = false
                })
            }
        },
        watch: {
            show () {
                this.dialogShow = this.show
            },
            dialogShow () {
                if (!this.dialogShow) {
                    this.$emit('close')
                    this.loading = true
                    this.mode = 'add'
                } else {
                    this.form = {
                        name: '',
                            group_id: '',
                            cn: {
                            name: ''
                        }
                    },
                    this.getPermissionGroups()
                }
            },
            data() {
                if (this.data) {
                    this.form = this.data
                    this.getGroupByPermissionID(this.form.id)
                    this.mode = 'edit'
                }
            }
        }
    }
</script>
