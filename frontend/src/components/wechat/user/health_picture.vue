<template>
    <popup v-model="isShow" height="100%">
        <x-button @click.native="close">关闭</x-button>
        <img class="img" src="https://static.chiphell.com/forum/201803/07/153547jdrjx766ndmb36qd.jpg">
    </popup>
</template>

<script>
    import { Popup, XButton } from 'vux'

    export default {
        components: {
            Popup, XButton
        },
        props: ['top_title', 'picture_url', 'show'],
        data() {
            return {
                isShow: false
            }
        },
        methods: {
            close() {
                this.$emit('close')
            }
        },
        watch: {
            show() {
                this.isShow = this.show
            }
        }
    }
</script>

<style>
    .img {
        width: 100%; height: 100%; vertical-align: middle;
    }
</style>
