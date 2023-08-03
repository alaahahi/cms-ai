import{r as u,u as C,o as d,c as i,a as o,b as p,w as b,F as x,H as A,J as S,d as s,t as r,e as F,f as M,v as D,g as w,O as R}from"./app.384e361f.js";import{_ as U}from"./AuthenticatedLayout.d78f3a8a.js";import{M as B}from"./Modal.079c15ba.js";import{t as j}from"./laravel-vue-pagination.es.d47c086d.js";import{_ as c}from"./InputLabel.c6477c64.js";import{_ as m}from"./TextInput.4b4e1a0b.js";/* empty css                                              */import"./_plugin-vue_export-helper.cdc0426e.js";const H=s("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," \u0627\u0644\u0647\u062F\u0641 \u0627\u0644\u0645\u0628\u0627\u0634\u0631 ",-1),I=s("h3",{class:"text-center"},"\u0625\u062F\u0627\u0631\u0629 \u0627\u0644\u0627\u0633\u062A\u0645\u0627\u0631\u0627\u062A",-1),L={key:0},P={id:"alert-2",class:"p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200 text-center",role:"alert"},E={class:"ml-3 text-sm font-medium text-red-700 dark:text-red-800"},J={class:"py-12"},O={class:"max-w-9xl mx-auto sm:px-6 lg:px-8"},T={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},q={class:"p-6 bg-white border-gray-200"},z={class:"flex flex-row"},G={class:"basis-1/2 px-4"},K=s("option",{value:"0",disabled:""},"\u0627\u062E\u062A\u0627\u0631 \u0627\u0644\u0645\u0646\u062F\u0648\u0628",-1),Q=["value"],W={class:"flex flex-row"},X={class:"grow"},Y={class:"pb-3"},Z={class:"mx-auto mx-7"},ss={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},es={class:"p-6 bg-white border-gray-200"},ts={class:"flex flex-row"},os={class:"basis-1/3"},as={className:"mb-4 mx-5"},ls={class:"basis-1/3"},ds={className:"mb-4 mx-5"},is={class:"basis-1/3"},ns={className:"mb-4 mx-5 print:hidden"},rs={class:"flex flex-row"},cs={class:"grow"},us={class:"pb-3"},ms={class:"mx-auto mx-7"},_s={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},vs={class:"p-6 bg-white border-gray-200"},ps={class:"flex flex-row"},hs={class:"basis-1/3"},bs={className:"mb-4 mx-5"},xs={class:"basis-1/3"},fs={className:"mb-4 mx-5"},gs={class:"basis-1/3"},ys={className:"mb-4 mx-5  print:hidden"},ws=["disabled"],ks={key:0},Ns={key:1},Vs={class:"overflow-x-auto shadow-md"},$s={class:"w-full my-5"},Cs=s("thead",{class:"700 bg-rose-500 text-white text-center rounded-l-lg"},[s("tr",{class:"bg-rose-500 rounded-l-lg mb-2 sm:mb-0"},[s("th",{className:"px-4 py-2"},"\u062A\u0645 \u0627\u0644\u062F\u0641\u0639"),s("th",{className:"px-4 py-2"},"\u0627\u0644\u0646\u0648\u0639"),s("th",{className:"px-4 py-2"},"\u0627\u0644\u0648\u0635\u0641"),s("th",{className:"px-4 py-2"},"\u0627\u0644\u0645\u0628\u0644\u063A")])],-1),As={className:"border px-4 py-2"},Ss={className:"border px-4 py-2 td"},Fs={className:"border px-4 py-2 td"},Ms={className:"border px-4 py-2 td"},Ds={class:"mt-3 text-center",style:{direction:"ltr"}},Rs=s("div",{class:"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"},[s("div",{class:"flex flex-row"},[s("div",{class:"basis-1/2 flex flex-col justify-center"}," \u062A\u0648\u0642\u064A\u0639 \u0642\u0633\u0645 \u0627\u0644\u0645\u062D\u0627\u0633\u0628\u0629 "),s("div",{class:"basis-1/2"}," \u062A\u0648\u0642\u064A\u0639 \u0627\u0644\u0645\u062F\u064A\u0631 ")])],-1),Js={__name:"FormRegistrationCourt",props:{url:String,users:Array},setup(k){const a=u({}),h=u(0),f=u(0);u(0);const _=async(l=1)=>{const t=await fetch(`/getIndexAccountsSelas?page=${l}&user_id=${h.value}`);a.value=await t.json()},N=C();let n=u(!1);const V=async l=>{await fetch(`/paySelse/${l}`),_()};function $(l){N.get(route("sentToCourt",l)),_(),n.value=!1}return(l,t)=>(d(),i(x,null,[o(p(A),{title:"Dashboard"}),o(U,null,{header:b(()=>[H]),default:b(()=>{var g,y;return[o(B,{show:!!p(n),data:p(n).toString(),onA:t[0]||(t[0]=e=>$(e,l.arg1)),onClose:t[1]||(t[1]=e=>S(n)?n.value=!1:n=!1)},{header:b(()=>[I]),_:1},8,["show","data"]),l.$page.props.success?(d(),i("div",L,[s("div",P,[s("div",E,r(l.$page.props.success),1)])])):F("",!0),s("div",J,[s("div",O,[s("div",T,[s("div",q,[s("div",z,[s("div",G,[M(s("select",{onChange:t[2]||(t[2]=e=>_()),"onUpdate:modelValue":t[3]||(t[3]=e=>h.value=e),id:"default",class:"pr-8 bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500"},[K,(d(!0),i(x,null,w(k.users,(e,v)=>(d(),i("option",{key:v,value:e.id},r(e.name),9,Q))),128))],544),[[D,h.value]])])]),s("div",W,[s("div",X,[s("div",Y,[s("div",Z,[s("div",ss,[s("div",es,[s("div",ts,[s("div",os,[s("div",as,[o(c,{for:"invoice_number",value:"\u0627\u0644\u0645\u0646\u062F\u0648\u0628"}),o(m,{id:"invoice_number",type:"text",class:"mt-1 block w-full",value:(g=a.value.sales)==null?void 0:g.name,disabled:""},null,8,["value"])])]),s("div",ls,[s("div",ds,[o(c,{for:"percentage",value:"\u0646\u0633\u0628\u0629 \u0627\u0644\u0628\u064A\u0639"}),o(m,{id:"percentage",type:"text",class:"mt-1 block w-full",value:(y=a.value.sales)==null?void 0:y.percentage,disabled:""},null,8,["value"])])]),s("div",is,[s("div",ns,[o(c,{for:"percentage",value:"\u0639\u062F\u062F \u0627\u0644\u0628\u0637\u0627\u0642\u0629 \u062A\u0645 \u0627\u0633\u062A\u0644\u0627\u0645\u0647\u0627"}),o(m,{id:"percentage",type:"text",class:"mt-1 block w-full",modelValue:a.value.count,"onUpdate:modelValue":t[4]||(t[4]=e=>a.value.count=e),disabled:""},null,8,["modelValue"])])])])])])])])])]),s("div",rs,[s("div",cs,[s("div",us,[s("div",ms,[s("div",_s,[s("div",vs,[s("div",ps,[s("div",hs,[s("div",bs,[o(c,{for:"date",value:"\u0628\u062A\u0627\u0631\u064A\u062E"}),o(m,{id:"date",type:"text",class:"mt-1 block w-full",modelValue:a.value.date,"onUpdate:modelValue":t[5]||(t[5]=e=>a.value.date=e),disabled:""},null,8,["modelValue"])])]),s("div",xs,[s("div",fs,[o(c,{for:"totalAmount",value:"\u0627\u0644\u0645\u062C\u0645\u0648\u0639 \u0628\u0627\u0644\u062F\u064A\u0646\u0627\u0631 \u0627\u0644\u0639\u0631\u0627\u0642\u064A"}),o(m,{id:"totalAmount",type:"text",class:"mt-1 block w-full",modelValue:a.value.totalAmount,"onUpdate:modelValue":t[6]||(t[6]=e=>a.value.totalAmount=e),disabled:""},null,8,["modelValue"])])]),s("div",gs,[s("div",ys,[o(c,{for:"pay",value:"\u062A\u0623\u0643\u064A\u062F \u0627\u0644\u062F\u0641\u0639"}),s("button",{onClick:t[7]||(t[7]=R(e=>{var v;return V((v=a.value.sales)==null?void 0:v.id)},["prevent"])),disabled:f.value||!parseInt(a.value.totalAmount),class:"px-6 mb-12 mx-2 py-2 mt-1 font-bold text-white bg-green-500 rounded",style:{width:"100%"}},[f.value?(d(),i("span",Ns,"\u062C\u0627\u0631\u064A \u0627\u0644\u062D\u0641\u0638...")):(d(),i("span",ks,"\u062F\u0641\u0639"))],8,ws)])])])])])])])])]),s("div",Vs,[s("table",$s,[Cs,s("tbody",null,[(d(!0),i(x,null,w(a.value.data,e=>(d(),i("tr",{key:e.id,class:"hover:bg-gray-100 text-center"},[s("td",As,r(e.is_pay?"\u0646\u0639\u0645":"\u0644\u0627"),1),s("td",Ss,r(e.type),1),s("td",Fs,r(e.description),1),s("td",Ms,r(e.amount),1)]))),128))])])]),s("div",Ds,[o(p(j),{data:a.value,onPaginationChangePage:_,limit:2},null,8,["data"])])])])])]),Rs]}),_:1})],64))}};export{Js as default};
