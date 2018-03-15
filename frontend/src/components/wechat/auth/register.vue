<template>
    <div>
        <x-header title="用户注册"></x-header>
        <group class="change_margin_top_">
            <x-input title="姓名" v-model="form.name"></x-input>
            <datetime title="出生日期" class="change_heights" v-model="form.birthday"></datetime>
            <x-input title="邮箱" v-model="form.email"></x-input>
            <x-input title="手机号" v-model="form.mobile"></x-input>
            <x-input title="发送验证码" class="weui-vcode" v-model="verify">
                <x-button slot="right" type="primary" mini @click.native="getVerify(form.mobile)">发送验证码</x-button>
            </x-input>
        </group>
        <group>
            <x-button type="primary" @click.native="judeg" style="width: 94%">注册</x-button>
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
                    name: '', mobile: '', birthday: '',sex:'', email:''
                },
                verify:'',
                is_verify:''
            }
        },
        methods: {
            judeg() {
                if(this.form.name.trim() != ''&& this.form.mobile.trim() != ''&&this.form.birthday.trim() != ''&&this.form.email != ''){
                    if(this.is_verify == this.verify){
                        this.register();
                    }else{
                        this.$vux.alert.show({
                            content:'短信验证失败，请重试！'
                        })
                    }
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
            },
            getVerify(mobile){
                axios.post('api/sms/sendSMSCode', {mobile:mobile}).then(response => {
                    console.log(response.data.data)
                    this.is_verify = response.data.data;
                }).catch(error => {
                    this.$vux.alert.show({
                        content:response.data.message
                    })
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
