(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["common/componets/cu-custom"],{3758:function(t,a,n){"use strict";var u=function(){var t=this,a=t.$createElement;t._self._c},e=[];n.d(a,"a",function(){return u}),n.d(a,"b",function(){return e})},"6c34":function(t,a,n){"use strict";n.r(a);var u=n("d617"),e=n.n(u);for(var c in u)"default"!==c&&function(t){n.d(a,t,function(){return u[t]})}(c);a["default"]=e.a},c8a9:function(t,a,n){"use strict";n.r(a);var u=n("3758"),e=n("6c34");for(var c in e)"default"!==c&&function(t){n.d(a,t,function(){return e[t]})}(c);var o=n("2877"),r=Object(o["a"])(e["default"],u["a"],u["b"],!1,null,null,null);a["default"]=r.exports},d617:function(t,a,n){"use strict";(function(t){Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{StatusBar:this.StatusBar,CustomBar:this.CustomBar}},name:"cu-custom",computed:{style:function(){var t=this.StatusBar,a=this.CustomBar,n=this.bgImage,u="height:".concat(a,"px;padding-top:").concat(t,"px;");return this.bgImage&&(u="".concat(u,"background-image:url(").concat(n,");")),u}},props:{bgColor:{type:String,default:""},isBack:{type:[Boolean,String],default:!1},bgImage:{type:String,default:""}},methods:{BackPage:function(){t.navigateBack({delta:1})}}};a.default=n}).call(this,n("543d")["default"])}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'common/componets/cu-custom-create-component',
    {
        'common/componets/cu-custom-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("c8a9"))
        })
    },
    [['common/componets/cu-custom-create-component']]
]);
