<template>
    <div v-show="isShow">

        <group class="ppp">
            <cell v-for="(user,index) in list"
                  :title="user.user.name"
                  :key="index" class="change_height_">
                <img slot="icon" :src="user.user.wechat.avatar" class="change_height_img">
                <div>
                    <x-button @click.native="toConcernUser(user.user.id)" style="display:inline;margin-left:-20vw;height:5vh;line-height:25%;">查看</x-button>
                    <x-button @click.native="agree(user.id)" style="display:inline;margin-top:0;height:5vh;line-height:25%;background:#57ffab;">同意</x-button>
                </div>
            </cell>
        </group>
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
                user_info: {
                    show: false,
                    user: {sex: '', name: '', wechat: { sex: '' }}
                },
                loadings: false
            }
        },
        methods: {
            getInviteList() {
                axios.post('api/concern/getInviteeList').then(response => {
                    this.list = response.data.data
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
<style scoped>
    .change_list{
        height:5vh;
    }
    .change_margin_top{
        margin-top:-20px
    }
</style>
