<template>
    <el-dialog title="添加客户" :visible.sync="dialogShow" width="400px">
        <el-form ref="form" :model="form" label-width="80px">
            <el-form-item label="客户账户">
                <el-input v-model="form.account"></el-input>
            </el-form-item>
            <el-form-item label="客户昵称">
                <el-input v-model="form.nickname"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="add">添加</el-button>
                <el-button @click="close">取消</el-button>
            </el-form-item>
        </el-form>
    </el-dialog>
</template>

<script>
    import { Dialog, Col, Row, Button, Form, FormItem, Input } from "element-ui"

    export default {
        components: {
            'el-dialog': Dialog,
            'el-col': Col,
            'el-row': Row,
            'el-button': Button,
            'el-form': Form,
            'el-form-item': FormItem,
            'el-input': Input
        },
        props: ['show'],
        data () {
            return {
                dialogShow: this.show,
                form: {
                    account: '',
                    nickname: ''
                }
            }
        },
        methods: {
            close: function() {
                this.$emit('close');
            },
            add: function() {
                axios.post('api/wechat/service/add', this.form).then(response => {
                    console.log(response.data);
                });
            }
        },
        watch: {
            show (val) {
                this.dialogShow = val;
            },
            dialogShow (val) {
                if (!val) {
                    this.close();
                }
            },
        }
    }
</script>

<style scoped>
    .el-col {
        height: 300px
    }
</style>