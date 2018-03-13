<template>
    <el-container>
        <el-main>
            <el-card class="box-card">
                <el-form ref="form" :model="form" label-width="120px">
                    <!--<el-form-item label="公众号名称">
                        <el-input size="small" v-model="form.name"></el-input>
                    </el-form-item>
                    <el-form-item label="微信号">
                        <el-input size="small" v-model="form.wechat_id"></el-input>
                    </el-form-item>
                    <el-form-item label="原始ID">
                        <el-input size="small" v-model="form.init_wechat_id"></el-input>
                    </el-form-item>-->
                    <el-form-item label="AppID">
                        <el-input size="small" v-model="form.app_id"></el-input>
                    </el-form-item>
                    <el-form-item label="AppSecret">
                        <el-input size="small" v-model="form.app_secret"></el-input>
                    </el-form-item>
                    <!--<el-form-item label="API">
                        <el-input size="small" v-model="form.api"></el-input>
                    </el-form-item>-->
                    <el-form-item label="TOKEN">
                        <el-input size="small" v-model="form.token"></el-input>
                    </el-form-item>
                    <el-form-item label="EncodingAESKey">
                        <el-input size="small" v-model="form.encoding_aes_key"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="set" size="small">设置</el-button>
                        <el-button size="small">取消</el-button>
                    </el-form-item>
                </el-form>
            </el-card>
            <el-card>
                <el-upload
                        class="upload-demo"
                        action="api/wechat/config/uploadJS"
                        :limit="1"
                        name="wechat_js_file"
                        auto-upload
                        :file-list="files">
                    <el-button size="small" type="primary">点击上传</el-button>
                    <div v-if="wechatJSExists == false" slot="tip" class="el-upload__tip">请JS接口安全域名文件</div>
                    <div v-else slot="tip" class="el-upload__tip" style="color: green">JS接口安全域名文件已上传，无需再次上传</div>
                </el-upload>
            </el-card>
        </el-main>
    </el-container>
</template>
<script>
    import {
        Container, Main, Row, Col, Button, Input, Breadcrumb, BreadcrumbItem, Form, FormItem, Card, Upload

    } from 'element-ui';

    export default {
        components: {
            'el-container': Container,
            'el-main': Main,
            'el-col': Col,
            'el-row': Row,
            'el-button': Button,
            'el-input': Input,
            'el-breadcrumb': Breadcrumb,
            'el-breadcrumb-item': BreadcrumbItem,
            'el-form': Form,
            'el-form-item': FormItem,
            'el-card': Card,
            'el-upload': Upload
        },
        data() {
            return {
                form: {
                    // name: '',
                    // wechat_id: '',
                    // init_wechat_id: '',
                    app_id: '',
                    app_secret: '',
                    // api: '',
                    token: '',
                    encoding_aes_key: '',
                },
                files: [],
                wechatJSExists: false, //JS文件是否已经上传
            }
        },
        methods: {
            set() {
                axios.post('/api/wechat/config/set', this.form).then(response => {
                    this.$message(response.data.message);
                }).catch(error => {
                    this.$message.error(error.response.data.message);
                });
            },
            checkJS() {
                axios.post('api/wechat/config/checkJS').then(response => {
                    this.wechatJSExists = response.data.data.isExists;
                });
            }
        },
        mounted: function () {
            this.$nextTick(function () {
                axios.post('/api/wechat/config/get').then(response => {
                    if (response.data.data !== undefined) {
                        this.form = response.data.data
                    }
                }).catch(error => {
                    this.$message.error(error.response.data.message);
                })
                this.checkJS();
            });
        }
    }
</script>

<style scoped>
    .el-row {
        margin-top: 15px
    }

    .el-form-item {
        width: 95%
    }
    .el-card {
        width: 600px;
    }
</style>
