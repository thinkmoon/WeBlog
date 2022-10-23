> 本项目不兼容最新版typecho，已停止维护，感谢关注

# WeBlog

<a href="https://996.icu"><img src="https://img.shields.io/badge/link-996.icu-red.svg" alt="996.icu" /></a>

typecho博客的小程序版1.x, 支持微信小程序，QQ小程序。

## 特性

1. 支持Aplayer插件播放audio/音乐 [详情](https://www.thinkmoon.cn/20191122/cid=555.html#article-header-1)
2. 基于uni-app开发，支持多端编译 [详情](https://www.thinkmoon.cn/20191122/cid=555.html#article-header-2)
3. 采用互补色彩设计原则，支持一键修改主题色 [详情](https://www.thinkmoon.cn/20191122/cid=555.html#article-header-3)

> 有建议欢迎提issue

## 从0.x迁移到1.x

1. 将原缩略图字段`thumb2`改为`thumb`
2. 禁用并删除原WeBlog插件，启用新版插件，由`typecho-plugin-Restful`修改

## 小程序二维码

|  微信小程序   | QQ小程序 |
|  ----  | ----  |
| ![微信小程序二维码][2]  | ![QQ小程序二维码][3] |

## 开发框架

本项目采用[uni-app](https://uniapp.dcloud.io/component/README)+[colorUI](https://github.com/weilanwl/ColorUI)开发

## 快速开始

### 小程序端

[点击阅读](https://github.com/thinkmoon/WeBlog/tree/v1.x/uni-app-cli)

### Typecho插件端

复制 `Restful` 到插件目录， 在后台激活并设置

## 特别鸣谢

虽然项目的作者显示的只有我一个，但是我一个人是无法完成该项目的，我想感谢与该项目相关的一些开源项目及作者。

* [uni-app](https://github.com/dcloudio/uni-app)
* [ColorUI](https://github.com/weilanwl/ColorUI)
* [WeTypecho](https://github.com/MingliangLu/WeTypecho)
* [Moment](https://momentjs.com/)
* [flyio](https://github.com/wendux/fly)
* [towxml](https://github.com/sbfkcel/towxml)
* [typecho-plugin-Restful](https://github.com/moefront/typecho-plugin-Restful)

> 感谢各位开源作者优秀的作品！还有些库没有列出，都可在package.json中查看引入情况

## 意见反馈

我会尽最大的努力确保文档和代码没有错误。可是，金无赤足，错误在所难免。如果您发现本项目中的任何错误，如错别字或代码错误等，希望您能及时给我反馈，您的反馈不仅可以让其他使用者受益，更可以提高项目的质量。

如果您对于项目有些好的建议，或者想法，欢迎您加入QQ群与我讨论。

## QQ群

如果您想与本项目作者或者其他使用者沟通，欢迎加入项目开发交流群:1062676924

## 开发计划，更新日志

[WIKI](https://github.com/thinkmoon/WeBlog/wiki) 
[更多](https://www.thinkmoon.cn/20191122/cid=555.html)

  [2]: https://blog.cdn.thinkmoon.cn/blog/typecho/2019-11-22T07:58:08.png
  [3]: https://blog.cdn.thinkmoon.cn/blog/typecho/2576c006617a8efb2218a1e9145646a4.png

