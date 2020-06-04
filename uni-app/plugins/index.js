import Vue from 'vue'
// 引入API
import * as API from './fly/api'
Vue.prototype.$api = API
// 引入moment
import moment from 'moment'
import 'moment/locale/zh-cn'
moment.locale('zh-cn')
Vue.prototype.$moment = moment
// 引入query-string
import qs from 'query-string'
Vue.prototype.$qs = qs
