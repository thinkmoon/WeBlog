var Fly = require("flyio/dist/npm/wx");
var fly = new Fly();

fly.config.baseURL = process.env.VUE_APP_BASE_URL;

fly.config.headers = {
  apisecret: process.env.VUE_APP_SECRET,
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
  let options = {
    headers: {
      openid: uni.getStorageSync("openid"),
    },
  };
  return fly.get(url, params, options);
}
export { GET };
