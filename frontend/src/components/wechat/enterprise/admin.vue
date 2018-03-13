<template>
    <div>
        <x-header :title="top_title"></x-header>
        <loading :show="loadings"></loading>
        <div v-if="!loadings">
            <group class="change_overflow">
                <cell v-for="(admin,index) in admins" :title="admin.user.name" :key="index" class="change_height">
                    <x-button @click.native="delAdmin(admin.user.id)" class="change_btn_height">删除</x-button>
                </cell>
            </group>
            <x-button @click.native="show.addAdmin = true" style="margin-top:10%;width:94%">添加管理员</x-button>
        </div>
        <popup v-model="show.addAdmin" height="100%">
            <div>
                <search
                    v-model="search.data"
                    position="absolute"
                    auto-scroll-to-top
                    :auto-fixed="search.auto_fixed"
                    placeholder="搜索用户"
                    @on-submit="searchUser"
                    @on-cancel="search.result = []"
                    ref="search"></search>
                <group>
                    <cell v-for="(item,args) in search.result" :title="item.name" :key="args" class="change_height">
                        <x-button @click.native="addAdmin(item.id)" class="change_btn_height_">添加</x-button>
                    </cell>
                </group>
                <x-button @click.native="show.addAdmin = false" style="width:94%;margin-top:2vh;">取消</x-button>
            </div>
        </popup>
    </div>
</template>

<script>
    import { Search, XHeader, Group, Cell, XButton, Popup, Grid, GridItem, Loading} from 'vux'

    export default {
        components: {
            XHeader, Group, Cell, Search, XButton, Popup, Grid, GridItem, Loading
        },
        data() {
            return {
                top_title: '企业管理员',
                admins: [],
                show: {addAdmin: false},
                search: { auto_fixed: false, data: '', result: [] },
                loadings:true
            }
        },
        methods: {
            getAdmins() {
                axios.post('api/enterprise_admin/getAll', {enterprise_id: 1}).then(response => {
                    this.admins = response.data.data
                    this.loadings = false
                }).catch(error => {
                    this.loadings = false
                })
            },
            searchUser(mobile) {
                this.loadings = true
                axios.post('api/worker/getByMobile', {enterprise_id: 1, mobile: mobile}).then(response => {
                    this.search.result = response.data.data
                    this.loadings = false
                }).catch(error => {
                    this.loadings = false
                })
            },
            clearSearch() {
                this.search.data = '';
            },
            addAdmin(user_id) {
                axios.post('api/enterprise_admin/add', {enterprise_id: 1, user_id: user_id}).then(response => {
                    this.$vux.alert.show({
                        content: response.data.message
                    })
                    this.getAdmins()
                }).catch(error => {
                    this.$vux.alert.show({
                        content: error.response.data.message
                    })
                })
            },
            delAdmin(user_id) {
                let _this = this
                this.$vux.confirm.show({
                    title: '确定要删除管理员',
                    onConfirm() {
                        axios.post('api/enterprise_admin/del', {user_id: user_id, enterprise_id: 1}).then(response => {
                            _this.$vux.alert.show({
                                content: response.data.message
                            })
                            _this.getAdmins()
                        }).catch(error => {
                            _this.$vux.alert.show({
                                content: error.response.data.message
                            })
                        })
                    }
                })
            }
        },
        mounted() {
            this.getAdmins()
        }
    }
</script>

<style scoped>
    .demo5-item {
        width: 100px;
        height: 26px;
        line-height: 26px;
        text-align: center;
        border-radius: 3px;
        border: 1px solid #ccc;
        background-color: #fff;
        margin-right: 6px;
    }
    .demo5-item-selected {
        /*background: #ffffff url(../assets/demo/checker/active.png) no-repeat right bottom;*/
        background: #ffffff  no-repeat right bottom;
        border-color: #ff4a00;
    }
    .change_height{
        height:5vh;
    }
    .change_overflow{
        height:75vh;
        overflow:auto;
        background:white;
        margin-top:-20px;
    }
    .change_btn_height{
        height:5vh;
        line-height:25%;
        background:#ff3232;
        color:white;
    }
    .change_btn_height_{
        height:5vh;
        line-height:25%;
    }
</style>
