<template>
    <el-card class="box-card">
        <el-form ref="form" :model="form" label-width="140px">
            <el-form-item label="选择短信服务商">
                <el-select v-model="form.service_provider" @change="selectServiceProvider"
                           @clear="clearServiceProvider" clearable placeholder="请选择">
                    <el-option
                            v-for="item in service_providers"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item v-for="item in rules" :label="item">
                <el-input></el-input>
            </el-form-item>
            <div id="rules"></div>
            <el-form-item>
                <el-button type="primary" @click="set">设置</el-button>
                <el-button>取消</el-button>
            </el-form-item>
        </el-form>
    </el-card>
</template>

<script>
    import {Form, FormItem, Button, Select, Option, Input, Card} from 'element-ui'

    export default {
        components: {
            'el-form': Form,
            'el-form-item': FormItem,
            'el-button': Button,
            'el-select': Select,
            'el-option': Option,
            'el-input': Input,
            'el-card': Card
        },
        data() {
            return {
                form: {
                    service_provider: ''
                },
                service_providers: [],
                rules: [],
            }
        },
        methods: {
            set: function () {

            },
            /**
             * 获取服务商
             */
            getServiceProvider: function () {
                axios.post('/api/system/getServiceProviders').then(response => {
                    this.service_providers = this.formatSelectOptions(response.data.data);
                });
            },
            /**
             * 选择服务商触发事件
             */
            selectServiceProvider: function (val) {
                this.getRuleByServiceProvider(val);
            },
            /**
             * 通过服务商获取所需填入信息(规则)
             * @param service_provider
             */
            getRuleByServiceProvider: function (service_provider) {
                axios.post('/api/system/getRuleByServiceProvider', {service_provider: service_provider}).then(response => {
                    this.rules = response.data.data;
                });
            },
            /**
             * 清空服务商select触发事件
             */
            clearServiceProvider: function() {
                this.rules = [];
            }
        },
        mounted: function () {
            this.getServiceProvider();
        }
    }
</script>

<style scoped>
    .el-card {
        width: 500px;
    }
</style>