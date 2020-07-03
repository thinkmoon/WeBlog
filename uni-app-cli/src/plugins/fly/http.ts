var Fly = require("flyio/dist/npm/wx");
var fly = new Fly();

fly.config.baseURL = process.env.NODE_ENV === "production" ? "https://www.thinkmoon.cn/api/" : "http://172.16.4.97/api/";

fly.config.headers = {
  openid: uni.getStorageSync("openid") || "xxx",
  apisecret: "xxx",
};

fly.interceptors.response.use(
  (response: any) => {
    return response.data.data;
  },
  (err: any) => {
    console.error(err);
    uni.showModal({
      title: err.request.url + "接口状态" + err.status,
      content: "错误原因:" + err.engine.response.message,
    });
    return Promise.reject(err);
  }
);

function GET(url: string, params = {}) {
  return fly.get(url, params);
}
export { GET };
