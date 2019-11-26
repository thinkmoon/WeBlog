(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["common/componets/cu-custom"],{6588:function(t,a,n){"use strict";(function(t){Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{StatusBar:this.StatusBar,CustomBar:this.CustomBar}},name:"cu-custom",computed:{style:function(){var t=this.StatusBar,a=this.CustomBar,n=this.bgImage,e="height:".concat(a,"px;padding-top:").concat(t,"px;");return this.bgImage&&(e="".concat(e,"background-image:url(").concat(n,");")),e}},props:{bgColor:{type:String,default:""},isBack:{type:[Boolean,String],default:!1},bgImage:{type:String,default:""}},methods:{BackPage:function(){t.navigateBack({delta:1})}}};a.default=n}).call(this,n("a821")["default"])},b9fd:function(t,a,n){"use strict";n.r(a);var e=n("ec00"),u=n("f253");for(var c in u)"default"!==c&&function(t){n.d(a,t,function(){return u[t]})}(c);var o=n("2877"),r=Object(o["a"])(u["default"],e["a"],e["b"],!1,null,null,null);a["default"]=r.exports},ec00:function(t,a,n){"use strict";var e=function(){var t=this,a=t.$createElement;t._self._c},u=[];n.d(a,"a",function(){return e}),n.d(a,"b",function(){return u})},f253:function(t,a,n){"use strict";n.r(a);var e=n("6588"),u=n.n(e);for(var c in e)"default"!==c&&function(t){n.d(a,t,function(){return e[t]})}(c);a["default"]=u.a}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'common/componets/cu-custom-create-component',
    {
        'common/componets/cu-custom-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('a821')['createComponent'](__webpack_require__("b9fd"))
        })
    },
    [['common/componets/cu-custom-create-component']]
]);                
