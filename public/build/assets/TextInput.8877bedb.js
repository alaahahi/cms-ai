import{o,a,t as l,r,m as i,p as c}from"./app.d86a7b2c.js";const d={class:"block font-medium text-sm text-gray-700"},p={key:0},m={key:1},g={__name:"InputLabel",props:["value"],setup(t){return(e,s)=>(o(),a("label",d,[t.value?(o(),a("span",p,l(t.value),1)):(o(),a("span",m,[r(e.$slots,"default")]))]))}},_=["value"],v={__name:"TextInput",props:["modelValue"],emits:["update:modelValue"],setup(t){const e=i(null);return c(()=>{e.value.hasAttribute("autofocus")&&e.value.focus()}),(s,u)=>(o(),a("input",{class:"border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm",value:t.modelValue,onInput:u[0]||(u[0]=n=>s.$emit("update:modelValue",n.target.value)),ref_key:"input",ref:e},null,40,_))}};export{g as _,v as a};
