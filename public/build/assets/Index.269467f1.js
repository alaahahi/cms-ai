import{m as l,b as N,o as r,a as n,f as m,u as i,e as c,F as x,H as k,h as e,t as a,g as h,d as g,j as y,L as b,w as $,I,J as B}from"./app.7dc60931.js";import{_ as V}from"./AuthenticatedLayout.d4ec404f.js";import{t as j}from"./laravel-vue-pagination.es.a14405e4.js";const C=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," \u0625\u062F\u0627\u0631\u0629 \u0627\u0644\u0639\u0642\u0648\u062F \u0627\u0644\u0625\u0644\u0643\u062A\u0631\u0648\u0646\u064A\u0629 ",-1),D={key:0},F={id:"alert-2",class:"p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200 text-center",role:"alert"},L={class:"ml-3 text-sm font-medium text-red-700 dark:text-red-800"},M={class:"py-12"},R={class:"max-w-9xl mx-auto sm:px-6 lg:px-8"},S={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},T={class:"p-6 bg-white border-b border-gray-200"},q={class:"flex flex-row"},z={class:"basis-1/2"},E={className:"flex items-center justify-between mb-6"},H={class:"basis-1/2"},P={class:"flex items-center max-w-5xl"},A=e("label",{for:"simple-search",class:"sr-only"},"\u0628\u062D\u062B",-1),J={class:"relative w-full"},U=e("div",{class:"absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"},[e("svg",{"aria-hidden":"true",class:"w-5 h-5 text-gray-500 dark:text-gray-400",fill:"currentColor",viewBox:"0 0 20 20",xmlns:"http://www.w3.org/2000/svg"},[e("path",{"fill-rule":"evenodd",d:"M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z","clip-rule":"evenodd"})])],-1),G={class:"overflow-x-auto shadow-md"},K={class:"w-full my-5"},O=e("thead",{class:"700 bg-rose-500 text-white text-center rounded-l-lg"},[e("tr",{class:"bg-rose-500 rounded-l-lg mb-2 sm:mb-0"},[e("th",{className:"px-4 py-2 w-20"},"\u062A\u0633\u0644\u0633\u0644"),e("th",{className:"px-4 py-2"},"\u0631\u0642\u0645 \u0627\u0644\u0628\u0637\u0627\u0642\u0629"),e("th",{className:"px-4 py-2"},"\u0627\u0644\u0623\u0633\u0645 \u0643\u0627\u0645\u0644"),e("th",{className:"px-4 py-2"},"\u0631\u0642\u0645 \u0627\u0644\u0645\u0648\u0628\u0627\u064A\u0644"),e("th",{className:"px-4 py-2"},"\u0627\u0644\u0639\u0646\u0648\u0627\u0646"),e("th",{className:"px-4 py-2"},"\u0627\u0644\u0645\u0646\u062F\u0648\u0628"),e("th",{className:"px-4 py-2"},"\u062A\u0627\u0631\u064A\u062E \u0627\u0644\u062A\u0633\u062C\u064A\u0644"),e("th",{className:"px-4 py-2"},"\u0623\u0641\u0631\u0627\u062F \u0627\u0644\u0639\u0627\u0626\u0644\u0629"),e("th",{className:"px-4 py-2"},"\u0627\u0644\u062D\u0627\u0644\u0629"),e("th",{className:"px-4 py-2"},"\u062A\u0646\u0641\u064A\u0630")])],-1),Q={class:"flex-1 sm:flex-none"},W={className:"border px-4 py-2"},X={className:"border px-4 py-2 td"},Y={className:"border px-4 py-2 td"},Z={className:"border px-4 py-2 td"},ee={className:"border px-4 py-2 td"},te={className:"border px-4 py-2 td"},se={className:"border px-4 py-2"},ae={className:"border px-4 py-2 td"},oe={className:"border px-4 py-2"},re={className:"border px-2 py-2"},de=["href"],le={class:"mt-3 text-center",style:{direction:"ltr"}},pe={__name:"Index",props:{url:String,card:String},setup(f){const d=l({});l({});const u=async(t=1)=>{const o=await fetch(`/getIndexFormRegistration?page=${t}`);d.value=await o.json()},p=l("");u();const v=async t=>{d.value=[];const o=await fetch(`/livesearch?q=${t}`);d.value=await o.json()};N();const w=t=>{if(t==0)return"\u0625\u0646\u062A\u0638\u0627\u0631 \u062A\u0633\u0644\u064A\u0645 \u0627\u0644\u0635\u0646\u062F\u0648\u0642";if(t==1)return"\u062A\u0645 \u0627\u0644\u062A\u0633\u0644\u064A\u0645";if(t==2)return"\u0645\u0643\u062A\u0645\u0644"};return l(!1),(t,o)=>(r(),n(x,null,[m(i(k),{title:"Dashboard"}),m(V,null,{header:c(()=>[C]),default:c(()=>[t.$page.props.success?(r(),n("div",D,[e("div",F,[e("div",L,a(t.$page.props.success),1)])])):h("",!0),e("div",M,[e("div",R,[e("div",S,[e("div",T,[e("div",q,[e("div",z,[e("div",E,[t.$page.props.auth.user.type_id==1||t.$page.props.auth.user.type_id==2||t.$page.props.auth.user.type_id==3&&f.card?(r(),g(i(b),{key:0,className:"px-6 py-2 text-white bg-rose-500 rounded-md focus:outline-none",href:t.route("\u062A\u0633\u062C\u064A\u0644-\u0627\u0644\u0627\u0633\u062A\u0645\u0627\u0631\u0629")},{default:c(()=>[y(" \u0625\u0646\u0634\u0627\u0621 \u0628\u0637\u0627\u0642\u0629 \u062C\u062F\u064A\u062F\u0629 ")]),_:1},8,["href"])):h("",!0)])]),e("div",H,[e("form",P,[A,e("div",J,[U,$(e("input",{"onUpdate:modelValue":o[0]||(o[0]=s=>p.value=s),onInput:o[1]||(o[1]=s=>v(p.value)),type:"text",id:"simple-search",class:"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500",placeholder:"\u0628\u062D\u062B \u062D\u0633\u0628 \u0631\u0642\u0645 \u0627\u0644\u0648\u0635\u0644 \u0627\u0648 \u0631\u0642\u0645 \u0627\u0644\u0628\u0637\u0627\u0642\u0629 \u0627\u0648 \u0627\u0633\u0645 \u0627\u0644\u0645\u0634\u062A\u0631\u0643",required:""},null,544),[[I,p.value]])])])])]),e("div",G,[e("table",K,[O,e("tbody",Q,[(r(!0),n(x,null,B(d.value.data,s=>{var _;return r(),n("tr",{key:s.id,class:"mb-2 sm:mb-0 hover:bg-gray-100 text-center"},[e("td",W,a(s.no),1),e("td",X,a(s.card_number),1),e("td",Y,a(s.name),1),e("td",Z,a(s.phone_number),1),e("td",ee,a(s.address),1),e("td",te,a((_=s.user)==null?void 0:_.name),1),e("td",se,a(s.created_at.substring(0,10)),1),e("td",ae,a(s.family_name),1),e("td",oe,a(w(s.results)),1),e("td",re,[e("a",{tabIndex:"-1",className:"mx-1 px-2 py-1 text-sm text-white bg-gray-400 rounded",href:t.route("document",s.id),target:"_self"}," \u0637\u0628\u0627\u0639\u0629 ",8,de),t.$page.props.auth.user.type_id==1||t.$page.props.auth.user.type_id==2?(r(),g(i(b),{key:0,tabIndex:"1",className:"px-2 py-1 text-sm text-white mx-1 bg-slate-500 rounded",href:t.route("formRegistrationEdit",s.id)},{default:c(()=>[y(" \u062A\u0639\u062F\u064A\u0644 ")]),_:2},1032,["href"])):h("",!0)])])}),128))])])]),e("div",le,[m(i(j),{data:d.value,onPaginationChangePage:u,limit:10},null,8,["data"])])])])])])]),_:1})],64))}};export{pe as default};
