<template>
    <div>
        <el-row class="admin-top">
            <el-col :span="4">
                <span class="admin-title">乐享健康云</span>
            </el-col>
            <el-col :span="7" style="margin-top: -10px">
                <top-menu @selected="topMenuSelected"></top-menu>
            </el-col>
            <el-col :span="2" :offset="11">
                <div>
                    <el-dropdown size="small">
                        <span class="el-dropdown-link">
                            {{ AuthUser.username }}<i class="el-icon-arrow-down el-icon--right"></i>
                        </span>
                        <el-dropdown-menu slot="dropdown" size="mini">
                            <el-dropdown-item @click.native="showResetPassword">修改密码</el-dropdown-item>
                            <el-dropdown-item divided @click.native="logout">登出</el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </div>
            </el-col>
        </el-row>

        <reset-password :show="reset_password_dialog.show" @close="reset_password_dialog.show = false"></reset-password>
    </div>
</template>

<script>
    import { Row, Col, Button,  Dropdown, DropdownMenu, DropdownItem} from 'element-ui'
    import TopMenu from './top_menu.vue'
    import ResetPassword from '../auth/reset_password'

    export default {
        components: {
            'el-col': Col,
            'el-row': Row,
            'el-button': Button,
            'el-dropdown': Dropdown,
            'el-dropdown-item': DropdownItem,
            'el-dropdown-menu': DropdownMenu,
            TopMenu,
            ResetPassword
        },
        data () {
            return {
                reset_password_dialog: {
                    show: false
                },
                AuthUser: {
                    username: '__',
                },
            }
        },
        methods: {
            logout: function() {
                this.$store.dispatch('logoutRequest').then(response => {
                    this.$message.success(response.data.message)
                    let that = this
                    setTimeout(function() {
                        that.$router.push({'name':'login'})
                    }, 2000)
                })
            },
            showResetPassword() {
                this.reset_password_dialog.show = true
            },
            //顶部按钮选择事件
            topMenuSelected: function(val) {
                this.$emit('topMenuSelected', val)
            },
        },
        mounted: function() {
            this.AuthUser = this.$store.state.AuthUser
        },
    }
</script>

<style scoped>
    .admin-top {
        /*margin-top: 5px;*/
        color: white;
        font-size: 20px;
    }
    .el-dropdown >>> span {
        color: white;
        font-size: 16px;
    }
    .el-dropdown-menu >>> li {
        font-size: 12px;
        /*line-height: 20px;*/
    }
</style>
