<template>
	<view class="bg-black tm-every-center flex-direction text-sm line-sub" style="height:300rpx;">
		<view class="margin-xs">Copyright © 2017-2019 指尖魔法屋. All rights reserved</view>
		<view class="margin-xs">本站已顽强运行：{{time[0]}}年{{time[1]}}天{{time[2]}}小时{{time[3]}}分钟{{time[4]}}秒</view>
		<view class="margin-xs">粤ICP备17055617号</view>
		<view class="margin-xs">版本号v0.1.6</view>
	</view>
</template>

<script>
import moment from 'moment'
import 'moment/locale/zh-cn'
moment.locale('zh-cn')

export default {
	data() {
		return {
			time:[0,0,0,0,0]
		}
	},
	name: 'tm-footer',
	computed: {},
	props: {},
	methods: {
		secondToDate(second) {
			if (!second) {
				return 0
			}
			var time = new Array(0, 0, 0, 0, 0)
			if (second >= 365 * 24 * 3600) {
				time[0] = parseInt(second / (365 * 24 * 3600))
				second %= 365 * 24 * 3600
			}
			if (second >= 24 * 3600) {
				time[1] = parseInt(second / (24 * 3600))
				second %= 24 * 3600
			}
			if (second >= 3600) {
				time[2] = parseInt(second / 3600)
				second %= 3600
			}
			if (second >= 60) {
				time[3] = parseInt(second / 60)
				second %= 60
			}
			if (second > 0) {
				time[4] = second
			}
			this.time = time
		}
	},
	onReady() {
		const _this = this
		var a = moment([2017, 4, 12])
		var b = moment()
		let seconds = b.diff(a, 'seconds')
		setInterval(()=>{
			_this.secondToDate(seconds++)
		},1000)
	}
}
</script>

<style></style>
