(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["common/componets/tm-footer"],{"2ca1":function(e,t,n){"use strict";n.r(t);var r=n("ff02"),a=n("cee6");for(var u in a)"default"!==u&&function(e){n.d(t,e,function(){return a[e]})}(u);var o=n("2877"),f=Object(o["a"])(a["default"],r["a"],r["b"],!1,null,null,null);t["default"]=f.exports},"57d2":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var r=a(n("1ffe"));function a(e){return e&&e.__esModule?e:{default:e}}n("7269"),r.default.locale("zh-cn");var u={data:function(){return{time:[0,0,0,0,0]}},name:"tm-footer",computed:{},props:{},methods:{secondToDate:function(e){if(!e)return 0;var t=new Array(0,0,0,0,0);e>=31536e3&&(t[0]=parseInt(e/31536e3),e%=31536e3),e>=86400&&(t[1]=parseInt(e/86400),e%=86400),e>=3600&&(t[2]=parseInt(e/3600),e%=3600),e>=60&&(t[3]=parseInt(e/60),e%=60),e>0&&(t[4]=e),this.time=t}},onReady:function(){var e=this,t=(0,r.default)([2017,4,12]),n=(0,r.default)(),a=n.diff(t,"seconds");setInterval(function(){e.secondToDate(a++)},1e3)}};t.default=u},cee6:function(e,t,n){"use strict";n.r(t);var r=n("57d2"),a=n.n(r);for(var u in r)"default"!==u&&function(e){n.d(t,e,function(){return r[e]})}(u);t["default"]=a.a},ff02:function(e,t,n){"use strict";var r=function(){var e=this,t=e.$createElement;e._self._c},a=[];n.d(t,"a",function(){return r}),n.d(t,"b",function(){return a})}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'common/componets/tm-footer-create-component',
    {
        'common/componets/tm-footer-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('a821')['createComponent'](__webpack_require__("2ca1"))
        })
    },
    [['common/componets/tm-footer-create-component']]
]);
