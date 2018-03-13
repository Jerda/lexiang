<template>
    <div>
        <x-header title="企业信息"></x-header>
        <loading :show="loadings"></loading>
        <div v-if="!loadings">
            <group class="change_margin_top">
                <cell title="企业名称" v-model="enterprise.name" class="change_height"></cell>
                <cell title="法人" v-model="enterprise.legal_person" class="change_height"></cell>
                <cell title="联系人" v-model="enterprise.linker" class="change_height"></cell>
                <cell title="手机号" v-model="enterprise.linker_mobile" class="change_height"></cell>
                <cell title="邮箱" v-model="enterprise.linker_email" class="change_height"></cell>
                <cell title="行业类型" v-model="enterprise.enterprise_type" class="change_height"></cell>
                <cell title="企业地址" class="change_height">
                    <div>{{ enterprise.province }}{{ enterprise.district }}{{ enterprise.city }}</div>
                </cell>
                <cell title="详情地址" v-model="enterprise.address" class="change_height"></cell>
            </group>
                <x-button @click.native="edit.show = true" style="width:94%;margin-top:2vh" v-if="is_admin">编辑企业信息</x-button>
            <edit-info :show="edit.show" :enterprise="enterprise" @close="edit.show = false"></edit-info>
        </div>
    </div>
</template>

<script>
    import EditInfo from './edit_info'
    import { Group, Cell, XHeader, XButton, Loading} from 'vux'

    export default {
        components: {
            Group, Cell, XHeader, XButton, EditInfo, Loading
        },
        data() {
            return {
                enterprise: {},
                edit: {
                    show: false
                },
                loadings:true,
                is_admin:false
            }
        },
        methods: {
            getInfo() {
                axios.post('api/user/getEnterprise').then(response => {
                    this.enterprise = response.data.data
                    this.loadings = false
                }).catch(error => {
                    this.loadings = false
                })
            },
            getAdmin() {
                axios.post('api/user/isAdmin').then(response => {
                    if(response.data.data.id){
                        this.is_admin = true
                    }
                }).catch(error => {

                })
            }
        },
        mounted() {
            this.getInfo()
            this.getAdmin()
        }
    }
</script>

<style>
    .change_height{
        height:5vh;
    }
    .change_margin_top{
        margin-top:-20px;
    }
</style>
