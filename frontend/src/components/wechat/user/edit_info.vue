<template>
    <div>
        <Header :title="top_title" :isBack="true"></Header>
        <group class="change_margin_top">
            <x-input title="姓名" class="change_height"></x-input>
            <x-input title="手机号" class="change_height"></x-input>
            <datetime title="出生日期" class="change_heights"></datetime>
            <selector title="性别" :options="options.sex" class="change_height_"></selector>
            <x-input title="年龄" class="change_height"></x-input>
            <x-input title="邮箱" class="change_height"></x-input>
        </group>
            <x-button style="width:94%;margin-top:2vh;"><span class="sure">确 定</span></x-button>
    </div>
</template>

<script>
    import Header from '../common/Header'
    import { Group, XButton, XInput, Datetime, Selector } from 'vux'

    export default {
        components: {
            Header,
            Group,
            XButton,
            XInput,
            Datetime,
            Selector
        },
        data() {
            return {
                top_title: '修改信息',
                options: {
                    sex: [{ key: 1, value: '男' }, { key: 2, value: '女' }]
                }
            }
        }
    }
</script>

<style>
    .sure{
        color:black;
    }
    .change_height{
        height:5vh;
    }
    .change_height_{
        height:8vh;
    }
    .change_heights{
        height:5vh;
        text-decoration:none;
    }
    .change_margin_top{
        margin-top:-20px;
    }
</style>
