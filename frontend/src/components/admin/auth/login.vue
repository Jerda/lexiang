<template>
    <el-container>
        <el-main>
            <div id="particle"></div>
            <top></top>
            <el-row type="flex" justify="center" id="row2">
                <el-card class="box-card">
                    <div class="title">
                        <span>登  录</span>
                    </div>
                    <el-form :model="form">
                        <el-form-item>
                            <el-input v-model="form.username" prefix-icon="el-icon-fa-user-o"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-input type="password" v-model="form.password" prefix-icon="el-icon-fa-lock"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-col :span="12">
                                <el-input v-model="form.captcha" placeholder="输入验证码"></el-input>
                            </el-col>
                            <el-col :span="8" :offset="2">
                                <img :src="captcha_src"
                                     alt=""
                                     width="120"
                                     height="36"
                                     ref="captcha"
                                     data-captcha-config="default"
                                     @click="refresh">
                            </el-col>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="login">登 录</el-button>
                        </el-form-item>
                    </el-form>
                </el-card>
            </el-row>
        </el-main>
        <el-footer style="height: 30px;">
            <bottom></bottom>
        </el-footer>
    </el-container>
</template>

<script>
    import JWTToken from '@/components/helpers/jwt'
    import top from './top.vue'
    import bottom from './bottom.vue'
    import { Container, Main, Row, Col, Button, Input, Form, FormItem, Footer, Header, Card } from 'element-ui';
    import Particleground from 'Particleground.js'

    const Particle = Particleground.particle

    export default {
        components: {
            'el-container': Container,
            'el-main': Main,
            'el-col': Col,
            'el-row': Row,
            'el-button': Button,
            'el-input': Input,
            'el-form': Form,
            'el-form-item': FormItem,
            'el-header': Header,
            'el-footer': Footer,
            'el-card': Card,
            top,
            bottom
        },
        data() {
            return {
                form: {
                    username: '',
                    password: '',
                    captcha: ''
                },
               captcha_src: '',
            }
        },
        methods: {
            /*
             验证码点击刷新
             */
            refresh: function() {
                axios.post('api/captcha/default/?' + Math.random()).then(response => {
                    console.log(response)
                    this.captcha_src = response.data
                })
                /*this.form.captcha = '';  //清除验证码内容
                let config = this.$refs.captcha.attributes['data-captcha-config'].value
                let url_refresh = 'http://120.78.56.75/captcha/' + config + '/?' + Math.random()
                this.captcha_src = url_refresh;*/
            },
            /*
             登录
             */
            login: function() {
                this.$store.dispatch('loginRequest', this.form).then(response => {
                    this.$message.success('登录成功!')
                    let that = this
                    setTimeout(function () {
                        that.$router.push({name:'index'})
                    }, 2000)
                }).catch(error => {
                    this.$message.error(error.response.data.message)
                });
            },
        },
        mounted: function() {
            let x = new Particle('#particle', {
//                color: ['#ce3f33'],
                dotColor: '#ff0000',
                lineColor: '#ff0000',
                maxSpeed: 0.5,
                minSpeed: 0.2,
            })
            this.refresh()

        }
    }
</script>

<style scoped>
    #particle {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
    #row2 {

    }
    .el-button {
        width: 100%;
    }
    .box-card {
        width: 340px;
        height: 340px;
        margin-top: 20px;
    }
    .title {
        margin-bottom: 20px;
        margin-left: 125px;
        font-size: 20px;
    }
    .el-footer {
        /*background: #f3f7ff;*/
    }
</style>
