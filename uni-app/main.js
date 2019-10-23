import Vue from 'vue'
import App from './App'

// 引入自定义导航栏
import cuCustom from './common/componets/cu-custom.vue'
Vue.component('cu-custom', cuCustom)
// 引入footer
import tmFooter from './common/componets/tm-footer.vue'
Vue.component('tm-footer', tmFooter)
// 引入vuex
import store from './common/store'
Vue.prototype.$store = store
// 引入API
import * as API from './static/utils/api'
Vue.prototype.$api = API

Vue.config.productionTip = false

App.mpType = 'app'

const app = new Vue({
	store,
	...App
})
app.$mount()
