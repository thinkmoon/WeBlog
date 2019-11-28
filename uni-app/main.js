import Vue from 'vue'
import App from './App'

// 引入自定义导航栏
import cuCustom from './common/componets/cu-custom.vue'
Vue.component('cu-custom', cuCustom)
// 引入footer
import tmFooter from './common/componets/tm-footer.vue'
Vue.component('tm-footer', tmFooter)
// 引入API
import * as API from './static/utils/api'
Vue.prototype.$api = API
// 引入moment
import moment from 'moment'
import 'moment/locale/zh-cn'
moment.locale('zh-cn')

Vue.prototype.$moment = moment

App.mpType = 'app'

const app = new Vue({
	...App
})
app.$mount()

Vue.mixin({
	onShareAppMessage() {
		return {
		};
	}
})
