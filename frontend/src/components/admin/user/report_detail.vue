<template>
    <div>
        <el-row id="header">
            <el-col :span="10">
                <span id="header-name">{{ user.name }}健康报表</span>
                <span id="header-date">{{ list.created_at }}</span>
            </el-col>
            <el-col :span="2" :offset="12">
                <el-button size="small" type="primary" @click="$router.go(-1)">返回</el-button>
            </el-col>
        </el-row>
        <el-row>
            <span class="header-two">性别 {{ user.wechat.sex == 1 ? '男' : '女'}} | </span>
            <span class="header-two">年龄 {{ user.age }}</span>
        </el-row>
        <el-row v-loading="loading"
                element-loading-text="请等待"
                element-loading-spinner="el-icon-loading">
            <el-tabs v-if="data != ''" v-model="activeName" type="border-card">
                <el-tab-pane v-for="field in fields" :key="field.name" :label="field.name">
                    <div style="font-size: 13px;margin-left: 30px" v-for="(value, key) in field">
                        <el-row v-if="(key !== 'db_name') && (key !== 'name') ">
                            <el-col :span="3">
                                <span>{{ value }}</span>
                            </el-col>
                            <el-col :span="2" :offset="2">
                                <span v-if="data[field['db_name']] != null">{{data[field['db_name']][key]}}</span>
                            </el-col>
                        </el-row>
                    </div>
                </el-tab-pane>
            </el-tabs>
        </el-row>
    </div>
</template>

<script>
    import { Input, Col, Row, Button, Tabs, TabPane } from 'element-ui'

    export default {
        components: {
            'el-input': Input,
            'el-col': Col,
            'el-row': Row,
            'el-button': Button,
            'el-tabs': Tabs,
            'el-tab-pane': TabPane
        },
        props: ['data_user', 'data_list'],
        data() {
            return {
                loading: false,
                user: {
                    wechat: {
                        sex: ''
                    }
                },
                activeName: '',
                fields: [],
                list: this.data_list,
                data: ''
            }
        },
        methods: {
            getHealthFields() {
                axios.post('api/health/fields').then(response => {
                    this.fields = response.data.data
                });
            },
            getHealthDetail() {
                axios.post('api/health/getDetail', {health_id: this.list.id}).then(response => {
                    this.data = response.data.data
                    this.loading = false
                });
            }
        },
        watch: {
            data_user() {
                this.user = this.data_user
            },
            data_list() {
                this.list = this.data_list
                this.getHealthDetail()
                this.loading = true
            }
        },
        mounted() {
            this.getHealthFields()
        },
    }
</script>

<style>
    #header {
        margin-bottom: 30px;
    }
    #header-name {
        font-size: 20px;
    }
    #header-date {
        font-size: 15px;
        color: #a9a9a9;
    }
    .header-two {
        font-size: 13px;
    }
</style>
