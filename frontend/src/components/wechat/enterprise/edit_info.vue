<template>
    <popup v-model="isShow" height="100%" style="background:white">
        <x-input title="企业名称" v-model="enterprise.name"></x-input>
        <x-input title="法人" v-model="enterprise.legal_person"></x-input>
        <x-input title="联系人" v-model="enterprise.linker"></x-input>
        <x-input title="手机号" v-model="enterprise.linker_mobile"></x-input>
        <x-input title="邮箱" v-model="enterprise.linker_email"></x-input>
        <selector title="行业类型" :options="options.enterprise_type" v-model="enterprise.enterprise_type"></selector>
        <x-textarea title="详情地址" v-model="enterprise.address"></x-textarea>
        <x-button style="width:94%;">修改</x-button>
        <x-button @click.native="close" style="width:94%;">取消</x-button>
    </popup>
</template>

<script>
    import { Popup, XInput, Selector, XButton, XTextarea } from 'vux'

    export default {
        components: {
            XInput, Selector, XButton, Popup, XTextarea
        },
        props: ['enterprise', 'show'],
        data() {
            return {
                isShow: false,
                options: {
                    enterprise_type: [],
                },
            }
        },
        methods: {
            getEnterpriseType() {
                axios.post('api/enterprise/getEnterpriseType').then(response => {
                    for (let value in response.data.data) {
                        this.options.enterprise_type.push(response.data.data[value])
                    }
                }).catch(error => {

                })
            },
            close() {
                this.$emit('close')
            }
        },
        mounted() {
            this.getEnterpriseType()
        },
        watch: {
            show () {
                this.isShow = this.show
            },
        }
    }
</script>

<style>
</style>
