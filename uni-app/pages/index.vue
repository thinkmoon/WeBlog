<template name="home">
	<block>
		<scroll-view scroll-y class="cu-card case scroll-view">
			<view class="cu-bar search bg-white" @click="toSearch">
				<view class="search-form round">
					<text class="icon-search"></text>
					<view :adjust-position="false" type="text" placeholder="" confirm-type="search">搜索分类、文章、标签</view>
				</view>
			</view>
			<!-- <swiper class="card-swiper margin-top margin-bottom" :class="dotStyle?'square-dot':'round-dot'" :indicator-dots="true" :circular="true"
			 :autoplay="true" interval="5000" duration="500" @change="cardSwiper" indicator-color="#8799a3"
			 indicator-active-color="#0081ff">
				<swiper-item v-for="(item,index) in swiperList" :key="index" :class="cardCur==index?'cur':''" style="padding: 0;">
					<view class="swiper-item">
						<image :src="item.url" mode="aspectFill" v-if="item.type=='image'"></image>
						<video :src="item.url" autoplay loop muted :show-play-btn="false" :controls="false" objectFit="cover" v-if="item.type=='video'"></video>
					</view>
				</swiper-item>
			</swiper>
			<view class="cu-bar bg-white margin-top">
				<view class="action">
					<text class="cuIcon-title text-green"></text>
					<text>底部操作条</text>
				</view>
			</view> -->
			<view v-for="(item, index) in postData" :key="index" class="margin list" style="overflow: hidden; ">
				<!-- #ifdef MP-QQ -->
				<ad unit-id="750221a1c0d4c6f021ab39df00a40ae7" type="feeds" v-if="index % 4 == 3" class="ad"></ad>
				<!-- #endif -->
				<navigator :url='"/pages/post?cid=" + item.cid + "&thumb=" + item.thumb[0].str_value' class="list__item animation-scale-up">
					<view class="image">
						<image :src="item.thumb[0].str_value" mode="widthFix" :lazy-load="true"></image>
						<!-- <view class="cu-tag bg-blue">置顶</view> -->
						<view class="cu-bar text-shadow bg-shadeBottom">{{ item.title }}</view>
					</view>
					<view class="cu-list menu menu-avatar">
						<view class="cu-item" style="height: 70upx;min-height: 70upx;">
							<view class="text-gray text-sm flex justify-between align-center" style=" width: 100%;">
								<view><text class="color-base margin-right-sm">{{ item.screenName }}</text>
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
				</navigator>
			</view>
			<view style="padding-bottom: 50px;">
				<view class="action">
					<view class="cu-load load-icon" :class="isLoading?'loading':'over'"></view>
				</view>
			</view>
		</scroll-view>
	</block>
</template>

<script>
	export default {
		name: "home",
		data() {
			return {
				// loadProgress: 0,
				isLoading: true,
				curPage: 0,
				postData: [],
				cardCur: 0,
				dotStyle: false,
				towerStart: 0,
				direction: ''
			}
		},
		methods: {
			toSearch() {
				uni.navigateTo({
					url: './search/search'
				})
			},
			// cardSwiper
			cardSwiper(e) {
				this.cardCur = e.detail.current
			},
			formatTime(value) {
				// return this.$moment.unix(value).fromNow()
				return this.$moment.unix(value).fromNow()
			},
			async loadPost() {
				this.isLoading = true
				// 每隔十页清空一下，避免内存溢出
				if (this.postData.curPage % 10 == 0) {
					this.postData = []
				}
				let res = await this.$api.getRecentPost({
					page: this.curPage + 1
				})
				// this.loadProgress = 100
				if (res != null &&
					res.length > 0) {
					this.postData = this.postData.concat(res)
					this.curPage++
				}
				this.isLoading = false
				uni.stopPullDownRefresh()
			}
		},
		async onReady() {
			await this.loadPost()
			this._observer = wx.createIntersectionObserver(this)
			this._observer.relativeToViewport({
				bottom: 600
			}).observe('.action', (res) => {
				console.log(res);
				this.loadPost()
			})
		},
		pageLifetimes: {

		}
	}
</script>
<style lang="less">
	.scroll-view {
		height: 100vh;
	}

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

	.ad {
		background-color: #fff;
		margin-bottom: 10px;
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
		min-height: 300upx;
	}
</style>
