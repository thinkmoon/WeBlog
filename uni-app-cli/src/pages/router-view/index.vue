<template name="home">
  <block>
    <cu-custom bgColor="bg-gradual" title="主页"></cu-custom>
    <scroll-view scroll-y class="cu-card case scroll-view" :scroll-into-view="top">
      <scroll-view scroll-x class="tab-list flex bg-white solid-bottom">
        <div class="tab-item padding-sm" :class="mid == '0' ? 'act' : ''" @click="changeCate('0')">全部</div>
        <div
          class="tab-item padding-sm"
          v-for="(item, index) in categoryList"
          :key="index"
          :class="mid == item.mid ? 'act' : ''"
          @click="changeCate(item.mid)"
        >
          {{ item.name }}
        </div>
      </scroll-view>
      <view class="bg-white article-list">
        <view v-for="(item, index) in postData" :key="index" class="article-container list radius shadow">
          <!-- #ifdef MP-QQ -->
          <ad unit-id="750221a1c0d4c6f021ab39df00a40ae7" type="feeds" v-if="index % 4 == 3" class="ad"></ad>
          <!-- #endif -->
          <navigator
            hover-class="none"
            :url="'/pages/post?cid=' + item.cid + '&thumb=' + item.thumb[0].str_value"
            class="list__item bg-white"
          >
            <view class="image-container" v-if="item.thumb.length">
              <view class="overplay"></view>
              <image :src="item.thumb[0].str_value" mode="aspectFill" :lazy-load="true" class="image"></image>
              <!-- <view class="cu-tag bg-blue">置顶</view> -->
              <!-- <view class="cu-bar text-shadow bg-shadeBottom">{{ item.title }}</view> -->
            </view>
            <view class="flex align-center padding-xs post-entry-categories text-xs">
              <view class="margin-left-xs" v-for="(tagItem, index) in item.tag" :key="index">{{ tagItem.name }}</view>
            </view>
            <view class="bg-white padding-left-sm title">{{ item.title }}</view>
            <view class="desc padding-sm">{{ item.desc.length ? item.desc[0].str_value : "" }}</view>
            <view class="cu-list padding-lr-sm padding-bottom-xs">
              <view class="cu-item">
                <view class="text-gray text-sm flex justify-between align-center">
                  <view>
                    <text class=" margin-right-sm">
                      <text class="icon-file margin-right-xs"></text>
                      {{ item.category[0].name }}
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

<script lang="ts">
import Vue from "vue";
let observer: any;

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
      this.loadPost()
      console.log("change mid:", mid);
    },
    goTop() {
      console.log("回到顶部");
      uni.pageScrollTo({
        scrollTop: 0,
        duration: 300,
      });
    },
    formatTime(value: string): string {
      // @ts-ignore：这行傻逼了，禁掉
      return this.$moment.unix(value).fromNow();
    },
    loadPost() {
      this.isLoading = true;
      if (this.requesting) return;
      this.requesting = true;
      this.$Api
        .getPost({
          page: this.curPage + 1,
          mid: this.mid,
        })
        .then((res: any) => {
          if (res != null && res.length > 0) {
            this.postData = this.postData.concat(res);
            this.curPage++;
          }
          this.requesting = false;
          this.isLoading = false;
        });
    },
  },
  async mounted() {
    observer = wx.createIntersectionObserver(this);
    observer
      .relativeToViewport({
        bottom: 600,
      })
      .observe(".action", (res: any) => {
        console.log(res);
        this.loadPost();
      });
    this.$Api.getCategories().then((res: any) => {
      this.categoryList = res;
    });
    this.loadPost();
  },
});
</script>
<style lang="scss" scoped>
.tab-list {
  width: 100%;
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
  // background-image: var(--gradualBlack);
  width: 100vw;
}

.image-container {
  height: 400upx;
  overflow: hidden;
}

.image {
  // object-fit: cover;
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
