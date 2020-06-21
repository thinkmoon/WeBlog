import { extend } from "vue/types/umd";

var Fly = require("flyio/dist/npm/wx");
var fly = new Fly();

fly.config.baseURL =
  process.env.NODE_ENV === "production"
    ? "https://www.thinkmoon.cn/WeBlog/api/"
    : "http://192.168.99.201/WeBlog/api/";

fly.config.headers = {
  openid: uni.getStorageSync("openid") || "xxx",
  apisecret: "xxx",
};

fly.interceptors.response.use(
  (response: any) => {
    //只将请求结果的data字段返回
    let res = response.data;
    if (res.status == "200") {
      return res.data;
    } else {
      uni.showModal({
        title: "API请求出错:" + res.status,
        content: "错误信息:" + res.data,
      });
    }
    return response.data.data;
  },
  (err: any) => {
    console.error(err);
    return Promise.reject(err);
  }
);

function GET(url: string, params: any) {
  return fly.get(url, params);
}
export { GET };
