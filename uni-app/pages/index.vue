<template>
	<block>
		<view class="cu-card case">
			<view class="load-progress" :class="loadProgress!=0?'show':'hide'">
				<view class="load-progress-bar bg-green" :style="[{transform: 'translate3d(-' + (100-loadProgress) + '%, 0px, 0px)'}]"></view>
				<view class="load-progress-spinner text-green"></view>
			</view>
			<view class="margin flex" v-if="postData.length == 0" style="min-height: 60vh;">
				<view class="bg-white flex-sub radius shadow-lg" style="padding-top: 200upx;">
					<image src="https://image.weilanwl.com/gif/loading-white.gif" mode="aspectFit" class="gif-white response" style="height:240upx"></image>
				</view>
			</view>
			<view v-for="(item, index) in postData" :key="index" class="cu-item shadow animation-slide-bottom">
				<view @tap="seePost(item.cid)">
					<view class="image">
						<image :src="item.thumb[0].str_value" mode="widthFix" :lazy-load="true"></image>
						<!-- <view class="cu-tag bg-blue">置顶</view> -->
						<view class="cu-bar text-shadow bg-shadeBottom">{{ item.title }}</view>
					</view>
					<view class="cu-list menu menu-avatar">
						<view class="cu-item" style="height: 70upx;min-height: 70upx;">
							<view class="text-gray text-sm flex justify-between align-center" style=" width: 100%;">
								<view><text class="text-green margin-right-sm">{{ item.screenName }}</text>
									{{ formatTime(item.created) }}</view>
								<view class="text-gray">
									<text class="icon-attentionfill margin-xs"></text>
									{{ item.views }}
									<text class="icon-appreciatefill margin-xs"></text>
									{{ item.likes }}
									<text class="icon-messagefill margin-xs"></text>
									{{ item.commentsNum }}
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>
		</view>
	</block>
</template>

<script>
	export default {
		data() {
			return {
				loadProgress: 0,
				curPage: 0,
				postData: [],
			}
		},
		methods: {
			formatTime(value) {
				// return this.$moment.unix(value).fromNow()
				return this.$moment.unix(value).fromNow()
			},
			seePost(cid) {
				wx.navigateTo({
					url: './post?cid=' + cid
				})
			},
			loadProcess() {
				var Interval = setInterval(() => {
					if (this.loadProgress < 100) {
						this.loadProgress = this.loadProgress + 1;
					} else {
						this.loadProgress = 0;
						clearInterval(Interval)
					}
				}, 100);
			},
			async loadPost() {
				this.loadProcess()
				let res = await this.$api.getRecentPost({
					page: ++this.curPage
				})
				this.loadProgress = 100
				if (res != null &&
					res.length > 0) {
					console.log("修改前", this.postData)
					this.postData = this.postData.concat(res)
					console.log("修改后", this.postData)
				}
			}
		},
		async onLoad(options) {
			this.loadPost()
		},
		onReachBottom() {
			console.log("触底事件")
			this.loadPost()
		}
	}
</script>
<style lang="less">
	// 博客内容的统计
	.intro {
		width: 506rpx;
		height: 112rpx;
		position: relative;
		color: #888;
		background-color: #656565;
	}

	// avatar的view容器
	.avatar-view {
		height: 110rpx;
	}

	// 头像
	.avatar {
		width: 200rpx;
		height: 200rpx;
		border-radius: 50%;
		top: -100rpx;
		margin: auto;
		border: 5rpx #eee solid;
	}

	.bg-img image {
		width: 506rpx;
		height: 200rpx;
	}

	.about {
		margin: auto;
		text-align: center;
		width: 506rpx;
		background-color: #545454;
		margin-top: 80%;
	}

	page {
		// background-image: var(--gradualBlack);
		width: 100vw;
	}

	.image {
		max-height: 400upx;
	}
</style>
