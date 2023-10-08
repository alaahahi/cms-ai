import{r as c,u as M,o as r,c as d,a as _,b as u,w as x,F as y,H as F,J as R,d as e,t as a,e as h,f as b,v as V,g as f,h as B}from"./app.a3a901d5.js";import{_ as D}from"./AuthenticatedLayout.1731f1f9.js";import{M as I}from"./Modal.5af49775.js";import{t as S}from"./laravel-vue-pagination.es.9269c251.js";/* empty css                                              */import"./_plugin-vue_export-helper.cdc0426e.js";const A=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," \u0627\u0644\u0628\u0637\u0627\u0642\u0627\u062A \u0627\u0644\u0645\u0646\u062C\u0632\u0629 ",-1),T=e("h3",{class:"text-center"},"\u0625\u062F\u0627\u0631\u0629 \u0627\u0644\u0627\u0633\u062A\u0645\u0627\u0631\u0627\u062A",-1),j={key:0},q={id:"alert-2",class:"p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200 text-center",role:"alert"},z={class:"ml-3 text-sm font-medium text-red-700 dark:text-red-800"},H={class:"py-12"},P={class:"max-w-9xl mx-auto sm:px-6 lg:px-8"},U={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},E={class:"p-6 bg-white border-b border-gray-200"},J={class:"flex flex-row"},L={class:"basis-1/2 px-4"},G=e("option",{value:"0"},"\u0627\u0644\u062C\u0645\u064A\u0639",-1),K=["value"],O={class:"basis-1/2 px-4"},Q={class:"flex items-center max-w-5xl"},W=e("label",{for:"simple-search",class:"sr-only"},"\u0628\u062D\u062B",-1),X={class:"relative w-full"},Y=e("div",{class:"absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"},[e("svg",{"aria-hidden":"true",class:"w-5 h-5 text-gray-500 dark:text-gray-400",fill:"currentColor",viewBox:"0 0 20 20",xmlns:"http://www.w3.org/2000/svg"},[e("path",{"fill-rule":"evenodd",d:"M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z","clip-rule":"evenodd"})])],-1),Z={class:"overflow-x-auto shadow-md"},ee={class:"w-full my-5"},te={class:"700 bg-rose-500 text-white text-center rounded-l-lg"},se={class:"bg-rose-500 rounded-l-lg mb-2 sm:mb-0"},oe=e("th",{className:"px-4 py-2 w-20"},"\u062A\u0633\u0644\u0633\u0644",-1),ae=e("th",{className:"px-4 py-2"},"\u0631\u0642\u0645 \u0627\u0644\u0628\u0637\u0627\u0642\u0629",-1),re=e("th",{className:"px-4 py-2"},"\u0627\u0644\u0623\u0633\u0645 \u0643\u0627\u0645\u0644",-1),de=e("th",{className:"px-4 py-2"},"\u0631\u0642\u0645 \u0627\u0644\u0645\u0648\u0628\u0627\u064A\u0644",-1),le=e("th",{className:"px-4 py-2"},"\u0627\u0644\u0639\u0646\u0648\u0627\u0646",-1),ne=e("th",{className:"px-4 py-2"},"\u0627\u0644\u0645\u0646\u062F\u0648\u0628",-1),ie=e("th",{className:"px-4 py-2"},"\u062A\u0627\u0631\u064A\u062E \u0627\u0644\u062A\u0633\u062C\u064A\u0644",-1),ce=e("th",{className:"px-4 py-2"},"\u0623\u0641\u0631\u0627\u062F \u0627\u0644\u0639\u0627\u0626\u0644\u0629",-1),pe=e("th",{className:"px-4 py-2"},"\u0627\u0644\u062D\u0627\u0644\u0629",-1),_e={key:0,className:"px-4 py-2"},ue={className:"border px-4 py-2"},he={className:"border px-4 py-2 td"},me={className:"border px-4 py-2 td"},ge={className:"border px-4 py-2 td"},xe={className:"border px-4 py-2 td"},ye={className:"border px-4 py-2 td"},be={className:"border px-4 py-2"},fe={className:"border px-4 py-2 td"},ve={className:"border px-4 py-2"},we={key:0,className:"border px-2 py-2"},ke=["href"],Ne=["className","onClick"],$e={class:"mt-3 text-center",style:{direction:"ltr"}},Ie={__name:"FormRegistrationCompleted",props:{url:String,users:Array},setup(v){const n=c({}),m=c(0),g=c("");c(0);const i=async(s=1)=>{const o=await fetch(`/getIndexFormRegistrationCompleted?page=${s}&user_id=${m.value}`);n.value=await o.json()};i();const w=async s=>{n.value=[];const o=await fetch(`/livesearchCompleted?q=${s}`);n.value=await o.json()},k=M();let l=c(!1);const N=async s=>{await fetch(`/receiveCard?id=${s}`),i()},$=s=>{if(s==0)return"\u0625\u0646\u062A\u0638\u0627\u0631 \u062A\u0633\u0644\u064A\u0645 \u0627\u0644\u0635\u0646\u062F\u0648\u0642";if(s==1)return"\u062A\u0645 \u0627\u0644\u062A\u0633\u0644\u064A\u0645";if(s==2)return"\u0645\u0643\u062A\u0645\u0644"};function C(s){k.get(route("sentToCourt",s)),i(),l.value=!1}return(s,o)=>(r(),d(y,null,[_(u(F),{title:"Dashboard"}),_(D,null,{header:x(()=>[A]),default:x(()=>[_(I,{show:!!u(l),data:u(l).toString(),onA:o[0]||(o[0]=t=>C(t,s.arg1)),onClose:o[1]||(o[1]=t=>R(l)?l.value=!1:l=!1)},{header:x(()=>[T]),_:1},8,["show","data"]),s.$page.props.success?(r(),d("div",j,[e("div",q,[e("div",z,a(s.$page.props.success),1)])])):h("",!0),e("div",H,[e("div",P,[e("div",U,[e("div",E,[e("div",J,[e("div",L,[b(e("select",{onChange:o[2]||(o[2]=t=>i()),"onUpdate:modelValue":o[3]||(o[3]=t=>m.value=t),id:"default",class:"pr-8 bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"},[G,(r(!0),d(y,null,f(v.users,(t,p)=>(r(),d("option",{key:p,value:t.id},a(t.name),9,K))),128))],544),[[V,m.value]])]),e("div",O,[e("form",Q,[W,e("div",X,[Y,b(e("input",{"onUpdate:modelValue":o[4]||(o[4]=t=>g.value=t),onInput:o[5]||(o[5]=t=>w(g.value)),type:"text",id:"simple-search",class:"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500",placeholder:" \u0628\u062D\u062B \u062D\u0633\u0628 \u0631\u0642\u0645 \u0627\u0644\u0648\u0635\u0644 \u0627\u0648 \u0631\u0642\u0645 \u0627\u0644\u0628\u0637\u0627\u0642\u0629 \u0627\u0648 \u0627\u0633\u0645 \u0627\u0644\u0645\u0634\u062A\u0631\u0643 ",required:""},null,544),[[B,g.value]])])])])]),e("div",Z,[e("table",ee,[e("thead",te,[e("tr",se,[oe,ae,re,de,le,ne,ie,ce,pe,s.$page.props.auth.user.type_id!=2?(r(),d("th",_e,"\u062A\u0646\u0641\u064A\u0630")):h("",!0)])]),e("tbody",null,[(r(!0),d(y,null,f(n.value.data,t=>{var p;return r(),d("tr",{key:t.id,class:"hover:bg-gray-100 text-center"},[e("td",ue,a(t.no),1),e("td",he,a(t.card_number),1),e("td",me,a(t.name),1),e("td",ge,a(t.phone_number),1),e("td",xe,a(t.address),1),e("td",ye,a((p=t==null?void 0:t.user)==null?void 0:p.name),1),e("td",be,a(t.created_at.substring(0,10)),1),e("td",fe,a(t.family_name),1),e("td",ve,a($(t.results)),1),s.$page.props.auth.user.type_id!=2?(r(),d("td",we,[e("a",{tabIndex:"-1",className:"mx-1 px-2 py-1 text-sm text-white bg-gray-400 rounded",href:s.route("document",t.id),target:"_self"}," \u0637\u0628\u0627\u0639\u0629 ",8,ke),s.$page.props.auth.user.type_id==1||s.$page.props.auth.user.type_id==5?(r(),d("button",{key:0,tabIndex:"1",className:"px-2 py-1 text-sm text-white mx-1 bg-slate-500 rounded user-"+t.id,onClick:Ce=>N(t.id)}," \u0627\u0633\u062A\u0644\u0627\u0645 \u0627\u0644\u0623\u0646 ",8,Ne)):h("",!0)])):h("",!0)])}),128))])])]),e("div",$e,[_(u(S),{data:n.value,onPaginationChangePage:i,limit:2},null,8,["data"])])])])])])]),_:1})],64))}};export{Ie as default};
