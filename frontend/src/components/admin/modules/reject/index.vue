<template>
    <el-dialog title="驳回申请" :visible.sync="dialogShow" width="500px">
        <el-form :model="form">
            <el-form-item label="申请企业/人" :label-width="formLabelWidth">
                <el-input :disabled="true" v-model="model.name"></el-input>
            </el-form-item>
            <el-form-item label="原因" :label-width="formLabelWidth">
                <el-input type="textarea" v-model="form.content"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="dialogShow = false">取 消</el-button>
            <el-button type="primary" @click="reject">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
    import { Dialog, Form, FormItem, Button, Input} from 'element-ui'

    export default {
        components: {
            'el-dialog': Dialog,
            'el-form': Form,
            'el-form-item': FormItem,
            'el-button': Button,
            'el-input': Input
        },
        props: ['show', 'data'],
        data() {
            return {
                dialogShow: false,
                formLabelWidth: '100px',
                title: '',
                form: {
                    name: '',
                    content: ''
                },
                model: {},
            }
        },
        methods: {
            reject() {
                this.$emit('reject', {content: this.form.content, model_id: this.model.id})
                this.dialogShow = false
            }
        },
        watch: {
            show () {
                this.dialogShow = this.show
            },
            dialogShow () {
                if (!this.dialogShow) {
                    this.$emit('close')
                    this.form = {
                        name: '',
                            content: ''
                    };
                }
            },
            data() {
                this.model = this.data
            }
        }
    }
</script>
