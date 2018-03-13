<template>
    <el-tabs v-model="tab_name" type="border-card" @tab-click="changeTab">
        <el-tab-pane label="企业管理" name="first">
            <normal-enterprise ref="normal_enterprise"></normal-enterprise>
        </el-tab-pane>
        <el-tab-pane label="待审核企业" name="second">
            <span slot="label">待审核企业<el-badge is-dot class="mark"></el-badge></span>
            <examine-enterprise ref="apply_enterprise"></examine-enterprise>
        </el-tab-pane>
    </el-tabs>
</template>

<script>
    import NormalEnterprise from './normal.vue'
    import ExamineEnterprise from './examine.vue'
    import { Tabs, TabPane, Badge } from 'element-ui'

    export default {
        components: {
            'el-tabs': Tabs,
            'el-tab-pane': TabPane,
            'el-badge': Badge,
            NormalEnterprise,
            ExamineEnterprise
        },
        data() {
            return {
                tab_name: 'first',
                show: {}
            }
        },
        methods: {
            changeTab(val) {
                this.$refs.normal_enterprise.getEnterprises();
                this.$refs.apply_enterprise.getEnterprise();
            }
        }
    }
</script>
