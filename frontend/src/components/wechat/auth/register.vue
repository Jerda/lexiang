<template>
    <div>
        <x-header title="用户注册"></x-header>
        <group class="change_margin_top_">
            <x-input title="姓名" v-model="form.name"></x-input>
            <datetime title="出生日期" class="change_heights" v-model="form.birthday"></datetime>
            <x-input title="邮箱" v-model="form.email"></x-input>
            <x-input title="手机号" v-model="form.mobile"></x-input>
            <x-input title="发送验证码" class="weui-vcode" v-model="verify">
                <x-button slot="right" type="primary" mini @click.native="getVerify(form.mobile,$event)" :style="is_disabled?disabled_style:''">发送验证码{{show_count}}</x-button>
            </x-input>
        </group>
        <group>
            <x-button type="primary" @click.native="register" style="width: 94%">注册</x-button>
            <!--<x-button type="primary" @click.native="judeg" style="width: 94%">注册</x-button>-->
        </group>

    </div>
</template>

<script>
    import Store from '@/components/store/index'
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
                is_verify:'',
                show: true,
                count: '',
                timer: null,
                disabled_style:'background:rgba(11, 178, 12, 0.45)',
                is_disabled:false,
                show_count: ''
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
                    let _this = this
                    this.$vux.alert.show({
                        content:response.data.message
                    })
                    Store.dispatch('registerSuccess').then(response => {
                        setTimeout(function() {
                            _this.$router.push('user_index')
                        }, 2000)
                    })
                }).catch(error => {
                    this.$vux.alert.show({
                        content:error.response.data.message
                    })
                })
            },
            getCode(dom){
                const TIME_COUNT = 30;
                if (!this.timer) {
                    this.count = TIME_COUNT;
                    this.show = false;
                    this.timer = setInterval(() => {
                        if (this.count > 0 && this.count <= TIME_COUNT) {
                            this.count--;
                            this.show_count = '('+this.count+'s)'
                        } else {
                            this.show = true;
                            dom.removeAttribute('disabled');
                            this.is_disabled = false;
                            clearInterval(this.timer);
                            this.timer = null;
                        }
                    }, 1000)
                }
            },
            getVerify(mobile,event){
                this.getCode(event.target)
                this.is_disabled = true
                event.target.setAttribute('disabled','disabled')
                if(mobile != ''){
                    axios.post('api/sms/sendSMSCode', {mobile:mobile}).then(response => {
                        // console.log(response.data.data)
                        this.is_verify = response.data.data;
                    }).catch(error => {
                        this.$vux.alert.show({
                            content:error.response.data.message
                        })
                    })
                }else{
                    this.$vux.alert.show({
                        content:'请输入手机号！'
                    })
                }
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
