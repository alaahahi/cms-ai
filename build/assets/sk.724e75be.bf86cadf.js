import{s as _}from"./Dashboard.0f543b4c.js";import"./AuthenticatedLayout.a2f1ad1f.js";import"./app.4998f7fa.js";function o(t){return t>1&&t<5&&~~(t/10)!==1}function n(t,e,s,r){var m=t+" ";switch(s){case"s":return e||r?"p\xE1r sek\xFAnd":"p\xE1r sekundami";case"m":return e?"min\xFAta":r?"min\xFAtu":"min\xFAtou";case"mm":return e||r?m+(o(t)?"min\xFAty":"min\xFAt"):m+"min\xFAtami";case"h":return e?"hodina":r?"hodinu":"hodinou";case"hh":return e||r?m+(o(t)?"hodiny":"hod\xEDn"):m+"hodinami";case"d":return e||r?"de\u0148":"d\u0148om";case"dd":return e||r?m+(o(t)?"dni":"dn\xED"):m+"d\u0148ami";case"M":return e||r?"mesiac":"mesiacom";case"MM":return e||r?m+(o(t)?"mesiace":"mesiacov"):m+"mesiacmi";case"y":return e||r?"rok":"rokom";case"yy":return e||r?m+(o(t)?"roky":"rokov"):m+"rokmi"}}var i={name:"sk",weekdays:"nede\u013Ea_pondelok_utorok_streda_\u0161tvrtok_piatok_sobota".split("_"),weekdaysShort:"ne_po_ut_st_\u0161t_pi_so".split("_"),weekdaysMin:"ne_po_ut_st_\u0161t_pi_so".split("_"),months:"janu\xE1r_febru\xE1r_marec_apr\xEDl_m\xE1j_j\xFAn_j\xFAl_august_september_okt\xF3ber_november_december".split("_"),monthsShort:"jan_feb_mar_apr_m\xE1j_j\xFAn_j\xFAl_aug_sep_okt_nov_dec".split("_"),weekStart:1,yearStart:4,ordinal:function(t){return t+"."},formats:{LT:"H:mm",LTS:"H:mm:ss",L:"DD.MM.YYYY",LL:"D. MMMM YYYY",LLL:"D. MMMM YYYY H:mm",LLLL:"dddd D. MMMM YYYY H:mm",l:"D. M. YYYY"},relativeTime:{future:"za %s",past:"pred %s",s:n,m:n,mm:n,h:n,hh:n,d:n,dd:n,M:n,MM:n,y:n,yy:n}};_.locale(i,null,!0);export{i as default};
