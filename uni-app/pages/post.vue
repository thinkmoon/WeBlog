<template>
	<block>
		<view class="cu-bar bg-white padding-top ">
			<view class="action">
				<text class="text-xxl text-bold text-black">{{
          postData[0].title
        }}</text>
			</view>
		</view>
		<view class="bg-white flex bg-white solid-bottom padding-bottom-sm padding-top-sm justify-around text-sm">
			<view>
				<text class="icon-time"></text>
				{{ formatTime(postData[0].created)}}
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
		<!-- #ifdef MP-QQ -->
		<ad unit-id="53bfa608c0f8bfad5ef40eddb665f864" class="ad"></ad>
		<!-- #endif -->
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
						<view class="cu-avatar round sm" v-for="(item, index) in likeUsers" v-if="index < 5" :key="index" :style="'background-image:url(' + item.avatarUrl + ');'"></view>
					</view>
					<text class="text-grey text-sm">{{ likeUsers[0].nickName }}等{{ likeUsers.length }}人觉得很赞</text>
				</view>
				<!-- #ifndef MP-QQ -->
				<textarea v-model="commentText" placeholder="(已开启评论审核模式,评论审核通过后方能显示)" class="solid padding margin-top-sm" />
				<!-- #endif -->
				<view class="margin-top-sm flex justify-between">
          <view style="width:46%" v-if="!isLogin">
            <button
              v-if="canIUse"
              open-type="getUserInfo"
              @getuserinfo="loadUserInfo"
              class="cu-btn bg-green"
							style="width:100%;margin-right: ;"
            >
              <text class="line-white">授权登录</text>
            </button>
            <view v-else>请升级微信版本</view>
          </view>
          <view class="text-bold" v-else>
            <open-data type="userNickName" class="line-green"></open-data>
          </view>
          <view style="width:46%">
							<!-- #ifndef MP-QQ -->
							<button style="width:100%" class="cu-btn line-green" @click="comment">发表</button>
							<!-- #endif -->
          </view>
        </view>
      </view>
    </view>
	<!-- #ifndef MP-QQ -->
    <view class="margin-sm" v-if="!isLoading">
      <view class="text-lg">
        <text class="icon-titles text-green"></text>
        <text class="text-bold">精彩评论</text>
      </view>
      <view class="comment-area bg-white margin-top padding">
        <view
          v-for="(item, index) in commentList"
          :key="index"
          class="margin-bottom"
        >
          <view class="flex align-center">
            <view
              class="cu-avatar sm round"
              :style="'background-image:url(' + item.avatarUrl + ')'"
            >
              <view
                class="cu-tag badge"
                :class="
                  item.gender == 2 ? 'icon-female bg-pink' : 'icon-male bg-blue'
                "
                style="font-size: 18rpx;width:24rpx;height:24rpx;"
              ></view>
            </view>
            <view class="solid-bottom padding-bottom-xs margin-left-xs">
              <text class="text-grey text-sm">{{ item.nickName }}</text>
              <text class="text-grey text-sm margin-left-xs">{{
                formatTime (item.created)
              }}</text>
            </view>
          </view>
          <view style="margin-left: 64rpx;">
            <text>{{ item.text }}</text>
          </view>
        </view>
      </view>
    </view>
	<!-- #endif -->
	<!-- #ifdef MP-QQ -->
	<ad unit-id="35cc08ee6d98e478f658c5acd1c2c11c" type="card"></ad>
	<!-- #endif -->
    <tm-footer></tm-footer>
    <view class="cu-load load-modal" v-if="isLoading">
      <view class="icon-emojifill text-green"></view>
      <view class="gray-text">加载中...</view>
    </view>
  </block>
</template>

<script>
export default {
	data() {
		return {
			cid: null,
			thumb:"",
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
	methods: {
		formatTime(value) {
			return this.$moment.unix(value).format('YYYY年MM月DD日')
		},
		async comment() {
			if(!this.isLogin){
				uni.showToast({
					icon:'none',
					title:"发表评论需要先授权登录"
				})
				return 
			}
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
			if(!this.isLogin){
				uni.showToast({
					icon:'none',
					title:"点赞需要先授权登录"
				})
				return 
			}
			this.$api.likePost({
				cid: this.cid
			})
			this.isLike = true
		},
		// #ifdef MP-WEIXIN
		reward() {
			wx.previewImage({
				current: 'https://www.thinkmoon.cn/usr/themes/armx/img/weixinpay.jpg', // 当前显示图片的http链接
				urls: ['https://www.thinkmoon.cn/usr/themes/armx/img/weixinpay.jpg'] ,// 需要预览的图片http链接列表
				})
		},
		// #endif
		// #ifdef MP-QQ
		reward() {
			let videoAd = qq.createRewardedVideoAd({
			              adUnitId: "2623e52894edca46483527a4e2355e2e"
			            })
			
			            videoAd.onError(function(res){
			              console.log('videoAd onError',res)
			            })
			            videoAd.onLoad(function(res){
			              console.log('videoAd onLoad',res)
			            })
			            videoAd.onClose(function(res){
			              console.log('videoAd onClose',res)
			            })
			            
			            videoAd.load()
			              .then(() => {
			                console.log('激励视频加载成功');
			                videoAd.show().then(() => {
			                  console.log('激励视频 广告显示成功')
			                })
			                .catch(err => {
			                  console.log('激励视频 广告显示失败')
			                })
			              })
			              .catch(err => {
			                console.log('激励视频加载失败');
			              })
		},
		// #endif
	},
	async onLoad(query) {
		this.cid = query.cid
		this.thumb = query.thumb
		this.postData = await this.$api.getPostBycid({
			cid: query.cid
		})
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
		// #ifndef APP-PLUS
		data = await this.$api.getComment({
			cid: this.cid
		})
		this.commentList = data
		// #endif
	},
	onShareAppMessage() {
		return {
			title: this.postData[0].title,
			path: this.$mp.page.route + "?" + this.$qs.stringify(this.$mp.query, {
						encode: false
					}),
			imageUrl: this.thumb
		};
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
.ad {
	background-color: #fff;
	padding-top: 10upx;
}
</style>
