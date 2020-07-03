<template>
  <view>
    <home v-if="PageCur == 'home'"></home>
    <discover v-if="PageCur == 'discover'"></discover>
    <about v-if="PageCur == 'about'"></about>
    <tab @onTabChange="tabChange"></tab>
  </view>
</template>

<script>
import tab from "@/componets/tm-tab";
import home from "./router-view/index.vue";
import discover from "./router-view/discover.vue";
import about from "./router-view/about.vue";

export default {
  components: {
    tab,
    about,
    discover,
    home,
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
    tabChange({ name }) {
      console.log("切换TAB", name);
      this.PageCur = name;
    },
  },
};
</script>

<style>
</style>
