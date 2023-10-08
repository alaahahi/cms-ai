import{_ as B}from"./AuthenticatedLayout.1731f1f9.js";/* empty css                                              */import{r as h,o as s,s as w,w as u,c as n,d as e,m as M,f as k,F as $,g as C,t as r,v as U,h as V,e as m,T as D,u as S,a as y,b as p,H as T,x as f,J as F,L as N}from"./app.a3a901d5.js";import{t as L}from"./laravel-vue-pagination.es.9269c251.js";const j={key:0,class:"modal-mask"},H={class:"modal-wrapper"},P={class:"modal-container"},R={class:"modal-header"},E={class:"modal-body"},J=e("h2",{class:"text-center"}," \u0625\u0636\u0627\u0641\u0629 \u0628\u0637\u0627\u0642\u0627\u062A \u0644\u0644\u0645\u0646\u062F\u0648\u0628 ",-1),q={className:"mb-4 mx-5"},z=e("label",{for:"card_id"}," \u0646\u0648\u0639 \u0627\u0644\u0628\u0637\u0627\u0642\u0629",-1),G=e("option",{selected:"",disabled:""},"\u062A\u062D\u062F\u064A\u062F \u0627\u0644\u0628\u0637\u0627\u0642\u0629",-1),K=["value"],O={className:"mb-4 mx-5"},Q=e("label",{for:"card"},"\u0639\u062F\u062F \u0627\u0644\u0628\u0637\u0627\u0642\u0629 \u0627\u0644\u062A\u064A \u062A\u0645 \u062A\u0633\u0644\u064A\u0645\u0647\u0627 \u0644\u0644\u0645\u0646\u062F\u0648\u0628",-1),W={class:"modal-footer my-2"},X={class:"flex flex-row"},Y={class:"basis-1/2 px-4"},Z={class:"basis-1/2 px-4"},ee=["disabled"],te={__name:"ModalAddCardUser",props:{show:Boolean,data:Array},setup(b){const o=h({card_id:null,card:""});return(_,d)=>(s(),w(D,{name:"modal"},{default:u(()=>[b.show?(s(),n("div",j,[e("div",H,[e("div",P,[e("div",R,[M(_.$slots,"header")]),e("div",E,[J,e("div",q,[z,k(e("select",{"onUpdate:modelValue":d[0]||(d[0]=l=>o.value.card_id=l),id:"card_id",class:"pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"},[G,(s(!0),n($,null,C(b.data,(l,g)=>(s(),n("option",{key:g,value:l.id},r(l.name),9,K))),128))],512),[[U,o.value.card_id]])]),e("div",O,[Q,k(e("input",{id:"card",type:"number",class:"mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm","onUpdate:modelValue":d[1]||(d[1]=l=>o.value.card=l)},null,512),[[V,o.value.card]])])]),e("div",W,[e("div",X,[e("div",Y,[e("button",{class:"modal-default-button py-3 bg-gray-500 rounded",onClick:d[2]||(d[2]=l=>{_.$emit("close")})},"\u062A\u0631\u0627\u062C\u0639")]),e("div",Z,[e("button",{class:"modal-default-button py-3 bg-rose-500 rounded col-6",onClick:d[3]||(d[3]=l=>{_.$emit("a",o.value)}),disabled:!(o.value.card&&o.value.card_id)},"\u0646\u0639\u0645",8,ee)])])])])])])):m("",!0)]),_:3}))}};const ae=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," \u0625\u062F\u0627\u0631\u0629 \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645\u064A\u0646 ",-1),se={class:"py-12"},oe={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},de={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},le={class:"p-6 bg-white border-b border-gray-200"},ne={className:"flex items-center justify-between mb-6"},re={class:"overflow-x-auto shadow-md"},ie={class:"w-full my-5"},ce=e("thead",{class:"700 bg-rose-500 text-white text-center rounded-l-lg"},[e("tr",{class:"bg-rose-500 rounded-l-lg mb-2 sm:mb-0"},[e("th",{className:"px-4 py-2 w-20"},"\u0627\u0644\u0631\u0642\u0645"),e("th",{className:"px-4 py-2"},"\u0627\u0644\u0623\u0633\u0645"),e("th",{className:"px-4 py-2"},"\u0627\u0633\u0645 \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645"),e("th",{className:"px-4 py-2"},"\u0627\u0644\u0635\u0644\u0627\u062D\u064A\u0627\u062A"),e("th",{className:"px-4 py-2"},"\u0646\u0633\u0628\u0629 \u0627\u0644\u0645\u0628\u064A\u0639\u0627\u062A"),e("th",{className:"px-4 py-2"},"\u0639\u062F\u062F \u0627\u0644\u0628\u0637\u0627\u0642\u0627\u062A"),e("th",{className:"px-4 py-2"},"\u0627\u0644\u0631\u0635\u064A\u062F"),e("th",{className:"px-4 py-2"},"\u062A\u0646\u0641\u064A\u0630")])],-1),me={class:"flex-1 sm:flex-none"},_e={className:"border px-4 py-2"},ue={className:"border px-4 py-2"},pe={className:"border px-4 py-2"},he={key:0,class:"text-sm text-green-500 font-bold py-2 px-2 hover:text-red-500"},be={className:"border px-4 py-2"},xe={className:"border px-4 py-2"},ye={className:"border px-4 py-2"},fe={className:"border px-4 py-2"},ge={className:"border px-4 py-2",style:{"min-height":"42px"}},ve=["onClick"],we=["onClick"],$e=["onClick"],ke={class:"mt-3 text-center",style:{direction:"ltr"}},Be={__name:"Index",props:{url:String,cards:Array},setup(b){const o=h({});h({});const _=async(a=1)=>{const i=await fetch(`/getIndex?page=${a}`);o.value=await i.json()};_();const d=S();function l(a){d.get(route("ban",a)),window.location.reload()}function g(a){d.get(route("unban",a)),window.location.reload()}let c=h(!1),x=h(0);function I(a){let i=a.card_id,t=a.card;fetch(`/addUserCard/${i}/${t}/${x.value}`).then(()=>{c.value=!1,x.value=0,window.location.reload()}).catch(v=>{c.value=!1,x.value=0})}function A(a){x.value=a,c.value=!0}return(a,i)=>(s(),n($,null,[y(p(T),{title:"Dashboard"}),a.$page.props.auth.user.type_id==1||a.$page.props.auth.user.type_id==5?(s(),w(B,{key:0},{header:u(()=>[ae]),default:u(()=>[y(te,{show:!!p(c),data:b.cards,onA:i[0]||(i[0]=t=>I(t)),onClose:i[1]||(i[1]=t=>F(c)?c.value=!1:c=!1)},{header:u(()=>[f(" \u0652 ")]),_:1},8,["show","data"]),e("div",se,[e("div",oe,[e("div",de,[e("div",le,[e("div",ne,[y(p(N),{className:"px-6 py-2 text-white bg-rose-500 rounded-md focus:outline-none",href:a.route("users.create")},{default:u(()=>[f(" \u0625\u0646\u0634\u0627\u0621 \u0645\u0633\u062A\u062E\u062F\u0645 ")]),_:1},8,["href"])]),e("div",re,[e("table",ie,[ce,e("tbody",me,[(s(!0),n($,null,C(o.value.data,t=>(s(),n("tr",{key:t.id,class:"text-center mb-2 sm:mb-0 hover:bg-gray-100"},[e("td",_e,r(t.id),1),e("td",ue,r(t.name),1),e("td",pe,[f(r(t.email),1),t.device?(s(),n("span",he,r(t.device),1)):m("",!0)]),e("td",be,r(t.user_type?t.user_type.name:""),1),e("td",xe,r(t.percentage),1),e("td",ye,r(t.wallet?t.wallet.card:""),1),e("td",fe,r(t.wallet?t.wallet.balance:""),1),e("td",ge,[t.email!="admin@admin.com"?(s(),w(p(N),{key:0,tabIndex:"1",className:"px-2 py-1 text-sm text-white bg-slate-500 rounded",href:a.route("users.edit",t.id)},{default:u(()=>[f(" \u062A\u0639\u062F\u064A\u0644 ")]),_:2},1032,["href"])):m("",!0),!t.is_band&&t.email!="admin@admin.com"?(s(),n("button",{key:1,onClick:v=>l(t.id),tabIndex:"-1",type:"button",className:"mx-1 px-2 py-1 text-sm text-white bg-orange-500 rounded"}," \u062A\u0642\u064A\u062F ",8,ve)):m("",!0),t.is_band&&t.email!="admin@admin.com"?(s(),n("button",{key:2,onClick:v=>g(t.id),tabIndex:"-1",type:"button",className:"mx-1 px-2 py-1 text-sm text-white bg-orange-500 rounded"}," \u0625\u0644\u063A\u0627\u0621 \u0627\u0644\u062A\u0642\u064A\u062F ",8,we)):m("",!0),!t.is_band&&t.email!="admin@admin.com"?(s(),n("button",{key:3,onClick:v=>A(t.id),tabIndex:"-1",type:"button",className:"mx-1 px-2 py-1 text-sm text-white bg-green-500 rounded"}," \u0625\u0636\u0627\u0641\u0629 \u0628\u0637\u0627\u0642\u0627\u062A ",8,$e)):m("",!0)])]))),128))])])]),e("div",ke,[y(p(L),{data:o.value,onPaginationChangePage:_,limit:2},null,8,["data"])])])])])])]),_:1})):m("",!0)],64))}};export{Be as default};
