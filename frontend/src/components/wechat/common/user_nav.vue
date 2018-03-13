<template>
    <div>
        <tabbar style="position:fixed">
            <tabbar-item :selected="selected.healthActive" link="/health" class="margin_change change_text_deco">
                <img slot="icon" src="../imgs/like.png">
                <span slot="label">健康中心</span>
            </tabbar-item>
            <tabbar-item :selected="selected.familyActive" link="/concern_index" class="margin_change change_text_deco">
                <img slot="icon" src="../imgs/group.png">
                <span slot="label">我的关注</span>
            </tabbar-item>
            <tabbar-item :selected="selected.userActive" link="/user_index" class="margin_change change_text_deco">
                <img slot="icon" src="../imgs/mine.png">
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
                }
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
