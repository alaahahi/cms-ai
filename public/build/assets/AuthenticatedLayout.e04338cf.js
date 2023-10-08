import{j,k as M,l as $,r as R,o as r,c as d,d as t,m,f as C,n as L,a as n,w as s,q as g,b as v,T as N,s as p,L as w,e as i,x as o,t as k}from"./app.806674ec.js";const B={class:"relative"},D={__name:"Dropdown",props:{align:{default:"right"},width:{default:"48"},contentClasses:{default:()=>["py-1","bg-white"]}},setup(u){const a=u,e=_=>{h.value&&_.key==="Escape"&&(h.value=!1)};j(()=>document.addEventListener("keydown",e)),M(()=>document.removeEventListener("keydown",e));const c=$(()=>({48:"w-48"})[a.width.toString()]),b=$(()=>a.align==="left"?"origin-top-left left-0":a.align==="right"?"origin-top-right right-0":"origin-top"),h=R(!1);return(_,y)=>(r(),d("div",B,[t("div",{onClick:y[0]||(y[0]=x=>h.value=!h.value)},[m(_.$slots,"trigger")]),C(t("div",{class:"fixed inset-0 z-40",onClick:y[1]||(y[1]=x=>h.value=!1)},null,512),[[L,h.value]]),n(N,{"enter-active-class":"transition ease-out duration-200","enter-from-class":"transform opacity-0 scale-95","enter-to-class":"transform opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"transform opacity-100 scale-100","leave-to-class":"transform opacity-0 scale-95"},{default:s(()=>[C(t("div",{class:g(["absolute z-50 mt-2 rounded-md shadow-lg",[v(c),v(b)]]),style:{display:"none"},onClick:y[2]||(y[2]=x=>h.value=!1)},[t("div",{class:g(["rounded-md ring-1 ring-black ring-opacity-5",u.contentClasses])},[m(_.$slots,"content")],2)],2),[[L,h.value]])]),_:3})]))}},E={__name:"DropdownLink",setup(u){return(a,e)=>(r(),p(v(w),{class:"block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"},{default:s(()=>[m(a.$slots,"default")]),_:3}))}},f={__name:"NavLink",props:["href","active"],setup(u){const a=u,e=$(()=>a.active?"inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition  duration-150 ease-in-out":"inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out");return(c,b)=>(r(),p(v(w),{href:u.href,class:g(v(e))},{default:s(()=>[m(c.$slots,"default")]),_:3},8,["href","class"]))}},l={__name:"ResponsiveNavLink",props:["href","active"],setup(u){const a=u,e=$(()=>a.active?"block pl-3 pr-4 py-2 border-l-4 border-indigo-400 text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out":"block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out");return(c,b)=>(r(),p(v(w),{href:u.href,class:g(v(e))},{default:s(()=>[m(c.$slots,"default")]),_:3},8,["href","class"]))}},z={class:"min-h-screen bg-gray-100"},F={class:"bg-white border-b border-gray-100 print:hidden"},S={class:"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"},V={class:"flex justify-between h-16"},T={class:"flex"},q={class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},A={key:1,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},O={key:2,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},U={key:3,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},G={key:4,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},H={key:5,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},I={key:6,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},J={class:"hidden sm:flex sm:items-center sm:ml-6"},K={class:"ml-3 relative"},P={class:"inline-flex rounded-md"},Q={type:"button",class:"inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"},W=t("svg",{class:"ml-2 -mr-0.5 h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor"},[t("path",{"fill-rule":"evenodd",d:"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z","clip-rule":"evenodd"})],-1),X={class:"-mr-2 flex items-center sm:hidden"},Y={class:"h-6 w-6",stroke:"currentColor",fill:"none",viewBox:"0 0 24 24"},Z={class:"pt-2 pb-3 space-y-1"},ee={class:"pt-4 pb-1 border-t border-gray-200"},te={class:"px-4"},se={class:"font-medium text-base text-gray-800"},re={class:"font-medium text-sm text-gray-500"},ae={class:"mt-3 space-y-1"},oe={key:0,class:"bg-white shadow"},ie={class:"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"},ne={class:"flex flex-row"},ue={class:"basis-1/2 flex flex-col justify-center"},de=t("div",{class:"basis-1/2"},[t("img",{src:"/asset/img/logo.jpg",alt:"karbala-alhassan",style:{"margin-right":"auto",width:"200px"}})],-1),pe={__name:"AuthenticatedLayout",setup(u){const a=R(!1);return(e,c)=>(r(),d("div",null,[t("div",z,[t("nav",F,[t("div",S,[t("div",V,[t("div",T,[i("",!0),t("div",q,[n(f,{href:e.route("dashboard"),active:e.route().current("dashboard")},{default:s(()=>[o(" \u0627\u0644\u0631\u0626\u064A\u0633\u064A\u0629 ")]),_:1},8,["href","active"])]),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==5?(r(),d("div",A,[n(f,{href:e.route("users.index"),active:e.route().current("users.index")},{default:s(()=>[o(" \u0627\u0644\u0645\u0646\u062F\u0648\u0628\u064A\u0646 ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id!=5?(r(),d("div",O,[n(f,{href:e.route("formRegistration"),active:e.route().current("formRegistration")},{default:s(()=>[o(" \u0627\u0644\u0639\u0642\u062F \u0627\u0644\u0625\u0644\u0643\u062A\u0631\u0648\u0646\u064A ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==5?(r(),d("div",U,[n(f,{href:e.route("FormRegistrationCourt"),active:e.route().current("FormRegistrationCourt")},{default:s(()=>[o(" \u062D\u0633\u0627\u0628\u0627\u062A \u0627\u0644\u0645\u0646\u062F\u0648\u0628\u064A\u0646 ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==5?(r(),d("div",G,[n(f,{href:e.route("accounting"),active:e.route().current("accounting")},{default:s(()=>[o(" \u0627\u0644\u0645\u062D\u0627\u0633\u0628\u0629 ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==6?(r(),d("div",H,[n(f,{href:e.route("hospital"),active:e.route().current("hospital")},{default:s(()=>[o(" \u0627\u0644\u062D\u062C\u0648\u0632\u0627\u062A ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==6||e.$page.props.auth.user.type_id==5?(r(),d("div",I,[n(f,{href:e.route("card"),active:e.route().current("card")},{default:s(()=>[o(" \u0639\u0631\u0636 \u0627\u0644\u0628\u0637\u0627\u0642\u0627\u062A ")]),_:1},8,["href","active"])])):i("",!0)]),t("div",J,[t("div",K,[n(D,{align:"right",width:"48"},{trigger:s(()=>[t("span",P,[t("button",Q,[o(k(e.$page.props.auth.user.name)+" ",1),W])])]),content:s(()=>[n(E,{href:e.route("logout"),method:"post",as:"button"},{default:s(()=>[o(" \u062A\u0633\u062C\u064A\u0644 \u0627\u0644\u062E\u0631\u0648\u062C ")]),_:1},8,["href"])]),_:1})])]),t("div",X,[t("button",{onClick:c[0]||(c[0]=b=>a.value=!a.value),class:"inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"},[(r(),d("svg",Y,[t("path",{class:g({hidden:a.value,"inline-flex":!a.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M4 6h16M4 12h16M4 18h16"},null,2),t("path",{class:g({hidden:!a.value,"inline-flex":a.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"},null,2)]))])])])]),t("div",{class:g([{block:a.value,hidden:!a.value},"sm:hidden"])},[t("div",Z,[n(l,{href:e.route("dashboard"),active:e.route().current("dashboard")},{default:s(()=>[o(" \u0644\u0648\u062D\u0629 \u0627\u0644\u0642\u064A\u0627\u062F\u0629 ")]),_:1},8,["href","active"])]),t("div",ee,[t("div",te,[t("div",se,k(e.$page.props.auth.user.name),1),t("div",re,k(e.$page.props.auth.user.email),1)]),t("div",ae,[n(l,{href:e.route("dashboard"),active:e.route().current("dashboard")},{default:s(()=>[o(" \u0627\u0644\u0631\u0626\u064A\u0633\u064A\u0629 ")]),_:1},8,["href","active"]),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==5?(r(),p(l,{key:0,href:e.route("users.index"),active:e.route().current("users.index")},{default:s(()=>[o(" \u0627\u0644\u0645\u0646\u062F\u0648\u0628\u064A\u0646 ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id!=5?(r(),p(l,{key:1,href:e.route("formRegistration"),active:e.route().current("formRegistration")},{default:s(()=>[o(" \u0627\u0644\u0639\u0642\u062F \u0627\u0644\u0625\u0644\u0643\u062A\u0631\u0648\u0646\u064A ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==5?(r(),p(l,{key:2,href:e.route("FormRegistrationCourt"),active:e.route().current("FormRegistrationCourt")},{default:s(()=>[o(" \u062D\u0633\u0627\u0628\u0627\u062A \u0627\u0644\u0645\u0646\u062F\u0648\u0628\u064A\u0646 ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==5?(r(),p(l,{key:3,href:e.route("accounting"),active:e.route().current("accounting")},{default:s(()=>[o(" \u0627\u0644\u0645\u062D\u0627\u0633\u0628\u0629 ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==6?(r(),p(l,{key:4,href:e.route("hospital"),active:e.route().current("hospital")},{default:s(()=>[o(" \u0627\u0644\u062D\u062C\u0648\u0632\u0627\u062A ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==6?(r(),p(l,{key:5,href:e.route("card"),active:e.route().current("card")},{default:s(()=>[o(" \u0639\u0631\u0636 \u0627\u0644\u0628\u0637\u0627\u0642\u0627\u062A ")]),_:1},8,["href","active"])):i("",!0),n(l,{href:e.route("logout"),method:"post",as:"button"},{default:s(()=>[o(" \u062A\u0633\u062C\u064A\u0644 \u0627\u0644\u062E\u0631\u0648\u062C ")]),_:1},8,["href"])])])],2)]),e.$slots.header?(r(),d("header",oe,[t("div",ie,[t("div",ne,[t("div",ue,[m(e.$slots,"header")]),de])])])):i("",!0),t("main",null,[m(e.$slots,"default")])])]))}};export{pe as _};
