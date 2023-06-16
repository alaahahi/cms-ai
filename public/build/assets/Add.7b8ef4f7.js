import{r as g,u as T,o as n,c as i,a as _,b as a,w as $,F as x,H as E,d as e,t as u,e as D,f as H,v as q,g as S,x as b,p as L,i as M}from"./app.81d45d65.js";import{_ as j}from"./AuthenticatedLayout.2ec3fd09.js";import{_ as y}from"./TextInput.9cc4aded.js";import{a as z}from"./index.425caac2.js";import{_ as G}from"./_plugin-vue_export-helper.cdc0426e.js";const m=p=>(L("data-v-41361f26"),p=p(),M(),p),J=m(()=>e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," \u062D\u062C\u0632 \u0648\u062A\u062B\u0628\u064A\u062A \u0645\u0648\u0639\u062F \u0644\u0644\u0645\u0633\u062A\u062E\u062F\u0645\u064A\u0646 ",-1)),K={key:0},O={id:"alert-2",class:"p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200 text-center",role:"alert"},P={class:"ml-3 text-sm font-medium text-red-700 dark:text-red-800"},Q={class:"max-w-8xl mx-auto sm:px-3 lg:px-4 mt-4"},R={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},W={class:"p-6 bg-white border-b border-gray-200"},X={class:"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2 lg:gap-4"},Y={class:"px-4"},Z=m(()=>e("option",{value:"",disabled:""},"\u064A\u0631\u062C\u0649 \u0627\u062E\u062A\u064A\u0627\u0631 \u0637\u0628\u064A\u0628",-1)),ee=["value"],te={class:"px-4"},se={class:"items-center max-w-5xl"},oe={key:0},de={className:"text-red-600"},ae={className:"text-red-600"},le={className:"text-red-600"},re={class:"px-5"},ne=m(()=>e("h5",{class:"py-3"},"\u0627\u0644\u064A\u0648\u0645",-1)),ie={class:"px-5"},ce=m(()=>e("h5",{class:"py-3"},"\u0627\u0644\u0645\u0648\u0639\u062F",-1)),ue={class:"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8"},pe=["disabled","onClick"],_e={class:"px-5"},me=m(()=>e("h5",{class:"py-3"},"\u0645\u0644\u0627\u062D\u0638\u0629",-1)),ge={class:"px-5 py-7 pt-12"},he=["disabled"],ve={__name:"Add",props:{url:String,userDoctor:Array},setup(p){g("");const h=g([]),v=g([]),t=T({user_id:"",card_id:"",date:"",start:"",end:"",note:""}),C=()=>{t.post(route("hospital"))},N=(()=>{const d=[];for(let s=9;s<=16;s+=1)d.push(`${s}:00-${s+1}:00`);return d})(),A=d=>{if(!t.date||new Date(t.date).getDay()===5)return!1;const s=new Date(`${t.date} ${d.split("-")[0]}:00`),l=new Date(`${t.date} ${d.split("-")[1]}:00`);return!h.value.some(r=>{const f=new Date(r.start),V=new Date(r.end);return s>=f&&s<V||l>f&&l<=V})&&!v.value.includes(d)},w=()=>{v.value=[],t.date},I=(d,s)=>{h.value=[];const l=t.date+" "+d.split("-")[0]+":00",o=t.date+" "+d.split("-")[1]+":00";t.start=l,t.end=o,h.value.push({start:l,end:o}),v.value.push(d),d.split("-")[0].split(":")[0],w()};let k=null;const B=1e3,c=g(0),U=d=>{clearTimeout(k),k=setTimeout(()=>{F(d)},B)},F=d=>{z.get("/api/checkCard?card_id="+d).then(s=>{c.value=s.data}).catch(s=>{c.value=0})};return(d,s)=>(n(),i(x,null,[_(a(E),{title:"Dashboard"}),_(j,null,{header:$(()=>[J]),default:$(()=>{var l;return[d.$page.props.success?(n(),i("div",K,[e("div",O,[e("div",P,u(d.$page.props.success),1)])])):D("",!0),e("div",Q,[e("div",R,[e("div",W,[e("div",X,[e("div",Y,[H(e("select",{"onUpdate:modelValue":s[0]||(s[0]=o=>a(t).user_id=o),id:"default",class:"pr-8 bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"},[Z,(n(!0),i(x,null,S(p.userDoctor,(o,r)=>(n(),i("option",{key:r,value:o.id},u(o.name),9,ee))),128))],512),[[q,a(t).user_id]])]),e("div",te,[e("form",se,[e("div",null,[_(y,{onInput:s[1]||(s[1]=o=>U(a(t).card_id)),modelValue:a(t).card_id,"onUpdate:modelValue":s[2]||(s[2]=o=>a(t).card_id=o),type:"number",id:"simple-search",class:"w-full",placeholder:"\u0631\u0642\u0645 \u0628\u0637\u0627\u0642\u0629 \u0627\u0644\u0645\u0631\u064A\u0636",required:""},null,8,["modelValue"]),c.value?(n(),i("span",oe,[b(" \u0627\u0644\u0628\u0637\u0627\u0642\u0629 \u062A\u0645 \u062A\u0633\u062C\u064A\u0644\u0647\u0627 \u0642\u0628\u0644 \u0628\u0623\u0633\u0645 "),e("span",de,u(c.value.name),1),b(" \u0644\u0644\u0645\u0646\u062F\u0648\u0628 "),e("span",ae,u((l=c.value.user)==null?void 0:l.name),1),b(" \u0623\u0641\u0631\u0627\u062F \u0627\u0644\u0639\u0627\u0626\u0644\u0629 "),e("span",le,u(c.value.family_name),1)])):D("",!0)])])]),e("div",re,[ne,_(y,{type:"date",class:"form-control w-full",modelValue:a(t).date,"onUpdate:modelValue":s[3]||(s[3]=o=>a(t).date=o),onChange:w},null,8,["modelValue"])]),e("div",ie,[ce,e("div",ue,[(n(!0),i(x,null,S(a(N),(o,r)=>(n(),i("div",{key:r},[e("button",{class:"px-6 py-2 text-white bg-rose-500 rounded-md focus:outline-none w-full",disabled:!A(o),onClick:f=>I(o,"vip")},u(o),9,pe)]))),128))])]),e("div",_e,[me,_(y,{type:"text",class:"form-control w-full",modelValue:a(t).note,"onUpdate:modelValue":s[4]||(s[4]=o=>a(t).note=o)},null,8,["modelValue"])]),e("div",ge,[e("button",{type:"date",class:"px-6 py-2 text-white bg-blue-500 rounded-md focus:outline-none w-full",onClick:C,disabled:!a(t).start||!a(t).end||!a(t).user_id||!a(t).card_id},"\u062D\u0641\u0638",8,he)])])])])])]}),_:1})],64))}},ke=G(ve,[["__scopeId","data-v-41361f26"]]);export{ke as default};
