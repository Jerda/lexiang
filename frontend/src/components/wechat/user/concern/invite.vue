<template>
    <div v-show="isShow">

        <group class="ppp">
            <cell v-for="(user,index) in list"
                  :title="user.invite_user.name"
                  :key="index" class="change_height_">
                <img slot="icon" :src="user.invite_user.wechat.avatar" class="change_height_img">
                <div>
                    <x-button @click.native="toConcernUser(user.invite_user.id)" style="display:inline;margin-left:-20vw;height:5vh;line-height:25%;">查看</x-button>
                    <x-button @click.native="agree(user.id)" style="display:inline;margin-top:0;height:5vh;line-height:25%;background:#57ffab;">同意</x-button>
                </div>
            </cell>
        </group>
        <x-button @click.native="sendConcern.show = true" style="margin-bottom: 2px">发起关注</x-button>

        <popup v-model="user_info.show" height="100%">
            <group v-if="user_info.show" class="change_margin_top">
                <cell title="姓名" v-model="user_info.user.name" class="change_list"></cell>
                <cell title="手机号" v-model="user_info.user.mobile" class="change_list"></cell>
                <cell title="性别" class="change_list">
                    <div v-if="user_info.user.wechat.sex == 1" >男</div>
                    <div v-else>女</div>
                </cell>
                <cell title="出生日期" v-model="user_info.user.birthday" class="change_list"></cell>
                <cell title="年龄" v-model="user_info.user.age" class="change_list"></cell>
                <cell title="邮箱" v-model="user_info.user.email" class="change_list"></cell>
            </group>
            <x-button @click.native=" user_info.show = false" style="width:94%;margin-top:3vh;">关闭</x-button>
        </popup>

        <popup v-model="sendConcern.show" height="100%" style="background:white;">
            <div>
                <search
                        v-model="search.data"
                        position="absolute"
                        auto-scroll-to-top
                        :auto-fixed='search.auto_fixed'
                        placeholder="搜索用户(手机号)"
                        @on-submit="searchUser"
                        @on-cancel="search.result = []"
                        ref="search" class="change_margin"></search>
                <group>
                    <cell v-for="(item,args) in search.result" :title="item.name" :key="args" class="change_height_">
                        <x-button style="height:5vh;line-height:25%;background:rgb(87,255,171);color:white;"
                                  @click.native="sendConcernRequest(item.id)">
                            关注
                        </x-button>
                    </cell>
                </group>
                <x-button @click.native="sendConcern.show = false" style="width:94%;margin-top:2vh;">取消</x-button>
            </div>
        </popup>
    </div>
</template>

<script>
    import { XButton, Popup, Group, Cell, Search  } from 'vux'

    export default {
        components: {
            XButton, Popup, Group, Cell, Search
        },
        props: ['show'],
        data() {
            return {
                isShow: this.show,
                sendConcern: { show: false },
                list: [],
                search: {
                    auto_fixed: false,
                    data: '',
                    result: []
                },
                user_info: {
                    show: false,
                    user: {sex: '', name: '', wechat: { sex: '' }}
                },
                loadings: false
            }
        },
        methods: {
            getInviteList() {
                axios.post('api/concern/getInviteList').then(response => {
                    this.list = response.data.data
                })
            },
            searchUser(mobile) {
                axios.post('api/user/getUserByMobile', {mobile: mobile}).then(response => {
                    this.search.result = response.data.data
                }).catch(error => {

                })
            },
            sendConcernRequest(user_id) {
                axios.post('api/concern/sendRequest', {invitees_user_id: user_id}).then(response => {
                    this.$vux.alert.show({
                        content: response.data.message
                    })
                }).catch(error => {
                    this.$vux.alert.show({
                        content: error.response.data.message
                    })
                })
            },
            toConcernUser(user_id) {
                this.user_info.show = true
                axios.post('api/user/info', {user_id: user_id}).then(response => {
                    this.user_info.user = response.data.data
                })
            },
            agree(id) {
                axios.post('api/concern/agreeRequest', {id: id}).then(response => {
                    this.$vux.alert.show({
                        content: response.data.message
                    })
                    this.getInviteList()
                    this.$emit('changed')
                })
            }

        },
        mounted() {
            this.getInviteList()
        },
        watch: {
            show () {
                this.isShow = this.show
            },
        },
    }
</script>
<style>
    .change_list{
        height:5vh;
    }
    .change_margin_top{
        margin-top:-20px
    }
</style>