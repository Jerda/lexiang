<template>
    <el-row>
        <el-col :span="7">
            <el-autocomplete
                    size="small"
                    v-model="nickname"
                    placeholder="请输入管理员微信名称"
                    valueKey="nickname"
                    :fetch-suggestions="searchWechatUser"
                    @select="autocompleteSelected"
                    :debounce='time'
                    :disabled="mode !== 'edit' ? false :'disabled'"
            ></el-autocomplete>
        </el-col>
        <el-col :span="2" style="margin-left: 5px">
            <img :src="wechat_user.avatar" alt="" id="avatar">
        </el-col>
        <el-col :span="4" style="margin-top: 4px; margin-left: 5px">
            <el-checkbox v-if="wechat_user.id" v-model="main">主管理员</el-checkbox>
        </el-col>
        <el-col :span="1" :offset="1">
            <el-button v-if="mode == 'edit'" size="small" icon="el-icon-delete" @click="del"></el-button>
            <el-button v-if="mode == 'add'" size="small" icon="el-icon-circle-plus-outline" @click="add"></el-button>
        </el-col>
    </el-row>
</template>

<script>
    import { Row, Col, Autocomplete, Button, Checkbox } from 'element-ui'

    export default {
        components: {
            'el-row': Row,
            'el-col': Col,
            'el-autocomplete': Autocomplete,
            'el-button': Button,
            'el-checkbox': Checkbox
        },
        props: ['admin'],
        data() {
            return {
                time: 700,
                mode: 'add',
                wechat_user: {
                    nickname: '',
                    avatar: '',
                    user_id: '',
                },
                nickname: '',
                main: 0,
                // user_id: '',
            }
        },
        methods: {
            searchWechatUser: function (nickname, callback) {
                if (nickname != this.wechat_user.nickname) { //修改输入框内容时，情况上一次查询并绑定数据
                    this.wechat_user = {};
                }

                if (nickname !== undefined && nickname.length > 0) {
                    axios.post('api/wechat/user/getUsersByNickname', {search: {nickname: nickname}}).then(response => {
                        callback(response.data.data);
                    });
                }
            },
            autocompleteSelected: function (user) {
                this.wechat_user = user;
            },
            add() {
                let main = this.main ? 1 : 0;
                if (this.wechat_user.user_id) {
                    this.$emit('add', {user_id: this.wechat_user.user_id, main: main})
                    this.nickname = ''
                    this.wechat_user = {
                        nickname: '',
                            avatar: '',
                            user_id: '',
                    }
                } else {
                    this.$message.error('请输入正确管理员微信名称');
                }
            },
            del() {
                this.$emit('close', this.wechat_user.user_id);
            }
        },
        mounted() {
            //参数有值表示是编辑企业
            if (this.admin) {
                this.mode = 'edit';
                this.main = this.admin.main == 1 ? true : false;
                this.wechat_user = this.admin.user.wechat;
                this.nickname = this.admin.user.wechat.nickname;
            }
        },
    }
</script>

<style>
    #avatar {
        width: 30px;
    }
</style>
