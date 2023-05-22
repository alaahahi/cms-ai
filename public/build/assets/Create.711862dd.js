import{b as y,o as l,a as d,f as o,u as e,d as g,e as c,g as n,F as b,H as h,h as t,j as x,L as w,k,w as v,N,J as V,t as T}from"./app.4f28e054.js";import{_ as S}from"./AuthenticatedLayout.291016b5.js";import{_ as i,a as m}from"./TextInput.b068478e.js";const D=t("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," \u0625\u0646\u0634\u0627\u0621 \u0645\u0633\u062A\u062E\u062F\u0645 ",-1),U={class:"py-12"},B={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},F={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},H={class:"p-6 bg-white border-b border-gray-200"},$={className:"flex items-center justify-between mb-6"},C=["onSubmit"],L={className:"flex flex-col"},j={className:"mb-4"},A={key:0,className:"text-red-600"},M={className:"mb-4"},E={key:0,className:"text-red-600"},J={className:"mb-4"},q={key:0,className:"text-red-600"},z={className:"mb-4"},G=t("option",{selected:"",disabled:""},"\u0635\u0644\u0627\u062D\u064A\u0627\u062A \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645 \u0627\u0644\u0645\u062A\u0627\u062D\u0629",-1),I=["value"],K={key:0,className:"text-red-600"},O={key:0,className:"mb-4"},P=t("div",{className:"mt-4"},[t("button",{type:"submit",className:"px-6 py-2 font-bold text-white bg-rose-500 rounded"}," \u062D\u0641\u0638 ")],-1),X={__name:"Create",props:{usersType:Array,coordinators:Array,userSeles:String,userHospital:String,userDoctor:String},setup(u){const s=y({name:"",email:"",password:"",userType:"",parent_id:"",percentage:""}),f=()=>{s.post(route("users.store"))};return(p,r)=>(l(),d(b,null,[o(e(h),{title:"Dashboard"}),p.$page.props.auth.user.type_id==1?(l(),g(S,{key:0},{header:c(()=>[D]),default:c(()=>[t("div",U,[t("div",B,[t("div",F,[t("div",H,[t("div",$,[o(e(w),{className:"px-6 py-2 text-white bg-gray-500 rounded-md focus:outline-none",href:p.route("users.index")},{default:c(()=>[x(" \u0627\u0644\u0639\u0648\u062F\u0629 ")]),_:1},8,["href"])]),t("form",{name:"createForm",onSubmit:k(f,["prevent"])},[t("div",L,[t("div",j,[o(i,{for:"name",value:"\u0627\u0644\u0623\u0633\u0645"}),o(m,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:e(s).name,"onUpdate:modelValue":r[0]||(r[0]=a=>e(s).name=a),autofocus:""},null,8,["modelValue"]),e(s).errors.name?(l(),d("span",A," \u0627\u0644\u0623\u0633\u0645 \u062D\u0642\u0644 \u0645\u0637\u0644\u0648\u0628 ")):n("",!0)]),t("div",M,[o(i,{for:"email",value:"\u0627\u0633\u0645 \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645"}),o(m,{id:"email",type:"text",class:"mt-1 block w-full",modelValue:e(s).email,"onUpdate:modelValue":r[1]||(r[1]=a=>e(s).email=a)},null,8,["modelValue"]),e(s).errors.email?(l(),d("span",E," \u0627\u0633\u0645 \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645 \u0647\u0630\u0627 \u063A\u064A\u0631 \u0645\u062A\u0627\u062D ")):n("",!0)]),t("div",J,[o(i,{for:"password",value:"\u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631"}),o(m,{id:"password",type:"text",class:"mt-1 block w-full",modelValue:e(s).password,"onUpdate:modelValue":r[2]||(r[2]=a=>e(s).password=a)},null,8,["modelValue"]),e(s).errors.password?(l(),d("span",q," \u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631 \u062D\u0642\u0644 \u0645\u0637\u0644\u0648\u0628 ")):n("",!0)]),t("div",z,[o(i,{for:"userType",value:"\u0635\u0644\u0627\u062C\u064A\u0627\u062A \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645"}),v(t("select",{"onUpdate:modelValue":r[3]||(r[3]=a=>e(s).userType=a),id:"userType",class:"pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"},[G,(l(!0),d(b,null,V(u.usersType,(a,_)=>(l(),d("option",{key:_,value:a.id},T(a.name),9,I))),128))],512),[[N,e(s).userType]]),e(s).errors.email?(l(),d("span",K," \u0635\u0644\u0627\u062D\u064A\u0627\u062A \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645 \u062D\u0642\u0644 \u0645\u0637\u0644\u0648\u0628 ")):n("",!0)]),e(s).userType==u.userSeles||e(s).userType==u.userHospital||e(s).userType==u.userDoctor?(l(),d("div",O,[o(i,{for:"percentage",value:"\u0646\u0633\u0628\u0629 \u0627\u0644\u0645\u0628\u064A\u0639\u0627\u062A"}),o(m,{id:"percentage",type:"number",class:"mt-1 block w-full",modelValue:e(s).percentage,"onUpdate:modelValue":r[4]||(r[4]=a=>e(s).percentage=a)},null,8,["modelValue"])])):n("",!0)]),P],40,C)])])])])]),_:1})):n("",!0)],64))}};export{X as default};
