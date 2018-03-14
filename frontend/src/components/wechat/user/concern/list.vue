<template>
    <div v-show="isShow">
        <loading :show="loadings"></loading>
        <group class="ppp">
            <cell v-for="(user,index) in concern_users"
                  :title="user.concern_user.name" :key="index" class="change_height_">
                <img slot="icon" :src="user.concern_user.wechat.avatar" class="change_height_img">
                <div>
                    <x-button @click.native="toConcernUser(user.concern_user.id)" style="display:inline;margin-left:-20vw;height:5vh;line-height:25%;">查看</x-button>
                    <x-button @click.native="confirm_unConcern(user.concern_user.id)" style="display:inline;margin-top:0;height:5vh;line-height:25%;background:#ff3232;color:white;">取关</x-button>
                </div>
            </cell>
        </group>
        <x-button @click.native="sendConcern.show = true" style="margin-bottom: 2px">发起关注</x-button>
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
                        <x-button @click.native="sendConcernRequest(item.id)" style="height:5vh;line-height:25%;background:rgb(87,255,171);color:white;">关注</x-button>
                    </cell>
                </group>
                <x-button @click.native="sendConcern.show = false" style="width:94%;margin-top:2vh;">取消</x-button>
            </div>
        </popup>
    </div>
</template>

<script>
    import { Group, Cell, Loading, XButton, Search, Popup} from 'vux'

    export default {
        components: {
            Group, Cell, Loading, XButton, Search, Popup
        },
        props: ['show'],
        data() {
            return {
                concern_users: [],
                isShow: this.show,
                sendConcern: { show: false },
                search: {
                    auto_fixed: false,
                    data: '',
                    result: []
                },
                loadings: true
            }
        },
        methods: {
            getConcernUsers() {

                axios.post('api/user/getConcernUserList').then(response => {
                    this.concern_users = response.data.data
                    this.loadings = false
                }).catch(error => {
                    this.loadings = false
                })
            },
            unConcern(concern_user_id){
                axios.post('api/concern/cancel',{concern_user_id:concern_user_id}).then(response => {
                    this.$vux.alert.show({
                        content: response.data.message
                    })
                    this.getConcernUsers()
                }).catch(error => {
                    this.$vux.alert.show({
                        content: response.data.message
                    })
                })
            },
            confirm_unConcern(concern_user_id){
                let _this = this
                this.$vux.confirm.show({
                    title: '确定要对改用户取消关注？',
                    onConfirm() {
                        _this.unConcern(concern_user_id)
                    }
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
                this.$router.push('/health/' + user_id)
            },
        },
        mounted() {
            this.getConcernUsers();
        },
        watch: {
            show () {
                this.isShow = this.show
            },
        },
    }
</script>
