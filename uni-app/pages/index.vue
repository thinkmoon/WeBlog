<template>
	<block>
		<scroll-view class="DrawerWindow" :class="!drawerHidden ? 'show' : ''">
			<view class="about shadow-lg radius animation-" :class="!drawerHidden ? 'animation-slide-left' : ''">
				<view class="bg-img">
					<image mode="aspectFill" src="https://www.thinkmoon.cn/usr/themes/armx/img/about_bg.png"></image>
				</view>
				<view class="avatar-view">
					<image class="avatar" :src="authorInfo.avatarUrl"></image>
				</view>
				<view class="intro shadow grid col-4 padding-sm solid-bottom">
					<view class="solid-left">
						文章
						<view>{{ Overview.posts[0].Num }}</view>
					</view>
					<view class="solid-left">
						留言
						<view>{{ Overview.comments[0].Num }}</view>
					</view>
					<view class="solid-left">
						分类
						<view>{{ Overview.categorys[0].Num }}</view>
					</view>
					<view>
						标签
						<view>{{ Overview.tags[0].Num }}</view>
					</view>
				</view>
			</view>
		</scroll-view>
		<scroll-view scroll-y class="DrawerPage" :class="!drawerHidden ? 'show' : ''">
			<view class="cu-custom" :style="'height:' + CustomBar + 'px;'">
				<view class="cu-bar fixed bg-gradual-black" :style="'height:' + CustomBar + 'px;padding-top:' + StatusBar + 'px;'">
					<view class="cu-avatar round" @click="showDrawer" :style="'background-image:url(' + authorInfo.avatarUrl + ');'"
					 v-if="authorInfo.avatarUrl"></view>
					<view class="content text-shadow text-white text-sm" :style="'top:' + StatusBar + 'px;'">指尖魔法屋</view>
				</view>
			</view>
			<view class="cu-card case">
				<view class="load-progress" :class="loadProgress!=0?'show':'hide'" :style="[{top:CustomBar+'px'}]">
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
							<view class="cu-item">
								<view class="cu-avatar round lg" :style="'background-image:url(' + authorInfo.avatarUrl + ');'" v-if="authorInfo.avatarUrl"></view>
								<view class="content flex-sub">
									<view class="text-green">{{ authorInfo.screenName }}</view>
									<view class="text-gray text-sm flex justify-between">
										{{ item.created | formatTime }}
										<view class="text-gray text-sm">
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
		</scroll-view>
		<view class="DrawerClose" :class="!drawerHidden ? 'show' : ''" @click="hideDrawer"><text class="icon-pullright"></text></view>
	</block>
</template>

<script>
	import {
		mapState
	} from 'vuex'
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
		computed: {
			...mapState(['authorInfo'])
		},
		methods: {
			seePost(cid) {
				wx.navigateTo({
					url: './post?cid=' + cid
				})
			},
			showDrawer(e) {
				this.drawerHidden = false
			},
			hideDrawer(e) {
				this.drawerHidden = true
			},
			async initPost(pageOffset) {
				this.postData = []
				const _this = this;
				var loadProcess = setInterval(() => {
					if (_this.loadProgress < 100) {
						_this.loadProgress = _this.loadProgress + 3;
					} else {
						_this.loadProgress = 0;
						clearInterval(loadProcess)
					}
				}, 300);
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
		background-image: var(--gradualBlack);
		width: 100vw;
		overflow: hidden;
	}

	.DrawerPage {
		position: fixed;
		width: 100vw;
		height: 100vh;
		left: 0vw;
		background-color: #f1f1f1;
		transition: all 0.6s;
	}

	.DrawerPage.show {
		transform: scale(0.9, 0.9);
		left: 85vw;
		box-shadow: 0 0 60rpx rgba(0, 0, 0, 0.2);
		transform-origin: 0;
	}

	.DrawerWindow {
		display: flex;
		align-items: center;
		justify-content: center;
		position: absolute;
		width: 85vw;
		height: 100vh;
		left: 0;
		top: 0;
		transform: scale(0.9, 0.9) translateX(-100%);
		opacity: 0;
		pointer-events: none;
		transition: all 0.6s;
	}

	.DrawerWindow.show {
		transform: scale(1, 1) translateX(0%);
		opacity: 1;
		pointer-events: all;
	}

	.DrawerClose {
		position: absolute;
		width: 40vw;
		height: 100vh;
		right: 0;
		top: 0;
		color: transparent;
		padding-bottom: 30rpx;
		display: flex;
		align-items: flex-end;
		justify-content: center;
		background-image: linear-gradient(90deg, rgba(0, 0, 0, 0.01), rgba(0, 0, 0, 0.6));
		letter-spacing: 5px;
		font-size: 50rpx;
		opacity: 0;
		pointer-events: none;
		transition: all 0.6s;
	}

	.DrawerClose.show {
		opacity: 1;
		pointer-events: all;
		width: 15vw;
		color: #fff;
	}

	.DrawerPage .cu-bar.tabbar .action button.icon {
		width: 64rpx;
		height: 64rpx;
		line-height: 64rpx;
		margin: 0;
		display: inline-block;
	}

	.DrawerPage .cu-bar.tabbar .action .cu-avatar {
		margin: 0;
	}

	.DrawerPage .nav {
		flex: 1;
	}

	.DrawerPage .nav .cu-item.cur {
		border-bottom: 0;
		position: relative;
	}

	.DrawerPage .nav .cu-item.cur::after {
		content: '';
		width: 10rpx;
		height: 10rpx;
		background-color: currentColor;
		position: absolute;
		bottom: 10rpx;
		border-radius: 10rpx;
		left: 0;
		right: 0;
		margin: auto;
	}

	.DrawerPage .cu-bar.tabbar .action {
		flex: initial;
	}

	.image {
		max-height: 400upx;
	}
</style>
