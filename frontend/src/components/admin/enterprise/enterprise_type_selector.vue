<template>
    <el-select size="small" v-model="type" clearable placeholder="请选择行业类型" @change="change">
        <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
        </el-option>
    </el-select>
</template>

<script>
    import { Select, Option } from 'element-ui'

    export default {
        components: {
            'el-select': Select,
            'el-option': Option
        },
        props: ['enterprise_type_id'],
        data() {
            return {
                options: [],
                type: ''
            }
        },
        methods: {
            change(value) {
                this.$emit('change', value)
            },
        },
        mounted: function() {
            axios.post('api/enterprise/getEnterpriseType').then(response => {
                this.options = this.formatSelectOptions(response.data.data);
            });
        },
        watch: {
            enterprise_type_id() {
                this.type = this.enterprise_type_id;
            }
        }
    }
</script>
