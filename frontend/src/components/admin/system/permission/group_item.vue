<template>
    <el-row v-loading="loading"
            element-loading-text="请等待"
            element-loading-spinner="el-icon-loading">
        <el-col :span="14">
            <span v-if="cannotEdit" style="font-size: 12px; margin-left: 17px">{{ group.name }}</span>
            <el-input v-else :class="{cannotEdit:cannotEdit}" size="mini" v-model="group.name"></el-input>
        </el-col>
        <el-col :span="3" :offset="5">
            <button v-if="cannotEdit" size="small" @click="canEdit"><i class="el-icon-edit"></i></button>
            <button v-else size="small" @click="edit"><i class="el-icon-check"></i></button>
        </el-col>
        <el-col :span="2">
            <button size="small" @click="del(group.id)"><i class="el-icon-delete"></i></button>
        </el-col>
    </el-row>
</template>

<script>
    import { Row, Col, Input, Button } from 'element-ui'

    export default {
        components: {
            'el-row': Row,
            'el-col': Col,
            'el-input': Input,
            'el-button': Button
        },
        props: ['data'],
        methods: {
            canEdit() {
                this.cannotEdit = false;
            },
            edit() {
                this.loading = true
                axios.post('api/system/power/modifyPermissionGroup', {id: this.group.id, name: this.group.name}).then(response => {
                    this.$message.success(response.data.message)
                })
                this.cannotEdit = true
                this.loading = false
            },
            //删除分组
            del(id) {
                this.loading = true
                axios.post('api/system/power/delPermissionGroup', {id: id}).then(response => {
                    this.$message.success(response.data.message)
                    this.$emit('delete')
                    this.loading = false
                }).catch(error => {
                    this.$message.error(error.response.data.message)
                    this.loading = false
                });
            },
        },
        data() {
            return {
                loading: false,
                cannotEdit: true,
                group: this.data
            }
        }
    }
</script>

<style scoped>
    .cannotEdit >>> input {
        border: 0;
    }
</style>
