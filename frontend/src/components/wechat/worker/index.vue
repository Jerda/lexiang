<template>
    <div>
        <x-header title="员工列表"></x-header>
        <tab>
            <tab-item selected @on-item-click="workerList">已加入员工</tab-item>
            <tab-item @on-item-click="showExamine">待审核员工</tab-item>
        </tab>
        <list :show="show.worker_list" class="change_height"></list>
        <examine :show="show.examine"></examine>
    </div>
</template>

<script>
    import List from './list'
    import Examine from './examine'
    import { XHeader, Tab, TabItem } from 'vux'

    export default {
        components: {
            XHeader, Tab, TabItem, List, Examine
        },
        data() {
            return {
                show: {
                    worker_list: true,
                    examine: false
                }
            }
        },
        methods: {
            workerList() {
                this.show.examine = false
                this.show.worker_list = true
            },
            showExamine() {
                this.show.examine = true
                this.show.worker_list = false
            }
        },
        mounted() {
        }
    }
</script>

<style>
    .change_height{
        
    }
</style>
