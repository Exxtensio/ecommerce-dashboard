const t=/"(?:_|\\u0{2}5[Ff]){2}(?:p|\\u0{2}70)(?:r|\\u0{2}72)(?:o|\\u0{2}6[Ff])(?:t|\\u0{2}74)(?:o|\\u0{2}6[Ff])(?:_|\\u0{2}5[Ff]){2}"\s*:/,r=/"(?:c|\\u0063)(?:o|\\u006[Ff])(?:n|\\u006[Ee])(?:s|\\u0073)(?:t|\\u0074)(?:r|\\u0072)(?:u|\\u0075)(?:c|\\u0063)(?:t|\\u0074)(?:o|\\u006[Ff])(?:r|\\u0072)"\s*:/,e=/^\s*["[{]|^\s*-?\d{1,16}(\.\d{1,17})?([Ee][+-]?\d+)?\s*$/;function n(t,r){if(!("__proto__"===t||"constructor"===t&&r&&"object"==typeof r&&"prototype"in r))return r;!function(t){console.warn(`[destr] Dropping "${t}" key to prevent prototype pollution.`)}(t)}function u(u,i={}){if("string"!=typeof u)return u;const o=u.trim();if('"'===u[0]&&u.endsWith('"')&&!u.includes("\\"))return o.slice(1,-1);if(o.length<=9){const t=o.toLowerCase();if("true"===t)return!0;if("false"===t)return!1;if("undefined"===t)return;if("null"===t)return null;if("nan"===t)return Number.NaN;if("infinity"===t)return Number.POSITIVE_INFINITY;if("-infinity"===t)return Number.NEGATIVE_INFINITY}if(!e.test(u)){if(i.strict)throw new SyntaxError("[destr] Invalid JSON");return u}try{if(t.test(u)||r.test(u)){if(i.strict)throw new Error("[destr] Possible prototype pollution");return JSON.parse(u,n)}return JSON.parse(u)}catch(f){if(i.strict)throw f;return u}}export{u as d};
