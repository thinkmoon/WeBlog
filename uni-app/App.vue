<script>
import Vue from 'vue'
import { mapActions } from 'vuex'

export default {
	methods: {
		...mapActions(['getAuthorInfo'])
	},
	onLaunch: function() {
		console.log('APP onLaunch')
		uni.getSystemInfo({
			success: function(e) {
				// #ifndef MP
				Vue.prototype.StatusBar = e.statusBarHeight
				if (e.platform == 'android') {
					Vue.prototype.CustomBar = e.statusBarHeight + 50
				} else {
					Vue.prototype.CustomBar = e.statusBarHeight + 45
				}
				// #endif
				// #ifdef MP
				Vue.prototype.StatusBar = e.statusBarHeight
				let custom = wx.getMenuButtonBoundingClientRect()
				Vue.prototype.Custom = custom
				Vue.prototype.CustomBar = custom.bottom + custom.top - e.statusBarHeight
				// #endif
			}
		})
		this.getAuthorInfo()
	},
	onShow: function() {
		console.log('App Show')
	},
	onHide: function() {
		console.log('App Hide')
	}
}
</script>

<style>
/* 引入color UI wxss 库 */
@import 'common/colorui/main.wxss';
@import 'common/colorui/icon.wxss';
@import 'common/colorui/animation.wxss';
/* ------------------ */
/* 引入自定义 wxss 库 */
@import 'common/tmui.wxss';
</style>
