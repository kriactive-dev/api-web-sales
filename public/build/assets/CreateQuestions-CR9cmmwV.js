import{a as U,s as H}from"./index-C59SAmHv.js";import{s as N}from"./index-DvQlKDmf.js";import{s as j}from"./index-DPZSgaxd.js";import{B as q,c as T,o as S,m as B,g as A,Q as D,l as F,W as O,b as o,d,j as L,e as Q,u as i,i as W,f as J,X as v,t as w,J as K}from"./app-ByZHPx0Q.js";import{a as X}from"./index-W5cJFRll.js";import{s as G}from"./index-BpSXXyfU.js";import{c as M,a as P,b as _,u as Y}from"./index.esm-uj6ElvHu.js";import"./index-CA68Oovn.js";var Z=`
    .p-textarea {
        font-family: inherit;
        font-feature-settings: inherit;
        font-size: 1rem;
        color: dt('textarea.color');
        background: dt('textarea.background');
        padding-block: dt('textarea.padding.y');
        padding-inline: dt('textarea.padding.x');
        border: 1px solid dt('textarea.border.color');
        transition:
            background dt('textarea.transition.duration'),
            color dt('textarea.transition.duration'),
            border-color dt('textarea.transition.duration'),
            outline-color dt('textarea.transition.duration'),
            box-shadow dt('textarea.transition.duration');
        appearance: none;
        border-radius: dt('textarea.border.radius');
        outline-color: transparent;
        box-shadow: dt('textarea.shadow');
    }

    .p-textarea:enabled:hover {
        border-color: dt('textarea.hover.border.color');
    }

    .p-textarea:enabled:focus {
        border-color: dt('textarea.focus.border.color');
        box-shadow: dt('textarea.focus.ring.shadow');
        outline: dt('textarea.focus.ring.width') dt('textarea.focus.ring.style') dt('textarea.focus.ring.color');
        outline-offset: dt('textarea.focus.ring.offset');
    }

    .p-textarea.p-invalid {
        border-color: dt('textarea.invalid.border.color');
    }

    .p-textarea.p-variant-filled {
        background: dt('textarea.filled.background');
    }

    .p-textarea.p-variant-filled:enabled:hover {
        background: dt('textarea.filled.hover.background');
    }

    .p-textarea.p-variant-filled:enabled:focus {
        background: dt('textarea.filled.focus.background');
    }

    .p-textarea:disabled {
        opacity: 1;
        background: dt('textarea.disabled.background');
        color: dt('textarea.disabled.color');
    }

    .p-textarea::placeholder {
        color: dt('textarea.placeholder.color');
    }

    .p-textarea.p-invalid::placeholder {
        color: dt('textarea.invalid.placeholder.color');
    }

    .p-textarea-fluid {
        width: 100%;
    }

    .p-textarea-resizable {
        overflow: hidden;
        resize: none;
    }

    .p-textarea-sm {
        font-size: dt('textarea.sm.font.size');
        padding-block: dt('textarea.sm.padding.y');
        padding-inline: dt('textarea.sm.padding.x');
    }

    .p-textarea-lg {
        font-size: dt('textarea.lg.font.size');
        padding-block: dt('textarea.lg.padding.y');
        padding-inline: dt('textarea.lg.padding.x');
    }
`,ee={root:function(e){var r=e.instance,n=e.props;return["p-textarea p-component",{"p-filled":r.$filled,"p-textarea-resizable ":n.autoResize,"p-textarea-sm p-inputfield-sm":n.size==="small","p-textarea-lg p-inputfield-lg":n.size==="large","p-invalid":r.$invalid,"p-variant-filled":r.$variant==="filled","p-textarea-fluid":r.$fluid}]}},te=q.extend({name:"textarea",style:Z,classes:ee}),ae={name:"BaseTextarea",extends:X,props:{autoResize:Boolean},style:te,provide:function(){return{$pcTextarea:this,$parentInstance:this}}};function c(t){"@babel/helpers - typeof";return c=typeof Symbol=="function"&&typeof Symbol.iterator=="symbol"?function(e){return typeof e}:function(e){return e&&typeof Symbol=="function"&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},c(t)}function re(t,e,r){return(e=ne(e))in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}function ne(t){var e=oe(t,"string");return c(e)=="symbol"?e:e+""}function oe(t,e){if(c(t)!="object"||!t)return t;var r=t[Symbol.toPrimitive];if(r!==void 0){var n=r.call(t,e);if(c(n)!="object")return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return(e==="string"?String:Number)(t)}var I={name:"Textarea",extends:ae,inheritAttrs:!1,observer:null,mounted:function(){var e=this;this.autoResize&&(this.observer=new ResizeObserver(function(){requestAnimationFrame(function(){e.resize()})}),this.observer.observe(this.$el))},updated:function(){this.autoResize&&this.resize()},beforeUnmount:function(){this.observer&&this.observer.disconnect()},methods:{resize:function(){if(this.$el.offsetParent){var e=this.$el.style.height,r=parseInt(e)||0,n=this.$el.scrollHeight,f=!r||n>r,l=r&&n<r;l?(this.$el.style.height="auto",this.$el.style.height="".concat(this.$el.scrollHeight,"px")):f&&(this.$el.style.height="".concat(n,"px"))}},onInput:function(e){this.autoResize&&this.resize(),this.writeValue(e.target.value,e)}},computed:{attrs:function(){return B(this.ptmi("root",{context:{filled:this.$filled,disabled:this.disabled}}),this.formField)},dataP:function(){return A(re({invalid:this.$invalid,fluid:this.$fluid,filled:this.$variant==="filled"},this.size,this.size))}}},ie=["value","name","disabled","aria-invalid","data-p"];function se(t,e,r,n,f,l){return S(),T("textarea",B({class:t.cx("root"),value:t.d_value,name:t.name,disabled:t.disabled,"aria-invalid":t.invalid||void 0,"data-p":l.dataP,onInput:e[0]||(e[0]=function(){return l.onInput&&l.onInput.apply(l,arguments)})},l.attrs),null,16,ie)}I.render=se;const le={class:"card flex flex-col gap-4"},de={class:"w-full"},ue={class:"flex flex-col gap-2"},pe={class:"p-error"},ce={class:"flex flex-col gap-2"},fe={class:"p-error"},me={class:"flex items-center gap-2"},be={class:"flex items-center gap-2"},Se={__name:"CreateQuestions",setup(t){const e=O(),r=D(),n=F(!1),f=[{label:"Texto",value:"text"},{label:"Botão",value:"button"}],l=M({text:_().required().trim().label("Texto da Pergunta"),type:_().required().label("Tipo"),is_start:P().label("Pergunta Inicial"),active:P().label("Ativa")}),{defineField:m,handleSubmit:C,errors:g,setErrors:R}=Y({validationSchema:l}),[h]=m("text"),[y]=m("type"),[$]=m("is_start"),[k]=m("active"),z=C(V=>{n.value=!0,U.post("/api/questions",V).then(()=>{r.add({severity:"success",summary:"Sucesso",detail:"Pergunta criada com sucesso",life:3e3}),e.back()}).catch(a=>{var u,b,x,p;n.value=!1,r.add({severity:"error",summary:"Erro",detail:((b=(u=a.response)==null?void 0:u.data)==null?void 0:b.message)||"Erro ao criar",life:3e3}),(p=(x=a.response)==null?void 0:x.data)!=null&&p.errors&&R(a.response.data.errors)}).finally(()=>{n.value=!1})});return(V,a)=>{const u=G,b=I,x=j,p=N,E=H;return S(),T("div",le,[o("div",de,[d(u,{label:"Voltar",class:"mr-2 mb-2",onClick:a[0]||(a[0]=s=>i(e).back())},{default:L(()=>[...a[6]||(a[6]=[o("i",{class:"pi pi-angle-left"},null,-1),Q(" Voltar",-1)])]),_:1})]),a[11]||(a[11]=o("div",{class:"font-semibold text-xl"},"Adicionar Pergunta",-1)),a[12]||(a[12]=o("small",{class:"p-error"},"Os campos marcados * são obrigatórios.",-1)),o("form",{onSubmit:a[5]||(a[5]=(...s)=>i(z)&&i(z)(...s))},[o("div",ue,[a[7]||(a[7]=o("label",{for:"text"},"Texto da Pergunta *",-1)),d(b,{modelValue:i(h),"onUpdate:modelValue":a[1]||(a[1]=s=>v(h)?h.value=s:null),rows:"5",cols:"50",placeholder:"Digite a pergunta"},null,8,["modelValue"]),o("small",pe,w(i(g).text),1)]),o("div",ce,[a[8]||(a[8]=o("label",{for:"type"},"Tipo *",-1)),d(x,{modelValue:i(y),"onUpdate:modelValue":a[2]||(a[2]=s=>v(y)?y.value=s:null),id:"type",options:f,optionLabel:"label",optionValue:"value",placeholder:"Selecione o tipo",class:K({"p-invalid":i(g).type})},null,8,["modelValue","class"]),o("small",fe,w(i(g).type),1)]),o("div",me,[d(p,{modelValue:i($),"onUpdate:modelValue":a[3]||(a[3]=s=>v($)?$.value=s:null),binary:!0,inputId:"is_start"},null,8,["modelValue"]),a[9]||(a[9]=o("label",{for:"is_start"},"Esta é a pergunta inicial?",-1))]),o("div",be,[d(p,{modelValue:i(k),"onUpdate:modelValue":a[4]||(a[4]=s=>v(k)?k.value=s:null),binary:!0,inputId:"active"},null,8,["modelValue"]),a[10]||(a[10]=o("label",{for:"active"},"Pergunta ativa?",-1))]),d(u,{label:"Submeter",class:"mt-2",disabled:n.value,onClick:i(z)},null,8,["disabled","onClick"]),n.value?(S(),W(E,{key:0,style:{width:"35px",height:"35px"},strokeWidth:"8",fill:"var(--surface-ground)",animationDuration:".5s","aria-label":"Salvando..."})):J("",!0)],32)])}}};export{Se as default};
