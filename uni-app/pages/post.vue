<template>
	<block>
		<cu-custom bgColor="bg-gradual-black" :isBack="true"></cu-custom>
		<view class="cu-bar bg-white solid-bottom padding-top padding-bottom">
			<view class="action">
				<text class="icon-titles text-green"></text>
				<text class="text-xl text-bold">{{ postData[0].title }}</text>
			</view>
		</view>
		<towxml :content="postData[0].text" v-if="postData[0].text"></towxml>
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
