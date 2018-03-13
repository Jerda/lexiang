<template>
    <div>
        <el-col style="width: 200px">
            <el-card class="box-card">
                <div slot="header" class="clearfix">
                    <span>健康报表历史</span>
                </div>
                <div id="test" style="overflow-y: scroll"
                     v-loading="loading.list"
                     element-loading-text="请等待"
                     element-loading-spinner="el-icon-loading">
                    <span v-if="lists.length == 0" id="no_data">无健康数据</span>
                    <div id="history-div" v-for="list in lists" class="text item">
                        <el-button id="history-button" size="small" @click="selectList(list)">
                            {{ list.created_at }}
                        </el-button>
                    </div>
                </div>
            </el-card>
        </el-col>
        <el-col :span="19" id="report">
            <el-card class="box-card">
                <report-detail :data_user="user" :data_list="list"></report-detail>
            </el-card>
        </el-col>
    </div>
</template>

<script>
    import ReportDetail from './report_detail.vue'
    import { Col, Card, Button } from 'element-ui'

    export default {
        components: {
            'el-col': Col,
            'el-card': Card,
            'el-button': Button,
            ReportDetail
        },
        data() {
            return {
                loading: {
                    list: true,
                },
                height: 0,
                user_id: '',
                user: {},
                lists: [],
                list: {}
            }
        },
        methods: {
            //健康报表历史Div高度设定
            healthListDivHeight: function() {
                this.height = document.body.clientHeight - 220;
                $('#test').css('height', this.height)
            },
            getUser: function() {
                axios.post('api/user/get', {user_id: this.user_id}).then(response => {
                    this.user = response.data.data
                });
            },
            getUserHealthList() {
                axios.post('api/health/userList', {user_id: this.user_id}).then(response => {
                    this.lists = response.data.data
                    this.loading.list = false
                });
            },
            selectList(list) {
                this.list = list
            },
        },
        mounted: function() {
            this.healthListDivHeight()
            this.user_id = this.$route.params.user_id
            this.getUser()
            this.getUserHealthList()
        }
    }
</script>

<style scoped>
    #history-div {
        margin-top: 4px;
    }

    #history-button {
        width: 150px;
    }

    #report {
        margin-left: 10px
    }
    #no_data {
        color: red;
        font-size: 12px;
    }
</style>
