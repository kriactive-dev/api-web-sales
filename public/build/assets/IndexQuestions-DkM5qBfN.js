import{h as H,s as L}from"./moment-BiYybmdS.js";import{s as W,a as G}from"./index-Clbj2G5w.js";import{B as O,s as J,g as X,c as v,o as m,i as Z,f as j,r as ee,m as $,K as te,b as i,t as f,Q as ae,l as d,P as oe,M as ne,d as n,j as s,e as c,u as x,U as C,V as se,F as re,W as ie}from"./app-DeRpOroz.js";import{s as le,a as de}from"./index-9x5Dav86.js";import{s as ue}from"./index-BMU10jgj.js";import{s as ce}from"./index-Bq2npM-B.js";import{s as pe,a as z}from"./index-Bb_4tGAF.js";import{d as me}from"./debounce-BMmXVQ06.js";import"./index-DwC4l-jk.js";import"./index-k8S3BKyS.js";var ge=`
    .p-tag {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: dt('tag.primary.background');
        color: dt('tag.primary.color');
        font-size: dt('tag.font.size');
        font-weight: dt('tag.font.weight');
        padding: dt('tag.padding');
        border-radius: dt('tag.border.radius');
        gap: dt('tag.gap');
    }

    .p-tag-icon {
        font-size: dt('tag.icon.size');
        width: dt('tag.icon.size');
        height: dt('tag.icon.size');
    }

    .p-tag-rounded {
        border-radius: dt('tag.rounded.border.radius');
    }

    .p-tag-success {
        background: dt('tag.success.background');
        color: dt('tag.success.color');
    }

    .p-tag-info {
        background: dt('tag.info.background');
        color: dt('tag.info.color');
    }

    .p-tag-warn {
        background: dt('tag.warn.background');
        color: dt('tag.warn.color');
    }

    .p-tag-danger {
        background: dt('tag.danger.background');
        color: dt('tag.danger.color');
    }

    .p-tag-secondary {
        background: dt('tag.secondary.background');
        color: dt('tag.secondary.color');
    }

    .p-tag-contrast {
        background: dt('tag.contrast.background');
        color: dt('tag.contrast.color');
    }
`,fe={root:function(a){var r=a.props;return["p-tag p-component",{"p-tag-info":r.severity==="info","p-tag-success":r.severity==="success","p-tag-warn":r.severity==="warn","p-tag-danger":r.severity==="danger","p-tag-secondary":r.severity==="secondary","p-tag-contrast":r.severity==="contrast","p-tag-rounded":r.rounded}]},icon:"p-tag-icon",label:"p-tag-label"},ve=O.extend({name:"tag",style:ge,classes:fe}),ye={name:"BaseTag",extends:J,props:{value:null,severity:null,rounded:Boolean,icon:String},style:ve,provide:function(){return{$pcTag:this,$parentInstance:this}}};function y(e){"@babel/helpers - typeof";return y=typeof Symbol=="function"&&typeof Symbol.iterator=="symbol"?function(a){return typeof a}:function(a){return a&&typeof Symbol=="function"&&a.constructor===Symbol&&a!==Symbol.prototype?"symbol":typeof a},y(e)}function be(e,a,r){return(a=he(a))in e?Object.defineProperty(e,a,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[a]=r,e}function he(e){var a=we(e,"string");return y(a)=="symbol"?a:a+""}function we(e,a){if(y(e)!="object"||!e)return e;var r=e[Symbol.toPrimitive];if(r!==void 0){var u=r.call(e,a);if(y(u)!="object")return u;throw new TypeError("@@toPrimitive must return a primitive value.")}return(a==="string"?String:Number)(e)}var N={name:"Tag",extends:ye,inheritAttrs:!1,computed:{dataP:function(){return X(be({rounded:this.rounded},this.severity,this.severity))}}},ke=["data-p"];function _e(e,a,r,u,b,g){return m(),v("span",$({class:e.cx("root"),"data-p":g.dataP},e.ptmi("root")),[e.$slots.icon?(m(),Z(te(e.$slots.icon),$({key:0,class:e.cx("icon")},e.ptm("icon")),null,16,["class"])):e.icon?(m(),v("span",$({key:1,class:[e.cx("icon"),e.icon]},e.ptm("icon")),null,16)):j("",!0),e.value!=null||e.$slots.default?ee(e.$slots,"default",{key:2},function(){return[i("span",$({class:e.cx("label")},e.ptm("label")),f(e.value),17)]}):j("",!0)],16,ke)}N.render=_e;const $e={key:0,class:"flex flex-col md:flex-row gap-12 min-h-screen items-center justify-center"},xe={class:"w-full"},Pe={class:"flex flex-col gap-4 text-center"},Se={key:1,class:"flex flex-col md:flex-row gap-12"},De={class:"w-full"},Ce={class:"card flex flex-col gap-4"},Te={class:"flex justify-between"},Be=["onClick"],Ye={__name:"IndexQuestions",setup(e){const a=ie(),r=ae();d(null);const u=d(!0),b=d(!1);let g=d(null);const h=d(""),w=d(null),k=d(1),P=d(15),T=d(0),_=d(!1);function V(){a&&a.back()}const B=()=>{_.value=!1},q=l=>{_.value=!0,g.value=l},S=async(l=1)=>{z.get(`/api/questions?page=${l}`,{params:{query:h.value}}).then(t=>{w.value=t.data,T.value=t.data.total,u.value=!1}).catch(t=>{u.value=!1,r.add({severity:"error",summary:`${t}`,detail:"Message Detail",life:3e3}),V()})},M=()=>{b.value=!0,z.delete(`/api/questions/${g.value}`).then(()=>{w.value.data=w.value.data.filter(l=>l.id!==g.value),B(),r.add({severity:"success",summary:"Sucesso",detail:"Sucesso ao apagar",life:3e3})}).catch(l=>{r.add({severity:"error",summary:"Erro",detail:`${l}`,life:3e3}),b.value=!1}).finally(()=>{b.value=!1})},R=l=>{k.value=l.page+1,P.value=l.rows,S(k.value)},A=me(()=>{S(k.value)},300);return oe(h,A),ne(()=>{S()}),(l,t)=>{const F=pe,D=ce,U=de,Y=ue,E=le,p=W,I=N,K=G,Q=L;return m(),v(re,null,[u.value?(m(),v("div",$e,[i("div",xe,[i("div",Pe,[n(F,{style:{width:"50px",height:"50px"},strokeWidth:"8",fill:"var(--surface-ground)",animationDuration:".5s","aria-label":"Custom ProgressSpinner"}),t[2]||(t[2]=i("p",null,"Por Favor Aguarde...",-1))])])])):(m(),v("div",Se,[i("div",De,[i("div",Ce,[t[11]||(t[11]=i("div",{class:"font-semibold text-xl"},"Perguntas",-1)),n(K,{value:w.value.data,paginator:!0,rows:P.value,totalRecords:T.value,dataKey:"id",lazy:!0,rowHover:!0,loading:u.value,first:(k.value-1)*P.value,onPage:R,showGridlines:""},{header:s(()=>[i("div",Te,[n(x(C),{to:"/admin/questions/create"},{default:s(()=>[n(D,{label:"Voltar",class:"mr-2 mb-2"},{default:s(()=>[...t[3]||(t[3]=[c("Novo Registro",-1),i("i",{class:"pi pi-plus"},null,-1)])]),_:1})]),_:1}),n(E,null,{default:s(()=>[n(U,null,{default:s(()=>[...t[4]||(t[4]=[i("i",{class:"pi pi-search"},null,-1)])]),_:1}),n(Y,{modelValue:h.value,"onUpdate:modelValue":t[0]||(t[0]=o=>h.value=o),placeholder:"Pesquisa"},null,8,["modelValue"])]),_:1})])]),empty:s(()=>[...t[5]||(t[5]=[c("Nenhuma registro encontrado. ",-1)])]),loading:s(()=>[...t[6]||(t[6]=[c(" Carregando, por favor espere. ",-1)])]),default:s(()=>[n(p,{header:"ID",style:{"min-width":"12rem"}},{body:s(({data:o})=>[c(f(o.id),1)]),_:1}),n(p,{header:"Texto",style:{"min-width":"12rem"}},{body:s(({data:o})=>[c(f(o.text),1)]),_:1}),n(p,{header:"Tipo",style:{"min-width":"12rem"}},{body:s(({data:o})=>[c(f(o.type),1)]),_:1}),n(p,{header:"Início",style:{"min-width":"12rem"}},{body:s(({data:o})=>[n(I,{value:o.is_start?"Sim":"Não",severity:o.is_start?"success":"secondary"},null,8,["value","severity"])]),_:1}),n(p,{header:"Ativo",style:{"min-width":"12rem"}},{body:s(({data:o})=>[n(I,{value:o.active?"Ativo":"Inativo",severity:o.active?"success":"danger"},null,8,["value","severity"])]),_:1}),n(p,{header:"Data",dataType:"date",style:{"min-width":"10rem"}},{body:s(({data:o})=>[c(f(x(H)(o.created_at).format("DD-MM-YYYY H:mm")),1)]),_:1}),n(p,{header:"Ações",style:{"min-width":"12rem"}},{body:s(({data:o})=>[n(x(C),{class:"m-3",to:"/admin/questions/"+o.id+"/edit"},{default:s(()=>[...t[7]||(t[7]=[i("i",{class:"pi pi-file-edit"},null,-1)])]),_:2},1032,["to"]),t[10]||(t[10]=c()),n(x(C),{class:"m-3",to:"/admin/questions/"+o.id},{default:s(()=>[...t[8]||(t[8]=[i("i",{class:"pi pi-eye"},null,-1)])]),_:2},1032,["to"]),i("a",{class:"m-3",href:"#",onClick:se(Ie=>q(o.id),["prevent"])},[...t[9]||(t[9]=[i("i",{class:"pi pi-trash"},null,-1)])],8,Be)]),_:1})]),_:1},8,["value","rows","totalRecords","loading","first"])])])])),n(Q,{header:"Confirmação",visible:_.value,"onUpdate:visible":t[1]||(t[1]=o=>_.value=o),style:{width:"350px"},modal:!0},{footer:s(()=>[n(D,{label:"Não",icon:"pi pi-times",onClick:B,class:"p-button-text"}),n(D,{label:"Sim",icon:"pi pi-check",onClick:M,class:"p-button-text",autofocus:""})]),default:s(()=>[t[12]||(t[12]=i("div",{class:"flex align-items-center justify-content-center"},[i("i",{class:"pi pi-exclamation-triangle mr-3",style:{"font-size":"2rem"}}),i("span",null,"Tem certeza que deseja proceder?")],-1))]),_:1},8,["visible"])],64)}}};export{Ye as default};
