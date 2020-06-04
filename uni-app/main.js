import Vue from 'vue'
import App from './App'
import '@/plugins'

// 由于uni-app的component实现差异，Vue.componet的使用只能在当前文件下生效
// -----------------------------------------------------------------------
// 引入自定义导航栏
import cuCustom from './componets/cu-custom.vue'
Vue.component('cu-custom', cuCustom)
// 引入footer
import tmFooter from './componets/tm-footer.vue'
Vue.component('tm-footer', tmFooter)
// 引入home
import home from './pages/index.vue'
Vue.component('home',home)
// 引入about
import about from './pages/about.vue'
Vue.component('about',about)
// -----------------------------------------------------------------------

App.mpType = 'app'

const app = new Vue({
	...App
})
app.$mount()

Vue.mixin({
	onShareAppMessage() {
		return {};
	}
})
