(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["pages/index"],{"2c60":function(t,n,e){"use strict";var o=e("871a"),a=e.n(o);a.a},"3f82":function(t,n,e){"use strict";var o=function(){var t=this,n=t.$createElement,e=(t._self._c,t.__map(t.postData,function(n,e){var o=t.formatTime(n.created);return{$orig:t.__get_orig(n),m0:o}}));t.$mp.data=Object.assign({},{$root:{l0:e}})},a=[];e.d(n,"a",function(){return o}),e.d(n,"b",function(){return a})},6667:function(t,n,e){"use strict";(function(t){Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var o=a(e("a34a"));function a(t){return t&&t.__esModule?t:{default:t}}function r(t,n,e,o,a,r,u){try{var i=t[r](u),s=i.value}catch(c){return void e(c)}i.done?n(s):Promise.resolve(s).then(o,a)}function u(t){return function(){var n=this,e=arguments;return new Promise(function(o,a){var u=t.apply(n,e);function i(t){r(u,o,a,i,s,"next",t)}function s(t){r(u,o,a,i,s,"throw",t)}i(void 0)})}}var i={data:function(){return{isLoading:!0,curPage:0,postData:[]}},methods:{formatTime:function(t){return this.$moment.unix(t).fromNow()},seePost:function(t){wx.navigateTo({url:"./post?cid="+t})},loadPost:function(){var n=u(o.default.mark(function n(){var e;return o.default.wrap(function(n){while(1)switch(n.prev=n.next){case 0:return this.isLoading=!0,this.postData.curPage%10==0&&(this.postData=[]),n.next=4,this.$api.getRecentPost({page:this.curPage+1});case 4:e=n.sent,null!=e&&e.length>0&&(console.log("修改前",this.postData),this.postData=this.postData.concat(e),this.curPage++,console.log("修改后",this.postData)),this.isLoading=!1,t.stopPullDownRefresh();case 8:case"end":return n.stop()}},n,this)}));function e(){return n.apply(this,arguments)}return e}()},onLoad:function(){var t=u(o.default.mark(function t(n){return o.default.wrap(function(t){while(1)switch(t.prev=t.next){case 0:this.loadPost();case 1:case"end":return t.stop()}},t,this)}));function n(n){return t.apply(this,arguments)}return n}(),onReachBottom:function(){console.log("触底事件"),this.loadPost()},onPullDownRefresh:function(){this.curPage=0,this.postData=[],this.loadPost()}};n.default=i}).call(this,e("543d")["default"])},"871a":function(t,n,e){},b81e:function(t,n,e){"use strict";e.r(n);var o=e("3f82"),a=e("def9");for(var r in a)"default"!==r&&function(t){e.d(n,t,function(){return a[t]})}(r);e("2c60");var u=e("2877"),i=Object(u["a"])(a["default"],o["a"],o["b"],!1,null,null,null);n["default"]=i.exports},def9:function(t,n,e){"use strict";e.r(n);var o=e("6667"),a=e.n(o);for(var r in o)"default"!==r&&function(t){e.d(n,t,function(){return o[t]})}(r);n["default"]=a.a},e62e:function(t,n,e){"use strict";(function(t){e("e13f"),e("921b");o(e("66fd"));var n=o(e("b81e"));function o(t){return t&&t.__esModule?t:{default:t}}t(n.default)}).call(this,e("543d")["createPage"])}},[["e62e","common/runtime","common/vendor"]]]);