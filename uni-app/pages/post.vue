<template>
	<block>
		<!-- <cu-custom bgColor="bg-gradual-black" :isBack="true"></cu-custom> -->
		<view class="cu-bar bg-white padding-top ">
			<view class="action">
				<!-- <text class="icon-titles text-green"></text> -->
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
				点赞(12)
			</view>
			<view>
				<text class="icon-comment"></text>
				评论({{ postData[0].commentsNum }})
			</view>
		</view>
		<towxml :content="postData[0].text" v-if="postData[0].text"></towxml>
		<view class="like-area solid-top bg-white tm-every-center flex-direction">
			<view class="like-btn text-xxl line-green tm-every-center padding border" @click="like" v-if="!isLike">赞</view>
			<view class="like-btn text-xxl line-red tm-every-center padding border" v-else @click="reward">赏</view>
			<view class="margin-top">您的支持是对我最大的鼓励</view>
		</view>
		<view class="margin-sm" v-if="!isLoading">
			<view class="text-lg">
				<text class="icon-titles text-green"></text>
				<text class="text-bold">发表评论</text>
			</view>
			<view class="comment-area bg-white margin-top padding">
				<textarea value="" placeholder="本是青灯不归客,却因杯酒恋红尘" class="solid padding" />
				<view class="margin-top-sm flex justify-between">
					<view class="text-bold" v-if="!isLogin">
						<button v-if="canIUse" open-type="getUserInfo" @getuserinfo="loadUserInfo" class="cu-btn bg-green"><text class="line-white icon-weixin">授权登录</text></button>
						<view v-else>请升级微信版本</view>
					</view>
					<view class="text-bold" v-else>
						<open-data type="userNickName" class="line-green"></open-data>
					</view>
					<view><button class="cu-btn line-green" @click="comment">发表</button></view>
				</view>
			</view>
		</view>
		<tm-footer></tm-footer>
		<view class="cu-load load-modal" v-if="isLoading">
			<view class="icon-emojifill text-orange"></view>
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
			postData: [{ created: 0 }],
			canIUse: wx.canIUse('button.open-type.getUserInfo'),
			isLogin: false,
			isLoading: true,
			isLike:false
		}
	},
	filters: {
		formatTime(value) {
			return moment.unix(value).format('YYYY年MM月DD日')
		}
	},
	methods:{
		comment(){
			uni.showToast({
				icon:"none",
				title:"该功能未开放"
			})
		},
		loadUserInfo(res){
			console.log("授权登录",res.detail.errMsg)
			if(res.detail.errMsg != "getUserInfo:fail auth deny"){
				this.isLogin = true
			}
		},
		like(){
			this.$api.likePost({cid:this.cid})
			this.isLike = true
		},
		reward(){
			wx.previewImage({
			  current: 'https://www.thinkmoon.cn/usr/themes/armx/img/weixinpay.png', // 当前显示图片的http链接
			  urls: ['https://www.thinkmoon.cn/usr/themes/armx/img/weixinpay.png'] // 需要预览的图片http链接列表
			})
		}
	},
	async onLoad(query) {
		this.cid = query.cid
		this.postData = await this.$api.getPostBycid({ cid: query.cid })
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
		   			   cid:this.cid
		   })
		   console.log("获取点赞状态",data)
		   this.isLike = JSON.parse(data)
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
.comment-area {
	height: 400upx;
}

.comment-area textarea {
	width: 650upx;
	height: 250upx;
}

.border {
	border: 1px solid;
}
</style>
