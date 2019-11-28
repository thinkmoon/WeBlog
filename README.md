# WeBlog

一个typecho博客的微信小程序版

![小程序二维码][1]

<a href="https://996.icu"><img src="https://img.shields.io/badge/link-996.icu-red.svg" alt="996.icu" /></a>

## 使用方法

### 小程序端

#### 开发框架

本项目采用[uni-app](https://uniapp.dcloud.io/component/README)+[colorUI](https://github.com/weilanwl/ColorUI)开发

#### 自行编译

1. 下载Hbuilderx
2. 将文件夹 `uni-app` 导入项目
3. 发行至微信小程序

> tips: 由于项目中引入了一些第三方库，首先在项目根目录下执行`npm install`自行安装依赖。[不懂npm](https://www.runoob.com/nodejs/nodejs-npm.html)

#### 不编译直接使用

1. 自行编译或者打开[uni-app/unpackage/dist/build/mp-weixin](https://github.com/thinkmoon/WeBlog/tree/master/uni-app/unpackage/dist/build/mp-weixin)目录导入小程序开发者工具
2. 激活插件后， 修改请求链接文件 `@/static/utils/api.js` 中的baseUrl, 将域名更换为你的域名。 

> 请确保插件已成功安装并且访问正常

### Typecho插件端

复制`WeBlog`到插件目录， 在后台激活并设置

## 开发计划

- [x] 浏览量和点赞量数据表

- [x] 评论功能

- [ ] 评论回复功能

- [ ] 评论通知功能

- [ ] 网页端微信登录

- [ ] 接口加密

## 更新日志

v0.1.4
1. 采用触底加载文章的分页形式
2. 优化文章页分享标题
3. 美化关于页界面

v0.1.3
1. 修复todo list样式问题
2. 为关于页面添加overView
3. 让页面支持转发操作
3. 修复获取文章数错误的问题

v0.1.2
1. 修复评论后台没有名字的BUG
2. 弃用Vuex
3. 支持博客多作者显示


  [1]: https://blog.cdn.thinkmoon.cn/blog/typecho/2019-11-22T07:58:08.png