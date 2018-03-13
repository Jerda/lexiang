<template>
    <el-menu
            router
            default-active="2"
            class="el-menu-vertical-demo"
            background-color="#383e49"
            text-color="#fff"
            active-text-color="#ffd04b">
        <div v-show="show == 'current'">
            <el-menu-item index="/user_index">
                <span slot="title">注册会员</span>
            </el-menu-item>
            <el-menu-item index="/enterprise_index">
                <span slot="title">企业管理</span>
            </el-menu-item>
        </div>
        <div v-show="show == 'wechat'">
            <el-menu-item index="/wechat_config">
                <span slot="title">接口管理</span>
            </el-menu-item>
            <el-menu-item index="/wechat_menu">
                <span slot="title">自定义菜单</span>
            </el-menu-item>
            <!--<el-menu-item index="/wechat_material">
                <span slot="title">素材管理</span>
            </el-menu-item>
            <el-menu-item index="/wechat_template_message">
                <span slot="title">模板消息</span>
            </el-menu-item>-->
        </div>
        <div v-show="show == 'system'">
            <el-menu-item index="/wechat_user">
                <span slot="title">微信会员</span>
            </el-menu-item>
            <!--<el-submenu index="1">
                <template slot="title">
                    <span>历史记录</span>
                </template>
                <el-menu-item-group>
                    <el-menu-item index="/history-reject">驳回记录</el-menu-item>
                </el-menu-item-group>
            </el-submenu>-->
            <!--<el-menu-item index="/yun">
                <span slot="title">云管理</span>
            </el-menu-item>
            <el-menu-item index="/sms">
                <span slot="title">短信管理</span>
            </el-menu-item>-->
            <el-menu-item index="/admins">
                <span slot="title">管理员管理</span>
            </el-menu-item>
            <el-menu-item index="/power">
                <span slot="title">系统权限</span>
            </el-menu-item>
        </div>
    </el-menu>
</template>

<script>
    import { Menu, MenuItemGroup, MenuItem, Submenu } from 'element-ui'

    export default {
        components: {
            'el-menu': Menu,
            'el-menu-item': MenuItem,
            'el-menu-item-group': MenuItemGroup,
            'el-submenu': Submenu
        },
        props: ['showMenus'],
        data() {
            return {
                show: 'current',
            }
        },
        watch: {
            showMenus() {
                this.show = this.showMenus;
            }
        }
    }
</script>

<style scoped>
    .el-sumenu .el-menu-item{
        min-width:100% !important;
    }
    .el-aside > .el-menu{
        min-height:100% !important;
    }
    .el-submenu .el-menu-item{
        text-align: center;
        min-width:100% !important;
    }
</style>
