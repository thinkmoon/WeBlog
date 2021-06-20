<template name="home">
  <block>
    <scroll-view scroll-y class="cu-card case scroll-view" @scrolltolower="loadPost" enhanced enable-back-to-top>
      <scroll-view scroll-x class="tab-list flex bg-white solid-bottom" enable-flex="flex-box">
        <div class="tab-item padding-sm" :class="mid === '0' ? 'act' : ''" @click="changeCate('0')">全部</div>
        <div
            class="tab-item padding-sm"
            v-for="(item, index) in categoryList"
            :key="index"
            :class="mid === item.mid ? 'act' : ''"
            @click="changeCate(item.mid)"
        >
          {{ item.name }}
        </div>
      </scroll-view>
      <view class="bg-white article-list">
        <view v-for="(item, index) in postData" :key="index" class="article-container list radius shadow">
          <navigator
              hover-class="none"
              :url="`/pages/post/index?cid=${item.cid}&thumb=${item.thumb}`"
              class="list__item bg-white"
          >
            <view class="image-container">
              <view class="overplay"></view>
              <image :src="item.thumb" mode="aspectFill" :lazy-load="true" class="image"></image>
              <!-- <view class="cu-tag bg-blue">置顶</view> -->
              <!-- <view class="cu-bar text-shadow bg-shadeBottom">{{ item.title }}</view> -->
            </view>
            <view class="flex align-center padding-xs post-entry-categories text-xs">
              <view class="margin-left-xs" v-for="(tagItem, index) in item.tag" :key="index">{{ tagItem }}</view>
            </view>
            <view class="bg-white padding-left-sm title">{{ item.title }}</view>
            <view class="desc padding-sm">{{ item.desc }}</view>
            <view class="cu-list padding-lr-sm padding-bottom-xs">
              <view class="cu-item">
                <view class="text-gray text-sm flex justify-between align-center">
                  <view>
                    <text class=" margin-right-sm">
                      <text class="icon-file margin-right-xs"></text>
                      {{ item.category }}
                    </text>
                    {{ formatTime(item.created) }}
                  </view>
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
          <!-- #ifdef MP-QQ -->
          <ad unit-id="750221a1c0d4c6f021ab39df00a40ae7" type="feeds" v-if="index % 10 === 0" class="ad"></ad>
          <!-- #endif -->
          <!-- #ifdef MP-WEIXIN -->
          <ad unit-id="adunit-2b1e70c8891057dd" v-if="index % 10 === 0" class="ad"></ad>
          <!-- #endif -->
        </view>
      </view>
      <!-- <view @click="goTop" style="bottom:100px;position: fixed;">回到顶部</view> -->
      <view>
        <view class="action">
          <view class="cu-load load-icon" :class="isLoading ? 'loading' : 'over'"></view>
        </view>
      </view>
    </scroll-view>
  </block>
</template>

<script lang="js">
import Vue from "vue";

export default Vue.extend({
  name: "home",
  data() {
    return {
      mid: "0",
      isLoading: true,
      curPage: 0,
      postData: [],
      categoryList: [],
      requesting: false,
    };
  },
  methods: {
    changeCate(mid = "") {
      this.mid = mid;
      this.curPage = 0;
      this.postData = [];
      this.loadPost();
      console.log("change mid:", mid);
    },
    goTop() {
      console.log("回到顶部");
      uni.pageScrollTo({
        scrollTop: 0,
        duration: 300,
      });
    },
    formatTime(value) {
      return this.$moment.unix(value).fromNow();
    },
    loadPost() {
      this.isLoading = true;
      if (this.requesting) return;
      this.requesting = true;
      this.$api
          .getPost({
            page: this.curPage + 1,
            mid: this.mid,
          })
          .then((res) => {
            let records = res.records
            if (records != null && records.length > 0) {
              this.postData = this.postData.concat(records.map((item) => {
                return {
                  ...item,
                  tag: item?.tag?.split(',') || [],
                }
              }));
              this.curPage++;
            }
            this.requesting = false;
            this.isLoading = false;
          });
    },
  },
  created() {
    this.$api.getCategories().then((res) => {
      this.categoryList = res;
    });
    this.loadPost();
  },
});
</script>
<style lang="scss" scoped>
.tab-list {
  height: 36px;
  white-space: nowrap;
}

.tab-item {
  display: inline-block;
}

.article-list {
  padding-top: 16rpx;
}

.article-list .article-container:first-child {
  margin-top: 0;
}

.article-container {
  width: 710rpx;
  margin: 32rpx auto;
}

.shadow {
  box-shadow: 40rpx 40rpx 40rpx 40rpx rgba(0, 0, 0, 0.07);
}

.image-banner {
  height: 260upx;
}

.swiper-title {
  height: 60rpx;
  display: flex;
  align-items: center;
  padding-left: 10rpx;
  color: #000;
}

.post-entry-categories {
  background-color: #fff;

  view {
    line-height: 1;
    color: #fff;
    padding: 8upx;
  }
}

.title {
  font-weight: bold;
  font-size: 32rpx;
  color: #333;
}

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

.ad {
  background-color: #fff;
  margin-bottom: 10px;
}

.bg-img image {
  width: 506rpx;
  min-height: 200rpx;
}

.about {
  margin: auto;
  text-align: center;
  width: 506rpx;
  background-color: #545454;
  margin-top: 80%;
}

page {
  width: 100vw;
}

.image-container {
  height: 400upx;
  overflow: hidden;
}

.image {
  width: 100%;
  height: 100%;
}

.overplay {
  width: 710rpx;
  height: 400upx;
  background: linear-gradient(180deg, rgba(0, 0, 0, 0.01) 2%, rgba(0, 0, 0, 0.95) 100%);
  position: absolute;
  opacity: 0.2;
  z-index: 99;
}
</style>
