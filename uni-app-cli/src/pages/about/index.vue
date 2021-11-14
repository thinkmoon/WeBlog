<template name="about">
  <view>
    <!-- <cu-custom bgColor="bg-gradual" title="关于"></cu-custom> -->
    <view class="overView flex justify-between flex-direction weather rain">
      <view class="flex justify-center align-center flex-direction margin-top-lg">
        <image class="icon shadow" src="https://www.thinkmoon.cn/usr/uploads/2018/12/55979974.jpg"></image>
        <view class="text-bold text-shadow text-lg" style="color:#fff">指尖魔法屋</view>
      </view>
     <view class="flex align-end padding-xs justify-around text-white text-shadow text-bold solid-top">
       <view class="margin-xs flex align-center flex-direction">
         <view>文章</view>
         <view>{{ Overview.posts[0].Num }}</view>
       </view>
       <view class="margin-xs flex align-center flex-direction">
         <view>留言</view>
         <view>{{ Overview.comments[0].Num }}</view>
       </view>
       <view class="margin-xs flex align-center flex-direction">
         <view>分类</view>
         <view>{{ Overview.categorys[0].Num }}</view>
       </view>
       <view class="margin-xs flex align-center flex-direction">
         <view>标签</view>
         <view>{{ Overview.tags[0].Num }}</view>
       </view>
     </view>
    </view>
    <!-- #ifndef H5 -->
    <parse :content="postData.text" v-if="!isLoading"></parse>
    <!-- #endif -->
    <!-- #ifdef H5 -->
    <div v-html="content" class="article-content"/>
    <!-- #endif -->
    <view class="modal bg-white" v-if="isLoading">
      <view class="spinner bg-base"></view>
    </view>
    <!-- #ifdef MP-QQ -->
    <ad unit-id="01a084f529176b8df06deaa2274f6721" type="card" style="margin-bottom:10px"></ad>
    <!-- #endif -->
    <tm-footer></tm-footer>
    <!-- <tab @onTabChange="tabChange" active="2"></tab> -->
  </view>
</template>

<script lang="js">
import Vue from "vue";
import {marked} from "@/utils/marked/index";

// @ts-ignore
import parse from "@/wxcomponents/tm-parse/index";
import tab from "@/components/tm-tab.vue";

export default Vue.extend({
  components: {
    parse,
    tab
  },
  data() {
    return {
      cid: null,
      isLoading: true,
      Overview: {},
      postData: {
        text:'',
      },
    };
  },
  computed: {
    content() {
      return marked(this.postData.text.replace("<!--markdown-->", ""), {breaks: true});
    },
  },
  mounted() {
    this.$api
      .getAboutCid()
      .then((data) => {
        this.postData = data;
        this.isLoading = false;
        this.$nextTick(() => {
          window.hljs.highlightAll();
        });
      });
  },
  onShow() {},
});
</script>

<style>
page {
  background: #fff
}
/* // 头像 */
.icon {
  width: 200rpx;
  height: 200rpx;
  border-radius: 50%;
  margin: auto;
  border: 5rpx #eee solid;
}

.overView {
  background-color: dimgrey;
  background-repeat: no-repeat;
  background-size: 100% 100%;
}

.gif-wave {
  margin: 0;
  mix-blend-mode: screen;
  height: 100upx;
  position: absolute;
  top: 555upx;
  z-index: 99;
  width: 100%;
}
</style>
