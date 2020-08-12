<template>
  <view class="bg-white content">
    <cu-custom bgColor="bg-gradual">
      <block slot="backText"><text class="icon-back"></text></block>
      <block slot="content">发现</block>
    </cu-custom>
    <view class="cu-bar tm-search bg-white">
      <view class="tm-search-input search-form">
        <text class="icon-search"></text>
        <input @confirm="onSearch" :adjust-position="false" type="text" placeholder="请输入你想知道的内容" confirm-type="search" />
      </view>
    </view>
    <view class="padding-sm bottom-holder bg-white">
      <view v-for="(item, index) in tagList" :key="index" class="cu-btn text-black margin-xs" @click="searchTag(item.mid)">
        {{ item.name }} ({{ item.count }})
      </view>
    </view>
  </view>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
  data() {
    return {
      searchHistory: [],
      tagList: [],
    };
  },
  methods: {
    // 点击搜索历史
    searchTag(mid: number) {
      console.log(`寻找mid为${mid}的文章`);
      uni.navigateTo({
        url: "./search/searchResult?mid=" + mid,
      });
    },
    // 开始搜索
    onSearch(e: any) {
      console.log(e);
      if (e.detail.value == "") {
        return;
      }
      uni.navigateTo({
        url: "./search/searchResult?keyWord=" + e.detail.value,
      });
    },
  },
  mounted() {
    this.$api.getTags().then((res: any) => {
      this.tagList = res;
    });
  },
});
</script>
<style lang="scss">
.bottom-holder {
  padding-bottom: 10vh;
}
</style>
