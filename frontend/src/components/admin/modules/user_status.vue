<template>
    <div>
        <el-dropdown size="small" trigger="click">
            <span v-if="data.status == 1" class="el-dropdown-link" style="color: green">
                正常<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <span v-else-if="data.status == 2" class="el-dropdown-link" style="color: green">
                正常/员工<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <span v-else-if="data.status == 3" class="el-dropdown-link" style="color: red">
                禁用<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <span v-else-if="data.status == 4" class="el-dropdown-link" style="color: orange">
                正常/申请员工<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <span v-else-if="data.status == 5" class="el-dropdown-link" style="color: red">
                正常/驳回员工申请<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item v-show="data.status == 3" @click.native="modify(1)">正常</el-dropdown-item>
                <el-dropdown-item v-show="data.status != 3" @click.native="modify(3)">禁用</el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
    </div>
</template>

<script>
    import { Dropdown, DropdownMenu, DropdownItem } from 'element-ui'

    export default {
        components: {
            'el-dropdown': Dropdown,
            'el-dropdown-menu': DropdownMenu,
            'el-dropdown-item': DropdownItem,
        },
        props: ['id', 'status'],
        data() {
            return {
                data: {
                    status: this.status,
                    id: this.id
                },
            }
        },
        methods: {
            modify(status) {
                this.$emit('modify', {user_id: this.data.id, status: status});
            },
        },
        watch: {
            status() {
                this.data.status = this.status;
                this.data.id = this.id;
            }
        }

    }
</script>

<style scoped>
    .el-dropdown >>> span {
        font-size: 11px;
    }
</style>
