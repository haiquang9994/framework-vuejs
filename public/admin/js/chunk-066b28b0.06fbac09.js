(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-066b28b0"],{"695a":function(t,e,a){},"9d86":function(t,e,a){"use strict";var i=a("695a"),s=a.n(i);s.a},a55b:function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"container"},[a("div",{staticClass:"content"},[t._m(0),a("div",{staticClass:"login"},[a("a-form",{attrs:{form:t.form},on:{submit:t.handleSubmit}},[a("a-form-item",[a("a-input",{attrs:{size:"large",placeholder:"admin@gmail.com"},model:{value:t.email,callback:function(e){t.email=e},expression:"email"}},[a("a-icon",{attrs:{slot:"prefix",type:"user"},slot:"prefix"})],1)],1),a("a-form-item",[a("a-input",{attrs:{size:"large",placeholder:"1234"},model:{value:t.password,callback:function(e){t.password=e},expression:"password"}},[a("a-icon",{attrs:{slot:"prefix",type:"lock"},slot:"prefix"})],1)],1),a("a-checkbox",{staticClass:"user-select-none",model:{value:t.rememberMe,callback:function(e){t.rememberMe=e},expression:"rememberMe"}},[t._v("Remember me")]),a("a-form-item",[a("a-button",{staticStyle:{width:"100%","margin-top":"24px"},attrs:{loading:t.logging,size:"large",htmlType:"submit",type:"primary"}},[t._v("Login")])],1)],1)],1)])])},s=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"top"},[a("div",{staticClass:"header"},[a("img",{staticClass:"logo",attrs:{alt:"logo",src:"https://iczer.gitee.io/vue-antd-pro/static/img/vue-antd-logo.png"}}),a("span",{staticClass:"title"},[t._v("Admin Page")])]),a("div",{staticClass:"desc"},[t._v("Login for begin work session.")])])}],o={data:function(){return{logging:!1,rememberMe:!1,checked:!0,disabled:!1,email:"admin@gmail.com",password:"1234"}},beforeCreate:function(){this.form=this.$form.createForm(this)},methods:{handleSubmit:function(t){var e=this;t.preventDefault(),this.logging=!0,this.$http.post("http://framework.lc/api/admin/login",{email:"admin@gmail.com",password:"1234"}).then((function(t){t.status&&t.token&&(e.logging=!0,e.$store.commit("setToken",{token:t.token}),e.$router.push("/"))})).catch((function(){e.logging=!0}))}}},n=o,r=(a("9d86"),a("2877")),l=Object(r["a"])(n,i,s,!1,null,"63bcf182",null);e["default"]=l.exports}}]);
//# sourceMappingURL=chunk-066b28b0.06fbac09.js.map