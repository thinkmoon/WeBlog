# WeBlog

<a href="https://996.icu"><img src="https://img.shields.io/badge/link-996.icu-red.svg" alt="996.icu" /></a>

typecho博客的小程序版, 支持微信小程序，QQ小程序。

> 有建议欢迎提issue，有问题请QQ联系

## 特性

1. 支持Aplayer插件播放audio/音乐 [详情](https://www.thinkmoon.cn/20191122/cid=555.html#article-header-1)
2. 基于uni-app开发，支持多端编译 [详情](https://www.thinkmoon.cn/20191122/cid=555.html#article-header-2)
3. 采用互补色彩设计原则，支持一键修改主题色 [详情](https://www.thinkmoon.cn/20191122/cid=555.html#article-header-3)

## 小程序二维码

|  微信小程序   | QQ小程序 |
|  ----  | ----  |
| ![微信小程序二维码][2]  | ![QQ小程序二维码][3] |

## 开发框架

本项目采用[uni-app](https://uniapp.dcloud.io/component/README)+[colorUI](https://github.com/weilanwl/ColorUI)开发

## 快速开始

### 小程序端

#### 自行编译(适用于有前端基础的同学)

1. 下载Hbuilderx
2. 将文件夹 `uni-app` 导入项目
3. 发行至微信小程序

#### 不编译直接使用(适用于零基础或基础较差的同学)

1. 打开[uni-app/unpackage/dist/build/mp-weixin](https://github.com/thinkmoon/WeBlog/tree/master/uni-app/unpackage/dist/build/mp-weixin)目录导入小程序开发者工具
2. ~~激活插件后，修改请求链接文件 `@/static/utils/api.js` 中的baseUrl, 将域名更换为你的域名。~~ 请修改文件`@/common/vendor.js`,搜索`www.thinkmoon.cn`替换为你的域名。

### Typecho插件端

复制 `WeBlog` 到插件目录， 在后台激活并设置

## 特别鸣谢

* [uni-app](https://github.com/dcloudio/uni-app)
* [ColorUI](https://github.com/weilanwl/ColorUI)
* [WeTypecho](https://github.com/MingliangLu/WeTypecho)
* [Moment](https://momentjs.com/)
* [flyio](https://github.com/wendux/fly)
* [towxml](https://github.com/sbfkcel/towxml)

> 感谢各位开源作者优秀的作品！

## 开发计划，更新日志

[WIKI](https://github.com/thinkmoon/WeBlog/wiki) [更多](https://www.thinkmoon.cn/20191122/cid=555.html)

  [2]: https://blog.cdn.thinkmoon.cn/blog/typecho/2019-11-22T07:58:08.png
  [3]: https://blog.cdn.thinkmoon.cn/blog/typecho/2576c006617a8efb2218a1e9145646a4.png

