import"./0f8de393.js";import{o as n}from"./cffa7190.js";import{t}from"./f30bb21e.js";var e=n,r=t,o=function(n,t,e){for(var r,o=n;null!=(r=o.next);o=r)if(r.key===t)return o.next=r.next,e||(r.next=n.next,n.next=r),r},u=function(){var n,t={assert:function(n){if(!t.has(n))throw new r("Side channel does not contain "+e(n))},delete:function(t){var e=n&&n.next,r=function(n,t){if(n)return o(n,t,!0)}(n,t);return r&&e&&e===r&&(n=void 0),!!r},get:function(t){return function(n,t){if(n){var e=o(n,t);return e&&e.value}}(n,t)},has:function(t){return function(n,t){return!!n&&!!o(n,t)}(n,t)},set:function(t,e){n||(n={next:void 0}),function(n,t,e){var r=o(n,t);r?r.value=e:n.next={key:t,next:n.next,value:e}}(n,t,e)}};return t};export{u as s};
