<script lang="ts">
import Vue from "vue";
// 引入query-string
import qs from "query-string";

export default Vue.extend({
  methods: {
    // 更新提示
    update() {
      const updateManager = uni.getUpdateManager();
      updateManager.onCheckForUpdate(function(res) {
        // 请求完新版本信息的回调
        console.log("是否有新版本？", res.hasUpdate);
      });
      updateManager.onUpdateReady(function() {
        uni.showModal({
          title: "发现新版本",
          content: "新版本已经准备好，是否重启应用？",
          success(res) {
            if (res.confirm) {
              // 新的版本已经下载好，调用 applyUpdate 应用新版本并重启
              updateManager.applyUpdate();
            }
          },
        });
      });
    },
    // 初始化
    init() {},
    login() {
      uni.login({
        success: async (res) => {
          console.log("尝试登录", res);
          if (res.code) {
            //发起网络请求
            uni.getSetting({
              success: async (setting) => {
                if (setting.authSetting["scope.userInfo"]) {
                  // 已经授权，可以直接调用 getUserInfo 获取头像昵称、
                  console.log("用户已授权", setting);
                  uni.getUserInfo({
                    success: async (Info) => {
                      console.log("尝试获取用户信息", Info);
                      let data = {
                        code: res.code,
                      };
                      Object.assign(data, Info.userInfo);
                      let openid = await this.$api.login(data);
                      uni.setStorageSync("openid", openid);
                    },
                  });
                } else {
                  let openid = await this.$api.login({
                    code: res.code,
                  });
                  uni.setStorageSync("openid", openid);
                }
              },
            });
          } else {
            console.log("登录失败！" + res.errMsg);
          }
        },
      });
    },
  },
  mounted() {
    console.log("APP onLaunch");

    this.update();
    this.init();
    this.login();
  },
  onHide() {
    console.log("App Hide");
  },
});
</script>

<style lang="scss">
/* 引入color UI wxss 库 */
@import "../assets/css/main.css";
@import "../assets/css/icon.css";
@import "../assets/css/animation.css";
/* ------------------ */
/* 引入自定义 less 库 */
@import "../assets/css/tmui.scss";

.content {
  height: 100vh;
}
checkbox .wx-checkbox-input {
   transform: scale(0.8, 0.8);
}
</style>
