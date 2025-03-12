import{a as t,c as e}from"./0f8de393.js";const r=t(Object.freeze(Object.defineProperty({__proto__:null,default:{}},Symbol.toStringTag,{value:"Module"})));var n="function"==typeof Map&&Map.prototype,o=Object.getOwnPropertyDescriptor&&n?Object.getOwnPropertyDescriptor(Map.prototype,"size"):null,i=n&&o&&"function"==typeof o.get?o.get:null,u=n&&Map.prototype.forEach,l="function"==typeof Set&&Set.prototype,c=Object.getOwnPropertyDescriptor&&l?Object.getOwnPropertyDescriptor(Set.prototype,"size"):null,a=l&&c&&"function"==typeof c.get?c.get:null,f=l&&Set.prototype.forEach,p="function"==typeof WeakMap&&WeakMap.prototype?WeakMap.prototype.has:null,y="function"==typeof WeakSet&&WeakSet.prototype?WeakSet.prototype.has:null,g="function"==typeof WeakRef&&WeakRef.prototype?WeakRef.prototype.deref:null,s=Boolean.prototype.valueOf,b=Object.prototype.toString,S=Function.prototype.toString,h=String.prototype.match,m=String.prototype.slice,d=String.prototype.replace,v=String.prototype.toUpperCase,j=String.prototype.toLowerCase,O=RegExp.prototype.test,_=Array.prototype.concat,w=Array.prototype.join,x=Array.prototype.slice,M=Math.floor,W="function"==typeof BigInt?BigInt.prototype.valueOf:null,k=Object.getOwnPropertySymbols,E="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?Symbol.prototype.toString:null,T="function"==typeof Symbol&&"object"==typeof Symbol.iterator,L="function"==typeof Symbol&&Symbol.toStringTag&&(typeof Symbol.toStringTag===T||"symbol")?Symbol.toStringTag:null,$=Object.prototype.propertyIsEnumerable,A=("function"==typeof Reflect?Reflect.getPrototypeOf:Object.getPrototypeOf)||([].__proto__===Array.prototype?function(t){return t.__proto__}:null);function q(t,e){if(t===1/0||t===-1/0||t!=t||t&&t>-1e3&&t<1e3||O.call(/e/,e))return e;var r=/[0-9](?=(?:[0-9]{3})+(?![0-9]))/g;if("number"==typeof t){var n=t<0?-M(-t):M(t);if(n!==t){var o=String(n),i=m.call(e,o.length+1);return d.call(o,r,"$&_")+"."+d.call(d.call(i,/([0-9]{3})/g,"$&_"),/_$/,"")}}return d.call(e,r,"$&_")}var I=r,P=I.custom,N=G(P)?P:null,R={__proto__:null,double:'"',single:"'"},D={__proto__:null,double:/(["\\])/g,single:/(['\\])/g},B=function t(r,n,o,l){var c=n||{};if(K(c,"quoteStyle")&&!K(R,c.quoteStyle))throw new TypeError('option "quoteStyle" must be "single" or "double"');if(K(c,"maxStringLength")&&("number"==typeof c.maxStringLength?c.maxStringLength<0&&c.maxStringLength!==1/0:null!==c.maxStringLength))throw new TypeError('option "maxStringLength", if provided, must be a positive integer, Infinity, or `null`');var b=!K(c,"customInspect")||c.customInspect;if("boolean"!=typeof b&&"symbol"!==b)throw new TypeError("option \"customInspect\", if provided, must be `true`, `false`, or `'symbol'`");if(K(c,"indent")&&null!==c.indent&&"\t"!==c.indent&&!(parseInt(c.indent,10)===c.indent&&c.indent>0))throw new TypeError('option "indent" must be "\\t", an integer > 0, or `null`');if(K(c,"numericSeparator")&&"boolean"!=typeof c.numericSeparator)throw new TypeError('option "numericSeparator", if provided, must be `true` or `false`');var v=c.numericSeparator;if(void 0===r)return"undefined";if(null===r)return"null";if("boolean"==typeof r)return r?"true":"false";if("string"==typeof r)return X(r,c);if("number"==typeof r){if(0===r)return 1/0/r>0?"0":"-0";var O=String(r);return v?q(r,O):O}if("bigint"==typeof r){var M=String(r)+"n";return v?q(r,M):M}var k=void 0===c.depth?5:c.depth;if(void 0===o&&(o=0),o>=k&&k>0&&"object"==typeof r)return H(r)?"[Array]":"[Object]";var P=function(t,e){var r;if("\t"===t.indent)r="\t";else{if(!("number"==typeof t.indent&&t.indent>0))return null;r=w.call(Array(t.indent+1)," ")}return{base:r,prev:w.call(Array(e+1),r)}}(c,o);if(void 0===l)l=[];else if(V(l,r)>=0)return"[Circular]";function D(e,r,n){if(r&&(l=x.call(l)).push(r),n){var i={depth:c.depth};return K(c,"quoteStyle")&&(i.quoteStyle=c.quoteStyle),t(e,i,o+1,l)}return t(e,c,o+1,l)}if("function"==typeof r&&!U(r)){var B=function(t){if(t.name)return t.name;var e=h.call(S.call(t),/^function\s*([\w$]+)/);if(e)return e[1];return null}(r),J=nt(r,D);return"[Function"+(B?": "+B:" (anonymous)")+"]"+(J.length>0?" { "+w.call(J,", ")+" }":"")}if(G(r)){var Y=T?d.call(String(r),/^(Symbol\(.*\))_[^)]*$/,"$1"):E.call(r);return"object"!=typeof r||T?Y:Z(Y)}if(function(t){if(!t||"object"!=typeof t)return!1;if("undefined"!=typeof HTMLElement&&t instanceof HTMLElement)return!0;return"string"==typeof t.nodeName&&"function"==typeof t.getAttribute}(r)){for(var ot="<"+j.call(String(r.nodeName)),it=r.attributes||[],ut=0;ut<it.length;ut++)ot+=" "+it[ut].name+"="+C(z(it[ut].value),"double",c);return ot+=">",r.childNodes&&r.childNodes.length&&(ot+="..."),ot+="</"+j.call(String(r.nodeName))+">"}if(H(r)){if(0===r.length)return"[]";var lt=nt(r,D);return P&&!function(t){for(var e=0;e<t.length;e++)if(V(t[e],"\n")>=0)return!1;return!0}(lt)?"["+rt(lt,P)+"]":"[ "+w.call(lt,", ")+" ]"}if(function(t){return"[object Error]"===Q(t)&&F(t)}(r)){var ct=nt(r,D);return"cause"in Error.prototype||!("cause"in r)||$.call(r,"cause")?0===ct.length?"["+String(r)+"]":"{ ["+String(r)+"] "+w.call(ct,", ")+" }":"{ ["+String(r)+"] "+w.call(_.call("[cause]: "+D(r.cause),ct),", ")+" }"}if("object"==typeof r&&b){if(N&&"function"==typeof r[N]&&I)return I(r,{depth:k-o});if("symbol"!==b&&"function"==typeof r.inspect)return r.inspect()}if(function(t){if(!i||!t||"object"!=typeof t)return!1;try{i.call(t);try{a.call(t)}catch(ot){return!0}return t instanceof Map}catch(e){}return!1}(r)){var at=[];return u&&u.call(r,(function(t,e){at.push(D(e,r,!0)+" => "+D(t,r))})),et("Map",i.call(r),at,P)}if(function(t){if(!a||!t||"object"!=typeof t)return!1;try{a.call(t);try{i.call(t)}catch(e){return!0}return t instanceof Set}catch(r){}return!1}(r)){var ft=[];return f&&f.call(r,(function(t){ft.push(D(t,r))})),et("Set",a.call(r),ft,P)}if(function(t){if(!p||!t||"object"!=typeof t)return!1;try{p.call(t,p);try{y.call(t,y)}catch(ot){return!0}return t instanceof WeakMap}catch(e){}return!1}(r))return tt("WeakMap");if(function(t){if(!y||!t||"object"!=typeof t)return!1;try{y.call(t,y);try{p.call(t,p)}catch(ot){return!0}return t instanceof WeakSet}catch(e){}return!1}(r))return tt("WeakSet");if(function(t){if(!g||!t||"object"!=typeof t)return!1;try{return g.call(t),!0}catch(e){}return!1}(r))return tt("WeakRef");if(function(t){return"[object Number]"===Q(t)&&F(t)}(r))return Z(D(Number(r)));if(function(t){if(!t||"object"!=typeof t||!W)return!1;try{return W.call(t),!0}catch(e){}return!1}(r))return Z(D(W.call(r)));if(function(t){return"[object Boolean]"===Q(t)&&F(t)}(r))return Z(s.call(r));if(function(t){return"[object String]"===Q(t)&&F(t)}(r))return Z(D(String(r)));if("undefined"!=typeof window&&r===window)return"{ [object Window] }";if("undefined"!=typeof globalThis&&r===globalThis||void 0!==e&&r===e)return"{ [object globalThis] }";if(!function(t){return"[object Date]"===Q(t)&&F(t)}(r)&&!U(r)){var pt=nt(r,D),yt=A?A(r)===Object.prototype:r instanceof Object||r.constructor===Object,gt=r instanceof Object?"":"null prototype",st=!yt&&L&&Object(r)===r&&L in r?m.call(Q(r),8,-1):gt?"Object":"",bt=(yt||"function"!=typeof r.constructor?"":r.constructor.name?r.constructor.name+" ":"")+(st||gt?"["+w.call(_.call([],st||[],gt||[]),": ")+"] ":"");return 0===pt.length?bt+"{}":P?bt+"{"+rt(pt,P)+"}":bt+"{ "+w.call(pt,", ")+" }"}return String(r)};function C(t,e,r){var n=r.quoteStyle||e,o=R[n];return o+t+o}function z(t){return d.call(String(t),/"/g,"&quot;")}function F(t){return!L||!("object"==typeof t&&(L in t||void 0!==t[L]))}function H(t){return"[object Array]"===Q(t)&&F(t)}function U(t){return"[object RegExp]"===Q(t)&&F(t)}function G(t){if(T)return t&&"object"==typeof t&&t instanceof Symbol;if("symbol"==typeof t)return!0;if(!t||"object"!=typeof t||!E)return!1;try{return E.call(t),!0}catch(e){}return!1}var J=Object.prototype.hasOwnProperty||function(t){return t in this};function K(t,e){return J.call(t,e)}function Q(t){return b.call(t)}function V(t,e){if(t.indexOf)return t.indexOf(e);for(var r=0,n=t.length;r<n;r++)if(t[r]===e)return r;return-1}function X(t,e){if(t.length>e.maxStringLength){var r=t.length-e.maxStringLength,n="... "+r+" more character"+(r>1?"s":"");return X(m.call(t,0,e.maxStringLength),e)+n}var o=D[e.quoteStyle||"single"];return o.lastIndex=0,C(d.call(d.call(t,o,"\\$1"),/[\x00-\x1f]/g,Y),"single",e)}function Y(t){var e=t.charCodeAt(0),r={8:"b",9:"t",10:"n",12:"f",13:"r"}[e];return r?"\\"+r:"\\x"+(e<16?"0":"")+v.call(e.toString(16))}function Z(t){return"Object("+t+")"}function tt(t){return t+" { ? }"}function et(t,e,r,n){return t+" ("+e+") {"+(n?rt(r,n):w.call(r,", "))+"}"}function rt(t,e){if(0===t.length)return"";var r="\n"+e.prev+e.base;return r+w.call(t,","+r)+"\n"+e.prev}function nt(t,e){var r=H(t),n=[];if(r){n.length=t.length;for(var o=0;o<t.length;o++)n[o]=K(t,o)?e(t[o],t):""}var i,u="function"==typeof k?k(t):[];if(T){i={};for(var l=0;l<u.length;l++)i["$"+u[l]]=u[l]}for(var c in t)K(t,c)&&(r&&String(Number(c))===c&&c<t.length||T&&i["$"+c]instanceof Symbol||(O.call(/[^\w$]/,c)?n.push(e(c,t)+": "+e(t[c],t)):n.push(c+": "+e(t[c],t))));if("function"==typeof k)for(var a=0;a<u.length;a++)$.call(t,u[a])&&n.push("["+e(u[a])+"]: "+e(t[u[a]],t));return n}export{B as o,r};
