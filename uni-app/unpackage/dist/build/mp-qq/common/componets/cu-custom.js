(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["common/componets/cu-custom"],{"0527":function(t,a,n){"use strict";(function(t){Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{StatusBar:this.StatusBar,CustomBar:this.CustomBar}},name:"cu-custom",computed:{style:function(){var t=this.StatusBar,a=this.CustomBar,n=this.bgImage,u="height:".concat(a,"px;padding-top:").concat(t,"px;");return this.bgImage&&(u="".concat(u,"background-image:url(").concat(n,");")),u}},props:{bgColor:{type:String,default:""},isBack:{type:[Boolean,String],default:!1},bgImage:{type:String,default:""}},methods:{BackPage:function(){t.navigateBack({delta:1})}}};a.default=n}).call(this,n("a821")["default"])},3758:function(t,a,n){"use strict";var u,e=function(){var t=this,a=t.$createElement;t._self._c},c=[];n.d(a,"b",function(){return e}),n.d(a,"c",function(){return c}),n.d(a,"a",function(){return u})},"6c34":function(t,a,n){"use strict";n.r(a);var u=n("0527"),e=n.n(u);for(var c in u)"default"!==c&&function(t){n.d(a,t,function(){return u[t]})}(c);a["default"]=e.a},c8a9:function(t,a,n){"use strict";n.r(a);var u=n("3758"),e=n("6c34");for(var c in e)"default"!==c&&function(t){n.d(a,t,function(){return e[t]})}(c);var o,r=n("f0c5"),i=Object(r["a"])(e["default"],u["b"],u["c"],!1,null,null,null,!1,u["a"],o);a["default"]=i.exports}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'common/componets/cu-custom-create-component',
    {
        'common/componets/cu-custom-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('a821')['createComponent'](__webpack_require__("c8a9"))
        })
    },
    [['common/componets/cu-custom-create-component']]
]);
