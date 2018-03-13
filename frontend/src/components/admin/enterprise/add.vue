<template>
    <div>
        <el-col v-loading="loading.enterprise"
                element-loading-text="请等待"
                element-loading-spinner="el-icon-loading"
                :span="12">
            <el-card>
                <div slot="header" class="clearfix">
                    <span class="title">注册企业</span>
                </div>
                <el-form>
                    <el-form-item label="企业名称" :label-width="formLabelWidth">
                        <el-col :span="22">
                            <el-input size="small" v-model="enterprise.name"></el-input>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="法人" :label-width="formLabelWidth">
                        <el-col :span="9">
                            <el-input size="small" v-model="enterprise.legal_person"></el-input>
                        </el-col>
                        <el-col class="line" :span="3" :offset="1">联系人</el-col>
                        <el-col :span="9">
                            <el-input size="small" v-model="enterprise.linker"></el-input>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="手机号" :label-width="formLabelWidth">
                        <el-col :span="9">
                            <el-input size="small" v-model="enterprise.linker_mobile"></el-input>
                        </el-col>
                        <el-col class="line" :span="3" :offset="1">邮箱</el-col>
                        <el-col :span="9">
                            <el-input size="small" v-model="enterprise.linker_email"></el-input>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="行业类型" :label-width="formLabelWidth">
                        <el-col :span="9">
                            <enterprise-type-selector :enterprise_type_id="enterprise.enterprise_type_id"
                                                      @change="(val) => {enterprise.enterprise_type_id = val}"></enterprise-type-selector>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="企业地址" :label-width="formLabelWidth">
                        <el-col :span="9">
                            <el-cascader
                                    size="small"
                                    :options="options"
                                    v-model="addressSelectedOptions"
                                    clearable
                                    style="width: 100%"
                            >
                            </el-cascader>
                        </el-col>
                    </el-form-item>
                    <el-form-item label="详情地址" :label-width="formLabelWidth">
                        <el-col :span="22">
                            <el-input size="small" v-model="enterprise.address"></el-input>
                        </el-col>
                    </el-form-item>
                    <el-form-item :label-width="formLabelWidth">
                        <el-col style="width: 70px">
                            <el-button size="small" type="primary" @click="register">注册</el-button>
                        </el-col>
                        <el-col :span="2">
                            <el-button size="small" @click="$router.go(-1)">返回</el-button>
                        </el-col>
                    </el-form-item>
                </el-form>
            </el-card>
        </el-col>
        <el-col v-loading="loading.admin"
                element-loading-text="请等待"
                element-loading-spinner="el-icon-loading"
                :span="11" style="margin-left: 10px">
            <el-card>
                <div slot="header" class="clearfix">
                    <span class="title">企业管理员</span>
                </div>
                <span class="form-warning">企业管理员必须先关注公众号</span>
                <div v-for="admin in admins">
                    <add-admin :admin="admin" @close="delAdmin"></add-admin>
                </div>
                <el-row>
                    <span style="font-size: 13px">添加管理员</span>
                    <add-admin @add="addAdmin"></add-admin>
                </el-row>
            </el-card>
        </el-col>
    </div>

</template>

<script>
    import {Form, FormItem, Col, Row, Input, Select, Option, Card, Button,
        MessageBox, Cascader, Autocomplete} from 'element-ui'
    import { regionData } from 'element-china-area-data'
    import AddAdmin from './add_admin'
    import EnterpriseTypeSelector from './enterprise_type_selector'

    export default {
        components: {
            MessageBox,
            'el-form': Form,
            'el-form-item': FormItem,
            'el-col': Col,
            'el-row': Row,
            'el-input': Input,
            'el-select': Select,
            'el-option': Option,
            'el-card': Card,
            'el-button': Button,
            'el-cascader': Cascader,
            'el-autocomplete': Autocomplete,
            AddAdmin,
            EnterpriseTypeSelector
        },
        data() {
            return {
                loading:{
                    enterprise: false,
                    admin: false
                },
                formLabelWidth: '120px',
                options: regionData,
                addressSelectedOptions: [],
                enterprise: {
                    id: '', name: '', legal_person: '', linker: '', linker_mobile: '', linker_email: '',
                    enterprise_type_id: ''
                },
                admins: {},
            }
        },
        methods: {
            getEnterprise(enterprise_id) {
                this.loading.enterprise = true
                this.loading.admin = true
                axios.post('api/enterprise/get', {enterprise_id: enterprise_id}).then(response => {
                    this.enterprise = response.data.data
                    let res = this.formatToCodeForAddress(this.enterprise.province, this.enterprise.city, this.enterprise.district)
                    this.addressSelectedOptions = res
                    this.getAdmins()
                    this.loading.enterprise = false
                })
            },
            /**
             * 获取企业管理员
             */
            getAdmins() {
                this.admins = []
                axios.post('api/enterprise_admin/getAll', {enterprise_id: this.enterprise.id}).then(response => {
                    this.admins = response.data.data
                    this.loading.admin = false
                })
            },
            register() {
                this.loading.enterprise = true
                this.loading.admin = true
                //将地址转换为后台所需格式
                let res = this.formatToTextForAddress(this.addressSelectedOptions);
                this.enterprise.province = res.province
                this.enterprise.city = res.city
                this.enterprise.district = res.district

                axios.post('api/enterprise/register', this.enterprise).then(response => {
                    this.$message.success(response.data.message)
                    this.$router.push({name: 'enterprise_index'})
                    this.loading.enterprise = false
                    this.loading.admin = false
                }).catch((error) => {
                    this.$message.error(error.response.data.message)
                    this.loading.enterprise = false
                    this.loading.admin = false
                });
            },
            addAdmin(data) {
                if (!this.enterprise.id) {
                    this.$message.error('请先注册企业后再添加管理员')
                    return
                }
                this.loading.admin = true
                axios.post('api/enterprise_admin/add', {user_id: data.user_id, main: data.main,enterprise_id: this.enterprise.id})
                    .then(response => {
                    this.$message.success(response.data.message)
                    this.getAdmins()
                    this.loading.admin = false
                    }).catch((error) => {
                    this.$message.error(error.response.data.message)
                    this.loading.admin = false
                })
            },
            delAdmin(user_id) {
                MessageBox.confirm('确认要删除管理员？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post('api/enterprise_admin/del', {user_id: user_id, enterprise_id: this.enterprise.id})
                        .then(response => {
                            this.$message.success(response.data.message)
                            this.getAdmins()
                        });
                }).catch(() => {})
            },
        },
        mounted() {
            if (this.$route.params.id) {
                this.getEnterprise(this.$route.params.id)
            }
        },
    }
</script>

<style scoped>
    .title {
        font-size: 20px
    }
    .form-warning {
        color: red;
        font-size: 13px
    }
</style>


