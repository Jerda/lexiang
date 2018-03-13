<template>
    <div>
        <el-row></el-row>
        <el-row>
            <el-table
                    :data="users"
                    style="width: 100%">
                <el-table-column
                        label="序号"
                        width="180">
                </el-table-column>
                <el-table-column
                        label="模板ID">
                </el-table-column>
                <el-table-column
                        label="标题">
                </el-table-column>
                <el-table-column
                        label="一级行业">
                </el-table-column>
                <el-table-column
                        label="二级行业">
                </el-table-column>
                <el-table-column
                        label="操作">
                    <template slot-scope="scope">
                        <el-button type="text" size="small" @click="showUserDetail(scope.row.id)">编辑</el-button>
                        <!--<el-button type="text" size="small" @click="block(scope.row.id)">拉黑用户</el-button>-->
                    </template>
                </el-table-column>
            </el-table>
        </el-row>
    </div>
</template>

<script>

</script>
