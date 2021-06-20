<template>
  <view class="bg-white">
    <view class="cu-bar bg-white padding-top">
      <view class="action">
        <text class="text-xxl text-bold text-black">{{ postData.title }}</text>
      </view>
    </view>
    <view class="bg-white flex bg-white solid-bottom padding-bottom-sm padding-top-sm justify-around text-sm">
      <view class="flex align-center">
        <text class="icon-time padding-right-xs"></text>
        {{ formatTime(postData.created) }}
      </view>
      <view class="flex align-center">
        <text class="icon-file padding-right-xs"></text>
        {{ postData.category }}
      </view>
      <view class="flex align-center">
        <text class="icon-like padding-right-xs"></text>
        点赞({{ postData.likes }})
      </view>
      <view class="flex align-center">
        <text class="icon-comment padding-right-xs"></text>
        评论({{ postData.commentsNum }})
      </view>
    </view>
    <!-- #ifdef MP-QQ -->
    <ad unit-id="53bfa608c0f8bfad5ef40eddb665f864" class="ad"></ad>
    <!-- #endif -->
    <!-- #ifdef MP-WEIXIN -->
    <ad unit-id="adunit-177073f05d52fdd3" class="ad"></ad>
    <!-- #endif -->
    <!-- #ifndef H5 -->
    <parse :content="postData.text" v-if="!isLoading"></parse>
    <!-- #endif -->
    <!-- #ifdef H5 -->
    <div v-html="content" class="article-content"/>
    <!-- #endif -->
    <view class="operation-area solid-top bg-white tm-every-center padding-top" v-if="!isLoading">
      <div @click="like" class="padding-xs line-blue solid margin-right">
        <text class="icon-appreciate padding-right-xs"></text>
        赞
        {{ postData.likes | formatNum }}
      </div>
      <!-- <div @click="reward" class="padding-xs margin-right line-red solid"><text class="icon-redpacket padding-right-xs"></text>赏</div> -->
      <!-- <div @click="reward" class="padding-xs margin-right line-orange solid"><text class="icon-favor padding-right-xs"></text>收藏</div> -->
      <div @click="share" class="padding-xs line-green solid">
        <text class="icon-attentionfill padding-right-xs"></text>
        阅读 {{ postData.views | formatNum }}
      </div>
    </view>
    <view class="comment-area padding-sm bg-white" v-if="!isLoading">
      <view class="text-lg">
        <text class="icon-titles color-base"></text>
        <text class="text-bold">发表看法</text>
      </view>
      <!-- #ifndef MP-QQ -->
      <textarea v-model="commentText" placeholder="(已开启评论审核模式,评论审核通过后方能显示)" class="solid padding margin-top-sm"/>
      <!-- #endif -->
      <view class="margin-top-sm flex justify-between">
        <view v-if="!isLogin">
          <button v-if="canIUse" open-type="getUserInfo" @getuserinfo="loadUserInfo" class="cu-btn bg-base">
            <text class="line-white">授权登录</text>
          </button>
          <view v-else>请升级微信版本</view>
        </view>
        <view class="text-bold" v-else>
          <!-- #ifndef H5 -->
          <open-data type="userNickName" class="line-base"></open-data>
          <!-- #endif -->
        </view>
        <view>
          <!-- #ifndef MP-QQ -->
          <button style="width: 100%;" class="cu-btn line-base" @click="comment">
            发表
          </button>
          <!-- #endif -->
        </view>
      </view>
    </view>
    <!-- #ifndef MP-QQ -->
    <view class="padding-sm bg-white" v-if="!isLoading">
      <view class="text-lg">
        <text class="icon-titles color-base"></text>
        <text class="text-bold">精彩评论</text>
      </view>
      <view class="comment-area bg-white margin-top padding-xs">
        <view v-for="(item, index) in commentList" :key="index" class="margin-bottom">
          <view class="flex align-center">
            <view class="cu-avatar sm round" :style="'background-image:url(' + item.avatarUrl + ')'">
              <view
                  class="cu-tag badge"
                  :class="item.gender == 2 ? 'icon-female bg-pink' : 'icon-male bg-blue'"
                  style="font-size: 18rpx; width: 24rpx; height: 24rpx;"
              ></view>
            </view>
            <view class="solid-bottom padding-bottom-xs margin-left-xs">
              <text class="text-grey text-sm">{{ item.nickName }}</text>
              <text class="text-grey text-sm margin-left-xs">{{ formatTime(item.created) }}</text>
            </view>
          </view>
          <view class="padding-left-lg">
            <text>{{ item.text }}</text>
          </view>
        </view>
      </view>
    </view>
    <!-- #endif -->
    <!-- #ifdef MP-QQ -->
    <ad unit-id="35cc08ee6d98e478f658c5acd1c2c11c" type="card" style="margin-bottom:10px;
"></ad>
    <!-- #endif -->
    <!-- #ifdef MP-WEIXIN -->
    <ad
        unit-id="adunit-ec07e753eb4e9dc8"
        ad-theme="white"
        ad-type="video"
        style="margin-bottom:10px;"
    ></ad>
    <!-- #endif -->
    <tm-footer></tm-footer>
    <view class="modal bg-white" v-if="isLoading">
      <view class="spinner bg-base"></view>
    </view>
  </view>
</template>

<script lang="js">
import Vue from 'vue';
import {marked} from "@/utils/marked/index";
// #ifdef MP-QQ
let videoAd = qq.createRewardedVideoAd({
  adUnitId: '2623e52894edca46483527a4e2355e2e',
});
videoAd.onError(function (res) {
  console.log('videoAd onError', res);
});
videoAd.onLoad(function (res) {
  console.log('videoAd onLoad', res);
});
videoAd.onClose(function (res) {
  console.log('videoAd onClose', res);
});
// #endif

export default Vue.extend({
  data() {
    return {
      cid: null,
      thumb: '',
      postData: {
        created: 0,
        category: '',
        text: '',
        title: '',
      },
      canIUse: wx.canIUse('button.open-type.getUserInfo'),
      isLogin: false,
      isLoading: true,
      isLike: false,
      likeUsers: [],
      commentText: null,
      commentList: [],
    };
  },
  computed: {
    content() {
      return marked(this.postData.text.replace("<!--markdown-->", ""), {breaks: true});
    },
  },
  filters: {
    formatNum(value) {
      if (value > 1000000) {
        return (value / 1000000).toFixed(1) + 'm';
      } else if (value > 1000) {
        return (value / 1000).toFixed(1) + 'k';
      }
      return value;
    },
  },
  methods: {
    formatTime(value) {
      return this.$moment.unix(value).format('YYYY年MM月DD日');
    },
    async comment() {
      if (!this.isLogin) {
        uni.showToast({
          icon: 'none',
          title: '发表评论需要先授权登录',
        });
        return;
      }
      if (this.commentText == null) {
        uni.showToast({
          icon: 'none',
          title: '请先输入内容',
        });
        return;
      }
      let coid = await this.$api.addComment({
        cid: this.cid,
        text: this.commentText,
      });
      console.log('发表评论', coid);
      this.commentText = null;
      uni.showToast({
        icon: 'none',
        title: '评论成功,等待审核',
      });
    },
    loadUserInfo(res) {
      console.log('授权登录', res.detail.errMsg);
      if (res.detail.errMsg != 'getUserInfo:fail auth deny') {
        this.isLogin = true;
      }
      uni.login({
        success: async (res) => {
          console.log('尝试登录', res);
          if (res.code) {
            //发起网络请求
            uni.getSetting({
              success: async (setting) => {
                if (setting.authSetting['scope.userInfo']) {
                  // 已经授权，可以直接调用 getUserInfo 获取头像昵称、
                  console.log('用户已授权', setting);
                  uni.getUserInfo({
                    success: async (Info) => {
                      console.log('尝试获取用户信息', Info);
                      let data = {
                        code: res.code,
                      };
                      Object.assign(data, Info.userInfo);
                      let openid = await this.$api.login(data);
                      uni.setStorageSync('openid', openid);
                    },
                  });
                } else {
                  let openid = await this.$api.login({
                    code: res.code,
                  });
                  uni.setStorageSync('openid', openid);
                }
              },
            });
          } else {
            console.log('登录失败！' + res.errMsg);
          }
        },
      });
    },
    like() {
      if (!this.isLogin) {
        uni.showToast({
          icon: 'none',
          title: '点赞需要先授权登录',
        });
        return;
      }
      if (this.isLike) {
        uni.showToast({
          icon: 'none',
          title: '你已为该文章点过赞啦~',
        });
        return;
      } else {
        this.$api
            .likePost({
              cid: this.cid,
            })
            .then((res) => {
              this.postData.likes++;
              this.isLike = true;
            });
      }
    },
    reward() {
      // #ifdef MP-WEIXIN
      wx.previewImage({
        current: 'https://www.thinkmoon.cn/usr/themes/armx/img/weixinpay.jpg', // 当前显示图片的http链接
        urls: ['https://www.thinkmoon.cn/usr/themes/armx/img/weixinpay.jpg'], // 需要预览的图片http链接列表
      });
      // #endif
      // #ifdef MP-QQ
      videoAd
          .load()
          .then(() => {
            console.log('激励视频加载成功');
            videoAd
                .show()
                .then(() => {
                  console.log('激励视频 广告显示成功');
                })
                .catch((err) => {
                  console.log('激励视频 广告显示失败');
                });
          })
          .catch((err) => {
            console.log('激励视频加载失败');
          });
      // #endif
    },
  },
  onLoad(query) {
    this.cid = query.cid;
    this.thumb = query.thumb;
    this.$api
        .getPostBycid({
          cid: query.cid,
        })
        .then((res) => {
          this.postData = res;
          this.isLoading = false;
          this.$nextTick(() => {
            window.hljs.highlightAll();
          });
        });
  },
  created() {
    // #ifndef H5
    uni.getSetting({
      success: (res) => {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称
          this.isLogin = true;
        }
      },
    });
    // #endif
    this.$api
        .getPostLikeStatus({
          cid: this.cid,
        })
        .then((res) => {
          console.log('获取点赞状态', res);
          this.isLike = JSON.parse(res);
        });
    this.$api
        .getComment({
          cid: this.cid,
        })
        .then((res) => {
          this.commentList = res;
        });
  },
  onShareAppMessage() {
    let shareData = {
      title: this.postData.title,
      path: this.$mp.page.route + '?' +
          this.$qs.stringify(this.$mp.query, {encode: false,}),
      imageUrl: this.thumb,
    };
    console.log('分享Data', shareData);
    return shareData;
  },
  onShareTimeline() {
    return {
      title: this.postData.title,
      imageUrl: this.thumb,
    };
  },
});
</script>
<style lang="scss">

.operation-area {
  text-align: center;

  ._div {
    padding: 16px 22px;
  }
}

.like-list {
  width: 90%;
  text-align: center;

  view {
    margin: 2 upx;
  }
}

.like-btn {
  height: 130 upx;
  width: 130 upx;
  border-radius: 50%;
}

.comment-area textarea {
  width: 100%;
  height: 250 upx;
}

.border {
  border: 1px solid;
}

.ad {
  background-color: #fff;
  margin-top: 10 upx;
}
</style>
