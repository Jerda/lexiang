<template>
    <div>
        <x-header :title="top_title" :isBack="true"></x-header>
        <loading :show="loadings"></loading>
        <div v-if="!loadings">
            <group class="change_margin_top">
                <card>
                    <div slot="content" class="card-padding" v-for="mes in refuse_mes">
                        <p style="color:#999;font-size:15px;margin-top:0px" >{{mes.created_at}}</p>
                        <p style="font-size:18px;margin-left: 5vw;">{{mes.content}}</p>
                    </div>
                </card>
            </group>
        </div>
    </div>
</template>

<script>
    import { Group, Cell, XHeader, Loading, Card} from 'vux'

    export default {
        components: {
            Group, Cell, XHeader, Loading, Card
        },
        data() {
            return {
                top_title: '员工申请驳回信息',
                loadings:false,
                refuse_mes:''
            }
        },
        methods: {
            getRefuseMes(){
                axios.post('api/user/getRejectListForApplyWorker').then(response => {
                    this.refuse_mes = response.data.data;
                    console.log(this.refuse_mes)
                })
            }
        },
        mounted() {
            this.getRefuseMes()
        }
    }
</script>

<style scoped>
    .change_margin_top{
        margin-top:-20px;
    }
    .card-padding{
        border-bottom: #ccc 1px solid;
    }

</style>