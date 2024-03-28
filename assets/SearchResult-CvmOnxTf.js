import{u as j,f as Z,g as ee,h as B,i as se,j as ae,t as le,k as te,l as P,m as L,n as re,p as M,q as a,s as _,v as I,R as U,x as ue,y as ie,z as ne,A as oe,B as ce,C as ve,D as pe,E as de,O as he,F as ye,G as me,P as ge,H as fe,I as He,J as $}from"./app-CK-8UmnJ.js";const Re=["/extending.html","/","/install.html","/upgrading.html","/uploads.html","/usage.html","/404.html"],ke="SEARCH_PRO_QUERY_HISTORY",h=j(ke,[]),Qe=()=>{const{queryHistoryCount:l}=$,t=l>0;return{enabled:t,queryHistory:h,addQueryHistory:r=>{t&&(h.value.length<l?h.value=Array.from(new Set([r,...h.value])):h.value=Array.from(new Set([r,...h.value.slice(0,l-1)])))},removeQueryHistory:r=>{h.value=[...h.value.slice(0,r),...h.value.slice(r+1)]}}},T=l=>Re[l.id]+("anchor"in l?`#${l.anchor}`:""),xe="SEARCH_PRO_RESULT_HISTORY",{resultHistoryCount:E}=$,y=j(xe,[]),we=()=>{const l=E>0;return{enabled:l,resultHistory:y,addResultHistory:t=>{if(l){const r={link:T(t),display:t.display};"header"in t&&(r.header=t.header),y.value.length<E?y.value=[r,...y.value]:y.value=[r,...y.value.slice(0,E-1)]}},removeResultHistory:t=>{y.value=[...y.value.slice(0,t),...y.value.slice(t+1)]}}},Ce=l=>{const t=ce(),r=B(),S=ve(),i=P(!1),g=pe([]);return de(()=>{const{search:k,terminate:m}=he(),Q=()=>{g.value=[],i.value=!1},f=He(p=>{i.value=!0,p?k(p,r.value,t.value).then(d=>{var x,H;return((H=(x=t.value).searchFilter)==null?void 0:H.call(x,d,p,r.value,S.value))??d}).then(d=>{g.value=d,i.value=!1}).catch(d=>{console.error(d),Q()}):Q()},$.searchDelay);M([l,r],()=>f(l.value),{immediate:!0}),ye(()=>{m()})}),{searching:i,results:g}};var qe=Z({name:"SearchResult",props:{query:{type:String,required:!0},isFocusing:Boolean},emits:["close","updateQuery"],setup(l,{emit:t}){const r=ee(),S=B(),i=se(ae),{enabled:g,addQueryHistory:k,queryHistory:m,removeQueryHistory:Q}=Qe(),{enabled:f,resultHistory:p,addResultHistory:d,removeResultHistory:x}=we(),H=g||f,q=le(l,"query"),{results:R,searching:Y}=Ce(q),u=te({isQuery:!0,index:0}),c=P(0),v=P(0),F=L(()=>H&&(m.value.length>0||p.value.length>0)),A=L(()=>R.value.length>0),D=L(()=>R.value[c.value]||null),z=()=>{const{isQuery:e,index:s}=u;s===0?(u.isQuery=!e,u.index=e?p.value.length-1:m.value.length-1):u.index=s-1},G=()=>{const{isQuery:e,index:s}=u;s===(e?m.value.length-1:p.value.length-1)?(u.isQuery=!e,u.index=0):u.index=s+1},J=()=>{c.value=c.value>0?c.value-1:R.value.length-1,v.value=D.value.contents.length-1},V=()=>{c.value=c.value<R.value.length-1?c.value+1:0,v.value=0},K=()=>{v.value<D.value.contents.length-1?v.value+=1:V()},N=()=>{v.value>0?v.value-=1:J()},b=e=>e.map(s=>me(s)?s:a(s[0],s[1])),W=e=>{if(e.type==="customField"){const s=ge[e.index]||"$content",[n,C=""]=fe(s)?s[S.value].split("$content"):s.split("$content");return e.display.map(o=>a("div",b([n,...o,C])))}return e.display.map(s=>a("div",b(s)))},w=()=>{c.value=0,v.value=0,t("updateQuery",""),t("close")};return re("keydown",e=>{if(l.isFocusing){if(A.value){if(e.key==="ArrowUp")N();else if(e.key==="ArrowDown")K();else if(e.key==="Enter"){const s=D.value.contents[v.value];k(l.query),d(s),r.push(T(s)),w()}}else if(f){if(e.key==="ArrowUp")z();else if(e.key==="ArrowDown")G();else if(e.key==="Enter"){const{index:s}=u;u.isQuery?(t("updateQuery",m.value[s]),e.preventDefault()):(r.push(p.value[s].link),w())}}}}),M([c,v],()=>{var e;(e=document.querySelector(".search-pro-result-list-item.active .search-pro-result-item.active"))==null||e.scrollIntoView(!1)},{flush:"post"}),()=>a("div",{class:["search-pro-result-wrapper",{empty:q.value?!A.value:!F.value}],id:"search-pro-results"},q.value===""?H?F.value?[g?a("ul",{class:"search-pro-result-list"},a("li",{class:"search-pro-result-list-item"},[a("div",{class:"search-pro-result-title"},i.value.queryHistory),m.value.map((e,s)=>a("div",{class:["search-pro-result-item",{active:u.isQuery&&u.index===s}],onClick:()=>{t("updateQuery",e)}},[a(_,{class:"search-pro-result-type"}),a("div",{class:"search-pro-result-content"},e),a("button",{class:"search-pro-remove-icon",innerHTML:I,onClick:n=>{n.preventDefault(),n.stopPropagation(),Q(s)}})]))])):null,f?a("ul",{class:"search-pro-result-list"},a("li",{class:"search-pro-result-list-item"},[a("div",{class:"search-pro-result-title"},i.value.resultHistory),p.value.map((e,s)=>a(U,{to:e.link,class:["search-pro-result-item",{active:!u.isQuery&&u.index===s}],onClick:()=>{w()}},()=>[a(_,{class:"search-pro-result-type"}),a("div",{class:"search-pro-result-content"},[e.header?a("div",{class:"content-header"},e.header):null,a("div",e.display.map(n=>b(n)).flat())]),a("button",{class:"search-pro-remove-icon",innerHTML:I,onClick:n=>{n.preventDefault(),n.stopPropagation(),x(s)}})]))])):null]:i.value.emptyHistory:i.value.emptyResult:Y.value?a(ue,{hint:i.value.searching}):A.value?a("ul",{class:"search-pro-result-list"},R.value.map(({title:e,contents:s},n)=>{const C=c.value===n;return a("li",{class:["search-pro-result-list-item",{active:C}]},[a("div",{class:"search-pro-result-title"},e||i.value.defaultTitle),s.map((o,X)=>{const O=C&&v.value===X;return a(U,{to:T(o),class:["search-pro-result-item",{active:O,"aria-selected":O}],onClick:()=>{k(l.query),d(o),w()}},()=>[o.type==="text"?null:a(o.type==="title"?ie:o.type==="heading"?ne:oe,{class:"search-pro-result-type"}),a("div",{class:"search-pro-result-content"},[o.type==="text"&&o.header?a("div",{class:"content-header"},o.header):null,a("div",W(o))])])})])})):i.value.emptyResult)}});export{qe as default};