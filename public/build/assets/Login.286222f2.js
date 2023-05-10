import{c as g,w as b,v as h,u as s,o as d,a as f,i as k,b as w,d as x,e as m,f as a,H as v,t as y,g as V,h as l,j as $,n as B,k as C}from"./app.5fbd953e.js";import{_ as N,a as c,b as S}from"./PrimaryButton.a30ac4e1.js";import{_ as u,a as p}from"./TextInput.442dfd57.js";import"./_plugin-vue_export-helper.cdc0426e.js";const U=["value"],j={__name:"Checkbox",props:{checked:{type:[Array,Boolean],default:!1},value:{default:null}},emits:["update:checked"],setup(r,{emit:e}){const i=r,n=g({get(){return i.checked},set(t){e("update:checked",t)}});return(t,o)=>b((d(),f("input",{type:"checkbox",value:r.value,"onUpdate:modelValue":o[0]||(o[0]=_=>k(n)?n.value=_:null),class:"rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"},null,8,U)),[[h,s(n)]])}},q={key:0,class:"mb-4 font-medium text-sm text-green-600"},D=["onSubmit"],F={class:"mt-4"},H={class:"block mt-4"},L={class:"flex items-center"},M=l("span",{class:"ml-2 text-sm text-gray-600"},"\u062A\u0630\u0643\u0631\u0646\u064A",-1),R={class:"flex items-center justify-end mt-4"},T={__name:"Login",props:{canResetPassword:Boolean,status:String},setup(r){const e=w({email:"",password:"",remember:!1}),i=()=>{e.post(route("login"),{onFinish:()=>e.reset("password")})};return(n,t)=>(d(),x(N,null,{default:m(()=>[a(s(v),{title:"Log in"}),r.status?(d(),f("div",q,y(r.status),1)):V("",!0),l("form",{onSubmit:C(i,["prevent"])},[l("div",null,[a(u,{for:"email",value:"\u0627\u0633\u0645 \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645"}),a(p,{id:"email",type:"text",class:"mt-1 block w-full",modelValue:s(e).email,"onUpdate:modelValue":t[0]||(t[0]=o=>s(e).email=o),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),a(c,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),l("div",F,[a(u,{for:"password",value:"\u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631"}),a(p,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:s(e).password,"onUpdate:modelValue":t[1]||(t[1]=o=>s(e).password=o),required:"",autocomplete:"current-password"},null,8,["modelValue"]),a(c,{class:"mt-2",message:s(e).errors.password},null,8,["message"])]),l("div",H,[l("label",L,[a(j,{name:"remember",checked:s(e).remember,"onUpdate:checked":t[2]||(t[2]=o=>s(e).remember=o)},null,8,["checked"]),M])]),l("div",R,[a(S,{class:B(["ml-4",{"opacity-25":s(e).processing}]),disabled:s(e).processing},{default:m(()=>[$(" \u062A\u0633\u062C\u064A\u0644 \u0627\u0644\u062F\u062E\u0648\u0644 ")]),_:1},8,["class","disabled"])])],40,D)]),_:1}))}};export{T as default};
