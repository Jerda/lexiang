<template>
    <el-dialog title="用户详情" :visible.sync="dialogShow" width="600px">
        <el-form :model="user">
            <el-form-item :label-width="formLabelWidth">
                <el-col :span="9">
                    <img :src="user.wechat.avatar" alt="" style="width:60px;height:60px">
                </el-col>
            </el-form-item>
            <el-form-item label="姓名" :label-width="formLabelWidth">
                <el-col :span="9">
                    <el-input size="small" v-model="user.name" disabled></el-input>
                </el-col>
                <el-col class="line" :span="2" :offset="2">年龄</el-col>
                <el-col :span="9">
                    <el-input size="small" v-model="user.age" disabled></el-input>
                </el-col>
            </el-form-item>
            <el-form-item label="手机" :label-width="formLabelWidth">
                <el-col :span="9">
                    <el-input size="small" v-model="user.mobile" disabled></el-input>
                </el-col>
                <el-col class="line" :span="2" :offset="2">邮箱</el-col>
                <el-col :span="9">
                    <el-input size="small" v-model="user.email" disabled></el-input>
                </el-col>
            </el-form-item>
            <el-form-item label="性别" :label-width="formLabelWidth">
                <el-col :span="9">
                    <el-input size="small" v-model="user.wechat.sex== 1? '男':'女'" disabled></el-input>
                </el-col>
                <el-col class="line" :span="2" :offset="2">城市</el-col>
                <el-col :span="9">
                    <el-input size="small" v-model="user.wechat.city" disabled></el-input>
                </el-col>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="history">检查记录</el-button>
            <el-button @click="dialogShow = false">取 消</el-button>
            <el-button type="primary" @click="">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
    import { Dialog, Button, Col, Form, FormItem, Input } from 'element-ui'

    export default {
        components: {
            'el-dialog': Dialog,
            'el-button': Button,
            'el-col': Col,
            'el-form': Form,
            'el-form-item': FormItem,
            'el-input': Input
        },
        props:['show', 'data'],
        data () {
            return {
                load: false,
                dialogShow: this.show,
                formLabelWidth: '40px',
                user: {
                    wechat: {
                        avatar: ''
                    }
                },
            }
        },
        methods: {
            history() {
                this.$router.push({name:'user_report', params: {user_id: this.user.id}});
            }
        },
        watch: {
            show () {
                this.dialogShow = this.show;
                this.user = this.data;
            },
            dialogShow () {
                if (!this.dialogShow) {
                    this.$emit('close');
                }
            }
        }

    }
</script>
