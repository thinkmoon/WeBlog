<template>
  <view>
    <view class="cu-custom" :style="[{ height: CustomBar + 'px' }]">
      <view class="cu-bar fixed" :style="style" :class="[bgImage != '' ? 'none-bg text-white bg-img' : '', bgColor]">
        <view class="action text-white" @tap="BackPage" v-if="isBack">
          <slot name="backText"></slot>
        </view>
        <view class="content color-contrast" :style="[{ top: StatusBar + 'px' }]"
          >{{ title || "" }}
          <slot name="content"></slot>
        </view>
        <slot name="right"></slot>
      </view>
    </view>
  </view>
</template>

<script>
export default {
  data() {
    return {
      StatusBar: 0,
      CustomBar: 0,
    };
  },
  name: "cu-custom",
  computed: {
    style() {
    console.log("computer")

      var bgImage = this.bgImage;
      var style = `height:${this.CustomBar}px;padding-top:${this.StatusBar}px;`;
      if (this.bgImage) {
        style = `${style}background-image:url(${bgImage});`;
      }
      return style;
    },
  },
  created() {
    console.log("created")
    uni.getSystemInfo({
      success: e => {
        // #ifndef MP
        this.StatusBar = e.statusBarHeight;
        if (e.platform == "android") {
          this.CustomBar = e.statusBarHeight + 50;
        } else {
          this.CustomBar = e.statusBarHeight + 45;
        }
        // #endif
        // #ifdef MP-WEIXIN || MP-QQ
        this.StatusBar = e.statusBarHeight;
        let capsule = wx.getMenuButtonBoundingClientRect();
        if (capsule) {
          this.Custom = capsule;
          // Vue.prototype.capsuleSafe = uni.upx2px(750) - capsule.left + uni.upx2px(750) - capsule.right;
          this.CustomBar = capsule.bottom + capsule.top - e.statusBarHeight;
        } else {
          this.CustomBar = e.statusBarHeight + 50;
        }
        // #endif

        // #ifdef MP-ALIPAY
        this.StatusBar = e.statusBarHeight;
        this.CustomBar = e.statusBarHeight + e.titleBarHeight;
        // #endif
      },
    });
  },
  props: {
    title: {
      type: String,
      default: "",
    },
    bgColor: {
      type: String,
      default: "",
    },
    isBack: {
      type: [Boolean, String],
      default: false,
    },
    bgImage: {
      type: String,
      default: "",
    },
  },
  methods: {
    BackPage() {
      if (getCurrentPages().length < 2 && "undefined" !== typeof __wxConfig) {
        let url = "/" + __wxConfig.pages[0];
        return uni.redirectTo({
          url,
        });
      }
      uni.navigateBack({
        delta: 1,
      });
    },
  },
};
</script>

<style></style>
