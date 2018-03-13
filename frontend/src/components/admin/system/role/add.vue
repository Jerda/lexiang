<template>
    <el-dialog :title="title" :visible.sync="dialogShow" width="400px">
        <div v-loading="loading"
             element-loading-text="请等待"
             element-loading-spinner="el-icon-loading">
            <el-form :model="form">
                <el-form-item label="角色名称" :label-width="formLabelWidth">
                    <el-input v-model="form.name" size="small"></el-input>
                </el-form-item>
                <el-form-item :label-width="formLabelWidth">
                    <el-button @click="dialogShow = false" size="small">取 消</el-button>
                    <el-button v-if="mode == 'add'" type="primary" @click="addRole" size="small">添 加</el-button>
                    <el-button v-else type="primary" @click="modifyRole" size="small">修 改</el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-dialog>
</template>

<script>
    import { Dialog, Input, Button, Form, FormItem } from 'element-ui'

    export default {
        components: {
            'el-dialog': Dialog,
            'el-input': Input,
            'el-button': Button,
            'el-form': Form,
            'el-form-item': FormItem,
        },
        props: ['show', 'role_id'],
        data() {
            return {
                loading: true,
                title: '添加角色',
                dialogShow: false,
                mode: 'add',
                formLabelWidth: '80px',
                form: {
                    id: '',
                    name: ''
                }
            }
        },
        methods: {
            addRole() {
                axios.post('api/system/power/addRole', {name: this.form.name}).then(response => {
                    this.$message.success(response.data.message)
                    this.dialogShow = false
                });
            },
            modifyRole() {
                axios.post('api/system/power/modifyRole', this.form).then(response => {
                    this.$message.success(response.data.message)
                    this.dialogShow = false
                })
            },
            getRole() {
                axios.post('api/system/power/getRole', {role_id: this.form.id}).then(response => {
                    this.loading = false
                    this.form = response.data.data
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
                    this.form = {
                        id: '',
                            name: ''
                    }
                } else {
                    if (this.role_id) {
                        this.mode = 'edit'
                        this.title = '编辑角色'
                        this.form.id = this.role_id
                        this.getRole()
                    } else {
                        this.loading = false
                    }
                }
            }
        }
    }
</script>
