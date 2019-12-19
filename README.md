# WeBlog

<a href="https://996.icu"><img src="https://img.shields.io/badge/link-996.icu-red.svg" alt="996.icu" /></a>

typecho博客的小程序版,支持微信小程序，QQ小程序

## 特性

### 支持Aplayer插件播放audio

<img src="https://blog.cdn.thinkmoon.cn/blog/typecho/2019-12-17T13:11:03.png" width="300" align=center />

> 示例请在小程序端打开此文章

| 微信小程序二维码 | QQ小程序二维码 |
|  ----  | ----  |
| ![微信小程序二维码][2]  | ![QQ小程序二维码][3] |

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

- [x] 点击图片预览功能

- [x] 点击链接复制功能

- [x] Aplayer插件的支持

- [ ] 文章搜索

- [ ] 评论回复功能

- [ ] 评论通知功能

- [ ] 网页端微信登录

- [ ] 接口加密

## 特别鸣谢
- [uni-app](https://github.com/dcloudio/uni-app)
- [ColorUI](https://github.com/weilanwl/ColorUI)
- [WeTypecho](https://github.com/MingliangLu/WeTypecho)
- [Moment](https://momentjs.com/)
- [flyio](https://github.com/wendux/fly)
- [towxml](https://github.com/sbfkcel/towxml)

> 感谢各位开源作者优秀的作品！

## 更新日志
v0.1.6
1. 支持Aplayer插件
2. 支持默认缩略图地址自定义

v0.1.5
1. 美化关于页界面
2. 优化页面下拉，上拉功能
3. 适配QQ小程序登录

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

  [2]: https://blog.cdn.thinkmoon.cn/blog/typecho/2019-11-22T07:58:08.png
  [3]: https://blog.cdn.thinkmoon.cn/blog/typecho/2576c006617a8efb2218a1e9145646a4.png
