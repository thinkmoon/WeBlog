<template>
  <view class="cu-bar tabbar bg-white shadow-blur foot">
    <view
      class="action"
      @click="changeTab(index)"
      v-for="(item, index) in tabList"
      :key="index"
    >
      <view>
        <view
          :class="(active == index ? 'color-base ' : 'text-gray ') + item.icon"
        ></view>
      </view>
      <view :class="active == index ? 'color-base' : 'text-gray'">{{
        item.text
      }}</view>
    </view>
  </view>
</template>

<script>
export default {
  data() {
    return {
      active: 0,
      tabList: [
        {
          icon: "icon-home",
          name: "home",
          text: "主页",
        },
        {
          icon: "icon-search",
          name: "discover",
          text: "发现",
        },
        {
          icon: "icon-info",
          name: "about",
          text: "关于",
        },
      ],
    };
  },
  methods: {
    changeTab(index) {
      this.active = index
      this.$emit("onTabChange", {
        name: this.tabList[index].name,
      });
    },
  },
};
</script>

<style></style>
