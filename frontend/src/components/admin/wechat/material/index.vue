<template>
    <el-container>
        <el-main>
            <el-row>
                <el-tabs v-model="activeName">
                    <el-tab-pane label="图文" name="first">
                        <el-col v-for="item in news" style="width:180px;margin-right:20px;margin-bottom:10px">
                            <el-card>
                                <div>
                                    <div class="span-title">{{ item.content.news_item[0].title }}</div>
                                </div>
                                <div style="padding: 14px;">
                                    <div class="digest">{{ item.content.news_item[0].digest }}</div>
                                    <div class="bottom clearfix">
                                        <span class="time">更新时间{{ item.content.update_time }}</span>
                                        <el-button type="text" class="button" @click="detail">查看</el-button>
                                    </div>
                                </div>
                            </el-card>
                        </el-col>
                        <el-alert
                                title="由于腾讯最新规则，微信多媒体资源无法在非微信客户端上显示，图文图片无法显示"
                                type="error">
                        </el-alert>
                    </el-tab-pane>
                    <el-tab-pane label="图片" name="second">
                        <el-alert
                                title="由于腾讯最新规则，微信多媒体资源无法在非微信客户端上显示，该功能暂时关闭"
                                type="error">
                        </el-alert>
                    </el-tab-pane>
                    <el-tab-pane label="语音" name="third">
                        <el-alert
                                title="由于腾讯最新规则，微信多媒体资源无法在非微信客户端上显示，该功能暂时关闭"
                                type="error">
                        </el-alert>
                    </el-tab-pane>
                    <el-tab-pane label="视频" name="fourth">
                        <el-alert
                                title="由于腾讯最新规则，微信多媒体资源无法在非微信客户端上显示，该功能暂时关闭"
                                type="error">
                        </el-alert>
                    </el-tab-pane>
                </el-tabs>
            </el-row>
        </el-main>
    </el-container>
</template>

<script>
    import { Container, Main, Col, Row, Button, Input, Tabs, TabPane, Card, Alert } from "element-ui"

    export default {
        components: {
            'el-button': Button,
            'el-container': Container,
            'el-main': Main,
            'el-col': Col,
            'el-row': Row,
            'el-input': Input,
            'el-tabs': Tabs,
            'el-tab-pane': TabPane,
            'el-card': Card,
            'el-alert': Alert
        },
        data () {
            return {
                activeName: 'first',
                news: [],
                start_index: 0,
                item_count: 0,
                total_count: 0,
            }
        },
        methods: {
            detail: function() {
                this.$router.push({name: 'wechat-material-add'});
            },
            /**
             * 获取所有图片
             */
            getNews: function() {
                axios.post('/api/wechat/material/news').then(response => {
                    this.start_index += response.data.item_count;
                    this.total_count = response.data.total_count;
                    this.news = response.data.item;
                }).catch(error => {
                    this.$message.error(error.response.data.message);
                });
            }
        },
        mounted: function() {
            this.getNews();
        }
    }
</script>

<style scoped>
    .span-title {
        font-size: 20px;
    }
    .digest {
        font-size: 12px;
        color: #969696;
        margin-left: -25px;
    }
    .time {
        font-size: 13px;
        color: #999;
        margin-left: -25px;
    }

    .bottom {
        margin-top: 13px;
        line-height: 12px;
    }

    .button {
        padding: 0;
        float: right;
        margin-right: -20px;
    }
</style>