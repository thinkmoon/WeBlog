<template>
  <view>
    <view class="cu-custom" :style="[{height:CustomBar + 'px'}]">
      <view class="cu-bar fixed" :style="style" :class="[bgImage!=''?'none-bg text-white bg-img':'',bgColor]">
        <view class="action text-white" @tap="BackPage" v-if="isBack">
          <slot name="backText"></slot>
        </view>
        <view class="content color-contrast" :style="[{top:StatusBar + 'px'}]">
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
        StatusBar: this.StatusBar,
        CustomBar: this.CustomBar
      };
    },
    name: 'cu-custom',
    computed: {
      style() {
        let {
        	StatusBar,
        	CustomBar,
        } = this;
        var bgImage = this.bgImage;
        var style = `height:${CustomBar}px;padding-top:${StatusBar}px;`;
        if (this.bgImage) {
          style = `${style}background-image:url(${bgImage});`;
        }
        console.log(`this.bgColor ${this.bgColor}`)
        return style
      }
    },
    props: {
      bgColor: {
        type: String,
        default: ''
      },
      isBack: {
        type: [Boolean, String],
        default: false
      },
      bgImage: {
        type: String,
        default: ''
      },
    },
    methods: {
      BackPage() {
        if (getCurrentPages().length < 2 && 'undefined' !== typeof __wxConfig) {
          let url = '/' + __wxConfig.pages[0]
          return uni.redirectTo({
            url
          })
        }
        uni.navigateBack({
          delta: 1
        });
      }
    }
  }
</script>

<style>

</style>
