import{u as y,o as l,c as d,a as o,b as e,s as g,w as p,e as i,F as b,H as h,d as t,x,L as v,O as w,f as k,v as N,g as V,t as T}from"./app.0cf4d3aa.js";import{_ as S}from"./AuthenticatedLayout.259e68bc.js";import{_ as n}from"./InputLabel.500228e7.js";import{_ as m}from"./TextInput.5f9f87a0.js";const D=t("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," \u0625\u0646\u0634\u0627\u0621 \u0645\u0633\u062A\u062E\u062F\u0645 ",-1),U={class:"py-12"},$={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},B={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},F={class:"p-6 bg-white border-b border-gray-200"},H={className:"flex items-center justify-between mb-6"},C=["onSubmit"],L={className:"flex flex-col"},A={className:"mb-4"},M={key:0,className:"text-red-600"},j={className:"mb-4"},E={key:0,className:"text-red-600"},O={className:"mb-4"},q={key:0,className:"text-red-600"},z={className:"mb-4"},G=t("option",{selected:"",disabled:""},"\u0635\u0644\u0627\u062D\u064A\u0627\u062A \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645 \u0627\u0644\u0645\u062A\u0627\u062D\u0629",-1),I=["value"],J={key:0,className:"text-red-600"},K={key:0,className:"mb-4"},P=t("div",{className:"mt-4"},[t("button",{type:"submit",className:"px-6 py-2 font-bold text-white bg-rose-500 rounded"}," \u062D\u0641\u0638 ")],-1),Y={__name:"Create",props:{usersType:Array,coordinators:Array,userSeles:String,userHospital:String,userDoctor:String},setup(u){const s=y({name:"",email:"",password:"",userType:"",parent_id:"",percentage:""}),f=()=>{s.post(route("users.store"))};return(c,r)=>(l(),d(b,null,[o(e(h),{title:"Dashboard"}),c.$page.props.auth.user.type_id==1||c.$page.props.auth.user.type_id==5?(l(),g(S,{key:0},{header:p(()=>[D]),default:p(()=>[t("div",U,[t("div",$,[t("div",B,[t("div",F,[t("div",H,[o(e(v),{className:"px-6 py-2 text-white bg-gray-500 rounded-md focus:outline-none",href:c.route("users.index")},{default:p(()=>[x(" \u0627\u0644\u0639\u0648\u062F\u0629 ")]),_:1},8,["href"])]),t("form",{name:"createForm",onSubmit:w(f,["prevent"])},[t("div",L,[t("div",A,[o(n,{for:"name",value:"\u0627\u0644\u0623\u0633\u0645"}),o(m,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:e(s).name,"onUpdate:modelValue":r[0]||(r[0]=a=>e(s).name=a),autofocus:""},null,8,["modelValue"]),e(s).errors.name?(l(),d("span",M," \u0627\u0644\u0623\u0633\u0645 \u062D\u0642\u0644 \u0645\u0637\u0644\u0648\u0628 ")):i("",!0)]),t("div",j,[o(n,{for:"email",value:"\u0627\u0633\u0645 \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645"}),o(m,{id:"email",type:"text",class:"mt-1 block w-full",modelValue:e(s).email,"onUpdate:modelValue":r[1]||(r[1]=a=>e(s).email=a)},null,8,["modelValue"]),e(s).errors.email?(l(),d("span",E," \u0627\u0633\u0645 \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645 \u0647\u0630\u0627 \u063A\u064A\u0631 \u0645\u062A\u0627\u062D ")):i("",!0)]),t("div",O,[o(n,{for:"password",value:"\u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631"}),o(m,{id:"password",type:"text",class:"mt-1 block w-full",modelValue:e(s).password,"onUpdate:modelValue":r[2]||(r[2]=a=>e(s).password=a)},null,8,["modelValue"]),e(s).errors.password?(l(),d("span",q," \u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631 \u062D\u0642\u0644 \u0645\u0637\u0644\u0648\u0628 ")):i("",!0)]),t("div",z,[o(n,{for:"userType",value:"\u0635\u0644\u0627\u062C\u064A\u0627\u062A \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645"}),k(t("select",{"onUpdate:modelValue":r[3]||(r[3]=a=>e(s).userType=a),id:"userType",class:"pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"},[G,(l(!0),d(b,null,V(u.usersType,(a,_)=>(l(),d("option",{key:_,value:a.id},T(a.name),9,I))),128))],512),[[N,e(s).userType]]),e(s).errors.email?(l(),d("span",J," \u0635\u0644\u0627\u062D\u064A\u0627\u062A \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645 \u062D\u0642\u0644 \u0645\u0637\u0644\u0648\u0628 ")):i("",!0)]),e(s).userType==u.userSeles||e(s).userType==u.userHospital||e(s).userType==u.userDoctor?(l(),d("div",K,[o(n,{for:"percentage",value:"\u0646\u0633\u0628\u0629 \u0627\u0644\u0645\u0628\u064A\u0639\u0627\u062A"}),o(m,{id:"percentage",type:"number",class:"mt-1 block w-full",modelValue:e(s).percentage,"onUpdate:modelValue":r[4]||(r[4]=a=>e(s).percentage=a)},null,8,["modelValue"])])):i("",!0)]),P],40,C)])])])])]),_:1})):i("",!0)],64))}};export{Y as default};
