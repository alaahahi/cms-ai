import{z as C,r as v,a5 as $,C as H,j as N,k as M,o as h,c as x,f as O,n as R,d as l,m as u,a as T,t as g,e as y,Q as E,p as V,i as j}from"./app.f5da40f8.js";function q(t,o=null){if(!t)return!1;const e=t.getBoundingClientRect(),n=o?o.getBoundingClientRect():{top:0,left:0,bottom:window.innerHeight,right:window.innerWidth};return e.bottom>=n.top&&e.top<=n.bottom&&e.right>=n.left&&e.left<=n.right}async function z(t){return t?(await E(),t.value instanceof HTMLElement?t.value:t.value?document.querySelector(t.value):null):null}function D(t){let o=`0px 0px ${t.distance}px 0px`;t.top&&(o=`${t.distance}px 0px 0px 0px`);const e=new IntersectionObserver(n=>{n[0].isIntersecting&&(t.firstload&&t.emit(),t.firstload=!0)},{root:t.parentEl,rootMargin:o});return t.infiniteLoading.value&&e.observe(t.infiniteLoading.value),e}async function w(t,o){if(await E(),!t.top)return;const e=t.parentEl||document.documentElement;e.scrollTop=e.scrollHeight-o}const k=(t,o)=>{const e=t.__vccOpts||t;for(const[n,r]of o)e[n]=r;return e},Q={},U=t=>(V("data-v-d3e37633"),t=t(),j(),t),W={class:"container"},A=U(()=>l("div",{class:"spinner"},null,-1)),F=[A];function G(t,o){return h(),x("div",W,F)}const J=k(Q,[["render",G],["__scopeId","data-v-d3e37633"]]),K={class:"state-error"},P=C({__name:"InfiniteLoading",props:{top:{type:Boolean,default:!1},target:{},distance:{default:0},identifier:{},firstload:{type:Boolean,default:!0},slots:{}},emits:["infinite"],setup(t,{emit:o}){const e=t;let n=null,r=0;const d=v(null),a=v(""),{top:b,firstload:I,distance:_}=e,{identifier:B,target:L}=$(e),i={infiniteLoading:d,top:b,firstload:I,distance:_,parentEl:null,emit(){r=(i.parentEl||document.documentElement).scrollHeight,p.loading(),o("infinite",p)}},p={loading(){a.value="loading"},async loaded(){a.value="loaded",await w(i,r),q(d.value,i.parentEl)&&i.emit()},async complete(){a.value="complete",await w(i,r),n==null||n.disconnect()},error(){a.value="error"}};function f(){n==null||n.disconnect(),n=D(i)}return H(B,f),N(async()=>{i.parentEl=await z(L),f()}),M(()=>n==null?void 0:n.disconnect()),(s,m)=>(h(),x("div",{ref_key:"infiniteLoading",ref:d,class:"v3-infinite-loading"},[O(l("div",null,[u(s.$slots,"spinner",{},()=>[T(J)],!0)],512),[[R,a.value=="loading"]]),a.value=="complete"?u(s.$slots,"complete",{key:0},()=>{var c;return[l("span",null,g(((c=s.slots)==null?void 0:c.complete)||"No more results!"),1)]},!0):y("",!0),a.value=="error"?u(s.$slots,"error",{key:1,retry:i.emit},()=>{var c;return[l("span",K,[l("span",null,g(((c=s.slots)==null?void 0:c.error)||"Oops something went wrong!"),1),l("button",{class:"retry",onClick:m[0]||(m[0]=(...S)=>i.emit&&i.emit(...S))},"retry")])]},!0):y("",!0)],512))}}),X=k(P,[["__scopeId","data-v-4bdee133"]]);export{X as Y};
