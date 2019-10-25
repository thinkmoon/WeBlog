<template>
	<view>
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
				postData:{}
			}
		},
		async onLoad(query) {
			this.cid = await this.$api.getAboutcid()
			this.postData = await this.$api.getPostBycid({
				cid: this.cid
			})
			console.log('请求成功')
			this.isLoading = false
		}
	}
</script>
