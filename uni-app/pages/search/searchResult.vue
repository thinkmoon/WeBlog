<template>
	<view class="bg-gray content">
		<cu-custom bgColor="bg-gradual" :isBack="true">
			<block slot="backText"><text class="icon-back"></text></block>
			<block slot="content">搜索结果</block>
		</cu-custom>
		<view>
			<navigator :url='"/pages/post?cid=" + item.cid' v-for="(item,index) in articleList" :key="index" class="margin padding bg-white">
				<view class="title text-bold text-lg padding-bottom-sm"> {{ item.title }} </view>
				<view> {{ item.text }} </view>
			</navigator>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				keyWord: null,
				articleList: []
			};
		},
		async onLoad(options) {
			this.keyWord = options.keyWord
			this.articleList = await this.$api.search({
				"keyWord": this.keyWord
			})
		}
	}
</script>

<style lang="less">

</style>
