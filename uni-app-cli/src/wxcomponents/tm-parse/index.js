let toWxml = require("../towxml/index");

Component({
  properties: {
    content: {
      type: String,
      value: "",
    },
  },
  methods: {
    tap(e) {
      let element = e.currentTarget.dataset.data;
      console.log("点击", element);
      if (element.tag == "img") {
        wx.previewImage({
          current: element.attr.src, // 当前显示图片的http链接
          urls: [element.attr.src], // 需要预览的图片http链接列表
        });
      } else if (element.tag == "navigator") {
        console.log(element.attr.href);
        wx.showModal({
          title: "外部链接提示",
          content: "这是一个外部链接,需要复制到浏览器中打开. 是否复制?",
          confirmText: "复制链接",
          success: (res) => {
            if (res.confirm) {
              wx.setClipboardData({
                data: element.attr.href,
              });
            }
          },
        });
      }
    },
  },
  lifetimes: {
    attached: function() {
      let content = this.data.content;
      // 处理meting
      let text = content.replace(
        /\[Meting\].*?\[Music.*?title="(.*?)".*?author="(.*?)".*?url="(.*?)".*?pic="(.*?)".*?\/\].*?\[\/Meting\]/s,
        "<audio autoplay='false' loop='true' name='$1' author='$2' poster='$4' src='$3'></audio>"
      );
      let result = toWxml(text.substring(15), "markdown", {
        base: "https://www.thinkmoon.cn", // 相对资源的base路径
        events: {
          // 为元素绑定的事件方法
          tap: (e) => {
            this.tap(e);
          },
        },
      });
      // 更新解析数据
      this.setData({
        article: result,
      });
    },
  },
});
