import{_ as n,r,o as l,c as d,a as e,b as o,d as t,w as c,e as a}from"./app-CK-8UmnJ.js";const p="/dynamic-forms/builder_demo.webm",m="/dynamic-forms/form_demo.webm",u={},h=a('<h1 id="dynamic-forms-for-laravel" tabindex="-1"><a class="header-anchor" href="#dynamic-forms-for-laravel"><span>Dynamic Forms for Laravel</span></a></h1><p>User-defined forms are a perennial problem for developers.</p><p>Dynamic Forms for Laravel gives you an easy solution: a drag-and-drop builder, an easy way to display the forms, and back-end validation to ensure the submitted data is good.</p><p>If you want to see it in action, here are some demo videos:</p><details class="custom-container details"><summary>Creating a form in the builder</summary><video controls="controls" preload="none" width="100%"><source src="'+p+'" type="video/webm"></video></details><details class="custom-container details"><summary>Filling out a dynamic form</summary><video controls="controls" preload="none" width="100%"><source src="'+m+'" type="video/webm"></video></details><h2 id="how-does-this-work" tabindex="-1"><a class="header-anchor" href="#how-does-this-work"><span>How does this work?</span></a></h2>',7),f={href:"https://github.com/formio/formio.js",target:"_blank",rel:"noopener noreferrer"},v=e("code",null,"$request->validateDynamicForm()",-1),g={href:"https://laravel.com/docs/8.x/validation#quick-writing-the-validation-logic",target:"_blank",rel:"noopener noreferrer"},b=e("code",null,"validate",-1),y=a('<p>You <strong>do not</strong> need to use the Form.io SaaS platform. Your Laravel application is filling that role.</p><h2 id="concepts" tabindex="-1"><a class="header-anchor" href="#concepts"><span>Concepts</span></a></h2><p>There are a couple pieces to be aware of:</p><ul><li>The <strong>form builder</strong> is a WYSIWYG editor that people use to create forms.</li><li><strong>Form definitions</strong> are created &amp; updated by the form builder. Definitions are JSON documents that describe a form&#39;s fields, validation logic, help text, etc.</li><li><strong>Components</strong> are individual form fields. Formiojs comes with a wide variety of components, from basic <code>&lt;input type=&#39;text&gt;</code> fields to rich WYSIWYG textareas and API-backed address lookups. You can reconfigure existing components to create new ones, or write your own from scratch.</li><li><strong>Forms</strong> are shown when you render a form definition. The form can be read-write or read-only (to display a submitted form). Forms produce submissions in the form of key:value JSON documents.</li></ul><h2 id="supported-features" tabindex="-1"><a class="header-anchor" href="#supported-features"><span>Supported Features</span></a></h2><p>Formiojs offers a lot of functionality. Dynamic Forms for Laravel has implemented a limited subset of all its available features.</p><p>Most of the decisions not to include something were driven by what would give us a good minimum viable product. If there are missing features that you would like to see, please feel free to submit an issue to discuss including it.</p><h3 id="components" tabindex="-1"><a class="header-anchor" href="#components"><span>Components</span></a></h3><p>Most of the Formiojs components are supported in some configuration. These components have limitations:</p>',9),w=e("li",null,"Address only supports Open Street Maps",-1),_=e("li",null,"Select only supports values, and not API-backed resources",-1),k=a('<p>These components are not supported at all:</p><ul><li>HTML Element</li><li>Tags</li><li>Data Map</li><li>Data Grid</li><li>Edit Grid</li><li>Tree</li><li>Tabs</li><li>ReCAPTCHA</li><li>Nested Form</li></ul><h3 id="scripting" tabindex="-1"><a class="header-anchor" href="#scripting"><span>Scripting</span></a></h3><p>Formiojs offers several methods for creating dependencies between form fields and calculating values: simple UI-driven setups, JSON Logic, and custom JavaScript.</p><p>Dynamic Forms for Laravel supports the simple UI-driven dependencies and JSON Logic.</p><p>No JS eval is supported.</p><h3 id="other-features" tabindex="-1"><a class="header-anchor" href="#other-features"><span>Other Features</span></a></h3><p>PDF forms and form wizards are not supported.</p>',8);function x(F,S){const s=r("ExternalLinkIcon"),i=r("RouteLink");return l(),d("div",null,[h,e("p",null,[o("The front-end is powered by the open source "),e("a",f,[o("Form.io"),t(s)]),o(" JavaScript library. This is an awesome library: the builder is user-friendly, you can adjust what's offered, and add your own custom form fields.")]),e("p",null,[o("On the backend, it's as simple as calling "),v,o(". It behaves just like the "),e("a",g,[b,t(s)]),o(" method you're used to in Laravel, giving you valid data you can trust.")]),y,e("ul",null,[w,e("li",null,[o("File only supports Amazon S3 and local storage (base64, dropbox, azure, and indexeddb support can be "),t(i,{to:"/extending.html#adding-storage-backends"},{default:c(()=>[o("added")]),_:1}),o(")")]),_]),k])}const T=n(u,[["render",x],["__file","index.html.vue"]]),I=JSON.parse('{"path":"/","title":"Dynamic Forms for Laravel","lang":"en-US","frontmatter":{},"headers":[{"level":2,"title":"How does this work?","slug":"how-does-this-work","link":"#how-does-this-work","children":[]},{"level":2,"title":"Concepts","slug":"concepts","link":"#concepts","children":[]},{"level":2,"title":"Supported Features","slug":"supported-features","link":"#supported-features","children":[{"level":3,"title":"Components","slug":"components","link":"#components","children":[]},{"level":3,"title":"Scripting","slug":"scripting","link":"#scripting","children":[]},{"level":3,"title":"Other Features","slug":"other-features","link":"#other-features","children":[]}]}],"git":{"updatedTime":1711640043000,"contributors":[{"name":"Nick Evans","email":"nick.evans@northwestern.edu","commits":1}]},"filePathRelative":"index.md","excerpt":"\\n<p>User-defined forms are a perennial problem for developers.</p>\\n<p>Dynamic Forms for Laravel gives you an easy solution: a drag-and-drop builder, an easy way to display the forms, and back-end validation to ensure the submitted data is good.</p>\\n<p>If you want to see it in action, here are some demo videos:</p>"}');export{T as comp,I as data};