<template>
    <el-dialog :visible.sync="dialogShow" width="390px">
        <el-form v-loading="loading.table"
                 element-loading-text="请等待"
                 element-loading-spinner="el-icon-loading"
                 v-model="form">
            <el-form-item label="账户名称" :label-width="formLabelWidth">
                <el-input size="small" v-model="form.username"></el-input>
            </el-form-item>
            <el-form-item v-if="mode == 'add'" label="账户密码" :label-width="formLabelWidth">
                <el-input size="small" type="password" v-model="form.password"></el-input>
            </el-form-item>
            <el-form-item v-if="mode == 'edit'" label="修改密码" :label-width="formLabelWidth">
                <el-input size="small" type="password" placeholder="如需修改密码填写" v-model="form.reset_password"></el-input>
            </el-form-item>
            <el-form-item label="账户角色" :label-width="formLabelWidth">
                <el-select v-model="form.role" size="small" multiple>
                    <el-option v-for="role in roles" :label="role.name" :value="role.name" :key="role.id"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item :label-width="formLabelWidth">
                <el-button v-if="mode == 'add'" size="small" type="primary" @click="add">添加管理员</el-button>
                <el-button v-else size="small" type="primary" @click="modify">修改管理员</el-button>
            </el-form-item>
        </el-form>
    </el-dialog>
</template>

<script>
    import {Dialog, Button, Select, Option, Input, Form, FormItem} from 'element-ui'

    export default {
        components: {
            'el-input': Input,
            'el-dialog': Dialog,
            'el-button': Button,
            'el-select': Select,
            'el-option': Option,
            'el-form': Form,
            'el-form-item': FormItem
        },
        props: ['show', 'data'],
        data() {
            return {
                loading: {
                    table: true
                },
                dialogShow: false,
                formLabelWidth: '80px',
                roles: [],
                form: {
                    user_id: '',
                    username: '',
                    password: '',
                    reset_password: '',
                    role: ''
                },
                user_id: '',
                mode: 'add'
            }
        },
        methods: {
            getRoles() {
                axios.post('api/system/power/getRoles').then(response => {
                    this.roles = response.data.data
                    this.loading.table = false
                })
            },
            add() {
                axios.post('api/system/admins/add', this.form).then(response => {
                    this.$message.success(response.data.message)
                    this.dialogShow = false
                }).catch(error => {
                    this.$message.error(error.response.data.message)
                })
            },
            modify() {
                axios.post('api/system/admins/modify', this.form).then(response => {
                    this.$message.success(response.data.message)
                    this.dialogShow = false
                }).catch(error => {
                    this.$message.error(error.response.data.message)
                })
            },
            get() {
                this.loading.table = true
                axios.post('api/system/admins/get', {user_id: this.user_id}).then(response => {
                    this.form.user_id = response.data.data.admin.id
                    this.form.username = response.data.data.admin.username
                    this.form.role = response.data.data.roles
                    this.loading.table = false
                })
            }
        },
        watch: {
            show() {
                this.dialogShow = this.show
            },
            dialogShow () {
                if (!this.dialogShow) {
                    this.$emit('close')
                } else {
                    this.getRoles()
                    if (this.data) {
                        this.user_id = this.data
                        this.mode = 'edit'
                        this.get()
                    } else {
                        this.mode = 'add'
                    }
                }
            },
        }
    }
</script>
