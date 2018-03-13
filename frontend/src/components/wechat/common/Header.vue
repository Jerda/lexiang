<template>
	<x-header :left-options="{showBack: isBack}" :title="title"></x-header>
</template>

<script>
	import { XHeader } from 'vux'
	export default{
		components: {
            XHeader
        },
        props: ['title', 'isBack', 'left', 'right']
	}
</script>

<style>

</style>
