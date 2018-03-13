<template>
    <el-dialog :title="model.name" :visible.sync="dialogShow" width="55%">
        <el-table :data="rejects" size="mini"
                  v-loading="data_loading"
                  element-loading-text="请等待"
                  element-loading-spinner="el-icon-loading">
            <el-table-column prop="content" label="驳回原因"></el-table-column>
            <el-table-column prop="created_at" label="时间" width="200"></el-table-column>
            <el-table-column prop="operator" label="操作人" width="80"></el-table-column>
        </el-table>
    </el-dialog>
</template>

<script>
    import { Dialog, Table, TableColumn } from 'element-ui'

    export default {
        components: {
            'el-dialog': Dialog,
            'el-table': Table,
            'el-table-column': TableColumn
        },
        props: ['show', 'data', 'url'],
        data() {
            return {
                dialogShow: false,
                data_loading: true,
                rejects: [],
                model: {
                    name: '',
                    model: ''
                }
            }
        },
        methods: {
            getRejects() {
                axios.post(this.url, {model_id: this.model.id}).then(response => {
                    this.rejects = response.data.data
                    this.data_loading = false
                })
            },
        },
        watch: {
            show() {
                this.dialogShow = this.show
            },
            dialogShow () {
                if (!this.dialogShow) {
                    this.data_loading = true
                    this.$emit('close')
                } else {
                    this.model = this.data
                    this.getRejects()
                }
            },
        }
    }
</script>
