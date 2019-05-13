import Vue from 'vue'
import Vuex from 'vuex'

import * as API from '../../static/utils/api'

Vue.use(Vuex)

const store = new Vuex.Store({
	state: {
		authorInfo:{},
		openid:''
	},
	mutations: {
		setAuthorInfo(state, authorInfo) {
			state.authorInfo = authorInfo
		}
	},
	actions: {
		async getUserOpenId (state) {
			return await new Promise((resolve, reject) => {
				if (state.openid) {
					resolve(state.openid)
				} else {
					
				}
			})
		},
		async getAuthorInfo({commit,state}) {
			let data = await API.getAuthorInfo()
			commit('setAuthorInfo',data[0])
		}
	}
})

export default store
