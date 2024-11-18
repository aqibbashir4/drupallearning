/*!
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md.
 */(()=>{var e={331:(e,t,n)=>{e.exports=n(237)("./src/clipboard.js")},782:(e,t,n)=>{e.exports=n(237)("./src/core.js")},783:(e,t,n)=>{e.exports=n(237)("./src/engine.js")},237:e=>{"use strict";e.exports=CKEditor5.dll}},t={};function n(r){var s=t[r];if(void 0!==s)return s.exports;var i=t[r]={exports:{}};return e[r](i,i.exports,n),i.exports}n.d=(e,t)=>{for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),n.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var r={};(()=>{"use strict";n.r(r),n.d(r,{MSWordNormalizer:()=>x,PasteFromOffice:()=>M,parseHtml:()=>P});var e=n(782),t=n(331),s=n(783);function i(e){return void 0!==e&&e.endsWith("px")}function o(e){return e.toFixed(2).replace(/\.?0+$/,"")+"px"}function l(e,t,n){if(!e.childCount)return;const r=new s.UpcastWriter(e.document),l=function(e,t){const n=t.createRangeIn(e),r=[],s=new Set;for(const e of n.getItems()){if(!e.is("element")||!e.name.match(/^(p|h\d+|li|div)$/))continue;let t=g(e);if(void 0===t||0!=parseFloat(t)||Array.from(e.getClassNames()).find((e=>e.startsWith("MsoList")))||(t=void 0),e.hasStyle("mso-list")||void 0!==t&&s.has(t)){const n=f(e);r.push({element:e,id:n.id,order:n.order,indent:n.indent,marginLeft:t}),void 0!==t&&s.add(t)}else s.clear()}return r}(e,r);if(!l.length)return;const a={},u=[];for(const e of l)if(void 0!==e.indent){c(e)||(u.length=0);const s=`${e.id}:${e.indent}`,l=Math.min(e.indent-1,u.length);if(l<u.length&&u[l].id!==e.id&&(u.length=l),l<u.length-1)u.length=l+1;else{const c=m(e,t);if(l>u.length-1||u[l].listElement.name!=c.type){0==l&&"ol"==c.type&&void 0!==e.id&&a[s]&&(c.startIndex=a[s]);const t=d(c,r,n);if(i(e.marginLeft)&&(0==l||i(u[l-1].marginLeft))){let n=e.marginLeft;l>0&&(n=o(parseFloat(n)-parseFloat(u[l-1].marginLeft))),r.setStyle("padding-left",n,t)}if(0==u.length){const n=e.element.parent,s=n.getChildIndex(e.element)+1;r.insertChild(s,t,n)}else{const e=u[l-1].listItemElements;r.appendChild(t,e[e.length-1])}u[l]={...e,listElement:t,listItemElements:[]},0==l&&void 0!==e.id&&(a[s]=c.startIndex||1)}}const f="li"==e.element.name?e.element:r.createElement("li");r.appendChild(f,u[l].listElement),u[l].listItemElements.push(f),0==l&&void 0!==e.id&&a[s]++,e.element!=f&&r.appendChild(e.element,f),p(e.element,r),r.removeStyle("text-indent",e.element),r.removeStyle("margin-left",e.element)}else{const t=u.find((t=>t.marginLeft==e.marginLeft));if(t){const n=t.listItemElements;r.appendChild(e.element,n[n.length-1]),r.removeStyle("margin-left",e.element)}else u.length=0}}function c(e){const t=e.element.previousSibling;return a(t||e.element.parent)}function a(e){return e.is("element","ol")||e.is("element","ul")}function m(e,t){const n=new RegExp(`@list l${e.id}:level${e.indent}\\s*({[^}]*)`,"gi"),r=/mso-level-number-format:([^;]{0,100});/gi,s=/mso-level-start-at:\s{0,100}([0-9]{0,10})\s{0,100};/gi,i=new RegExp(`@list\\s+l${e.id}:level\\d\\s*{[^{]*mso-level-text:"%\\d\\\\.`,"gi"),o=new RegExp(`@list l${e.id}:level\\d\\s*{[^{]*mso-level-number-format:`,"gi"),l=i.exec(t),c=o.exec(t),a=l&&!c,m=n.exec(t);let d="decimal",f="ol",p=null;if(m&&m[1]){const t=r.exec(m[1]);if(t&&t[1]&&(d=t[1].trim(),f="bullet"!==d&&"image"!==d?"ol":"ul"),"bullet"===d){const t=function(e){if("li"==e.name&&"ul"==e.parent.name&&e.parent.hasAttribute("type"))return e.parent.getAttribute("type");const t=function(e){if(e.getChild(0).is("$text"))return null;for(const t of e.getChildren()){if(!t.is("element","span"))continue;const e=t.getChild(0);if(e)return e.is("$text")?e:e.getChild(0)}return null}(e);if(!t)return null;const n=t._data;if("o"===n)return"circle";if("·"===n)return"disc";if("§"===n)return"square";return null}(e.element);t&&(d=t)}else{const e=s.exec(m[1]);e&&e[1]&&(p=parseInt(e[1]))}a&&(f="ol")}return{type:f,startIndex:p,style:u(d),isLegalStyleList:a}}function u(e){if(e.startsWith("arabic-leading-zero"))return"decimal-leading-zero";switch(e){case"alpha-upper":return"upper-alpha";case"alpha-lower":return"lower-alpha";case"roman-upper":return"upper-roman";case"roman-lower":return"lower-roman";case"circle":case"disc":case"square":return e;default:return null}}function d(e,t,n){const r=t.createElement(e.type);return e.style&&t.setStyle("list-style-type",e.style,r),e.startIndex&&e.startIndex>1&&t.setAttribute("start",e.startIndex,r),e.isLegalStyleList&&n&&t.addClass("legal-list",r),r}function f(e){const t=e.getStyle("mso-list");if(void 0===t)return{};const n=t.match(/(^|\s{1,100})l(\d+)/i),r=t.match(/\s{0,100}lfo(\d+)/i),s=t.match(/\s{0,100}level(\d+)/i);return n&&r&&s?{id:n[2],order:r[1],indent:parseInt(s[1])}:{indent:1}}function p(e,t){const n=new s.Matcher({name:"span",styles:{"mso-list":"Ignore"}}),r=t.createRangeIn(e);for(const e of r)"elementStart"===e.type&&n.match(e.item)&&t.remove(e.item)}function g(e){const t=e.getStyle("margin-left");return void 0===t||t.endsWith("px")?t:function(e){const t=parseFloat(e);return e.endsWith("pt")?o(96*t/72):e.endsWith("pc")?o(12*t*96/72):e.endsWith("in")?o(96*t):e.endsWith("cm")?o(96*t/2.54):e.endsWith("mm")?o(t/10*96/2.54):e}(t)}function h(e,t){if(!e.childCount)return;const n=new s.UpcastWriter(e.document),r=function(e,t){const n=t.createRangeIn(e),r=new s.Matcher({name:/v:(.+)/}),i=[];for(const e of n){if("elementStart"!=e.type)continue;const t=e.item,n=t.previousSibling,s=n&&n.is("element")?n.name:null,o=["Chart"],l=r.match(t),c=t.getAttribute("o:gfxdata"),a="v:shapetype"===s,m=c&&o.some((e=>t.getAttribute("id").includes(e)));l&&c&&!a&&!m&&i.push(e.item.getAttribute("id"))}return i}(e,n);!function(e,t,n){const r=n.createRangeIn(t),i=new s.Matcher({name:"img"}),o=[];for(const t of r)if(t.item.is("element")&&i.match(t.item)){const n=t.item,r=n.getAttribute("v:shapes")?n.getAttribute("v:shapes").split(" "):[];r.length&&r.every((t=>e.indexOf(t)>-1))?o.push(n):n.getAttribute("src")||o.push(n)}for(const e of o)n.remove(e)}(r,e,n),function(e,t,n){const r=n.createRangeIn(t),s=[];for(const t of r)if("elementStart"==t.type&&t.item.is("element","v:shape")){const n=t.item.getAttribute("id");if(e.includes(n))continue;i(t.item.parent.getChildren(),n)||s.push(t.item)}for(const e of s){const t={src:o(e)};e.hasAttribute("alt")&&(t.alt=e.getAttribute("alt"));const r=n.createElement("img",t);n.insertChild(e.index+1,r,e.parent)}function i(e,t){for(const n of e)if(n.is("element")){if("img"==n.name&&n.getAttribute("v:shapes")==t)return!0;if(i(n.getChildren(),t))return!0}return!1}function o(e){for(const t of e.getChildren())if(t.is("element")&&t.getAttribute("src"))return t.getAttribute("src")}}(r,e,n),function(e,t){const n=t.createRangeIn(e),r=new s.Matcher({name:/v:(.+)/}),i=[];for(const e of n)"elementStart"==e.type&&r.match(e.item)&&i.push(e.item);for(const e of i)t.remove(e)}(e,n);const i=function(e,t){const n=t.createRangeIn(e),r=new s.Matcher({name:"img"}),i=[];for(const e of n)e.item.is("element")&&r.match(e.item)&&e.item.getAttribute("src").startsWith("file://")&&i.push(e.item);return i}(e,n);i.length&&function(e,t,n){if(e.length===t.length)for(let r=0;r<e.length;r++){const s=`data:${t[r].type};base64,${y(t[r].hex)}`;n.setAttribute("src",s,e[r])}}(i,function(e){if(!e)return[];const t=/{\\pict[\s\S]+?\\bliptag-?\d+(\\blipupi-?\d+)?({\\\*\\blipuid\s?[\da-fA-F]+)?[\s}]*?/,n=new RegExp("(?:("+t.source+"))([\\da-fA-F\\s]+)\\}","g"),r=e.match(n),s=[];if(r)for(const e of r){let n=!1;e.includes("\\pngblip")?n="image/png":e.includes("\\jpegblip")&&(n="image/jpeg"),n&&s.push({hex:e.replace(t,"").replace(/[^\da-fA-F]/g,""),type:n})}return s}(t),n)}function y(e){return btoa(e.match(/\w{2}/g).map((e=>String.fromCharCode(parseInt(e,16)))).join(""))}const b=/<meta\s*name="?generator"?\s*content="?microsoft\s*word\s*\d+"?\/?>/i,v=/xmlns:o="urn:schemas-microsoft-com/i;class x{constructor(e,t=!1){this.document=e,this.hasMultiLevelListPlugin=t}isActive(e){return b.test(e)||v.test(e)}execute(e){const{body:t,stylesString:n}=e._parsedData;l(t,n,this.hasMultiLevelListPlugin),h(t,e.dataTransfer.getData("text/rtf")),function(e){const t=[],n=new s.UpcastWriter(e.document);for(const{item:r}of n.createRangeIn(e))if(r.is("element")){for(const e of r.getClassNames())/\bmso/gi.exec(e)&&n.removeClass(e,r);for(const e of r.getStyleNames())/\bmso/gi.exec(e)&&n.removeStyle(e,r);(r.is("element","w:sdt")||r.is("element","w:sdtpr")&&r.isEmpty||r.is("element","o:p")&&r.isEmpty)&&t.push(r)}for(const e of t){const t=e.parent,r=t.getChildIndex(e);n.insertChild(r,e.getChildren(),t),n.remove(e)}}(t),e.content=t}}function w(e,t,n,{blockElements:r,inlineObjectElements:s}){let i=n.createPositionAt(e,"forward"==t?"after":"before");return i=i.getLastMatchingPosition((({item:e})=>e.is("element")&&!r.includes(e.name)&&!s.includes(e.name)),{direction:t}),"forward"==t?i.nodeAfter:i.nodeBefore}function C(e,t){return!!e&&e.is("element")&&t.includes(e.name)}const S=/id=("|')docs-internal-guid-[-0-9a-f]+("|')/i;class A{constructor(e){this.document=e}isActive(e){return S.test(e)}execute(e){const t=new s.UpcastWriter(this.document),{body:n}=e._parsedData;!function(e,t){for(const n of e.getChildren())if(n.is("element","b")&&"normal"===n.getStyle("font-weight")){const r=e.getChildIndex(n);t.remove(n),t.insertChild(r,n.getChildren(),e)}}(n,t),function(e,t){for(const n of t.createRangeIn(e)){const e=n.item;if(e.is("element","li")){const n=e.getChild(0);n&&n.is("element","p")&&t.unwrapElement(n)}}}(n,t),function(e,t){const n=new s.ViewDocument(t.document.stylesProcessor),r=new s.DomConverter(n,{renderingMode:"data"}),i=r.blockElements,o=r.inlineObjectElements,l=[];for(const n of t.createRangeIn(e)){const e=n.item;if(e.is("element","br")){const n=w(e,"forward",t,{blockElements:i,inlineObjectElements:o}),r=w(e,"backward",t,{blockElements:i,inlineObjectElements:o}),s=C(n,i);(C(r,i)||s)&&l.push(e)}}for(const e of l)e.hasClass("Apple-interchange-newline")?t.remove(e):t.replace(e,t.createElement("p"))}(n,t),e.content=n}}const E=/<google-sheets-html-origin/i;class I{constructor(e){this.document=e}isActive(e){return E.test(e)}execute(e){const t=new s.UpcastWriter(this.document),{body:n}=e._parsedData;!function(e,t){for(const n of e.getChildren())if(n.is("element","google-sheets-html-origin")){const r=e.getChildIndex(n);t.remove(n),t.insertChild(r,n.getChildren(),e)}}(n,t),function(e,t){for(const n of e.getChildren())n.is("element","table")&&n.hasAttribute("xmlns")&&t.removeAttribute("xmlns",n)}(n,t),function(e,t){for(const n of e.getChildren())n.is("element","table")&&"0px"===n.getStyle("width")&&t.removeStyle("width",n)}(n,t),function(e,t){for(const n of Array.from(e.getChildren()))n.is("element","style")&&t.remove(n)}(n,t),e.content=n}}function L(e){return e.replace(/<span(?: class="Apple-converted-space"|)>(\s+)<\/span>/g,((e,t)=>1===t.length?" ":Array(t.length+1).join("  ").substr(0,t.length)))}function P(e,t){const n=new DOMParser,r=function(e){return L(L(e)).replace(/(<span\s+style=['"]mso-spacerun:yes['"]>[^\S\r\n]*?)[\r\n]+([^\S\r\n]*<\/span>)/g,"$1$2").replace(/<span\s+style=['"]mso-spacerun:yes['"]><\/span>/g,"").replace(/(<span\s+style=['"]letter-spacing:[^'"]+?['"]>)[\r\n]+(<\/span>)/g,"$1 $2").replace(/ <\//g," </").replace(/ <o:p><\/o:p>/g," <o:p></o:p>").replace(/<o:p>(&nbsp;|\u00A0)<\/o:p>/g,"").replace(/>([^\S\r\n]*[\r\n]\s*)</g,"><")}(function(e){const t="</body>",n="</html>",r=e.indexOf(t);if(r<0)return e;const s=e.indexOf(n,r+t.length);return e.substring(0,r+t.length)+(s>=0?e.substring(s):"")}(e=(e=e.replace(/<!--\[if gte vml 1]>/g,"")).replace(/<o:SmartTagType(?:\s+[^\s>=]+(?:="[^"]*")?)*\s*\/?>/gi,""))),i=n.parseFromString(r,"text/html");!function(e){e.querySelectorAll("span[style*=spacerun]").forEach((e=>{const t=e,n=t.innerText.length||0;t.innerText=Array(n+1).join("  ").substr(0,n)}))}(i);const o=i.body.innerHTML,l=function(e,t){const n=new s.ViewDocument(t),r=new s.DomConverter(n,{renderingMode:"data"}),i=e.createDocumentFragment(),o=e.body.childNodes;for(;o.length>0;)i.appendChild(o[0]);return r.domToView(i,{skipComments:!0})}(i,t),c=function(e){const t=[],n=[],r=Array.from(e.getElementsByTagName("style"));for(const e of r)e.sheet&&e.sheet.cssRules&&e.sheet.cssRules.length&&(t.push(e.sheet),n.push(e.innerHTML));return{styles:t,stylesString:n.join(" ")}}(i);return{body:l,bodyString:o,styles:c.styles,stylesString:c.stylesString}}class M extends e.Plugin{static get pluginName(){return"PasteFromOffice"}static get isOfficialPlugin(){return!0}static get requires(){return[t.ClipboardPipeline]}init(){const e=this.editor,t=e.plugins.get("ClipboardPipeline"),n=e.editing.view.document,r=[],s=this.editor.plugins.has("MultiLevelList");r.push(new x(n,s)),r.push(new A(n)),r.push(new I(n)),t.on("inputTransformation",((t,s)=>{if(s._isTransformedWithPasteFromOffice)return;if(e.model.document.selection.getFirstPosition().parent.is("element","codeBlock"))return;const i=s.dataTransfer.getData("text/html"),o=r.find((e=>e.isActive(i)));o&&(s._parsedData||(s._parsedData=P(i,n.stylesProcessor)),o.execute(s),s._isTransformedWithPasteFromOffice=!0)}),{priority:"high"})}}})(),(window.CKEditor5=window.CKEditor5||{}).pasteFromOffice=r})();