<template>
    <el-tabs type="border-card">
        <el-tab-pane label="基本设置">
            <Set></Set>
        </el-tab-pane>
        <el-tab-pane label="模板管理">
            <sms-template></sms-template>
        </el-tab-pane>
    </el-tabs>
</template>

<script>
    import {Tabs, TabPane, Breadcrumb, BreadcrumbItem} from 'element-ui'
    import Set from './set.vue'
    import SmsTemplate from './sms_template.vue'

    export default {
        components: {
            'el-tabs': Tabs,
            'el-tab-pane': TabPane,
            'el-breadcrumb': Breadcrumb,
            'el-breadcrumb-item': BreadcrumbItem,
            Set,
            SmsTemplate
        }
    }
</script>

<style>
    .el-tabs {
        height: 100%;
    }
</style>