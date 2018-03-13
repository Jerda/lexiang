<template>
    <div>
        <loading :show="loadings"></loading>
        <x-header :title="top_title"><a slot="right" @click="show.field = true">列表</a></x-header>
        <div>
            <p class="change_align">{{ current.fields.name }}</p>
        </div>
        <div v-if="(current.fields['db_name'] == 'SEDXDTJ') && current.data[current.fields['db_name']] !== null">
            <x-button @click.native="show.health_picture = true">查看心电图</x-button>
        </div>
        <div v-if="current.data[current.fields['db_name']] !== null">
            <card v-if="(index !== 'db_name') && (index !== 'name') "
                  v-for="(item, index) in current.fields"
                  :header="{title: item}" :key="index" class="change_border_bottom">
                <p v-if="current.data[current.fields['db_name']][index] != ''" slot="content"
                   class="content">{{ current.data[current.fields['db_name']][index] }}</p>
                <p v-else slot="content" class="content">无数据</p>
            </card>
        </div>
        <div  v-else style="background:white">
            <div class="change_height_curr"></div>
            <div class="change_error_mes_">
                <img slot="icon" style="display:block;margin-right:5px;" src="../imgs/error.png" class="change_error">
                <span class="change_margin_left">无数据!</span>
            </div>
        </div>
        <popup v-model="show.field" position="right">
            <div style="width:200px;">
                <x-button v-for="(field,args) in fields"
                          @click.native="showDetailByField(field)" :key="args" style="margin:0;">{{ field.name }}
                </x-button>
            </div>
        </popup>

        <health-picture :title="111" :show="show.health_picture"
                        @close="show.health_picture = false"></health-picture>
    </div>
</template>

<script>
    import HealthPicture from './health_picture'
    import { Popup, XHeader, XButton, Group, Cell, Card, Loading} from 'vux'

    export default {
        components: {
            Popup, XHeader, XButton, Group, Cell, Card, HealthPicture, Loading
        },
        data() {
            return {
                top_title: '健康详情',
                fields: {},
                show: {
                    field: false,
                    health_picture: false
                },
                current: {
                    name: '',
                    data: {},
                    fields: []
                },
                loadings:true
            }
        },
        methods: {
            getHealthFields() {
                this.loadings = true
                axios.post('api/health/fields').then(response => {
                    this.fields = response.data.data
                    this.getHealthDetail()
                    this.loadings = false
                });
            },
            getHealthDetail() {
                axios.post('api/health/getDetail', {health_id: this.$route.params.health_id}).then(response => {
                    this.current.data = response.data.data
                    this.loadings = false
                    this.showDetailByField(this.fields.chao_shen_gu_mi_du_fen_xi_yi) //首次进入显示
                })
            },
            showDetailByField(field) {
                this.show.field = false
                this.current.name = field.name
                this.current.fields = field
            }
        },
        mounted() {
            this.getHealthFields()
        },
    }
</script>

<style>
    #app {
        background: #D5D5D5;
    }
    .change_height{
        height:5vh;
    }
    .weui-panel {
        margin-top: 0px;
    }
    .content {
        margin-left: 20px;
    }
    .change_align{
        text-align:center;
    }
    .change_height_curr{
        height:0.5vh;
        border-bottom: 1px gray solid;
    }
    .change_error{
        margin-left:2vw;
        margin-top:15vh;
    }
    .change_error_mes_{
        margin-left:40vw;
        background:white;
        margin-top:5vh;
    }
    .change_margin_left{
        margin-left:4vw;
    }
    .change_border_bottom{
        border-bottom:1px solid #9e8888;
    }

</style>
