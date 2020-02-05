<template>
  <block>
    <view class="cu-card case">
      <!-- <view class="load-progress" :class="loadProgress!=0?'show':'hide'">
				<view class="load-progress-bar bg-green" :style="[{transform: 'translate3d(-' + (100-loadProgress) + '%, 0px, 0px)'}]"></view>
				<view class="load-progress-spinner text-green"></view>
			</view> -->
      <!-- 			<view class="margin flex" v-if="postData.length == 0" style="min-height: 60vh;">
				<view class="bg-white flex-sub radius shadow-lg" style="padding-top: 200upx;">
					<image src="https://image.weilanwl.com/gif/loading-white.gif" mode="aspectFit" class="gif-white response" style="height:240upx"></image>
				</view>
			</view> -->
      <view v-for="(item, index) in postData" :key="index" class="margin list" style="overflow: hidden;">
        <!-- #ifdef MP-QQ -->
        <ad unit-id="750221a1c0d4c6f021ab39df00a40ae7" type="feeds" v-if="index % 4 == 3" class="ad"></ad>
        <!-- #endif -->
        <navigator :url='"/pages/post?cid=" + item.cid + "&thumb=" + item.thumb[0].str_value' class="list__item">
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
        </navigator>
      </view>
      <view style="padding-bottom: 50px;">
        <view class="action">
          <view class="cu-load load-icon" :class="isLoading?'loading':'over'"></view>
        </view>
      </view>
    </view>
  </block>
</template>

<script>
  export default {
    data() {
      return {
        // loadProgress: 0,
        isLoading: true,
        curPage: 0,
        postData: [],
      }
    },
    methods: {
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
    async onLoad(options) {
      this.loadPost()
    },
    onReachBottom() {
      console.log("触底事件")
      this.loadPost()
    },
    onPullDownRefresh() {
      this.curPage = 0
      this.postData = []
      this.loadPost()
    }
  }
</script>
<style lang="scss">
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
  }

  // list动画
  .list {
    &__item {
      animation: list 1s ease both;
    }
  }

  @keyframes list {
    0% {
      transform: scale(0);
    }

    100% {
      transform: scale(1);
    }
  }

  @mixin item($num) {
    $waitTime: ($num)*0.2;
    animation-delay: #{$waitTime}s;
  }

  @for $i from 1 through 7 {
    .list__item:nth-child(#{$i}) {
      @include item($i);
    }
  }

  // --------------
</style>
