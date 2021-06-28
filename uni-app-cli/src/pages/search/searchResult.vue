<template>
  <view class="bg-gray content">
    <view>
      <navigator :url='"/pages/post/index?cid=" + item.cid' v-for="(item,index) in articleList" :key="index" class="margin padding bg-white">
        <view class="image-container">
          <view class="overplay"></view>
          <image :src="item.thumb" mode="aspectFill" :lazy-load="true" class="image"></image>
        </view>
        <view class="flex align-center padding-xs post-entry-categories text-xs">
          <view class="margin-right-xs" v-for="(tagItem,index) in item.tag" :key="index">{{ tagItem.name }}</view>
        </view>
        <view class="padding-bottom-sm title text-bold text-lg "> {{ item.title }} </view>
        <view v-if="item.desc"> {{ item.desc }} </view>
      </navigator>
    </view>
  </view>
</template>

<script>
  export default {
    data() {
      return {
        keyWord: null,
        articleList: []
      };
    },
    async onLoad(options) {
      this.$api.search(options).then(res => {
        this.articleList = res.records;
      })
    }
  }
</script>

<style lang="scss" scoped>
  .image-container {
    height: 300upx;
    overflow: hidden;
  }
  
  .image {
    // object-fit: cover;
    width: 100%;
    height: 100%;
  }
  
  .overplay {
    width: 630rpx;
    height: 300upx;
    background: -webkit-linear-gradient(270deg, rgba(0, 0, 0, .01) 2%, rgba(0, 0, 0, .95) 100%);
    background: linear-gradient(180deg, rgba(0, 0, 0, .01) 2%, rgba(0, 0, 0, .95) 100%);
    position: absolute;
    opacity: 0.2;
    z-index: 99;
  }
</style>
