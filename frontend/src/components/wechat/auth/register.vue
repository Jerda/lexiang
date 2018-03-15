<template>
    <div>
        <x-header title="用户注册"></x-header>
        <group class="change_margin_top_">
            <x-input title="姓名" v-model="form.name"></x-input>
            <selector title="性别" :options="options.sex" v-model="form.sex"></selector>
            <datetime title="出生日期" class="change_heights" v-model="form.birthday"></datetime>
            <x-input title="手机号" v-model="form.mobile"></x-input>
            <x-input title="发送验证码" class="weui-vcode">
                <x-button slot="right" type="primary" mini>发送验证码</x-button>
            </x-input>
            <!--<x-input title="加入企业">
                <x-button slot="right" typp="primary" mini @click.native="showSearchEnterprise = true">查询企业</x-button>
            </x-input>-->
        </group>
        <group>
            <x-button type="primary" @click.native="judeg">注册</x-button>
        </group>

    </div>
</template>

<script>
    import { XHeader, Group, XInput, XButton, Selector, Datetime } from 'vux'

    export default {
        components: {
            XHeader, Group, XInput, XButton, Selector, Datetime
        },
        data() {
            return {
                showSearchEnterprise: false,
                options: {
                    sex:[
                        {key: 0, value: '女'},
                        {key: 1, value: '男'}
                    ]
                },
                form: {
                    name: '', mobile: '', birthday: '',sex:''
                }
            }
        },
        methods: {
            judeg() {
                if(this.form.name.trim() != ''&& this.form.mobile.trim() != ''&&this.form.birthday.trim() != ''&&this.form.sex != ''){
                    this.register();
                }else{
                    this.$vux.alert.show({
                        content:'请填写完整的数据！'
                    })
                }
            },
            register() {
                axios.post('api/user/registerWechat', this.form).then(response => {
                    this.$vux.alert.show({
                        content:response.data.message
                    })
                }).catch(error => {

                })
            }
        }
    }
</script>

<style>
    .change_heights{
        height:4vh;
        text-decoration:none;
    }
    .change_margin_top_{
        margin-top:-20px;
    }
</style>
