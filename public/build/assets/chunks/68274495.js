import"./0f8de393.js";import{g as t}from"./c90bc5ac.js";import{c as o}from"./f2f26ab9.js";import{o as e}from"./cffa7190.js";import{t as r}from"./f30bb21e.js";var n=o,a=e,p=r,s=t("%Map%",!0),f=n("Map.prototype.get",!0),i=n("Map.prototype.set",!0),c=n("Map.prototype.has",!0),u=n("Map.prototype.delete",!0),m=n("Map.prototype.size",!0),d=!!s&&function(){var t,o={assert:function(t){if(!o.has(t))throw new p("Side channel does not contain "+a(t))},delete:function(o){if(t){var e=u(t,o);return 0===m(t)&&(t=void 0),e}return!1},get:function(o){if(t)return f(t,o)},has:function(o){return!!t&&c(t,o)},set:function(o,e){t||(t=new s),i(t,o,e)}};return o};export{d as s};
