import{r as n,o as d,c as r,a as l,b as S,w as x,F as U,H as K,d as e,t as p,e as i,x as g,f as M,v as H,g as j,L as E,O as D,N as O}from"./app.a3a901d5.js";import{_ as Y}from"./AuthenticatedLayout.1731f1f9.js";import{_ as u}from"./InputLabel.2289e231.js";import{_ as f}from"./TextInput.b6ac4325.js";/* empty css                                                 */import{a as $}from"./index.ef04f04c.js";const q=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," \u0627\u0644\u0639\u0642\u062F \u0627\u0644\u0625\u0644\u0643\u062A\u0631\u0648\u0646\u064A ",-1),z={key:0},G={id:"alert-2",class:"p-4 mb-4 bg-green-300 rounded-lg dark:bg-green-300 text-center",role:"alert"},J={class:"ml-3 text-sm font-medium text-green-700 dark:text-green-800"},P={key:1},Q=e("div",{id:"alert-2",class:"p-4 mb-4 bg-red-300 rounded-lg dark:bg-red-300 text-center",role:"alert"},[e("div",{class:"ml-3 text-sm font-medium text-red-700 dark:text-red-800"}," \u064A\u0631\u062C\u0649 \u0627\u062F\u062E\u0627\u0644 \u0627\u0644\u0645\u0639\u0644\u0648\u0645\u0627\u062A \u0627\u0644\u0645\u0637\u0644\u0648\u0628\u0629 ")],-1),W=[Q],X=["onSubmit"],Z={class:"flex flex-row"},ee={class:"grow"},te={class:"py-6"},ae={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},se={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},oe={class:"p-6 bg-white border-b border-gray-200"},le=e("h2",{class:"text-center text-xl py-2"},"\u0645\u0639\u0644\u0648\u0645\u0627\u062A \u0627\u0644\u0628\u0637\u0627\u0642\u0629",-1),de={className:"flex flex-col"},re={className:"mb-4"},ne=["src"],ue={className:"mb-4"},ie={key:0},me={className:"text-red-600"},ce={className:"text-red-600"},_e={className:"text-red-600"},ve={key:1},pe={className:"mb-4"},fe={key:0},be={className:"mb-4"},he=e("option",{selected:"",disabled:""},"\u0627\u0644\u0645\u0646\u062F\u0648\u0628",-1),ge=["value"],ye={key:0},xe={className:"mb-4"},ke={className:"mb-4"},we={className:"mb-4"},Ve={className:"mb-4"},Ne={key:0},Ce={className:"flex items-center justify-center my-6 "},Se=["onClick","onKeyup","disabled"],Ue={key:0},De={key:1},Re={__name:"FormRegistration",props:{usersType:Array,sales:Array,cards:Array},setup(F){const a=n({created:n(k())});function k(){const s=new Date,t=s.getFullYear(),v=String(s.getMonth()+1).padStart(2,"0"),h=String(s.getDate()).padStart(2,"0");return`${t}-${v}-${h}`}const m=n(0);n(!1);const c=n(0),_=n(0),b=n(!1),y=()=>{b.value=!0,$.post("/api/formRegistration",a.value).then(s=>{c.value=s.data,a.value={created:n(k())},b.value=!1}).catch(s=>{c.value=0,_.value=s.response.data.errors,b.value=!1})},T=s=>{const t=s.target.files[0];A(t)},A=s=>{const t=new FileReader;t.onload=v=>{a.value.image=v.target.result},t.readAsDataURL(s)};let w=null;const I=1e3,L=s=>{clearTimeout(w),w=setTimeout(()=>{R(s)},I)},R=s=>{$.get("/api/checkCard?card_id="+s).then(t=>{m.value=t.data}).catch(t=>{m.value=0})};return(s,t)=>(d(),r(U,null,[l(S(K),{title:"Dashboard"}),l(Y,null,{header:x(()=>[q]),default:x(()=>{var v,h,V,N,C;return[c.value?(d(),r("div",z,[e("div",G,[e("div",J," \u062A\u0645 \u0627\u062F\u062E\u0627\u0644 \u0627\u0644\u0628\u0637\u0627\u0642\u0629 \u0628\u0646\u062C\u0627\u062D \u0631\u0642\u0645 "+p(c.value.card_number)+" \u0628\u0623\u0633\u0645 \u0627\u0644\u0632\u0628\u0648\u0646 "+p(c.value.name),1)])])):i("",!0),_.value&&!c.value?(d(),r("div",P,W)):i("",!0),e("form",{name:"createForm",onSubmit:D(y,["prevent"])},[e("div",Z,[e("div",ee,[e("div",te,[e("div",ae,[e("div",se,[e("div",oe,[le,e("div",de,[e("div",re,[l(u,{for:"name",value:"\u0627\u0644\u0635\u0648\u0631\u0629 \u0627\u0644\u0634\u062E\u0635\u064A\u0629"}),e("img",{src:a.value.image},null,8,ne),e("input",{onChange:T,type:"file",accept:"image/*",class:"px-2 mt-3 py-1 font-bold text-white bg-rose-500 rounded"},null,32)]),e("div",ue,[l(u,{for:"card_number",value:"\u0631\u0642\u0645 \u0627\u0644\u0628\u0637\u0627\u0642\u0629"}),l(f,{id:"card_number",type:"number",class:"mt-1 block w-full",autofocus:"",onInput:t[0]||(t[0]=o=>L(a.value.card_number)),modelValue:a.value.card_number,"onUpdate:modelValue":t[1]||(t[1]=o=>a.value.card_number=o)},null,8,["modelValue"]),m.value?(d(),r("span",ie,[g(" \u0627\u0644\u0628\u0637\u0627\u0642\u0629 \u062A\u0645 \u062A\u0633\u062C\u064A\u0644\u0647\u0627 \u0642\u0628\u0644 \u0628\u0623\u0633\u0645 "),e("span",me,p(m.value.name),1),g(" \u0644\u0644\u0645\u0646\u062F\u0648\u0628 "),e("span",ce,p((v=m.value.user)==null?void 0:v.name),1),g(" \u0623\u0641\u0631\u0627\u062F \u0627\u0644\u0639\u0627\u0626\u0644\u0629 "),e("span",_e,p(m.value.family_name),1)])):i("",!0),(h=_.value)!=null&&h.card_number?(d(),r("div",ve," \u0627\u0644\u0628\u0637\u0627\u0642\u0629\u0627\u0644\u0628\u0637\u0627\u0642\u0629 \u062D\u0642\u0644 \u0645\u0637\u0644\u0648\u0628 ")):i("",!0)]),e("div",pe,[l(u,{for:"name",value:"\u0627\u0644\u0623\u0633\u0645"}),l(f,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:a.value.name,"onUpdate:modelValue":t[2]||(t[2]=o=>a.value.name=o)},null,8,["modelValue"]),(V=_.value)!=null&&V.name?(d(),r("div",fe," \u0627\u0644\u0628\u0637\u0627\u0642\u0629\u0627\u0644\u0628\u0637\u0627\u0642\u0629 \u062D\u0642\u0644 \u0645\u0637\u0644\u0648\u0628 ")):i("",!0)]),e("div",be,[l(u,{for:"sales_id",value:"\u0627\u0644\u0645\u0646\u062F\u0648\u0628"}),M(e("select",{"onUpdate:modelValue":t[3]||(t[3]=o=>a.value.saler_id=o),id:"userType",class:"pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"},[he,(d(!0),r(U,null,j(F.sales,(o,B)=>(d(),r("option",{key:B,value:o.id},p(o.name),9,ge))),128))],512),[[H,a.value.saler_id]]),(N=_.value)!=null&&N.saler_id?(d(),r("div",ye," \u0627\u0644\u0645\u0646\u062F\u0648\u0628 \u062D\u0642\u0644 \u0645\u0637\u0644\u0648\u0628 ")):i("",!0)]),e("div",xe,[l(u,{for:"address",value:"\u0627\u0644\u0639\u0646\u0648\u0627\u0646"}),l(f,{id:"address",type:"text",class:"mt-1 block w-full",modelValue:a.value.address,"onUpdate:modelValue":t[4]||(t[4]=o=>a.value.address=o)},null,8,["modelValue"])]),e("div",ke,[l(u,{for:"family_name",value:"\u0623\u0641\u0631\u0627\u062F \u0627\u0644\u0639\u0627\u0626\u0644\u0629"}),l(f,{id:"family_name",type:"text",class:"mt-1 block w-full",modelValue:a.value.family_name,"onUpdate:modelValue":t[5]||(t[5]=o=>a.value.family_name=o)},null,8,["modelValue"])]),e("div",we,[l(u,{for:"created",value:"\u062A\u0627\u0631\u064A\u062E \u0627\u0644\u0628\u064A\u0639"}),l(f,{id:"created",type:"date",class:"mt-1 block w-full",modelValue:a.value.created,"onUpdate:modelValue":t[6]||(t[6]=o=>a.value.created=o)},null,8,["modelValue"])]),e("div",Ve,[l(u,{for:"phone_number",value:"\u0631\u0642\u0645 \u0627\u0644\u0647\u0627\u062A\u0641"}),l(f,{id:"phone_number",type:"text",class:"mt-1 block w-full",modelValue:a.value.phone_number,"onUpdate:modelValue":t[7]||(t[7]=o=>a.value.phone_number=o)},null,8,["modelValue"]),(C=_.value)!=null&&C.phone_number?(d(),r("div",Ne," \u0631\u0642\u0645 \u0627\u0644\u0647\u0627\u062A\u0641 \u062D\u0642\u0644 \u0645\u0637\u0644\u0648\u0628 ")):i("",!0)])])])])])])])]),e("div",Ce,[l(S(E),{className:"px-6 mx-2 py-2 mb-12 text-white bg-gray-500 rounded-md focus:outline-none rounded",href:s.route("formRegistration")},{default:x(()=>[g(" \u0627\u0644\u0639\u0648\u062F\u0629 ")]),_:1},8,["href"]),e("button",{onClick:D(y,["prevent"]),onKeyup:O(y,["enter"]),disabled:b.value,class:"px-6 mb-12 mx-2 py-2 font-bold text-white bg-rose-500 rounded"},[b.value?(d(),r("span",De,"\u062C\u0627\u0631\u064A \u0627\u0644\u062D\u0641\u0638...")):(d(),r("span",Ue,"\u062D\u0641\u0638"))],40,Se)])],40,X)]}),_:1})],64))}};export{Re as default};
