import{d as e}from"./265177bd.js";import{d as r,a as t}from"./74acc748.js";function i(e,{storage:i,serializer:s,key:a,debug:o,pick:n,omit:c,beforeHydrate:d,afterHydrate:l},p,u=!0){try{u&&(null==d||d(p));const o=i.getItem(a);if(o){const i=s.deserialize(o),a=n?r(i,n):i,d=c?t(a,c):a;e.$patch(d)}u&&(null==l||l(p))}catch(f){o&&console.error("[pinia-plugin-persistedstate]",f)}}function s(e,{storage:i,serializer:s,key:a,debug:o,pick:n,omit:c}){try{const o=n?r(e,n):e,d=c?t(o,c):o,l=s.serialize(d);i.setItem(a,l)}catch(d){o&&console.error("[pinia-plugin-persistedstate]",d)}}var a=function(r={}){return function(t){!function(e,r,t){const{pinia:a,store:o,options:{persist:n=t}}=e;if(!n)return;if(!(o.$id in a.state.value)){const e=a._s.get(o.$id.replace("__hot:",""));return void(e&&Promise.resolve().then((()=>e.$persist())))}const c=(Array.isArray(n)?n:!0===n?[{}]:[n]).map(r);o.$hydrate=({runHooks:r=!0}={})=>{c.forEach((t=>{i(o,t,e,r)}))},o.$persist=()=>{c.forEach((e=>{s(o.$state,e)}))},c.forEach((r=>{i(o,r,e),o.$subscribe(((e,t)=>s(t,r)),{detached:!0})}))}(t,(i=>({key:(r.key?r.key:e=>e)(i.key??t.store.$id),debug:i.debug??r.debug??!1,serializer:i.serializer??r.serializer??{serialize:e=>JSON.stringify(e),deserialize:r=>e(r)},storage:i.storage??r.storage??window.localStorage,beforeHydrate:i.beforeHydrate,afterHydrate:i.afterHydrate,pick:i.pick,omit:i.omit})),r.auto??!1)}}();export{a as s};
