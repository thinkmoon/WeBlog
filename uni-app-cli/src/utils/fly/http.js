const Fly = require("flyio/dist/npm/wx");
const fly = new Fly();

fly.config.baseURL = process.env.VUE_APP_BASE_URL;

fly.config.headers = {
  apisecret: process.env.VUE_APP_SECRET,
};

fly.interceptors.response.use(
  (response) => {
    return response.data.data;
  },
  (err) => {
    console.error(err);
    return Promise.reject(err);
  }
);

function GET(url, params = {}) {
  let options = {
    headers: {
      openid: uni.getStorageSync("openid"),
    },
  };
  return fly.get(url, params, options);
}
export { GET };
