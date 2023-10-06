import{j as F,k as j,l as $,r as L,o as r,c as u,d as t,m as g,f as C,n as R,a as n,w as s,q as m,b as v,T as M,s as p,L as w,e as i,x as a,t as k}from"./app.281563e4.js";const N={class:"relative"},B={__name:"Dropdown",props:{align:{default:"right"},width:{default:"48"},contentClasses:{default:()=>["py-1","bg-white"]}},setup(d){const o=d,e=_=>{h.value&&_.key==="Escape"&&(h.value=!1)};F(()=>document.addEventListener("keydown",e)),j(()=>document.removeEventListener("keydown",e));const c=$(()=>({48:"w-48"})[o.width.toString()]),b=$(()=>o.align==="left"?"origin-top-left left-0":o.align==="right"?"origin-top-right right-0":"origin-top"),h=L(!1);return(_,y)=>(r(),u("div",N,[t("div",{onClick:y[0]||(y[0]=x=>h.value=!h.value)},[g(_.$slots,"trigger")]),C(t("div",{class:"fixed inset-0 z-40",onClick:y[1]||(y[1]=x=>h.value=!1)},null,512),[[R,h.value]]),n(M,{"enter-active-class":"transition ease-out duration-200","enter-from-class":"transform opacity-0 scale-95","enter-to-class":"transform opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"transform opacity-100 scale-100","leave-to-class":"transform opacity-0 scale-95"},{default:s(()=>[C(t("div",{class:m(["absolute z-50 mt-2 rounded-md shadow-lg",[v(c),v(b)]]),style:{display:"none"},onClick:y[2]||(y[2]=x=>h.value=!1)},[t("div",{class:m(["rounded-md ring-1 ring-black ring-opacity-5",d.contentClasses])},[g(_.$slots,"content")],2)],2),[[R,h.value]])]),_:3})]))}},D={__name:"DropdownLink",setup(d){return(o,e)=>(r(),p(v(w),{class:"block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"},{default:s(()=>[g(o.$slots,"default")]),_:3}))}},f={__name:"NavLink",props:["href","active"],setup(d){const o=d,e=$(()=>o.active?"inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition  duration-150 ease-in-out":"inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out");return(c,b)=>(r(),p(v(w),{href:d.href,class:m(v(e))},{default:s(()=>[g(c.$slots,"default")]),_:3},8,["href","class"]))}},l={__name:"ResponsiveNavLink",props:["href","active"],setup(d){const o=d,e=$(()=>o.active?"block pl-3 pr-4 py-2 border-l-4 border-indigo-400 text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out":"block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out");return(c,b)=>(r(),p(v(w),{href:d.href,class:m(v(e))},{default:s(()=>[g(c.$slots,"default")]),_:3},8,["href","class"]))}},E={class:"min-h-screen bg-gray-100"},z={class:"bg-white border-b border-gray-100 print:hidden"},S={class:"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"},V={class:"flex justify-between h-16"},T={class:"flex"},q={class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},A={key:1,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},O={key:2,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},U={key:3,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},G={key:4,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},H={key:5,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},I={key:6,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},J={key:7,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},K={class:"hidden sm:flex sm:items-center sm:ml-6"},P={class:"ml-3 relative"},Q={class:"inline-flex rounded-md"},W={type:"button",class:"inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"},X=t("svg",{class:"ml-2 -mr-0.5 h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor"},[t("path",{"fill-rule":"evenodd",d:"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z","clip-rule":"evenodd"})],-1),Y={class:"-mr-2 flex items-center sm:hidden"},Z={class:"h-6 w-6",stroke:"currentColor",fill:"none",viewBox:"0 0 24 24"},ee={class:"pt-2 pb-3 space-y-1"},te={class:"pt-4 pb-1 border-t border-gray-200"},se={class:"px-4"},re={class:"font-medium text-base text-gray-800"},ae={class:"font-medium text-sm text-gray-500"},oe={class:"mt-3 space-y-1"},ie={key:0,class:"bg-white shadow"},ne={class:"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"},ue={class:"flex flex-row"},de={class:"basis-1/2 flex flex-col justify-center"},le=t("div",{class:"basis-1/2"},[t("img",{src:"/asset/img/logo.jpg",alt:"karbala-alhassan",style:{"margin-right":"auto",width:"200px"}})],-1),ce={__name:"AuthenticatedLayout",setup(d){const o=L(!1);return(e,c)=>(r(),u("div",null,[t("div",E,[t("nav",z,[t("div",S,[t("div",V,[t("div",T,[i("",!0),t("div",q,[n(f,{href:e.route("dashboard"),active:e.route().current("dashboard")},{default:s(()=>[a(" \u0627\u0644\u0631\u0626\u064A\u0633\u064A\u0629 ")]),_:1},8,["href","active"])]),e.$page.props.auth.user.type_id==1?(r(),u("div",A,[n(f,{href:e.route("users.index"),active:e.route().current("users.index")},{default:s(()=>[a(" \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645\u064A\u0646 ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id!=5?(r(),u("div",O,[n(f,{href:e.route("formRegistration"),active:e.route().current("formRegistration")},{default:s(()=>[a(" \u0627\u0644\u0639\u0642\u062F \u0627\u0644\u0625\u0644\u0643\u062A\u0631\u0648\u0646\u064A ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==4||e.$page.props.auth.user.type_id==5?(r(),u("div",U,[n(f,{href:e.route("FormRegistrationCompleted"),active:e.route().current("FormRegistrationCompleted")},{default:s(()=>[a(" \u0627\u0633\u062A\u0644\u0627\u0645 \u0627\u0644\u0628\u0637\u0627\u0642\u0627\u062A ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1?(r(),u("div",G,[n(f,{href:e.route("FormRegistrationCourt"),active:e.route().current("FormRegistrationCourt")},{default:s(()=>[a(" \u062D\u0633\u0627\u0628\u0627\u062A \u0627\u0644\u0645\u0646\u062F\u0648\u0628\u064A\u0646 ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2?(r(),u("div",H,[n(f,{href:e.route("accounting"),active:e.route().current("accounting")},{default:s(()=>[a(" \u0627\u0644\u0645\u062D\u0627\u0633\u0628\u0629 ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==6?(r(),u("div",I,[n(f,{href:e.route("hospital"),active:e.route().current("hospital")},{default:s(()=>[a(" \u0627\u0644\u062D\u062C\u0648\u0632\u0627\u062A ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==6?(r(),u("div",J,[n(f,{href:e.route("card"),active:e.route().current("card")},{default:s(()=>[a(" \u0639\u0631\u0636 \u0627\u0644\u0628\u0637\u0627\u0642\u0627\u062A ")]),_:1},8,["href","active"])])):i("",!0)]),t("div",K,[t("div",P,[n(B,{align:"right",width:"48"},{trigger:s(()=>[t("span",Q,[t("button",W,[a(k(e.$page.props.auth.user.name)+" ",1),X])])]),content:s(()=>[n(D,{href:e.route("logout"),method:"post",as:"button"},{default:s(()=>[a(" \u062A\u0633\u062C\u064A\u0644 \u0627\u0644\u062E\u0631\u0648\u062C ")]),_:1},8,["href"])]),_:1})])]),t("div",Y,[t("button",{onClick:c[0]||(c[0]=b=>o.value=!o.value),class:"inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"},[(r(),u("svg",Z,[t("path",{class:m({hidden:o.value,"inline-flex":!o.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M4 6h16M4 12h16M4 18h16"},null,2),t("path",{class:m({hidden:!o.value,"inline-flex":o.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"},null,2)]))])])])]),t("div",{class:m([{block:o.value,hidden:!o.value},"sm:hidden"])},[t("div",ee,[n(l,{href:e.route("dashboard"),active:e.route().current("dashboard")},{default:s(()=>[a(" \u0644\u0648\u062D\u0629 \u0627\u0644\u0642\u064A\u0627\u062F\u0629 ")]),_:1},8,["href","active"])]),t("div",te,[t("div",se,[t("div",re,k(e.$page.props.auth.user.name),1),t("div",ae,k(e.$page.props.auth.user.email),1)]),t("div",oe,[n(l,{href:e.route("dashboard"),active:e.route().current("dashboard")},{default:s(()=>[a(" \u0627\u0644\u0631\u0626\u064A\u0633\u064A\u0629 ")]),_:1},8,["href","active"]),e.$page.props.auth.user.type_id==1?(r(),p(l,{key:0,href:e.route("users.index"),active:e.route().current("users.index")},{default:s(()=>[a(" \u0627\u0644\u0645\u0633\u062A\u062E\u062F\u0645\u064A\u0646 ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id!=5?(r(),p(l,{key:1,href:e.route("formRegistration"),active:e.route().current("formRegistration")},{default:s(()=>[a(" \u0627\u0644\u0639\u0642\u062F \u0627\u0644\u0625\u0644\u0643\u062A\u0631\u0648\u0646\u064A ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==4?(r(),p(l,{key:2,href:e.route("FormRegistrationCompleted"),active:e.route().current("FormRegistrationCompleted")},{default:s(()=>[a(" \u0627\u0633\u062A\u0645\u0627\u0631\u0629 \u0627\u0644\u0645\u0646\u062C\u0632\u0629 ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1?(r(),p(l,{key:3,href:e.route("FormRegistrationCourt"),active:e.route().current("FormRegistrationCourt")},{default:s(()=>[a(" \u062D\u0633\u0627\u0628\u0627\u062A \u0627\u0644\u0645\u0646\u062F\u0648\u0628\u064A\u0646 ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2?(r(),p(l,{key:4,href:e.route("accounting"),active:e.route().current("accounting")},{default:s(()=>[a(" \u0627\u0644\u0645\u062D\u0627\u0633\u0628\u0629 ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==6?(r(),p(l,{key:5,href:e.route("hospital"),active:e.route().current("hospital")},{default:s(()=>[a(" \u0627\u0644\u062D\u062C\u0648\u0632\u0627\u062A ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==6?(r(),p(l,{key:6,href:e.route("card"),active:e.route().current("card")},{default:s(()=>[a(" \u0639\u0631\u0636 \u0627\u0644\u0628\u0637\u0627\u0642\u0627\u062A ")]),_:1},8,["href","active"])):i("",!0),n(l,{href:e.route("logout"),method:"post",as:"button"},{default:s(()=>[a(" \u062A\u0633\u062C\u064A\u0644 \u0627\u0644\u062E\u0631\u0648\u062C ")]),_:1},8,["href"])])])],2)]),e.$slots.header?(r(),u("header",ie,[t("div",ne,[t("div",ue,[t("div",de,[g(e.$slots,"header")]),le])])])):i("",!0),t("main",null,[g(e.$slots,"default")])])]))}};export{ce as _};
