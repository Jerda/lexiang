<template>
    <div>
        <Header :title="top_title" :isBack="true"></Header>
        <group class="change_margin_top">
            <x-input title="姓名" class="change_height" v-model="user.name"></x-input>
            <x-input title="手机号" class="change_height" v-model="user.mobile"></x-input>
            <datetime title="出生日期" class="change_heights" v-model="user.birthday"></datetime>
            <selector title="性别" :options="options.sex" class="change_height_" v-model="user.sex"></selector>
            <x-input title="邮箱" class="change_height" v-model="user.email"></x-input>
        </group>
            <x-button style="width:94%;margin-top:2vh;" @click.native="saveMes"><span class="sure">确 定</span></x-button>
    </div>
</template>

<script>
    import Header from '../common/Header'
    import { Group, XButton, XInput, Datetime, Selector } from 'vux'

    export default {
        components: {
            Header,
            Group,
            XButton,
            XInput,
            Datetime,
            Selector
        },
        data() {
            return {
                top_title: '修改信息',
                options: {
                    sex: [{ key: 1, value: '男' }, { key: 2, value: '女' }]
                },
                user:{
                    name:'',
                    mobile:'',
                    birthday:'',
                    sex:'',
                    email:''
                },
                age:'',
                finall_user:{}

            }
        },
        methods:{
            saveMes(){
                if(this.user.name != ''&&this.user.mobile != ''&&this.user.birthday != ''&&this.user.sex != ''&&this.user.email != ''&&this.user.age!== ''){
                    this.finall_user['name'] = this.user.name
                    this.finall_user['mobile'] = this.user.mobile
                    this.finall_user['birthday'] = this.user.birthday
                    this.finall_user['sex'] = this.user.sex
                    this.finall_user['email'] = this.user.email
                    axios.post('api/user/modify', this.finall_user).then(response => {
                        this.$vux.alert.show({
                            content: response.data.message
                        })
                        this.$router.push('/user_info')
                    }).catch(error => {
                        this.$vux.alert.show({
                            content: error.response.data.message
                        })
                    })
                }else{
                    this.$vux.alert.show({
                        content: '请填写完整的数据！'
                    })
                }
            },
            getInfo() {
                axios.post('api/user/info').then(response => {
                    this.user = response.data.data
                    this.age = response.data.data.age
                }).catch(error => {

                })
            }
        },
        mounted() {
            this.getInfo()
        }
    }
</script>

<style>
    .sure{
        color:black;
    }
    .change_height{
        height:5vh;
    }
    .change_height_{
        height:10vh;
    }
    .change_heights{
        height:5vh;
        text-decoration:none
    }
    .change_margin_top{
        margin-top:-20px;
    }
</style>
