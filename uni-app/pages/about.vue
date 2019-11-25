<template>
	<view>
		<view class="overView flex justify-between flex-direction" style="height: 40vh;width: 100%;">
			<view class="flex justify-between align-center" style="height: 30vh;width: 100%;">
				<image class="avatar shadow" src="https://www.thinkmoon.cn/usr/uploads/2018/12/55979974.jpg"></image>
			</view>
			<view class="flex align-end padding-xs justify-around text-white text-shadow text-bold bg-shadeBottom" style="width: 100%;">
				<view class="margin-xs flex align-center flex-direction">
					<view>文章</view>
					<view>{{ Overview.posts[0].Num }}</view>
				</view>
				<view class="margin-xs flex align-center flex-direction">
					<view>留言</view>
					<view>{{ Overview.comments[0].Num }}</view>
				</view>
				<view class="margin-xs flex align-center flex-direction">
					<view>分类</view>
					<view>{{ Overview.categorys[0].Num }}</view>
				</view>
				<view class="margin-xs flex align-center flex-direction">
					<view>标签</view>
					<view>{{ Overview.tags[0].Num }}</view>
				</view>
			</view>
		</view>
		<towxml :content="postData[0].text" v-if="postData[0].text"></towxml>
		<view class="cu-load load-modal" v-if="isLoading">
			<view class="icon-emojifill text-green"></view>
			<view class="gray-text">加载中...</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				cid: null,
				isLoading: true,
				Overview: {},
				postData: {}
			}
		},
		async onLoad(query) {
			this.cid = await this.$api.getAboutcid()
			this.postData = await this.$api.getPostBycid({
				cid: this.cid
			})
			console.log('请求成功')
			this.isLoading = false
			this.Overview = await this.$api.getOverview()
		}
	}
</script>

<style>
	// 头像
	.avatar {
		width: 200rpx;
		height: 200rpx;
		border-radius: 50%;
		margin: auto;
		border: 5rpx #eee solid;
	}

	.overView {
		background-image: url("https://www.thinkmoon.cn/usr/themes/armx/img/about_bg.png");
		background-repeat: no-repeat;
		background-size: 100% 100%;
	}
</style>
