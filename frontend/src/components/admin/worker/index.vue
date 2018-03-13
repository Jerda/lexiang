<template>
    <el-tabs v-model="tab_name" type="border-card" @tab-click="changeTab">
        <el-tab-pane label="员工管理" name="first">
            <normal-worker ref="normal_worker"></normal-worker>
        </el-tab-pane>
        <el-tab-pane label="待审核企业" name="second">
            <span slot="label">待审核员工<el-badge is-dot class="mark"></el-badge></span>
            <examine-worker ref="apply_worker"></examine-worker>
        </el-tab-pane>
    </el-tabs>
</template>

<script>
    import NormalWorker from './normal.vue'
    import ExamineWorker from './examine.vue'
    import { Tabs, TabPane, Badge } from 'element-ui'

    export default {
        components: {
            'el-tabs': Tabs,
            'el-tab-pane': TabPane,
            'el-badge': Badge,
            NormalWorker,
            ExamineWorker
        },
        data() {
            return {
                tab_name: 'first',
                show: {

                }
            }
        },
        methods: {
            changeTab(val) {
                this.$refs.normal_worker.getWorkers(this.$route.params.enterprise_id);
                this.$refs.apply_worker.getWorkers(this.$route.params.enterprise_id);
            }
        }
    }
</script>
