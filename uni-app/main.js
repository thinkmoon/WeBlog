import Vue from 'vue'
import App from './App'

// 引入自定义导航栏
import cuCustom from './common/componets/cu-custom.vue'
Vue.component('cu-custom', cuCustom)

import store from './common/store'
Vue.prototype.$store = store

Vue.config.productionTip = false

App.mpType = 'app'

const app = new Vue({
	store,
	...App
})
app.$mount()
