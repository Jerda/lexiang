<template>
    <div v-show="isShow">
        <loading :show="loadings"></loading>
        <group class="change_margin_top">
            <cell v-for="(worker,index) in workers" :title="worker.name" :key="index" class="change_height_oth">
                <x-button @click.native="showUserInfo(worker)" style="display:inline;margin-left:-20vw;height:5vh;line-height:25%;">查看</x-button>
                <x-button @click.native="agree(worker.id)" style="display:inline;margin-top:0;height:5vh;line-height:25%;background:#57ffab;">通过</x-button>
            </cell>
        </group>

        <popup v-model="user_info.show" height="100%">
            <div>
                <group>
                    <cell title="姓名" v-model="user_info.user.name" class="change_height"></cell>
                    <cell title="手机号" v-model="user_info.user.mobile" class="change_height"></cell>
                    <cell title="性别" v-model="user_info.user.sex" class="change_height"></cell>
                    <cell title="出生日期" v-model="user_info.user.birthday" class="change_height"></cell>
                    <cell title="邮箱" v-model="user_info.user.email" class="change_height"></cell>
                </group>
                    <x-button @click.native="agree(user_info.user.id)" style="width:94%;margin-top:2vh;">通过</x-button>
                    <x-button @click.native="dialogShow = true" style="width:94%;">驳回</x-button>
                    <x-button @click.native="user_info.show = false" style="width:94%;">取消</x-button>
            </div>

            <x-dialog v-model="dialogShow">
                <div class="change_padding">
                    <x-textarea type="text" v-model="reject.data" class="change_allborder"></x-textarea>
                </div>
                <x-button @click.native="actionReject" style="display:inline;height:5vh;line-height:25%;background:#57ffab;width:48%;margin-bottom: 2px">提交</x-button>
                <x-button @click.native="dialogShow = false" style="display:inline;margin-top:0;height:5vh;line-height:25%;width:48%;">取消</x-button>
            </x-dialog>
        </popup>
    </div>
</template>

<script>
    import { Group, Cell, XButton, Popup, XDialog, XInput, Loading, XTextarea} from 'vux'

    export default {
        components: {
            Group, Cell, XButton, Popup, XDialog, XInput, Loading, XTextarea
        },
        props: ['show'],
        data() {
            return {
                isShow: this.show,
                workers: [],
                user_info: {
                    show: false,
                    user: ''
                },
                dialogShow: false,
                reject: {
                    data: '',
                    user_id: ''
                },
                loadings:true
            }
        },
        methods: {
            getExamineWorkers() {
                axios.post('api/worker/getWaitExamineWorker', {enterprise_id: 1}).then(response => {
                    this.workers = response.data.data.data
                    this.loadings = false
                }).catch(error => {
                    this.loadings = false
                })
            },
            showUserInfo(user) {
                this.user_info.user = user
                console.log(user)
                this.user_info.show = true
            },
            actionReject() {
                axios.post('api/worker/reject', {user_id: this.user_info.user.id, content: this.reject.data}).then(response => {
                    this.$vux.alert.show({
                        content: response.data.message
                    })
                    this.dialogShow = false
                    this.user_info.show = false
                }).catch(error => {
                    this.$vux.alert.show({
                        content: error.response.data.message
                    })
                })
            },
            agree(user_id) {
                let _this = this
                this.$vux.confirm.show({
                    content: '确认同意用户申请',
                    onConfirm() {
                        axios.post('api/enterprise/agreeWorkerJoin', {user_id: user_id}).then(response => {
                            _this.user_info.show = false
                            _this.getExamineWorkers()
                            _this.$emit('change')
                        }).catch(error => {

                        })
                    }
                })
            }
        },
        watch: {
            show () {
                this.isShow = this.show
            },
        },
        mounted() {
            this.getExamineWorkers()
        }
    }
</script>
<style>

    .change_height_oth{
        height:5vh;
        position: relative;
    }
    .change_margin_top{
        margin-top:-20px;
    }
    .change_height{
        height:5vh;
    }
    .change_allborder{
        border:1px gray solid
    }
    .change_padding{
        padding: 3px;
    }

</style>
