import Vue from "vue";
// 引入API
import { API } from "./fly/api";
declare module "vue/types/vue" {
  // 3. 声明为 Vue 补充的东西
  interface Vue {
    $api: any,
    $moment:any
  }
}
Vue.prototype.$api = API;
// 引入moment
import moment from "moment";
import "moment/locale/zh-cn";
moment.locale("zh-cn");
Vue.prototype.$moment = moment;
// 引入query-string
import qs from "query-string";
Vue.prototype.$qs = qs;
