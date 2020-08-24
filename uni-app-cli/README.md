# WeBlog小程序端

## 安装环境

1. 下载nodejs环境
2. 打开小程序项目文件夹uni-app-cli,执行`npm install`
```bash
cd uni-app-cli
npm install
```
3. 默认运行`npm run build`会编译到微信小程序平台

## 编译到微信小程序

1. 运行`npm run build:mp-weixin`
2. 打开微信开发者工具，导入项目文件夹`uni-app-cli/dist/build/mp-weixin`

## 编译到QQ小程序

1. 运行`npm run build:mp-qq`
2. 打开微信开发者工具，导入项目文件夹`uni-app-cli/dist/build/mp-qq`

## 小程序项目配置

如需修改关于小程序项目配置，建议直接修改`manifest.json`文件

## 请求根地址及密钥配置

开发环境修改`.env.development`,正式环境修改`.env.production`

## 更多配置

详见[WIKI](https://github.com/thinkmoon/WeBlog/wiki) 
