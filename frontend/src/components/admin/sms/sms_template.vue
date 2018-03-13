<template>
    <div>
        <el-row>
            <el-button></el-button>
        </el-row>
        <el-row>
            <el-table
                    :data="templates"
                    style="width: 100%">
                <el-table-column
                        label="模板名称"
                        width="180">
                </el-table-column>
                <el-table-column
                        label="模板CODE"
                        width="180">
                </el-table-column>
                <el-table-column
                        label="模板类型">
                </el-table-column>
            </el-table>
        </el-row>
    </div>

</template>

<script>
    import { Col, Row, Table, TableColumn, Button } from 'element-ui'

    export default {
        components: {
            'el-col': Col,
            'el-row': Row,
            'el-table': Table,
            'el-table-column': TableColumn,
            'el-button': Button
        },
        data() {
            return {
                templates: []
            }
        }
    }
</script>