<template>
	<view class="bg-black tm-every-center flex-direction text-sm line-sub" style="height:300rpx;">
		<view class="margin-xs">Copyright © 2017-2019 指尖魔法屋. All rights reserved</view>
		<view class="margin-xs">本站已顽强运行：2年5天16时31分9秒</view>
		<view class="margin-xs">粤ICP备17055617号</view>
	</view>
</template>

<script>
import moment from 'moment'
import 'moment/locale/zh-cn'
moment.locale('zh-cn')
export default {
	data() {
		return {}
	},
	name: 'tm-footer',
	computed: {},
	props: {},
	methods: {},
	onReady() {
		var a = moment([2017, 4, 12])
		a.from(new Date())
		console.log(a.format('dddd, MMMM Do YYYY, h:mm:ss a'))
	}
}
</script>

<style></style>
