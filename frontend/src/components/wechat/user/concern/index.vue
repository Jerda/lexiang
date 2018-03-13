<template>
    <div>
        <x-header title="我的关注"></x-header>
        <tab>
            <tab-item selected @on-item-click="list">关注的人</tab-item>
            <tab-item @on-item-click="showInvite">邀请</tab-item>
        </tab>
        <list :show="show.list" ref="list"></list>
        <invite :show="show.invite" @changed="refreshList"></invite>

        <Nav :familyActive="true"></Nav>
    </div>
</template>

<script>
    import Nav from '../../common/user_nav'
    import List from './list'
    import Invite from './invite'
    import { Group, Cell, XHeader, Popup, XButton, Search, Tab, TabItem} from 'vux'

    export default {
        components: {
            List, Invite,Group, Cell, XHeader, Popup, XButton, Search, Nav, Tab, TabItem
        },
        data() {
            return {
                show: {
                    list: true,
                    invite: false,
                },
            }
        },
        methods: {
            toConcernUser(user_id) {
                this.$router.push('/health/' + user_id)
            },
            showInvite() {
                this.show.list = false
                this.show.invite = true

            },
            list() {
                this.show.list = true
                this.show.invite = false
            },
            refreshList() {
                this.$refs.list.getConcernUsers()
            }
        },
    }
</script>

<style>
    .change_height_img{
        height:6vh;
        margin-right:5vw;
        margin-top:6px;
    }
    .change_height_{
        height:5vh;
    }
    .ppp{
        height:81vh;
        overflow:auto;
        margin-top:-20px;
        background:white;
    }
    .change_btn_height{
        height:5vh;
        line-height:25%;
        background:#ff3232;
        color:white;
    }
    .change_background{
        background:white;
    }
    
</style>
