<template>
	<block>
		<cu-custom bgColor="bg-gradual-black" :isBack="true"></cu-custom>
		<view class="cu-bar bg-white padding-top ">
			<view class="action">
				<!-- <text class="icon-titles text-green"></text> -->
				<text class="text-xxl text-bold text-black">{{ postData[0].title }}</text>
			</view>
		</view>
		<view class="bg-white flex bg-white solid-bottom padding-bottom-sm padding-top-sm justify-around text-sm">
			<view>
				<text class="icon-time"></text>
				2019年03月23日
			</view>
			<view>
				<text class="icon-file"></text>
				学习笔记
			</view>
			<view>
				<text class="icon-like"></text>
				点赞(12)
			</view>
			<view>
				<text class="icon-comment"></text>
				评论(34)
			</view>
		</view>
		<towxml :content="postData[0].text" v-if="postData[0].text"></towxml>
		<view class="like-area solid-top bg-white tm-every-center flex-direction">
			<view class="like-btn text-xxl line-green tm-every-center padding">赞</view>
			<view class="margin-top">您的支持是对我最大的鼓励</view>
		</view>
		<view class="margin-sm">
			<view class="text-lg">
				<text class="icon-titles text-green"></text>
				<text class="text-bold">发表评论</text>
			</view>
			<view class="comment-area bg-white margin-top padding"><textarea value="" placeholder="本是青灯不归客,却因杯酒恋红尘" class="solid padding" />
			<view class="margin-top-sm flex justify-between">
				<view class="text-bold">欢迎您，<text class="line-green">醉月思</text></view>
				<view><button class="cu-btn line-green">发表</button></view>
			</view></view>
		</view>
	</block>
</template>

<script>
export default {
	data() {
		return {
			postData: {}
		}
	},
	async onLoad(query) {
		this.postData = await this.$api.getPostBycid({ cid: query.cid })
		console.log('请求成功')
	}
}
</script>
<style>
.like-area {
	height: 300upx;
}
.like-btn {
	height: 130upx;
	width: 130upx;
	border-radius: 50%;
	border: 1upx solid #42b983;
}
.comment-area {
	height: 400upx;
}

.comment-area textarea {
	width: 650upx;
	height: 250upx;
}
</style>
