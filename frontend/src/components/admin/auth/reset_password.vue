<template>
    <el-dialog :visible.sync="dialogShow" width="300px">
        <el-form>
            <el-form-item label="原密码" v-model="form" :label-width="formLabelWidth">
                <el-input v-model="form.password" type="password" size="mini"></el-input>
            </el-form-item>
            <el-form-item label="新密码" v-model="form" :label-width="formLabelWidth">
                <el-input v-model="form.reset_password" type="password" size="mini"></el-input>
            </el-form-item>
            <el-form-item :label-width="formLabelWidth">
                <el-button type="primary" size="mini" @click="reset">修改密码</el-button>
            </el-form-item>
        </el-form>
    </el-dialog>
</template>

<script>
    import { Dialog, Button, Input, Form, FormItem } from 'element-ui'

    export default {
        components: {
            'el-dialog': Dialog,
            'el-button': Button,
            'el-input': Input,
            'el-form': Form,
            'el-form-item': FormItem
        },
        props: ['show'],
        data() {
            return {
                dialogShow: false,
                formLabelWidth: '60px',
                form: {
                    password: '',
                    reset_password: ''
                }
            }
        },
        methods: {
            reset() {
                axios.post('api/system/admins/resetPassword', this.form).then(response => {
                    this.$message.success(response.data.message)
                    this.dialogShow = false
                }).catch(error => {
                    this.$message.error(error.response.data.message)
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
                }
            },
        }
    }
</script>
