<template>
	<div class="main_view">
		<Header :title="top_title" :isBack="isBack"></Header>
		<blur :blur-amount=40 :url="user.wechat.avatar">
      		<p class="center"><img :src="user.wechat.avatar"></p>
    	</blur>
		<group class="change_size">
            <cell title="个人资料" is-link link="user_info">
                <img slot="icon" style="display:block;margin-right:5px;" src="../imgs/self_info.png">
            </cell>
            <cell title="我的关注" is-link link="concern_index">
            	<img slot="icon" style="display:block;margin-right:5px;" src="../imgs/my_group.png">
            </cell>
            <cell title="我的企业" is-link link="enterprise_index">
                <img slot="icon" style="display:block;margin-right:5px;" src="../imgs/interactive_fill.png">
            </cell>
		</group>
		<Nav :userActive="true"></Nav>
	</div>
</template>

<script>
	import Nav from '../common/user_nav'
	import Header from '../common/Header'
	import { Cell, Group, Blur } from 'vux'

	export default {
		components: {
			Group,
			Cell,
			Blur,
			Header,
			Nav,
		},
		data() {
			return {
				top_title: '个人中心',
				isBack: false,
                user: {
				    wechat: {
				        avatar: ''
                    }
                },
				loadings:false
			}
		},
        methods: {
		    getUser() {
		        axios.post('api/user/info').then(response => {

                    this.user = response.data.data
                })
            }
        },
        mounted() {
		    this.getUser()
        }
	}
</script>

<style scoped>
	.center {
  		text-align: center;
  		padding-top: 20px;
  		color: #fff;
  		font-size: 18px;
	}
	.center img {
  		width: 100px;
  		height: 100px;
  		border-radius: 50%;
  		border: 4px solid #ececec;
	}
	.main_view{
	background-color:#eaf8ff;
	}
	.change_size{
		margin-top:-35px;
	}
</style>
