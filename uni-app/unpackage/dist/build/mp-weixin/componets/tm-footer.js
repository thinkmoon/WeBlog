(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["componets/tm-footer"],{"7f2d":function(t,e,n){"use strict";n.r(e);var a=n("f6c4"),r=n.n(a);for(var u in a)"default"!==u&&function(t){n.d(e,t,(function(){return a[t]}))}(u);e["default"]=r.a},c1ad:function(t,e,n){"use strict";n.r(e);var a=n("c836"),r=n("7f2d");for(var u in r)"default"!==u&&function(t){n.d(e,t,(function(){return r[t]}))}(u);var o,c=n("f0c5"),f=Object(c["a"])(r["default"],a["b"],a["c"],!1,null,null,null,!1,a["a"],o);e["default"]=f.exports},c836:function(t,e,n){"use strict";var a,r=function(){var t=this,e=t.$createElement,n=(t._self._c,(new Date).getFullYear());t.$mp.data=Object.assign({},{$root:{g0:n}})},u=[];n.d(e,"b",(function(){return r})),n.d(e,"c",(function(){return u})),n.d(e,"a",(function(){return a}))},f6c4:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=r(n("8a64"));function r(t){return t&&t.__esModule?t:{default:t}}n("487f"),a.default.locale("zh-cn");var u={data:function(){return{time:[0,0,0,0,0]}},name:"tm-footer",computed:{},props:{},methods:{secondToDate:function(t){if(!t)return 0;var e=new Array(0,0,0,0,0);t>=31536e3&&(e[0]=parseInt(t/31536e3),t%=31536e3),t>=86400&&(e[1]=parseInt(t/86400),t%=86400),t>=3600&&(e[2]=parseInt(t/3600),t%=3600),t>=60&&(e[3]=parseInt(t/60),t%=60),t>0&&(e[4]=t),this.time=e}},onReady:function(){var t=this,e=(0,a.default)([2017,4,12]),n=(0,a.default)(),r=n.diff(e,"seconds");setInterval((function(){t.secondToDate(r++)}),1e3)}};e.default=u}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'componets/tm-footer-create-component',
    {
        'componets/tm-footer-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("c1ad"))
        })
    },
    [['componets/tm-footer-create-component']]
]);
