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
									{{ item.created | formatTime }}</view>
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
		<!-- 分页栏 -->
		<view class="flex justify-center padding-bottom-sm">
			<button class="cu-btn sm bg-white margin-xs" @tap="navPage(1)">首页</button>
			<button @tap="navPage(curPage * 1 - 1 * 1)" class="cu-btn sm bg-white margin-xs">上一页</button>
			<view class="cu-btn sm  margin-xs">{{curPage}}/{{pageNum}}</view>
			<button @tap="navPage(curPage * 1 + 1 * 1)" class="cu-btn sm bg-white margin-xs">下一页</button>
			<button class="cu-btn sm bg-white margin-xs" @tap="navPage(pageNum)">末页</button>
		</view>
		<tm-footer></tm-footer>
	</block>
</template>

<script>
	import moment from 'moment'
	import 'moment/locale/zh-cn'
	moment.locale('zh-cn')

	export default {
		data() {
			return {
				loadProgress: 0,
				Overview: {},
				pageNum: 0,
				curPage: 1,
				drawerHidden: true,
				postData: [],
				StatusBar: this.StatusBar,
				CustomBar: this.CustomBar,
			}
		},
		filters: {
			formatTime(value) {
				return moment.unix(value).fromNow()
			}
		},
		methods: {
			seePost(cid) {
				wx.navigateTo({
					url: './post?cid=' + cid
				})
			},
			async initPost(pageOffset) {
				this.postData = []
				const _this = this;
				var loadProcess = setInterval(() => {
					if (_this.loadProgress < 100) {
						_this.loadProgress = _this.loadProgress + 1;
					} else {
						_this.loadProgress = 0;
						clearInterval(loadProcess)
					}
				}, 100);
				this.postData = await this.$api.getRecentPost({
					page: pageOffset
				})
				_this.loadProgress = 100
			},
			navPage(page) {
				if (page <= 0 || page > this.pageNum) return
				this.curPage = page
				this.drawerHidden = this.initPost(page)
			}
		},
		async onLoad(options) {
			const _this = this
			this.pageNum = await this.$api.getPageNum()
			this.initPost(1)
			_this.Overview = await this.$api.getOverview()
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
