(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["common/componets/cu-custom"],{"0527":function(t,n,a){"use strict";(function(t){Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var a={data:function(){return{StatusBar:this.StatusBar,CustomBar:this.CustomBar}},name:"cu-custom",computed:{style:function(){var t=this.StatusBar,n=this.CustomBar,a=this.bgImage,u="height:".concat(n,"px;padding-top:").concat(t,"px;");return this.bgImage&&(u="".concat(u,"background-image:url(").concat(a,");")),u}},props:{bgColor:{type:String,default:""},isBack:{type:[Boolean,String],default:!1},bgImage:{type:String,default:""}},methods:{BackPage:function(){t.navigateBack({delta:1})}}};n.default=a}).call(this,a("543d")["default"])},3758:function(t,n,a){"use strict";var u,e=function(){var t=this,n=t.$createElement;t._self._c},c=[];a.d(n,"b",function(){return e}),a.d(n,"c",function(){return c}),a.d(n,"a",function(){return u})},"6c34":function(t,n,a){"use strict";a.r(n);var u=a("0527"),e=a.n(u);for(var c in u)"default"!==c&&function(t){a.d(n,t,function(){return u[t]})}(c);n["default"]=e.a},c8a9:function(t,n,a){"use strict";a.r(n);var u=a("3758"),e=a("6c34");for(var c in e)"default"!==c&&function(t){a.d(n,t,function(){return e[t]})}(c);var o,r=a("f0c5"),i=Object(r["a"])(e["default"],u["b"],u["c"],!1,null,null,null,!1,u["a"],o);n["default"]=i.exports}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'common/componets/cu-custom-create-component',
    {
        'common/componets/cu-custom-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("c8a9"))
        })
    },
    [['common/componets/cu-custom-create-component']]
]);
