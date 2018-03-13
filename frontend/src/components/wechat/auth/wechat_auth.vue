<template>
	<div>
		<Header :title="top_title" :isBack="false"></Header>
		<div class="warning-text">
	    	<icon type="warn" is-msg></icon>
	    	<h2>您还不是企业管理员</h2>
    	</div>
    </div>
</template>

<script>
	import Header from '../common/Header'
	import { Icon } from 'vux'
	export default{
		components:{
			Header,
			Icon
		},
		name:'wechat_auth',
		data(){
			return{
				top_title:'乐享健康云'
			}
		}
	}
</script>

<style>
	.warning-text{
		padding-top: 100px;
		text-align: center
	}
</style>