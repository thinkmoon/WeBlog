(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["common/componets/tm-footer"],{"1eb5":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var a=r(n("0618"));function r(e){return e&&e.__esModule?e:{default:e}}n("c784"),a.default.locale("zh-cn");var u={data:function(){return{time:[0,0,0,0,0]}},name:"tm-footer",computed:{},props:{},methods:{secondToDate:function(e){if(!e)return 0;var t=new Array(0,0,0,0,0);e>=31536e3&&(t[0]=parseInt(e/31536e3),e%=31536e3),e>=86400&&(t[1]=parseInt(e/86400),e%=86400),e>=3600&&(t[2]=parseInt(e/3600),e%=3600),e>=60&&(t[3]=parseInt(e/60),e%=60),e>0&&(t[4]=e),this.time=t}},onReady:function(){var e=this,t=(0,a.default)([2017,4,12]),n=(0,a.default)(),r=n.diff(t,"seconds");setInterval(function(){e.secondToDate(r++)},1e3)}};t.default=u},dfaf:function(e,t,n){"use strict";n.r(t);var a=n("1eb5"),r=n.n(a);for(var u in a)"default"!==u&&function(e){n.d(t,e,function(){return a[e]})}(u);t["default"]=r.a},ed51:function(e,t,n){"use strict";n.r(t);var a=n("f908"),r=n("dfaf");for(var u in r)"default"!==u&&function(e){n.d(t,e,function(){return r[e]})}(u);var o=n("2877"),f=Object(o["a"])(r["default"],a["a"],a["b"],!1,null,null,null);t["default"]=f.exports},f908:function(e,t,n){"use strict";var a=function(){var e=this,t=e.$createElement;e._self._c},r=[];n.d(t,"a",function(){return a}),n.d(t,"b",function(){return r})}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'common/componets/tm-footer-create-component',
    {
        'common/componets/tm-footer-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("ed51"))
        })
    },
    [['common/componets/tm-footer-create-component']]
]);
