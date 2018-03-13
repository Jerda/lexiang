<template>
    <div>
        <el-tabs v-model="tabs.defaultSelected" type="border-card">
            <el-tab-pane label="角色管理" name="role">
                <role></role>
            </el-tab-pane>
            <el-tab-pane label="权限管理" name="permission">
                <permission></permission>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
    import { Tabs, TabPane } from 'element-ui'
    import role from './role/index'
    import permission from './permission/index'

    export default {
        components: {
            'el-tabs': Tabs,
            'el-tab-pane': TabPane,
            role,
            permission
        },
        data() {
            return {
                tabs: {
                    defaultSelected: 'role', //tabs默认选中
                }
            }
        }
    }
</script>
