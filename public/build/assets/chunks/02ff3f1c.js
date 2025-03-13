import{a as e,i as t,b as a,c as n,d as l,e as s,s as r,g as o,f as i,m as c,D as _,h as u,j as m,k as f,l as g,u as p,n as b,N as d,o as E,p as v,C as L,q as I,r as k,t as F,v as h,w as T,x as R,y as N,z as O,A as y,B as P,M,E as W,F as D,G as U,H as A,I as C,J as S,K as $,L as w,O as V,P as H,Q as x,R as j,S as Y}from"./c91cdefb.js";import{d as G,h as B,e as X,c as z,w as J,F as q,i as Q,o as K,a as Z,b as ee,g as te,r as ae,s as ne,f as le,T as se}from"./e1f92d6d.js";const re={UNEXPECTED_RETURN_TYPE:L,INVALID_ARGUMENT:25,MUST_BE_CALL_SETUP_TOP:26,NOT_INSTALLED:27,REQUIRED_VALUE:28,INVALID_VALUE:29,CANNOT_SETUP_VUE_DEVTOOLS_PLUGIN:30,NOT_INSTALLED_WITH_PROVIDE:31,UNEXPECTED_ERROR:32,NOT_COMPATIBLE_LEGACY_VUE_I18N:33,NOT_AVAILABLE_COMPOSITION_IN_LEGACY:34};function oe(e,...t){return i(e,null,void 0)}const ie=c("__translateVNode"),ce=c("__datetimeParts"),_e=c("__numberParts"),ue=c("__setPluralRules"),me=c("__injectWithOption"),fe=c("__dispose");function ge(e){if(!l(e))return e;for(const t in e)if(k(e,t))if(t.includes(".")){const a=t.split("."),s=a.length-1;let r=e,o=!1;for(let e=0;e<s;e++){if(a[e]in r||(r[a[e]]=n()),!l(r[a[e]])){o=!0;break}r=r[a[e]]}o||(r[a[s]]=e[t],delete e[t]),l(r[a[s]])&&ge(r[a[s]])}else l(e[t])&&ge(e[t]);return e}function pe(e,t){const{messages:l,__i18n:s,messageResolver:r,flatJson:o}=t,i=m(l)?l:u(s)?n():{[e]:n()};if(u(s)&&s.forEach((e=>{if("locale"in e&&"resource"in e){const{locale:t,resource:a}=e;t?(i[t]=i[t]||n(),I(a,i[t])):I(a,i)}else a(e)&&I(JSON.parse(e),i)})),null==r&&o)for(const a in i)k(i,a)&&ge(i[a]);return i}function be(e){return e.type}function de(e,t,a){let s=l(t.messages)?t.messages:n();"__i18nGlobal"in a&&(s=pe(e.locale.value,{messages:s,__i18n:a.__i18nGlobal}));const r=Object.keys(s);if(r.length&&r.forEach((t=>{e.mergeLocaleMessage(t,s[t])})),l(t.datetimeFormats)){const a=Object.keys(t.datetimeFormats);a.length&&a.forEach((a=>{e.mergeDateTimeFormat(a,t.datetimeFormats[a])}))}if(l(t.numberFormats)){const a=Object.keys(t.numberFormats);a.length&&a.forEach((a=>{e.mergeNumberFormat(a,t.numberFormats[a])}))}}function Ee(e){return le(se,null,e,0)}const ve="__INTLIFY_META__",Le=()=>[],Ie=()=>!1;let ke=0;function Fe(e){return(t,a,n,l)=>e(a,n,te()||void 0,l)}const he=()=>{const e=te();let t=null;return e&&(t=be(e)[ve])?{[ve]:t}:null};function Te(n={}){const{__root:r,__injectWithOption:o}=n,i=void 0===r,c=n.flatJson,d=b?ae:ne;let E=!s(n.inheritLocale)||n.inheritLocale;const v=d(r&&E?r.locale.value:a(n.locale)?n.locale:_),L=d(r&&E?r.fallbackLocale.value:a(n.fallbackLocale)||u(n.fallbackLocale)||m(n.fallbackLocale)||!1===n.fallbackLocale?n.fallbackLocale:v.value),w=d(pe(v.value,n)),V=d(m(n.datetimeFormats)?n.datetimeFormats:{[v.value]:{}}),H=d(m(n.numberFormats)?n.numberFormats:{[v.value]:{}});let j=r?r.missingWarn:!s(n.missingWarn)&&!f(n.missingWarn)||n.missingWarn,Y=r?r.fallbackWarn:!s(n.fallbackWarn)&&!f(n.fallbackWarn)||n.fallbackWarn,G=r?r.fallbackRoot:!s(n.fallbackRoot)||n.fallbackRoot,B=!!n.fallbackFormat,X=g(n.missing)?n.missing:null,q=g(n.missing)?Fe(n.missing):null,Q=g(n.postTranslation)?n.postTranslation:null,K=r?r.warnHtmlMessage:!s(n.warnHtmlMessage)||n.warnHtmlMessage,Z=!!n.escapeParameter;const ee=r?r.modifiers:m(n.modifiers)?n.modifiers:{};let te,le=n.pluralRules||r&&r.pluralRules;te=(()=>{i&&x(null);const e={version:"11.1.1",locale:v.value,fallbackLocale:L.value,messages:w.value,modifiers:ee,pluralRules:le,missing:null===q?void 0:q,missingWarn:j,fallbackWarn:Y,fallbackFormat:B,unresolving:!0,postTranslation:null===Q?void 0:Q,warnHtmlMessage:K,escapeParameter:Z,messageResolver:n.messageResolver,messageCompiler:n.messageCompiler,__meta:{framework:"vue"}};e.datetimeFormats=V.value,e.numberFormats=H.value,e.__datetimeFormatters=m(te)?te.__datetimeFormatters:void 0,e.__numberFormatters=m(te)?te.__numberFormatters:void 0;const t=F(e);return i&&x(t),t})(),p(te,v.value,L.value);const se=z({get:()=>v.value,set:e=>{te.locale=e,v.value=e}}),fe=z({get:()=>L.value,set:e=>{te.fallbackLocale=e,L.value=e,p(te,v.value,e)}}),be=z((()=>w.value)),de=z((()=>V.value)),ve=z((()=>H.value));const Te=(e,a,n,l,s,o)=>{let c;v.value,L.value,w.value,V.value,H.value;try{__INTLIFY_PROD_DEVTOOLS__&&R(he()),i||(te.fallbackContext=r?N():void 0),c=e(te)}finally{__INTLIFY_PROD_DEVTOOLS__,i||(te.fallbackContext=void 0)}if("translate exists"!==n&&t(c)&&c===O||"translate exists"===n&&!c){const[e,t]=a();return r&&G?l(r):s(e)}if(o(c))return c;throw oe(re.UNEXPECTED_RETURN_TYPE)};function Re(...e){return Te((t=>Reflect.apply(P,null,[t,...e])),(()=>y(...e)),"translate",(t=>Reflect.apply(t.t,t,[...e])),(e=>e),(e=>a(e)))}const Ne={normalize:function(e){return e.map((e=>a(e)||t(e)||s(e)?Ee(String(e)):e))},interpolate:e=>e,type:"vnode"};function Oe(e){return w.value[e]||{}}ke++,r&&b&&(J(r.locale,(e=>{E&&(v.value=e,te.locale=e,p(te,v.value,L.value))})),J(r.fallbackLocale,(e=>{E&&(L.value=e,te.fallbackLocale=e,p(te,v.value,L.value))})));const ye={id:ke,locale:se,fallbackLocale:fe,get inheritLocale(){return E},set inheritLocale(e){E=e,e&&r&&(v.value=r.locale.value,L.value=r.fallbackLocale.value,p(te,v.value,L.value))},get availableLocales(){return Object.keys(w.value).sort()},messages:be,get modifiers(){return ee},get pluralRules(){return le||{}},get isGlobal(){return i},get missingWarn(){return j},set missingWarn(e){j=e,te.missingWarn=j},get fallbackWarn(){return Y},set fallbackWarn(e){Y=e,te.fallbackWarn=Y},get fallbackRoot(){return G},set fallbackRoot(e){G=e},get fallbackFormat(){return B},set fallbackFormat(e){B=e,te.fallbackFormat=B},get warnHtmlMessage(){return K},set warnHtmlMessage(e){K=e,te.warnHtmlMessage=e},get escapeParameter(){return Z},set escapeParameter(e){Z=e,te.escapeParameter=e},t:Re,getLocaleMessage:Oe,setLocaleMessage:function(e,t){if(c){const a={[e]:t};for(const e in a)k(a,e)&&ge(a[e]);t=a[e]}w.value[e]=t,te.messages=w.value},mergeLocaleMessage:function(e,t){w.value[e]=w.value[e]||{};const a={[e]:t};if(c)for(const n in a)k(a,n)&&ge(a[n]);I(t=a[e],w.value[e]),te.messages=w.value},getPostTranslationHandler:function(){return g(Q)?Q:null},setPostTranslationHandler:function(e){Q=e,te.postTranslation=e},getMissingHandler:function(){return X},setMissingHandler:function(e){null!==e&&(q=Fe(e)),X=e,te.missing=q},[ue]:function(e){le=e,te.pluralRules=le}};return ye.datetimeFormats=de,ye.numberFormats=ve,ye.rt=function(...t){const[a,n,s]=t;if(s&&!l(s))throw oe(re.INVALID_ARGUMENT);return Re(a,n,e({resolvedMessage:!0},s||{}))},ye.te=function(e,t){return Te((()=>{if(!e)return!1;const n=Oe(a(t)?t:v.value),l=te.messageResolver(n,e);return C(l)||S(l)||a(l)}),(()=>[e]),"translate exists",(a=>Reflect.apply(a.te,a,[e,t])),Ie,(e=>s(e)))},ye.tm=function(e){const t=function(e){let t=null;const a=$(te,L.value,v.value);for(let n=0;n<a.length;n++){const l=w.value[a[n]]||{},s=te.messageResolver(l,e);if(null!=s){t=s;break}}return t}(e);return null!=t?t:r&&r.tm(e)||{}},ye.d=function(...e){return Te((t=>Reflect.apply(D,null,[t,...e])),(()=>W(...e)),"datetime format",(t=>Reflect.apply(t.d,t,[...e])),(()=>M),(e=>a(e)))},ye.n=function(...e){return Te((t=>Reflect.apply(A,null,[t,...e])),(()=>U(...e)),"number format",(t=>Reflect.apply(t.n,t,[...e])),(()=>M),(e=>a(e)))},ye.getDateTimeFormat=function(e){return V.value[e]||{}},ye.setDateTimeFormat=function(e,t){V.value[e]=t,te.datetimeFormats=V.value,h(te,e,t)},ye.mergeDateTimeFormat=function(t,a){V.value[t]=e(V.value[t]||{},a),te.datetimeFormats=V.value,h(te,t,a)},ye.getNumberFormat=function(e){return H.value[e]||{}},ye.setNumberFormat=function(e,t){H.value[e]=t,te.numberFormats=H.value,T(te,e,t)},ye.mergeNumberFormat=function(t,a){H.value[t]=e(H.value[t]||{},a),te.numberFormats=H.value,T(te,t,a)},ye[me]=o,ye[ie]=function(...e){return Te((t=>{let a;const n=t;try{n.processor=Ne,a=Reflect.apply(P,null,[n,...e])}finally{n.processor=null}return a}),(()=>y(...e)),"translate",(t=>t[ie](...e)),(e=>[Ee(e)]),(e=>u(e)))},ye[ce]=function(...e){return Te((t=>Reflect.apply(D,null,[t,...e])),(()=>W(...e)),"datetime format",(t=>t[ce](...e)),Le,(e=>a(e)||u(e)))},ye[_e]=function(...e){return Te((t=>Reflect.apply(A,null,[t,...e])),(()=>U(...e)),"number format",(t=>t[_e](...e)),Le,(e=>a(e)||u(e)))},ye}function Re(t={}){const n=Te(function(t){const n=a(t.locale)?t.locale:_,l=a(t.fallbackLocale)||u(t.fallbackLocale)||m(t.fallbackLocale)||!1===t.fallbackLocale?t.fallbackLocale:n,r=g(t.missing)?t.missing:void 0,o=!s(t.silentTranslationWarn)&&!f(t.silentTranslationWarn)||!t.silentTranslationWarn,i=!s(t.silentFallbackWarn)&&!f(t.silentFallbackWarn)||!t.silentFallbackWarn,c=!s(t.fallbackRoot)||t.fallbackRoot,p=!!t.formatFallbackMessages,b=m(t.modifiers)?t.modifiers:{},d=t.pluralizationRules,E=g(t.postTranslation)?t.postTranslation:void 0,v=!a(t.warnHtmlInMessage)||"off"!==t.warnHtmlInMessage,L=!!t.escapeParameterHtml,I=!s(t.sync)||t.sync;let k=t.messages;if(m(t.sharedMessages)){const a=t.sharedMessages;k=Object.keys(a).reduce(((t,n)=>{const l=t[n]||(t[n]={});return e(l,a[n]),t}),k||{})}const{__i18n:F,__root:h,__injectWithOption:T}=t,R=t.datetimeFormats,N=t.numberFormats;return{locale:n,fallbackLocale:l,messages:k,flatJson:t.flatJson,datetimeFormats:R,numberFormats:N,missing:r,missingWarn:o,fallbackWarn:i,fallbackRoot:c,fallbackFormat:p,modifiers:b,pluralRules:d,postTranslation:E,warnHtmlMessage:v,escapeParameter:L,messageResolver:t.messageResolver,inheritLocale:I,__i18n:F,__root:h,__injectWithOption:T}}(t)),{__extender:l}=t,r={id:n.id,get locale(){return n.locale.value},set locale(e){n.locale.value=e},get fallbackLocale(){return n.fallbackLocale.value},set fallbackLocale(e){n.fallbackLocale.value=e},get messages(){return n.messages.value},get datetimeFormats(){return n.datetimeFormats.value},get numberFormats(){return n.numberFormats.value},get availableLocales(){return n.availableLocales},get missing(){return n.getMissingHandler()},set missing(e){n.setMissingHandler(e)},get silentTranslationWarn(){return s(n.missingWarn)?!n.missingWarn:n.missingWarn},set silentTranslationWarn(e){n.missingWarn=s(e)?!e:e},get silentFallbackWarn(){return s(n.fallbackWarn)?!n.fallbackWarn:n.fallbackWarn},set silentFallbackWarn(e){n.fallbackWarn=s(e)?!e:e},get modifiers(){return n.modifiers},get formatFallbackMessages(){return n.fallbackFormat},set formatFallbackMessages(e){n.fallbackFormat=e},get postTranslation(){return n.getPostTranslationHandler()},set postTranslation(e){n.setPostTranslationHandler(e)},get sync(){return n.inheritLocale},set sync(e){n.inheritLocale=e},get warnHtmlInMessage(){return n.warnHtmlMessage?"warn":"off"},set warnHtmlInMessage(e){n.warnHtmlMessage="off"!==e},get escapeParameterHtml(){return n.escapeParameter},set escapeParameterHtml(e){n.escapeParameter=e},get pluralizationRules(){return n.pluralRules||{}},__composer:n,t:(...e)=>Reflect.apply(n.t,n,[...e]),rt:(...e)=>Reflect.apply(n.rt,n,[...e]),te:(e,t)=>n.te(e,t),tm:e=>n.tm(e),getLocaleMessage:e=>n.getLocaleMessage(e),setLocaleMessage(e,t){n.setLocaleMessage(e,t)},mergeLocaleMessage(e,t){n.mergeLocaleMessage(e,t)},d:(...e)=>Reflect.apply(n.d,n,[...e]),getDateTimeFormat:e=>n.getDateTimeFormat(e),setDateTimeFormat(e,t){n.setDateTimeFormat(e,t)},mergeDateTimeFormat(e,t){n.mergeDateTimeFormat(e,t)},n:(...e)=>Reflect.apply(n.n,n,[...e]),getNumberFormat:e=>n.getNumberFormat(e),setNumberFormat(e,t){n.setNumberFormat(e,t)},mergeNumberFormat(e,t){n.mergeNumberFormat(e,t)}};return r.__extender=l,r}function Ne(e,t){e.locale=t.locale||e.locale,e.fallbackLocale=t.fallbackLocale||e.fallbackLocale,e.missing=t.missing||e.missing,e.silentTranslationWarn=t.silentTranslationWarn||e.silentFallbackWarn,e.silentFallbackWarn=t.silentFallbackWarn||e.silentFallbackWarn,e.formatFallbackMessages=t.formatFallbackMessages||e.formatFallbackMessages,e.postTranslation=t.postTranslation||e.postTranslation,e.warnHtmlInMessage=t.warnHtmlInMessage||e.warnHtmlInMessage,e.escapeParameterHtml=t.escapeParameterHtml||e.escapeParameterHtml,e.sync=t.sync||e.sync,e.__composer[ue](t.pluralizationRules||e.pluralizationRules);const a=pe(e.locale,{messages:t.messages,__i18n:t.__i18n});return Object.keys(a).forEach((t=>e.mergeLocaleMessage(t,a[t]))),t.datetimeFormats&&Object.keys(t.datetimeFormats).forEach((a=>e.mergeDateTimeFormat(a,t.datetimeFormats[a]))),t.numberFormats&&Object.keys(t.numberFormats).forEach((a=>e.mergeNumberFormat(a,t.numberFormats[a]))),e}const Oe={tag:{type:[String,Object]},locale:{type:String},scope:{type:String,validator:e=>"parent"===e||"global"===e,default:"parent"},i18n:{type:Object}};function ye(){return q}const Pe=G({name:"i18n-t",props:e({keypath:{type:String,required:!0},plural:{type:[Number,String],validator:e=>t(e)||!isNaN(e)}},Oe),setup(t,s){const{slots:r,attrs:o}=s,i=t.i18n||$e({useScope:t.scope,__useComponent:!0});return()=>{const c=Object.keys(r).filter((e=>"_"!==e)),_=n();t.locale&&(_.locale=t.locale),void 0!==t.plural&&(_.plural=a(t.plural)?+t.plural:t.plural);const u=function({slots:e},t){if(1===t.length&&"default"===t[0])return(e.default?e.default():[]).reduce(((e,t)=>[...e,...t.type===q?t.children:[t]]),[]);return t.reduce(((t,a)=>{const n=e[a];return n&&(t[a]=n()),t}),n())}(s,c),m=i[ie](t.keypath,u,_),f=e(n(),o),g=a(t.tag)||l(t.tag)?t.tag:ye();return B(g,f,m)}}});function Me(t,s,r,o){const{slots:i,attrs:c}=s;return()=>{const s={part:!0};let _=n();t.locale&&(s.locale=t.locale),a(t.format)?s.key=t.format:l(t.format)&&(a(t.format.key)&&(s.key=t.format.key),_=Object.keys(t.format).reduce(((a,l)=>r.includes(l)?e(n(),a,{[l]:t.format[l]}):a),n()));const m=o(t.value,s,_);let f=[s.key];u(m)?f=m.map(((e,t)=>{const n=i[e.type],l=n?n({[e.type]:e.value,index:t,parts:m}):[e.value];var s;return u(s=l)&&!a(s[0])&&(l[0].key=`${e.type}-${t}`),l})):a(m)&&(f=[m]);const g=e(n(),c),p=a(t.tag)||l(t.tag)?t.tag:ye();return B(p,g,f)}}const We=G({name:"i18n-n",props:e({value:{type:Number,required:!0},format:{type:[String,Object]}},Oe),setup(e,t){const a=e.i18n||$e({useScope:e.scope,__useComponent:!0});return Me(e,t,d,((...e)=>a[_e](...e)))}});function De(e){if(a(e))return{path:e};if(m(e)){if(!("path"in e))throw oe(re.REQUIRED_VALUE);return e}throw oe(re.INVALID_VALUE)}function Ue(e){const{path:n,locale:l,args:s,choice:r,plural:o}=e,i={},c=s||{};return a(l)&&(i.locale=l),t(r)&&(i.plural=r),t(o)&&(i.plural=o),[n,c,i]}function Ae(e,t,...a){const n=m(a[0])?a[0]:{};(!s(n.globalInstall)||n.globalInstall)&&([Pe.name,"I18nT"].forEach((t=>e.component(t,Pe))),[We.name,"I18nN"].forEach((t=>e.component(t,We))),[He.name,"I18nD"].forEach((t=>e.component(t,He)))),e.directive("t",function(e){const t=t=>{const{instance:a,value:n}=t;if(!a||!a.$)throw oe(re.UNEXPECTED_ERROR);const l=function(e,t){const a=e;if("composition"===e.mode)return a.__getInstance(t)||e.global;{const n=a.__getInstance(t);return null!=n?n.__composer:e.global.__composer}}(e,a.$),s=De(n);return[Reflect.apply(l.t,l,[...Ue(s)]),l]};return{created:(a,n)=>{const[l,s]=t(n);b&&e.global===s&&(a.__i18nWatcher=J(s.locale,(()=>{n.instance&&n.instance.$forceUpdate()}))),a.__composer=s,a.textContent=l},unmounted:e=>{b&&e.__i18nWatcher&&(e.__i18nWatcher(),e.__i18nWatcher=void 0,delete e.__i18nWatcher),e.__composer&&(e.__composer=void 0,delete e.__composer)},beforeUpdate:(e,{value:t})=>{if(e.__composer){const a=e.__composer,n=De(t);e.textContent=Reflect.apply(a.t,a,[...Ue(n)])}},getSSRProps:e=>{const[a]=t(e);return{textContent:a}}}}(t))}const Ce=c("global-vue-i18n");function Se(e={}){const t=__VUE_I18N_LEGACY_API__&&s(e.legacy)?e.legacy:__VUE_I18N_LEGACY_API__,a=!s(e.globalInjection)||e.globalInjection,n=new Map,[l,r]=function(e,t){const a=X(),n=__VUE_I18N_LEGACY_API__&&t?a.run((()=>Re(e))):a.run((()=>Te(e)));if(null==n)throw oe(re.UNEXPECTED_ERROR);return[a,n]}(e,t),o=c("");const i={get mode(){return __VUE_I18N_LEGACY_API__&&t?"legacy":"composition"},async install(e,...n){if(e.__VUE_I18N_SYMBOL__=o,e.provide(e.__VUE_I18N_SYMBOL__,i),m(n[0])){const e=n[0];i.__composerExtend=e.__composerExtend,i.__vueI18nExtend=e.__vueI18nExtend}let l=null;!t&&a&&(l=function(e,t){const a=Object.create(null);we.forEach((e=>{const n=Object.getOwnPropertyDescriptor(t,e);if(!n)throw oe(re.UNEXPECTED_ERROR);const l=ee(n.value)?{get:()=>n.value.value,set(e){n.value.value=e}}:{get:()=>n.get&&n.get()};Object.defineProperty(a,e,l)})),e.config.globalProperties.$i18n=a,Ve.forEach((a=>{const n=Object.getOwnPropertyDescriptor(t,a);if(!n||!n.value)throw oe(re.UNEXPECTED_ERROR);Object.defineProperty(e.config.globalProperties,`$${a}`,n)}));const n=()=>{delete e.config.globalProperties.$i18n,Ve.forEach((t=>{delete e.config.globalProperties[`$${t}`]}))};return n}(e,i.global)),__VUE_I18N_FULL_INSTALL__&&Ae(e,i,...n),__VUE_I18N_LEGACY_API__&&t&&e.mixin(function(e,t,a){return{beforeCreate(){const n=te();if(!n)throw oe(re.UNEXPECTED_ERROR);const l=this.$options;if(l.i18n){const n=l.i18n;if(l.__i18n&&(n.__i18n=l.__i18n),n.__root=t,this===this.$root)this.$i18n=Ne(e,n);else{n.__injectWithOption=!0,n.__extender=a.__vueI18nExtend,this.$i18n=Re(n);const e=this.$i18n;e.__extender&&(e.__disposer=e.__extender(this.$i18n))}}else if(l.__i18n)if(this===this.$root)this.$i18n=Ne(e,l);else{this.$i18n=Re({__i18n:l.__i18n,__injectWithOption:!0,__extender:a.__vueI18nExtend,__root:t});const e=this.$i18n;e.__extender&&(e.__disposer=e.__extender(this.$i18n))}else this.$i18n=e;l.__i18nGlobal&&de(t,l,l),this.$t=(...e)=>this.$i18n.t(...e),this.$rt=(...e)=>this.$i18n.rt(...e),this.$te=(e,t)=>this.$i18n.te(e,t),this.$d=(...e)=>this.$i18n.d(...e),this.$n=(...e)=>this.$i18n.n(...e),this.$tm=e=>this.$i18n.tm(e),a.__setInstance(n,this.$i18n)},mounted(){},unmounted(){const e=te();if(!e)throw oe(re.UNEXPECTED_ERROR);const t=this.$i18n;delete this.$t,delete this.$rt,delete this.$te,delete this.$d,delete this.$n,delete this.$tm,t.__disposer&&(t.__disposer(),delete t.__disposer,delete t.__extender),a.__deleteInstance(e),delete this.$i18n}}}(r,r.__composer,i));const s=e.unmount;e.unmount=()=>{l&&l(),i.dispose(),s()}},get global(){return r},dispose(){l.stop()},__instances:n,__getInstance:function(e){return n.get(e)||null},__setInstance:function(e,t){n.set(e,t)},__deleteInstance:function(e){n.delete(e)}};return i}function $e(t={}){const a=te();if(null==a)throw oe(re.MUST_BE_CALL_SETUP_TOP);if(!a.isCE&&null!=a.appContext.app&&!a.appContext.app.__VUE_I18N_SYMBOL__)throw oe(re.NOT_INSTALLED);const n=function(e){const t=Q(e.isCE?Ce:e.appContext.app.__VUE_I18N_SYMBOL__);if(!t)throw oe(e.isCE?re.NOT_INSTALLED_WITH_PROVIDE:re.UNEXPECTED_ERROR);return t}(a),l=function(e){return"composition"===e.mode?e.global:e.global.__composer}(n),s=be(a),r=function(e,t){return E(e)?"__i18n"in t?"local":"global":e.useScope?e.useScope:"local"}(t,s);if("global"===r)return de(l,t,s),l;if("parent"===r){let e=function(e,t,a=!1){let n=null;const l=t.root;let s=function(e,t=!1){if(null==e)return null;return t&&e.vnode.ctx||e.parent}(t,a);for(;null!=s;){const t=e;if("composition"===e.mode)n=t.__getInstance(s);else if(__VUE_I18N_LEGACY_API__){const e=t.__getInstance(s);null!=e&&(n=e.__composer,a&&n&&!n[me]&&(n=null))}if(null!=n)break;if(l===s)break;s=s.parent}return n}(n,a,t.__useComponent);return null==e&&(e=l),e}const o=n;let i=o.__getInstance(a);if(null==i){const n=e({},t);"__i18n"in s&&(n.__i18n=s.__i18n),l&&(n.__root=l),i=Te(n),o.__composerExtend&&(i[fe]=o.__composerExtend(i)),function(e,t,a){K((()=>{}),t),Z((()=>{const n=a;e.__deleteInstance(t);const l=n[fe];l&&(l(),delete n[fe])}),t)}(o,a,i),o.__setInstance(a,i)}return i}const we=["locale","fallbackLocale","availableLocales"],Ve=["t","rt","d","n","tm","te"];const He=G({name:"i18n-d",props:e({value:{type:[Number,Date],required:!0},format:{type:[String,Object]}},Oe),setup(e,t){const a=e.i18n||$e({useScope:e.scope,__useComponent:!0});return Me(e,t,v,((...e)=>a[ce](...e)))}});if("boolean"!=typeof __VUE_I18N_FULL_INSTALL__&&(o().__VUE_I18N_FULL_INSTALL__=!0),"boolean"!=typeof __VUE_I18N_LEGACY_API__&&(o().__VUE_I18N_LEGACY_API__=!0),"boolean"!=typeof __INTLIFY_DROP_MESSAGE_COMPILER__&&(o().__INTLIFY_DROP_MESSAGE_COMPILER__=!1),"boolean"!=typeof __INTLIFY_PROD_DEVTOOLS__&&(o().__INTLIFY_PROD_DEVTOOLS__=!1),w(j),V(Y),H($),__INTLIFY_PROD_DEVTOOLS__){const e=o();e.__INTLIFY__=!0,r(e.__INTLIFY_DEVTOOLS_GLOBAL_HOOK__)}export{Se as c};
