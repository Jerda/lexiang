<template>
    <div>
        <Header :title="top_title" :isBack="true"></Header>
        <loading :show="loadings"></loading>
        <div v-if="!loadings">
            <group class="change_margin_top">
                <cell title="姓名" v-model="user.name" class="change_list"></cell>
                <cell title="手机号" v-model="user.mobile" class="change_list"></cell>
                <cell title="性别" class="change_list">
                    <div v-if="user.wechat.sex == 1" >男</div>
                    <div v-else>女</div>
                </cell>
                <cell title="出生日期" v-model="user.birthday" class="change_list"></cell>
                <cell title="年龄" v-model="user.age" class="change_list"></cell>
                <cell title="邮箱" v-model="user.email" class="change_list"></cell>
            </group>
                <x-button link="/edit_info" class="weui-btn weui-btn_default" style="width:94%;margin-top:2vh;" :disabled="is_admin?'disabled':''"><span class="change_color">修改信息</span></x-button>
            <toast v-model="show" :type="type" :is-show-mask="true">{{text}}</toast>
        </div>
    </div>
</template>

<script>
    import Header from '../common/Header'
    import EditInfo from './edit_info'
    import { Toast, XInput, Group, Selector, XButton, Datetime, Cell, Loading} from 'vux'

    export default {
        components: {
            Toast, Header, Group, XInput, XButton, Selector, Datetime, Cell, Loading
        },
        data() {
            return {
                top_title:'基本资料修改',
                show: false,
                alert_title:'',
                text:'',
                type:'',
                options:[
                    '女','男'
                ],
                user: {
                    birthday: '',
                    wechat: {
                        sex: 1
                    }
                },
                loadings:true,
                is_admin:true
            }
        },
        methods: {
            getInfo() {
                axios.post('api/user/info').then(response => {
                    this.user = response.data.data;
                    this.loadings = false
                }).catch(error => {
                    this.loadings = false

                })
            },
            getAdmin() {
                axios.post('api/user/isAdmin').then(response => {
                    if(response.data.data.id){
                        this.is_admin = false
                    }
                }).catch(error => {

                })
            }
        },
        mounted() {
            this.getInfo()
            this.getAdmin()
        }
    }
</script>

<style>
    .change_color{
        color:black;
    }
    .weui-btn{
        width:94%;
    }
    .change_list{
        height:5vh;
    }
    .change_margin_top{
        margin-top:-20px;
    }
</style>
