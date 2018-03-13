<template>
    <el-dialog title="权限分组" :visible.sync="dialogShow" width="700px">
        <el-row>
            <el-col :span="12">
                <el-card v-loading="loading.list"
                         element-loading-text="请等待"
                         element-loading-spinner="el-icon-loading">
                    <div v-for="group in permission_groups" :key="group.id">
                        <group-item :data="group" @delete="getPermissionGroups"></group-item>
                    </div>
                </el-card>
            </el-col>
            <el-col :span="11" :offset="1">
                <el-card v-loading="loading.add"
                         element-loading-text="请等待"
                         element-loading-spinner="el-icon-loading">
                    <el-form :model="form">
                        <el-form-item :label-width="formLabelWidth">
                            <el-input v-model="form.name" placeholder="添加分组的名称" size="small"></el-input>
                        </el-form-item>
                        <el-form-item :label-width="formLabelWidth">
                            <el-button size="small" type="primary" @click="add">添加</el-button>
                        </el-form-item>
                    </el-form>
                </el-card>
            </el-col>
        </el-row>
    </el-dialog>
</template>

<script>
    import { Dialog, Input, Button, Form, FormItem, Card, Col, Row } from 'element-ui'
    import GroupItem from './group_item'

    export default {
        components: {
            'el-dialog': Dialog,
            'el-input': Input,
            'el-button': Button,
            'el-form': Form,
            'el-form-item': FormItem,
            'el-card': Card,
            'el-col': Col,
            'el-row': Row,
            GroupItem
        },
        props: ['show'],
        data() {
            return {
                loading: {
                    list: true,
                    add: false
                },
                edit: true,
                dialogShow: false,
                formLabelWidth: '20px',
                form: {
                    name: '',
                },
                permission_groups: []
            }
        },
        methods: {
            //添加分组
            add() {
                this.loading.add = true
                axios.post('api/system/power/addPermissionGroup', this.form).then(response => {
                    this.getPermissionGroups()
                    this.loading.add = false
                }).catch(error => {
                    this.$message.error(error.response.data.message)
                    this.loading.add = false
                });;
            },
            //获取权限分组
            getPermissionGroups() {
                axios.post('api/system/power/getPermissionGroups').then(response => {
                    this.permission_groups = response.data.data;
                    this.form.name = ''; //添加后充值input框值
                    this.form.action = ''; //添加后充值input框值
                    this.loading.list = false
                });
            }
        },
        watch: {
            show () {
                this.dialogShow = this.show;
            },
            dialogShow () {
                if (!this.dialogShow) {
                    this.$emit('close');
                }
            },
            permission_groups(val) {
                this.permission_groups = val;
            }
        },
        mounted() {
            this.getPermissionGroups();
        }
    }
</script>


