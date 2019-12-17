const Towxml = require('./towxml/main');
Component({
	properties: {
		/**     
		 * 文章内容
		 */
		'content': {
			type: String,
			value: ''
		}
	},
	data() {
		article: {}
	},
	methods: {
		__bind_tap(e) {
			let element = e.currentTarget.dataset._el
			console.log("点击", element.tag)
			if (element.tag == 'image') {
				wx.previewImage({
					current: element.attr.src, // 当前显示图片的http链接
					urls: [element.attr.src] // 需要预览的图片http链接列表
				})
			} else if (element.tag == 'navigator') {
				console.log(element)
				wx.showModal({
					title: "外部链接提示",
					content: "这是一个外部链接,需要复制到浏览器中打开. 是否复制?",
					confirmText: "复制链接",
					success: res => {
						if (res.confirm) {
							wx.setClipboardData({
								data: element.attr.href
							});
						}
					}
				})
			}
		},
	},
	lifetimes: {
		attached() {
			const towxml = new Towxml()
			console.log('渲染的内容为', this.data)
			this['event_bind_tap'] = (event) => {
				console.log(event.target.dataset._el); // 打印出元素信息
			};
			this['eventRun_image_tap'] = (event) => {
				console.log(event.detail); // todoList checkbox发生change事件
			};
			let text = this.data.content.replace(
				/\[Meting\].*?\[Music.*?title="(.*?)".*?author="(.*?)".*?url="(.*?)".*?pic="(.*?)".*?\/\].*?\[\/Meting\]/s,
				"<audio autoplay='true' loop='true' name='$1' author='$2' poster='https:$4' src='https:$3'></audio>")
			// let data = /\[Meting\].*?\[Music title="(.*?)" author="(.*?)" url="(.*?)" pic="(.*?)" \/\].*?\[\/Meting\]/s.exec(this.data.content)
			let data = towxml.toJson(
				text.slice(15), // `markdown`或`html`文本内容
				'markdown', // `markdown`或`html`
			);
			console.log(text)
			//前台初始化小程序数据（2.1.2新增，如果小程序中无相对资源需要添加`base`根地址，也无`audio`内容可无需初始化）
			data = towxml.initData(data, {
				base: 'https://thinkmoon.cn/', // 需要解析的内容中相对路径的资源`base`地址
				app: this // 传入小程序页面的`this`对象，以用于音频播放器初始化
			});
			
			//设置数据
			this.setData({
				article: towxml.toJson(text.slice(15), 'markdown')
			})
		}
	},
})
