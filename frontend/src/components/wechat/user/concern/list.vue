<template>
    <div v-show="isShow">
        <group class="ppp">
            <cell v-for="(user,index) in concern_users"
                  is-link
                  :title="user.concern_user.name"
                  @click.native="toConcernUser(user.concern_user.id)" :key="index" class="change_height_">
                <img slot="icon" :src="user.concern_user.wechat.avatar" class="change_height_img">
            </cell>
        </group>
        <loading :show="loadings"></loading>
    </div>
</template>

<script>
    import { Group, Cell, Loading} from 'vux'

    export default {
        components: {
            Group, Cell, Loading
        },
        props: ['show'],
        data() {
            return {
                concern_users: [],
                isShow: this.show,
                loadings: true
            }
        },
        methods: {
            getConcernUsers() {
                axios.post('api/user/getConcernUserList').then(response => {
                    this.concern_users = response.data.data
                    this.loadings = false
                }).catch(error => {
                    this.loadings = false
                })
            },
            toConcernUser(user_id) {
                this.$router.push('/health/' + user_id)
            },
        },
        mounted() {
            this.getConcernUsers();
        },
        watch: {
            show () {
                this.isShow = this.show
            },
        },
    }
</script>
