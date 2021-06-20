import Vue from "vue";
import App from "./App.vue";

import "./utils";

// 由于uni-app的component实现差异，Vue.component的使用只能在当前文件下生效
// -----------------------------------------------------------------------
// 引入自定义导航栏
import cuCustom from "./components/cu-custom.vue";
Vue.component("cu-custom", cuCustom);
// 引入footer
import tmFooter from "./components/tm-footer.vue";
Vue.component("tm-footer", tmFooter);
// -----------------------------------------------------------------------

new App().$mount()

Vue.mixin({
    onShareAppMessage() {
        return {};
    },
});


