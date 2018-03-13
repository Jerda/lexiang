<template>
    <div>
        <el-dialog
                :visible.sync="dialogShow"
                width="190px">
            <div id="name" v-loading="data_loading">
                <el-row v-if="concern_users.length === 0">
                    <span>该用户暂无关注人</span>
                </el-row>
                <el-row v-else v-for="(user,index) in concern_users" :key="index">
                    <el-button size="small" @click="showUserDetail(user.concern_user.id)">{{ user.concern_user.name }}</el-button>
                </el-row>
            </div>
        </el-dialog>

        <UserDetail :show="userDetail.show"
                    :data="userDetail.data"
                    @close="userDetail.show = false"></UserDetail>
    </div>
</template>

<script>
    import { Dialog, Button, Row } from 'element-ui'
    import UserDetail from "./detail.vue"

    export default {
        components: {
            UserDetail,
            'el-dialog': Dialog,
            'el-button': Button,
            'el-row': Row
        },
        props: ['show', 'data_user_id'],
        data() {
            return {
                data_loading: true,
                dialogShow: false,
                user_id: '',
                concern_users: [],
                userDetail: {
                    data: '',
                    show: false,
                },
            }
        },
        methods: {
            getConcernUserList() {
                axios.post('api/user/getConcernUserList', {user_id: this.user_id}).then(response => {
                    this.concern_users = response.data.data
                    this.data_loading = false
                })
            },
            showUserDetail(user_id) {
                axios.post('api/user/get', {user_id: user_id}).then(response => {
                    this.userDetail.data = response.data.data
                    this.userDetail.show = true
                })
            }
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
                    this.user_id = this.data_user_id
                    this.getConcernUserList()
                }
            },
        }
    }
</script>

<style scoped>
    #name {
        margin-left: 10px;
        margin-top: -20px;
    }
</style>
