import{u as c,o as f,s as _,w as d,a,b as s,H as w,d as r,x as n,L as g,q as V,O as b}from"./app.81d45d65.js";import{_ as v,a as t,b as x}from"./GuestLayout.04a7ebf2.js";import{_ as m}from"./InputLabel.873c11f4.js";import{_ as i}from"./TextInput.9cc4aded.js";import"./_plugin-vue_export-helper.cdc0426e.js";const y=["onSubmit"],k={class:"mt-4"},q={class:"mt-4"},N={class:"mt-4"},U={class:"flex items-center justify-end mt-4"},H={__name:"Register",setup($){const e=c({name:"",email:"",password:"",password_confirmation:"",terms:!1}),u=()=>{e.post(route("register"),{onFinish:()=>e.reset("password","password_confirmation")})};return(p,o)=>(f(),_(v,null,{default:d(()=>[a(s(w),{title:"Register"}),r("form",{onSubmit:b(u,["prevent"])},[r("div",null,[a(m,{for:"name",value:"Name"}),a(i,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:s(e).name,"onUpdate:modelValue":o[0]||(o[0]=l=>s(e).name=l),required:"",autofocus:"",autocomplete:"name"},null,8,["modelValue"]),a(t,{class:"mt-2",message:s(e).errors.name},null,8,["message"])]),r("div",k,[a(m,{for:"email",value:"User Name"}),a(i,{id:"email",type:"text",class:"mt-1 block w-full",modelValue:s(e).email,"onUpdate:modelValue":o[1]||(o[1]=l=>s(e).email=l),required:"",autocomplete:"username"},null,8,["modelValue"]),a(t,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),r("div",q,[a(m,{for:"password",value:"Password"}),a(i,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:s(e).password,"onUpdate:modelValue":o[2]||(o[2]=l=>s(e).password=l),required:"",autocomplete:"new-password"},null,8,["modelValue"]),a(t,{class:"mt-2",message:s(e).errors.password},null,8,["message"])]),r("div",N,[a(m,{for:"password_confirmation",value:"Confirm Password"}),a(i,{id:"password_confirmation",type:"password",class:"mt-1 block w-full",modelValue:s(e).password_confirmation,"onUpdate:modelValue":o[3]||(o[3]=l=>s(e).password_confirmation=l),required:"",autocomplete:"new-password"},null,8,["modelValue"]),a(t,{class:"mt-2",message:s(e).errors.password_confirmation},null,8,["message"])]),r("div",U,[a(s(g),{href:p.route("login"),class:"underline text-sm text-gray-600 hover:text-gray-900"},{default:d(()=>[n(" Already registered? ")]),_:1},8,["href"]),a(x,{class:V(["ml-4",{"opacity-25":s(e).processing}]),disabled:s(e).processing},{default:d(()=>[n(" Register ")]),_:1},8,["class","disabled"])])],40,y)]),_:1}))}};export{H as default};
