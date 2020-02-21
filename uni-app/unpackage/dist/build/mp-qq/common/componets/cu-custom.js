(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["common/componets/cu-custom"],{"0527":function(t,n,a){"use strict";(function(t){Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var a={data:function(){return{StatusBar:this.StatusBar,CustomBar:this.CustomBar}},name:"cu-custom",computed:{style:function(){var t=this.StatusBar,n=this.CustomBar,a=this.bgImage,e="height:".concat(n,"px;padding-top:").concat(t,"px;");return this.bgImage&&(e="".concat(e,"background-image:url(").concat(a,");")),e}},props:{bgColor:{type:String,default:""},isBack:{type:[Boolean,String],default:!1},bgImage:{type:String,default:""}},methods:{BackPage:function(){if(getCurrentPages().length<2&&"undefined"!==typeof __wxConfig){var n="/"+__wxConfig.pages[0];return t.redirectTo({url:n})}t.navigateBack({delta:1})}}};n.default=a}).call(this,a("a821")["default"])},"6c34":function(t,n,a){"use strict";a.r(n);var e=a("0527"),u=a.n(e);for(var r in e)"default"!==r&&function(t){a.d(n,t,function(){return e[t]})}(r);n["default"]=u.a},c8a9:function(t,n,a){"use strict";a.r(n);var e=a("f51f"),u=a("6c34");for(var r in u)"default"!==r&&function(t){a.d(n,t,function(){return u[t]})}(r);var c,o=a("f0c5"),i=Object(o["a"])(u["default"],e["b"],e["c"],!1,null,null,null,!1,e["a"],c);n["default"]=i.exports},f51f:function(t,n,a){"use strict";var e,u=function(){var t=this,n=t.$createElement;t._self._c},r=[];a.d(n,"b",function(){return u}),a.d(n,"c",function(){return r}),a.d(n,"a",function(){return e})}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'common/componets/cu-custom-create-component',
    {
        'common/componets/cu-custom-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('a821')['createComponent'](__webpack_require__("c8a9"))
        })
    },
    [['common/componets/cu-custom-create-component']]
]);
