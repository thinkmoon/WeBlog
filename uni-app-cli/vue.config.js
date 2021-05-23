const path = require("path");
const CopyWebpackPlugin = require("copy-webpack-plugin");

module.exports = {
  configureWebpack: {
    plugins: [
      new CopyWebpackPlugin([
        {
          from: path.join(__dirname, "src/utils"),
          to: path.join(__dirname, "dist", process.env.NODE_ENV === "production" ? "build" : "dev", process.env.UNI_PLATFORM, "utils"),
        }
      ]),
    ],
  },
  devServer:{
    host:'0.0.0.0',
    disableHostCheck: true,
    port:'8080',
    // https:false,
    open:false,
    //以上的ip和端口是我们本机的;下面为需要跨域的
    proxy:{ //配置跨域
      '/api':{
        target:'http://localhost:9447',
        ws:true,
        changeOrigin:true,//允许跨域
      }
    }
  }
};
