<template>
  <view class="cu-bar tabbar bg-white shadow-blur foot">
    <view class="action" @click="NavChange(index)" v-for="(item,index) in tabList">
      <view>
        <view :class="(active == index ? 'color-base ' : 'text-gray ') + item.icon"></view>
      </view>
      <view :class="active == index ? 'color-base' : 'text-gray'">{{ item.text }}</view>
    </view>
  </view>
</template>

<script>
  export default {
    data() {
      return {
        active: 0,
        tabList: [{
            icon: "icon-home",
            name: "home",
            text: "主页"
          },
          {
            icon: "icon-search",
            name: "home",
            text: "关于"
          },
          {
            icon: "icon-info",
            name: "about",
            text: "关于"
          }
        ]
      }
    }
  }
</script>

<style>
</style>
