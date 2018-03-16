<template>
    <div>
        <tabbar style="position:fixed">
            <tabbar-item :selected="selected.healthActive" link="/health" class="margin_change change_text_deco">
                <img slot="icon" :src="selected.healthActive?health_avtive:health_unActive">
                <span slot="label">健康中心</span>
            </tabbar-item>
            <tabbar-item :selected="selected.familyActive" link="/concern_index" class="margin_change change_text_deco">
                <img slot="icon" :src="selected.familyActive?concren_active:concern_unActive">
                <span slot="label">我的关注</span>
            </tabbar-item>
            <tabbar-item :selected="selected.userActive" link="/user_index" class="margin_change change_text_deco">
                <img slot="icon" :src="selected.userActive?mine_active:mine_unActive">
                <span slot="label">个人中心</span>
            </tabbar-item>
        </tabbar>
    </div>
</template>

<script>
    import {Tabbar, TabbarItem} from 'vux'

    export default {
        components: {
            Tabbar,
            TabbarItem
        },
        props: ['healthActive', 'familyActive', 'userActive'],
        data() {
            return {
                selected: {
                    healthActive: this.healthActive,
                    userActive: this.userActive,
                    familyActive: this.familyActive
                },
                health_avtive:require('../imgs/like_change.png'),
                health_unActive:require('../imgs/like.png'),
                concern_unActive:require('../imgs/group.png'),
                concren_active:require('../imgs/group_change.png'),
                mine_active:require('../imgs/mine_change.png'),
                mine_unActive:require('../imgs/mine.png'),
            }
        }
    }
</script>

<style scoped>
    .margin_change >>> p{
        margin:0;
    } 
    .change_text_deco{
        text-decoration:none;
    }
</style>
