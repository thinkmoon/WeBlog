<template>
  <view style="width: 750rpx;">
    <home v-if="PageCur == 'home'"></home>
    <about v-if="PageCur == 'about'"></about>
    <tab></tab>
  </view>
</template>

<script>
  import tab from "@/componets/tm-tab";
  export default {
    components: {
      tab
    },
    data() {
      return {
        PageCur: "home",
        navigation: {
          home: "主页",
          about: "关于",
        },
      };
    },
    methods: {
      NavChange: function(name) {
        this.PageCur = name;
      },
    },
  };
</script>

<style>
  .card-swiper {
    height: 360rpx;
  }
</style>
