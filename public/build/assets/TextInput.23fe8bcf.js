import{r as n,j as r,o as s,c as l}from"./app.fac329ba.js";const i=["value"],c={__name:"TextInput",props:["modelValue"],emits:["update:modelValue"],setup(u){const e=n(null);return r(()=>{e.value.hasAttribute("autofocus")&&e.value.focus()}),(t,o)=>(s(),l("input",{class:"border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm",value:u.modelValue,onInput:o[0]||(o[0]=a=>t.$emit("update:modelValue",a.target.value)),ref_key:"input",ref:e},null,40,i))}};export{c as _};
