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
			console.log("点击",element.tag)
			if (element.tag == 'image') {
				wx.previewImage({
					current: element.attr.src, // 当前显示图片的http链接
					urls: [element.attr.src] // 需要预览的图片http链接列表
				})
			}else if(element.tag == 'navigator'){
				console.log(element)
				wx.showModal({
					title:"外部链接提示",
					content:"这是一个外部链接,需要复制到浏览器中打开. 是否复制?",
					confirmText: "复制链接",
					success: res => {
						if(res.confirm){
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
			this.setData({
				article: towxml.toJson(this.data.content.slice(15), 'markdown')
			})
		}
	},
})
