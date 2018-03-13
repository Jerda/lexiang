<template>
    <div>
        <el-dialog :title="role.name" :visible.sync="dialogShow" width="450px">
            <div style="margin-top: -40px"
                 v-loading="loading"
                 element-loading-text="请等待"
                 element-loading-spinner="el-icon-loading">
                <div v-for="permission in permissions">
                    <el-row>
                        <span style="color: #f18282">{{ permission.name }}</span>
                    </el-row>
                    <el-row style="margin-top: 1px">
                        <el-col v-for="(value,index) in permission.data" :span="6" :key="index">
                            <el-checkbox :label="value.name" v-model="checkList">{{ value.cn.name }}</el-checkbox>
                        </el-col>
                    </el-row>
                </div>
            </div>
            <div id='button-groups'>
                <el-button size="mini" type="primary" @click="givePermissionToRole">确 定</el-button>
                <el-button size="mini" type="primary" @click="dialogShow = false">取 消</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import { Dialog, Form, FormItem, Checkbox, CheckboxGroup, Card, Row, Col, Button } from 'element-ui'

    export default {
        components: {
            'el-dialog': Dialog,
            'el-form': Form,
            'el-form-item': FormItem,
            'el-checkbox-group': CheckboxGroup,
            'el-checkbox': Checkbox,
            'el-card': Card,
            'el-row': Row,
            'el-col': Col,
            'el-button': Button
        },
        props: ['show', 'role'],
        data() {
            return {
                loading: true,
                dialogShow: false,
                checkList: [],
                permissions: []
            }
        },
        methods: {
            getPermissions() {
                axios.post('api/system/power/getPermissions').then(response => {
                    this.permissions = response.data.data
                    this.loading = false
                });
            },
            getHasPermissions() {
                this.loading = true
                axios.post('api/system/power/getHasPermission', {role_id: this.role.id}).then(response => {
                    let that = this
                    response.data.data.forEach(function(i) {
                        that.checkList.push(i.name)
                    })
                    this.loading = false
                })
            },
            givePermissionToRole() {
                axios.post('api/system/power/givePermissionToRole', {
                    role_id: this.role.id, permissions: this.checkList}).then(response => {
                    this.$message.success(response.data.message)
                    this.dialogShow = false
                })
            }
        },
        watch: {
            show () {
                this.dialogShow = this.show;
            },
            dialogShow () {
                if (!this.dialogShow) {
                    this.$emit('close');
                    this.checkList = []
                } else {
                    this.getPermissions()
                    this.getHasPermissions()
                }
            },
            /*role() {
                this.data.role = this.role
            }*/
        }
    }
</script>

<style scoped>
    .el-dialog__body {
        margin-top: -25px;
    }
    .el-checkbox >>> span {
        font-size: 12px;
        font-weight: 400;
    }
    #button-groups {
        margin-top: 10px;
    }
</style>
