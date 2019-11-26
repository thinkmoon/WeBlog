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
			if (element.tag == 'image') {
				wx.previewImage({
					current: element.attr.src, // 当前显示图片的http链接
					urls: [element.attr.src] // 需要预览的图片http链接列表
				})
			}
		},
		__bind_touchstart() {},
		__bind_touchend() {}
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
			this.setData({
				article: towxml.toJson(this.data.content.slice(15), 'markdown')
			})
		}
	},
})
