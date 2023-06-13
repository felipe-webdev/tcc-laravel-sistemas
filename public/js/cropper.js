"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[297],{624:(t,e,n)=>{n.d(e,{Z:()=>r});var o=n(879),i=n.n(o)()((function(t){return t[1]}));i.push([t.id,"",""]);const r=i},879:t=>{t.exports=function(t){var e=[];return e.toString=function(){return this.map((function(e){var n=t(e);return e[2]?"@media ".concat(e[2]," {").concat(n,"}"):n})).join("")},e.i=function(t,n,o){"string"==typeof t&&(t=[[null,t,""]]);var i={};if(o)for(var r=0;r<this.length;r++){var a=this[r][0];null!=a&&(i[a]=!0)}for(var s=0;s<t.length;s++){var c=[].concat(t[s]);o&&i[c[0]]||(n&&(c[2]?c[2]="".concat(n," and ").concat(c[2]):c[2]=n),e.push(c))}},e}},379:(t,e,n)=>{var o,i=function(){return void 0===o&&(o=Boolean(window&&document&&document.all&&!window.atob)),o},r=function(){var t={};return function(e){if(void 0===t[e]){var n=document.querySelector(e);if(window.HTMLIFrameElement&&n instanceof window.HTMLIFrameElement)try{n=n.contentDocument.head}catch(t){n=null}t[e]=n}return t[e]}}(),a=[];function s(t){for(var e=-1,n=0;n<a.length;n++)if(a[n].identifier===t){e=n;break}return e}function c(t,e){for(var n={},o=[],i=0;i<t.length;i++){var r=t[i],c=e.base?r[0]+e.base:r[0],u=n[c]||0,l="".concat(c," ").concat(u);n[c]=u+1;var f=s(l),p={css:r[1],media:r[2],sourceMap:r[3]};-1!==f?(a[f].references++,a[f].updater(p)):a.push({identifier:l,updater:h(p,e),references:1}),o.push(l)}return o}function u(t){var e=document.createElement("style"),o=t.attributes||{};if(void 0===o.nonce){var i=n.nc;i&&(o.nonce=i)}if(Object.keys(o).forEach((function(t){e.setAttribute(t,o[t])})),"function"==typeof t.insert)t.insert(e);else{var a=r(t.insert||"head");if(!a)throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");a.appendChild(e)}return e}var l,f=(l=[],function(t,e){return l[t]=e,l.filter(Boolean).join("\n")});function p(t,e,n,o){var i=n?"":o.media?"@media ".concat(o.media," {").concat(o.css,"}"):o.css;if(t.styleSheet)t.styleSheet.cssText=f(e,i);else{var r=document.createTextNode(i),a=t.childNodes;a[e]&&t.removeChild(a[e]),a.length?t.insertBefore(r,a[e]):t.appendChild(r)}}function d(t,e,n){var o=n.css,i=n.media,r=n.sourceMap;if(i?t.setAttribute("media",i):t.removeAttribute("media"),r&&"undefined"!=typeof btoa&&(o+="\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(r))))," */")),t.styleSheet)t.styleSheet.cssText=o;else{for(;t.firstChild;)t.removeChild(t.firstChild);t.appendChild(document.createTextNode(o))}}var m=null,v=0;function h(t,e){var n,o,i;if(e.singleton){var r=v++;n=m||(m=u(e)),o=p.bind(null,n,r,!1),i=p.bind(null,n,r,!0)}else n=u(e),o=d.bind(null,n,e),i=function(){!function(t){if(null===t.parentNode)return!1;t.parentNode.removeChild(t)}(n)};return o(t),function(e){if(e){if(e.css===t.css&&e.media===t.media&&e.sourceMap===t.sourceMap)return;o(t=e)}else i()}}t.exports=function(t,e){(e=e||{}).singleton||"boolean"==typeof e.singleton||(e.singleton=i());var n=c(t=t||[],e);return function(t){if(t=t||[],"[object Array]"===Object.prototype.toString.call(t)){for(var o=0;o<n.length;o++){var i=s(n[o]);a[i].references--}for(var r=c(t,e),u=0;u<n.length;u++){var l=s(n[u]);0===a[l].references&&(a[l].updater(),a.splice(l,1))}n=r}}}},904:(t,e,n)=>{n.r(e),n.d(e,{default:()=>u});function o(t){return o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},o(t)}const i={components:{},directives:{},props:[],data:function(){return{modelSrc:"",model:!1,crap:!1,show:!0,previews:{},option:{img:"",mode:"cover",maxImgSize:2e3,info:!1,infoTrue:!0,original:!1,outputType:"png",outputSize:1,full:!1,high:!1,canScale:!0,canMove:!0,canMoveBox:!1,centerBox:!0,fixedBox:!0,fixed:!0,fixedNumber:[1,1],limitMinSize:[400,400],autoCrop:!0,autoCropWidth:400,autoCropHeight:400}}},computed:{},watch:{},created:function(){},mounted:function(){},updated:function(){},activated:function(){},deactivated:function(){},methods:{rotateLeft:function(){this.$refs.cropper.rotateLeft()},rotateRight:function(){this.$refs.cropper.rotateRight()},finish:function(t){var e=this;"blob"===t?this.$refs.cropper.getCropBlob((function(t){e.$emit("blob",t);var n=window.URL.createObjectURL(t);console.log(n),e.model=!0,e.modelSrc=n})):this.$refs.cropper.getCropData((function(t){e.model=!0,e.modelSrc=t}))},uploadImg:function(t,e){var n=this,i=t.target.files[0];if(!/\.(gif|jpg|jpeg|png|bmp|GIF|JPG|PNG)$/.test(t.target.value))return alert("O formato da imagem deve ser um dos seguintes: .gif, .jpeg, .jpg, .png, or .bmp"),!1;var r=new FileReader;r.onload=function(t){var i;i="object"===o(t.target.result)?window.URL.createObjectURL(new Blob([t.target.result])):t.target.result,1===e?n.option.img=i:2===e&&(n.example2.img=i)},r.readAsArrayBuffer(i)}}};var r=n(379),a=n.n(r),s=n(624),c={insert:"head",singleton:!1};a()(s.Z,c);s.Z.locals;const u=(0,n(900).Z)(i,(function(){var t=this,e=t._self._c;return e("div",{staticClass:"container-lg hstack gap-3"},[e("div",[e("div",{staticStyle:{width:"500px",height:"500px"}},[e("vue-cropper",{ref:"cropper",attrs:{img:t.option.img,mode:t.option.mode,"max-img-size":t.option.maxImgSize,info:t.option.info,"info-true":t.option.infoTrue,original:t.option.original,"output-type":t.option.outputType,"output-size":t.option.outputSize,full:t.option.full,high:t.option.high,"can-scale":t.option.canScale,"can-move":t.option.canMove,"can-move-box":t.option.canMoveBox,"center-box":t.option.centerBox,"fixed-box":t.option.fixedBox,fixed:t.option.fixed,"fixed-number":t.option.fixedNumber,"limit-min-size":t.option.limitMinSize,"auto-crop":t.option.autoCrop,"auto-crop-width":t.option.autoCropWidth,"auto-crop-height":t.option.autoCropHeight}})],1),t._v(" "),e("div",{staticClass:"hstack justify-content-center gap-3 mt-3"},[t._m(0),t._v(" "),e("input",{staticStyle:{position:"absolute",clip:"rect(0 0 0 0)"},attrs:{type:"file",id:"uploads",accept:"image/png, image/jpeg, image/gif, image/jpg"},on:{change:function(e){return t.uploadImg(e,1)}}}),t._v(" "),e("button",{staticClass:"btn btn-info btn-sm",on:{click:t.rotateLeft}},[e("i",{staticClass:"fa-solid fa-rotate-left"}),t._v("\n        Girar\n      ")]),t._v(" "),e("button",{staticClass:"btn btn-info btn-sm",on:{click:t.rotateRight}},[e("i",{staticClass:"fa-solid fa-rotate-right"}),t._v("\n        Girar\n      ")]),t._v(" "),e("button",{staticClass:"btn btn-orange btn-sm",on:{click:function(e){return t.finish("blob")}}},[e("i",{staticClass:"fa-solid fa-check"}),t._v("\n        Confirmar\n      ")])])]),t._v(" "),t._m(1)])}),[function(){var t=this._self._c;return t("label",{staticClass:"btn btn-info btn-sm",attrs:{for:"uploads"}},[t("i",{staticClass:"fa-solid fa-folder-open"}),this._v("\n        Selecionar arquivo\n      ")])},function(){var t=this,e=t._self._c;return e("div",{staticClass:"vstack justify-content-center text-light"},[e("h1",{staticClass:"fs-3"},[t._v("Instruções:")]),t._v(" "),e("p",[t._v("A imagem final será o conteúdo da área iluminada.")]),t._v(" "),e("p",[t._v("Centralize o rosto na área iluminada.")]),t._v(" "),e("p",[t._v("Utilize o zoom com a roda do mouse.")]),t._v(" "),e("p",[t._v("Clique e arraste para mover a imagem.")])])}],!1,null,"b72b09d6",null).exports},900:(t,e,n)=>{function o(t,e,n,o,i,r,a,s){var c,u="function"==typeof t?t.options:t;if(e&&(u.render=e,u.staticRenderFns=n,u._compiled=!0),o&&(u.functional=!0),r&&(u._scopeId="data-v-"+r),a?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),i&&i.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(a)},u._ssrRegister=c):i&&(c=s?function(){i.call(this,(u.functional?this.parent:this).$root.$options.shadowRoot)}:i),c)if(u.functional){u._injectStyles=c;var l=u.render;u.render=function(t,e){return c.call(e),l(t,e)}}else{var f=u.beforeCreate;u.beforeCreate=f?[].concat(f,c):[c]}return{exports:t,options:u}}n.d(e,{Z:()=>o})}}]);