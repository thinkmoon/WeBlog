<template>
	<block>
		<block>

		</block>
		<view class="cu-bar bg-white padding-top ">
			<view class="action">
				<text class="text-xxl text-bold text-black">{{ postData[0].title }}</text>
			</view>
		</view>
		<view class="bg-white flex bg-white solid-bottom padding-bottom-sm padding-top-sm justify-around text-sm">
			<view>
				<text class="icon-time"></text>
				{{ postData[0].created | formatTime }}
			</view>
			<view>
				<text class="icon-file"></text>
				学习笔记
			</view>
			<view>
				<text class="icon-like"></text>
				点赞({{ postData[0].likes }})
			</view>
			<view>
				<text class="icon-comment"></text>
				评论({{ postData[0].commentsNum }})
			</view>
		</view>
		<towxml :content="postData[0].text" v-if="postData[0].text"></towxml>
		<view class="like-area solid-top bg-white tm-every-center flex-direction" v-if="!isLoading">
			<view class="like-btn text-xxl line-green tm-every-center padding border" @click="like" v-if="!isLike">赞</view>
			<view class="like-btn text-xxl line-red tm-every-center padding border" v-else @click="reward">赏</view>
			<view class="margin-top">您的支持是对我最大的鼓励</view>
		</view>
		<view class="margin-sm" v-if="!isLoading">
			<view class="text-lg">
				<text class="icon-titles text-green"></text>
				<text class="text-bold">发表看法</text>
			</view>
			<view class="comment-area bg-white margin-top padding">
				<view class="action" v-if="likeUsers != null">
					<view class="cu-avatar-group">
						<view class="cu-avatar round sm" v-for="(item,index) in likeUsers" v-if="index < 5" :key="index" :style="'background-image:url('+item.avatarUrl+');'"></view>
					</view>
					<text class="text-grey text-sm">{{ likeUsers[0].nickName }} 等 {{ likeUsers.length }} 人觉得很赞</text>
				</view>
				<textarea v-model="commentText" placeholder="(已开启评论审核模式,评论审核通过后方能显示)" class="solid padding margin-top-sm" />
				<view class="margin-top-sm flex justify-between">
					<view class="text-bold" v-if="!isLogin">
						<button v-if="canIUse" open-type="getUserInfo" @getuserinfo="loadUserInfo" class="cu-btn bg-green"><text class="line-white icon-weixin">授权登录</text></button>
						<view v-else>请升级微信版本</view>
					</view>
					<view class="text-bold" v-else>
						<open-data type="userNickName" class="line-green"></open-data>
					</view>
					<view>
						<button class="cu-btn line-green" @click="comment">发表</button>
					</view>
				</view>
			</view>
		</view>
		<view class="margin-sm" v-if="!isLoading">
			<view class="text-lg">
				<text class="icon-titles text-green"></text>
				<text class="text-bold">精彩评论</text>
			</view>
			<view class="comment-area bg-white margin-top padding">
				<view v-for="(item,index) in commentList" class="margin-bottom">
					<view  class="flex align-center">
						<view class="cu-avatar sm round" :style="'background-image:url('+ item.avatarUrl +')'">
							<view class="cu-tag badge" :class="item.gender==2?'icon-female bg-pink':'icon-male bg-blue'" style="font-size: 18rpx;width:24rpx;height:24rpx;"></view>
						</view>
						<view class="solid-bottom padding-bottom-xs margin-left-xs">
							<text class="text-grey text-sm">{{ item.nickName }}</text>
							<text class="text-grey text-sm margin-left-xs">{{ item.created | formatTime}}</text>
						</view>
					</view>
					<view style="margin-left: 64rpx;">
						<text>{{ item.text }}</text>
					</view>
				</view>
			</view>
		</view>
		<tm-footer></tm-footer>
		<view class="cu-load load-modal" v-if="isLoading">
			<view class="icon-emojifill text-green"></view>
			<view class="gray-text">加载中...</view>
		</view>
	</block>
</template>

<script>
	import moment from 'moment'
	import 'moment/locale/zh-cn'
	moment.locale('zh-cn')
	export default {
		data() {
			return {
				cid: null,
				postData: [{
					created: 0
				}],
				canIUse: wx.canIUse('button.open-type.getUserInfo'),
				isLogin: false,
				isLoading: true,
				isLike: false,
				likeUsers: [],
				commentText:null,
				commentList:[]
			}
		},
		filters: {
			formatTime(value) {
				return moment.unix(value).format('YYYY年MM月DD日')
			}
		},
		methods: {
			async comment() {
				if(this.commentText == null){
					return
				}
				let coid = await this.$api.addComment({
					cid: this.cid,
					text: this.commentText
				})
				console.log("发表评论",coid)
				this.commentText = null
				uni.showToast({
					icon:"none",
					title:"评论成功,等待审核"
				})
			},
			loadUserInfo(res) {
				console.log("授权登录", res.detail.errMsg)
				if (res.detail.errMsg != "getUserInfo:fail auth deny") {
					this.isLogin = true
				}
				wx.login({
					success: async res => {
						console.log("尝试登录", res)
						if (res.code) {
							//发起网络请求
							wx.getSetting({
								success: async setting => {
									if (setting.authSetting['scope.userInfo']) {
										// 已经授权，可以直接调用 getUserInfo 获取头像昵称、
										console.log("用户已授权", setting)
										wx.getUserInfo({
											success: async Info => {
												console.log("尝试获取用户信息", Info)
												let data = {
													code: res.code
												}
												Object.assign(data, Info.userInfo);
												let openid = await this.$api.login(data)
												uni.setStorageSync('openid', openid)
											}
										})
									} else {
										let openid = await this.$api.login({
											code: res.code
										})
										uni.setStorageSync('openid', openid)
									}
								}
							})
						} else {
							console.log('登录失败！' + res.errMsg)
						}
					}
				})
			},
			like() {
				this.$api.likePost({
					cid: this.cid
				})
				this.isLike = true
			},
			reward() {
				wx.previewImage({
					current: 'https://www.thinkmoon.cn/usr/themes/armx/img/weixinpay.jpg', // 当前显示图片的http链接
					urls: ['https://www.thinkmoon.cn/usr/themes/armx/img/weixinpay.jpg'] // 需要预览的图片http链接列表
				})
			}
		},
		async onLoad(query) {
			this.cid = query.cid
			this.postData = await this.$api.getPostBycid({
				cid: query.cid
			})
			console.log('请求成功')
			this.isLoading = false
		},
		async onShow() {
			wx.getSetting({
				success: res => {
					if (res.authSetting['scope.userInfo']) {
						// 已经授权，可以直接调用 getUserInfo 获取头像昵称
						this.isLogin = true
					}
				}
			})
			let data = await this.$api.getPostLikeStatus({
				cid: this.cid
			})
			console.log("获取点赞状态", data)
			this.isLike = JSON.parse(data)
			data = await this.$api.getLikeUsers({
				cid: this.cid
			})
			console.log("文章点赞用户列表", data)
			this.likeUsers = data
			data = await this.$api.getComment({
				cid: this.cid
			})
			this.commentList = data
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
	}

	.comment-area textarea {
		width: 650upx;
		height: 250upx;
	}

	.border {
		border: 1px solid;
	}
</style>
