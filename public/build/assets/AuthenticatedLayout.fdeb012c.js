import{j,k as M,l as $,r as L,o as r,c as u,d as t,m,f as C,n as R,a as n,w as s,q as g,b as v,T as N,s as h,L as w,e as i,x as a,t as k}from"./app.c7f723cc.js";const B={class:"relative"},D={__name:"Dropdown",props:{align:{default:"right"},width:{default:"48"},contentClasses:{default:()=>["py-1","bg-white"]}},setup(d){const o=d,e=b=>{f.value&&b.key==="Escape"&&(f.value=!1)};j(()=>document.addEventListener("keydown",e)),M(()=>document.removeEventListener("keydown",e));const c=$(()=>({48:"w-48"})[o.width.toString()]),_=$(()=>o.align==="left"?"origin-top-left left-0":o.align==="right"?"origin-top-right right-0":"origin-top"),f=L(!1);return(b,y)=>(r(),u("div",B,[t("div",{onClick:y[0]||(y[0]=x=>f.value=!f.value)},[m(b.$slots,"trigger")]),C(t("div",{class:"fixed inset-0 z-40",onClick:y[1]||(y[1]=x=>f.value=!1)},null,512),[[R,f.value]]),n(N,{"enter-active-class":"transition ease-out duration-200","enter-from-class":"transform opacity-0 scale-95","enter-to-class":"transform opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"transform opacity-100 scale-100","leave-to-class":"transform opacity-0 scale-95"},{default:s(()=>[C(t("div",{class:g(["absolute z-50 mt-2 rounded-md shadow-lg",[v(c),v(_)]]),style:{display:"none"},onClick:y[2]||(y[2]=x=>f.value=!1)},[t("div",{class:g(["rounded-md ring-1 ring-black ring-opacity-5",d.contentClasses])},[m(b.$slots,"content")],2)],2),[[R,f.value]])]),_:3})]))}},E={__name:"DropdownLink",setup(d){return(o,e)=>(r(),h(v(w),{class:"block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"},{default:s(()=>[m(o.$slots,"default")]),_:3}))}},l={__name:"NavLink",props:["href","active"],setup(d){const o=d,e=$(()=>o.active?"inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition  duration-150 ease-in-out":"inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out");return(c,_)=>(r(),h(v(w),{href:d.href,class:g(v(e))},{default:s(()=>[m(c.$slots,"default")]),_:3},8,["href","class"]))}},p={__name:"ResponsiveNavLink",props:["href","active"],setup(d){const o=d,e=$(()=>o.active?"block pl-3 pr-4 py-2 border-l-4 border-indigo-400 text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out":"block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out");return(c,_)=>(r(),h(v(w),{href:d.href,class:g(v(e))},{default:s(()=>[m(c.$slots,"default")]),_:3},8,["href","class"]))}},z={class:"min-h-screen bg-gray-100"},F={class:"bg-white border-b border-gray-100 print:hidden"},S={class:"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"},V={class:"flex justify-between h-16"},q={class:"flex"},T={class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},P={key:1,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},A={key:2,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},O={key:3,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},U={key:4,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},G={key:5,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},H={key:6,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},I={key:7,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},J={key:8,class:"hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"},K={class:"hidden sm:flex sm:items-center sm:ml-6"},Q={class:"ml-3 relative"},W={class:"inline-flex rounded-md"},X={type:"button",class:"inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"},Y=t("svg",{class:"ml-2 -mr-0.5 h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor"},[t("path",{"fill-rule":"evenodd",d:"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z","clip-rule":"evenodd"})],-1),Z={class:"-mr-2 flex items-center sm:hidden"},ee={class:"h-6 w-6",stroke:"currentColor",fill:"none",viewBox:"0 0 24 24"},te={class:"pt-2 pb-3 space-y-1"},se={class:"pt-4 pb-1 border-t border-gray-200"},re={class:"px-4"},ae={class:"font-medium text-base text-gray-800"},oe={class:"font-medium text-sm text-gray-500"},ie={class:"mt-3 space-y-1"},ne={key:0,class:"bg-white shadow"},ue={class:"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"},de={class:"flex flex-row"},le={class:"basis-1/2 flex flex-col justify-center"},pe=t("div",{class:"basis-1/2"},[t("img",{src:"/asset/img/logo.jpg",alt:"karbala-alhassan",style:{"margin-right":"auto",width:"200px"}})],-1),ce={__name:"AuthenticatedLayout",setup(d){const o=L(!1);return(e,c)=>(r(),u("div",null,[t("div",z,[t("nav",F,[t("div",S,[t("div",V,[t("div",q,[i("",!0),t("div",T,[n(l,{href:e.route("dashboard"),active:e.route().current("dashboard")},{default:s(()=>[a(" \u0627\u0644\u0631\u0626\u064A\u0633\u064A\u0629 ")]),_:1},8,["href","active"])]),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==5?(r(),u("div",P,[n(l,{href:e.route("users.index"),active:e.route().current("users.index")},{default:s(()=>[a(" \u0627\u0644\u0645\u0646\u062F\u0648\u0628\u064A\u0646 ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id!=5?(r(),u("div",A,[n(l,{href:e.route("formRegistration"),active:e.route().current("formRegistration")},{default:s(()=>[a(" \u0627\u0644\u0639\u0642\u062F \u0627\u0644\u0625\u0644\u0643\u062A\u0631\u0648\u0646\u064A ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==5?(r(),u("div",O,[n(l,{href:e.route("FormRegistrationCourt"),active:e.route().current("FormRegistrationCourt")},{default:s(()=>[a(" \u062D\u0633\u0627\u0628\u0627\u062A \u0627\u0644\u0645\u0646\u062F\u0648\u0628\u064A\u0646 ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==5?(r(),u("div",U,[n(l,{href:e.route("accounting"),active:e.route().current("accounting")},{default:s(()=>[a(" \u0627\u0644\u0645\u062D\u0627\u0633\u0628\u0629 ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==6?(r(),u("div",G,[n(l,{href:e.route("hospital"),active:e.route().current("hospital")},{default:s(()=>[a(" \u0627\u0644\u062D\u062C\u0648\u0632\u0627\u062A ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==6||e.$page.props.auth.user.type_id==5?(r(),u("div",H,[n(l,{href:e.route("card"),active:e.route().current("card")},{default:s(()=>[a(" \u0639\u0631\u0636 \u0627\u0644\u0628\u0637\u0627\u0642\u0627\u062A ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==6||e.$page.props.auth.user.type_id==5?(r(),u("div",I,[n(l,{href:e.route("PendingRequest"),active:e.route().current("PendingRequest")},{default:s(()=>[a(" \u0637\u0644\u0628\u0627\u062A \u0627\u0644\u0645\u0639\u0644\u0642\u0629 ")]),_:1},8,["href","active"])])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==6||e.$page.props.auth.user.type_id==5?(r(),u("div",J,[n(l,{href:e.route("settings"),active:e.route().current("settings")},{default:s(()=>[a(" \u0627\u0639\u062F\u0627\u062F\u0627\u062A \u0627\u0644\u062A\u0637\u0628\u064A\u0642 ")]),_:1},8,["href","active"])])):i("",!0)]),t("div",K,[t("div",Q,[n(D,{align:"right",width:"48"},{trigger:s(()=>[t("span",W,[t("button",X,[a(k(e.$page.props.auth.user.name)+" ",1),Y])])]),content:s(()=>[n(E,{href:e.route("logout"),method:"post",as:"button"},{default:s(()=>[a(" \u062A\u0633\u062C\u064A\u0644 \u0627\u0644\u062E\u0631\u0648\u062C ")]),_:1},8,["href"])]),_:1})])]),t("div",Z,[t("button",{onClick:c[0]||(c[0]=_=>o.value=!o.value),class:"inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"},[(r(),u("svg",ee,[t("path",{class:g({hidden:o.value,"inline-flex":!o.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M4 6h16M4 12h16M4 18h16"},null,2),t("path",{class:g({hidden:!o.value,"inline-flex":o.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"},null,2)]))])])])]),t("div",{class:g([{block:o.value,hidden:!o.value},"sm:hidden"])},[t("div",te,[n(p,{href:e.route("dashboard"),active:e.route().current("dashboard")},{default:s(()=>[a(" \u0644\u0648\u062D\u0629 \u0627\u0644\u0642\u064A\u0627\u062F\u0629 ")]),_:1},8,["href","active"])]),t("div",se,[t("div",re,[t("div",ae,k(e.$page.props.auth.user.name),1),t("div",oe,k(e.$page.props.auth.user.email),1)]),t("div",ie,[n(p,{href:e.route("dashboard"),active:e.route().current("dashboard")},{default:s(()=>[a(" \u0627\u0644\u0631\u0626\u064A\u0633\u064A\u0629 ")]),_:1},8,["href","active"]),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==5?(r(),h(p,{key:0,href:e.route("users.index"),active:e.route().current("users.index")},{default:s(()=>[a(" \u0627\u0644\u0645\u0646\u062F\u0648\u0628\u064A\u0646 ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id!=5?(r(),h(p,{key:1,href:e.route("formRegistration"),active:e.route().current("formRegistration")},{default:s(()=>[a(" \u0627\u0644\u0639\u0642\u062F \u0627\u0644\u0625\u0644\u0643\u062A\u0631\u0648\u0646\u064A ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==5?(r(),h(p,{key:2,href:e.route("FormRegistrationCourt"),active:e.route().current("FormRegistrationCourt")},{default:s(()=>[a(" \u062D\u0633\u0627\u0628\u0627\u062A \u0627\u0644\u0645\u0646\u062F\u0648\u0628\u064A\u0646 ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==5?(r(),h(p,{key:3,href:e.route("accounting"),active:e.route().current("accounting")},{default:s(()=>[a(" \u0627\u0644\u0645\u062D\u0627\u0633\u0628\u0629 ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==6?(r(),h(p,{key:4,href:e.route("hospital"),active:e.route().current("hospital")},{default:s(()=>[a(" \u0627\u0644\u062D\u062C\u0648\u0632\u0627\u062A ")]),_:1},8,["href","active"])):i("",!0),e.$page.props.auth.user.type_id==1||e.$page.props.auth.user.type_id==2||e.$page.props.auth.user.type_id==6?(r(),h(p,{key:5,href:e.route("card"),active:e.route().current("card")},{default:s(()=>[a(" \u0639\u0631\u0636 \u0627\u0644\u0628\u0637\u0627\u0642\u0627\u062A ")]),_:1},8,["href","active"])):i("",!0),n(p,{href:e.route("logout"),method:"post",as:"button"},{default:s(()=>[a(" \u062A\u0633\u062C\u064A\u0644 \u0627\u0644\u062E\u0631\u0648\u062C ")]),_:1},8,["href"])])])],2)]),e.$slots.header?(r(),u("header",ne,[t("div",ue,[t("div",de,[t("div",le,[m(e.$slots,"header")]),pe])])])):i("",!0),t("main",null,[m(e.$slots,"default")])])]))}};export{ce as _};
