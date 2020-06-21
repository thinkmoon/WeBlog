<template>
  <view class="bg-white content">
    <cu-custom bgColor="bg-gradual" :isBack="true">
      <block slot="backText"><text class="icon-back"></text></block>
      <block slot="content">搜索</block>
    </cu-custom>
    <view class="cu-bar tm-search bg-white">
    		<view class="tm-search-input search-form">
    			<text class="icon-search"></text>
    			<input @confirm="onSearch" :adjust-position="false" type="text" placeholder="搜索文章,标签,分类" confirm-type="search" />
    		</view>
    	</view>
    	<view class="flex solid-bottom justify-between">
    		<view class="padding-xs margin-xs radius text-black">搜索历史</view>
    		<view @click="cleanHistory" class="margin-xs shadow-blur">
          <text class="icon-delete"></text>
    			清空搜索历史
    		</view>
    	</view>
    	<view class="padding">
        <view v-if="searchHistory.length == 0">暂无搜索历史</view>
    		<view @click="searchString(item.value)" v-for="(item, index) in searchHistory" :key="index" class="cu-btn text-black margin-xs">
    			{{ item.value }}
    		</view>
    	</view>
    </view>
</template>

<script>
	export default {
		data() {
			return {
				searchHistory: []
			}
		},
		methods: {
			// 点击搜索历史
			searchString(key) {
				uni.navigateTo({
					url: 'searchResult?keyWord=' + key
				})
			},
			// 开始搜索
			onSearch(e) {
				if (e.detail.value == "") {
					return;
				}
				uni.getStorageSync('searchHistory') ? (this.searchHistory = uni.getStorageSync('searchHistory')) : (this.searchHistory = [])
				this.searchHistory.push(e.detail)
				uni.setStorage({
					key: 'searchHistory',
					data: this.searchHistory
				})
				uni.navigateTo({
					url: 'searchResult?keyWord=' + e.detail.value
				})
			},
			// 清除搜索历史
			cleanHistory() {
				this.searchHistory = []
				uni.removeStorageSync('searchHistory')
			}
		},
		onShow() {
			uni.getStorage({
				key: 'searchHistory',
				success: res => {
					this.searchHistory = res.data
				}
			})
		}
	}
</script>
<style lang="scss">

</style>