(window.webpackJsonp=window.webpackJsonp||[]).push([[23],{306:function(t,s,a){"use strict";a.r(s);var e=a(14),n=Object(e.a)({},(function(){var t=this,s=t._self._c;return s("ContentSlotsDistributor",{attrs:{"slot-key":t.$parent.slotKey}},[s("h1",{attrs:{id:"extending"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#extending"}},[t._v("#")]),t._v(" Extending")]),t._v(" "),s("p",[t._v("There are several ways you can extend Dynamic Forms.")]),t._v(" "),s("h2",{attrs:{id:"adjusting-global-settings"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#adjusting-global-settings"}},[t._v("#")]),t._v(" Adjusting Global Settings")]),t._v(" "),s("p",[t._v("Dynamic Forms modifies the default settings for most components to disable unsupported features and hides a lot of technical options that end-users would not be able to use.")]),t._v(" "),s("p",[t._v("These customizations are achieved by hijacking the "),s("code",[t._v("Formio.builder()")]),t._v(" & "),s("code",[t._v("Formio.createForm()")]),t._v(" methods, applying the Dynamic Forms defaults, and then applying the "),s("code",[t._v("options")]),t._v(" parameter.")]),t._v(" "),s("p",[t._v("All of the defaults are set in "),s("code",[t._v("resources/js/formio/defaults.js")]),t._v(". There are two important parts of this file:")]),t._v(" "),s("ol",[s("li",[t._v("The "),s("code",[t._v("global")]),t._v(" key contains options that are applied to the builder config screens for every component")]),t._v(" "),s("li",[t._v("The "),s("code",[t._v("specificFields")]),t._v(" key contains options applied to one type of component")])]),t._v(" "),s("p",[t._v("You can adjust these settings, at your own risk. Some options are disabled because Dynamic Forms does not support validating them server-side.")]),t._v(" "),s("h2",{attrs:{id:"custom-components"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#custom-components"}},[t._v("#")]),t._v(" Custom Components")]),t._v(" "),s("p",[t._v("There are two ways you can add components to the frontend:")]),t._v(" "),s("ol",[s("li",[t._v("Customizing existing components for the form builder, so they are presented as new components to users")]),t._v(" "),s("li",[t._v("Writing your own component from scratch & registering it with Formiojs")])]),t._v(" "),s("h3",{attrs:{id:"customizing-existing-components"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#customizing-existing-components"}},[t._v("#")]),t._v(" Customizing Existing Components")]),t._v(" "),s("p",[t._v("Customizing an existing component can be something like giving your form builder users a Select component with a standard set of options used across your whole application. This component can have standard validation rules, labeling, and help text -- the developer is in control of how much or how little a user can configure it.")]),t._v(" "),s("p",[t._v("All of the work involved in making this custom component happens in JavaScript. Your Laravel application is not aware that a form is using one of these components. To continue the example above: when a user fills out that form and submits it, Dynamic Forms just sees a typical Select component with values and validation rules.")]),t._v(" "),s("p",[t._v("Examples of this approach can be found in the "),s("a",{attrs:{href:"https://formio.github.io/formio.js/app/examples/custombuilder.html",target:"_blank",rel:"noopener noreferrer"}},[t._v("Formiojs documentation's examples section"),s("OutboundLink")],1),t._v(".")]),t._v(" "),s("h3",{attrs:{id:"writing-components-from-scratch"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#writing-components-from-scratch"}},[t._v("#")]),t._v(" Writing Components from Scratch")]),t._v(" "),s("p",[t._v("Writing a component from scratch gives you complete control over your component's UI, builder config options, and behaviours.")]),t._v(" "),s("p",[t._v("There are two aspects to the custom component: the JavaScript to add your component to Formiojs, and a component in your Laravel application that tells Dynamic Forms how to validate submissions.")]),t._v(" "),s("p",[t._v("For examples of custom Formiojs components, see the "),s("a",{attrs:{href:"https://github.com/formio/contrib/tree/master/src/components",target:"_blank",rel:"noopener noreferrer"}},[t._v("formio/contrib repository"),s("OutboundLink")],1),t._v(", or look to "),s("a",{attrs:{href:"https://github.com/formio/formio.js/tree/master/src/components",target:"_blank",rel:"noopener noreferrer"}},[t._v("the Formiojs components"),s("OutboundLink")],1),t._v(" for examples.")]),t._v(" "),s("h3",{attrs:{id:"registering-with-formiojs"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#registering-with-formiojs"}},[t._v("#")]),t._v(" Registering with Formiojs")]),t._v(" "),s("p",[t._v("You need to tell Formiojs about your component before it will show up in the builder.")]),t._v(" "),s("p",[t._v("To do this, edit your "),s("code",[t._v("resources/js/formio/index.js")]),t._v(" file. You need to import the component & edit form, then locate the comment that says '"),s("em",[t._v("If you want to load custom code")]),t._v("' and perform the registration.")]),t._v(" "),s("div",{staticClass:"language-js extra-class"},[s("pre",{pre:!0,attrs:{class:"language-js"}},[s("code",[s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// . . .")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("import")]),t._v(" DirectorySearch "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("from")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"../directory-search"')]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("import")]),t._v(" DirectoryEditForm "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("from")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"../directory-search/form"')]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// . . .")]),t._v("\n\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// -------------------------------------------------------------------------")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// If you want to load custom code (like additional components), do it here!")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// -------------------------------------------------------------------------")]),t._v("\nFormio"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),s("span",{pre:!0,attrs:{class:"token function"}},[t._v("use")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),t._v("DirectorySearch"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\nFormio"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("Components"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("components"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("directorySearch"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("editForm "),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=")]),t._v(" DirectoryEditForm"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// . . .")]),t._v("\n")])])]),s("h4",{attrs:{id:"registering-server-side"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#registering-server-side"}},[t._v("#")]),t._v(" Registering Server-side")]),t._v(" "),s("p",[t._v("Dynamic Form comes with support for most of the components that Formiojs supports.")]),t._v(" "),s("p",[t._v("If you want to implement a missing component in the server-side validations, or if you've written your own Formiojs component, you need to register it with the component registry. The best place to register components is in a service provider's boot method.")]),t._v(" "),s("p",[t._v("Here is an example of creating and registering a layout component for tabs.")]),t._v(" "),s("div",{staticClass:"language-php extra-class"},[s("pre",{pre:!0,attrs:{class:"language-php"}},[s("code",[s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// app\\Tabs.php")]),t._v("\n\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("namespace")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token package"}},[t._v("App")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("use")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token package"}},[t._v("Northwestern"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("SysDev"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("DynamicForms"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("Components"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("ComponentInterface")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("use")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token package"}},[t._v("Northwestern"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("SysDev"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("DynamicForms"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("Components"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("BaseComponent")]),t._v("\n\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// You can extend BaseComponent for a lot of functionality, or implement ")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// the ComponentInterface if you need to go totally outside the box.")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("class")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token class-name-definition class-name"}},[t._v("Tabs")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("extends")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token class-name"}},[t._v("BaseComponent")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("{")]),t._v("\n    "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("const")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token constant"}},[t._v("TYPE")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string single-quoted-string"}},[t._v("'tabs'")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n    \n    "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("public")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("function")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token function-definition function"}},[t._v("canValidate")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token keyword return-type"}},[t._v("bool")]),t._v("\n    "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("{")]),t._v("\n        "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("return")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token constant boolean"}},[t._v("false")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n    "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("}")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("}")]),t._v("\n")])])]),s("div",{staticClass:"language-php extra-class"},[s("pre",{pre:!0,attrs:{class:"language-php"}},[s("code",[s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// app\\Providers\\AppServiceProvider.php")]),t._v("\n\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("namespace")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token package"}},[t._v("App"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("Providers")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("use")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token package"}},[t._v("App"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("Tabs")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("use")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token package"}},[t._v("Illuminate"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("Support"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("ServiceProvider")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("use")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token package"}},[t._v("Northwestern"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("SysDev"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("DynamicForms"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("ComponentRegistry")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("class")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token class-name-definition class-name"}},[t._v("AppServiceProvider")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("extends")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token class-name"}},[t._v("ServiceProvider")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("{")]),t._v("\n    "),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// . . .")]),t._v("\n    \n    "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("public")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("function")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token function-definition function"}},[t._v("boot")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),t._v("\n    "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("{")]),t._v("\n        "),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// . . .")]),t._v("\n        \n        "),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("/** @var ComponentRegistry $registry */")]),t._v("\n        "),s("span",{pre:!0,attrs:{class:"token variable"}},[t._v("$registry")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token variable"}},[t._v("$this")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("->")]),s("span",{pre:!0,attrs:{class:"token property"}},[t._v("app")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("->")]),s("span",{pre:!0,attrs:{class:"token function"}},[t._v("make")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),s("span",{pre:!0,attrs:{class:"token class-name static-context"}},[t._v("ComponentRegistry")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("::")]),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("class")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n        "),s("span",{pre:!0,attrs:{class:"token variable"}},[t._v("$registry")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("->")]),s("span",{pre:!0,attrs:{class:"token function"}},[t._v("register")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),s("span",{pre:!0,attrs:{class:"token class-name static-context"}},[t._v("Tabs")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("::")]),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("class")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n    "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("}")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("}")]),t._v("\n")])])]),s("h2",{attrs:{id:"adding-storage-backends"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#adding-storage-backends"}},[t._v("#")]),t._v(" Adding Storage backends")]),t._v(" "),s("p",[t._v("In order to add a storage backend you must create a driver that implements StorageInterface for the backend.")]),t._v(" "),s("p",[t._v("Form.js supports base64, dropbox, azure, indexeddb, which are all not currently implemented.")]),t._v(" "),s("h3",{attrs:{id:"writing-new-backend"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#writing-new-backend"}},[t._v("#")]),t._v(" Writing new backend")]),t._v(" "),s("p",[t._v("There are two aspects to the new backend component: the controller to handle the requests, and a StorageInterface driver in your Laravel application that tells Dynamic Forms how to validate submissions.")]),t._v(" "),s("h3",{attrs:{id:"registering-with-formiojs-2"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#registering-with-formiojs-2"}},[t._v("#")]),t._v(" Registering with Formiojs")]),t._v(" "),s("p",[t._v("You need to tell Formiojs about your component before it will be usable by the application.")]),t._v(" "),s("p",[t._v("To do this, edit your "),s("code",[t._v("resources/js/formio/defaults.js")]),t._v(" file and add your backend to globalFileCustomization")]),t._v(" "),s("div",{staticClass:"language-js extra-class"},[s("pre",{pre:!0,attrs:{class:"language-js"}},[s("code",[t._v("    "),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("/**\n     * Builder dropdown values cannot be modified by overriding defaults.\n     *\n     * This modifies the File editForm directly & globally, which seems to be\n     * the only approach that works.\n     *\n     * It also modifies the behaviour of the 'saveState' additional field, state,\n     * which was not possible from the overrides either.\n     */")]),t._v("\n    "),s("span",{pre:!0,attrs:{class:"token function-variable function"}},[t._v("globalFileCustomization")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=>")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("{")]),t._v("\n        "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("var")]),t._v(" editForm "),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=")]),t._v(" Formio"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("Components"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("components"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("file"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),s("span",{pre:!0,attrs:{class:"token function"}},[t._v("editForm")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n        Formio"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("Utils"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),s("span",{pre:!0,attrs:{class:"token function"}},[t._v("getComponent")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),t._v("editForm"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("components"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(",")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v("'storage'")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("data"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("values "),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("[")]),t._v("\n            "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("{")]),s("span",{pre:!0,attrs:{class:"token literal-property property"}},[t._v("label")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"S3"')]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(",")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token literal-property property"}},[t._v("value")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"s3"')]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("}")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(",")]),t._v("\n            "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("{")]),s("span",{pre:!0,attrs:{class:"token literal-property property"}},[t._v("label")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"Local"')]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(",")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token literal-property property"}},[t._v("value")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v(":")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token string"}},[t._v('"url"')]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("}")]),t._v("\n            "),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("//INSERT NEW BACKEND HERE")]),t._v("\n        "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("]")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n        Formio"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("Components"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("components"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),t._v("file"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(".")]),s("span",{pre:!0,attrs:{class:"token function-variable function"}},[t._v("editForm")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("function")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("{")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("return")]),t._v(" editForm"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("}")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n    "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("}")]),t._v("\n")])])]),s("h4",{attrs:{id:"registering-server-side-2"}},[s("a",{staticClass:"header-anchor",attrs:{href:"#registering-server-side-2"}},[t._v("#")]),t._v(" Registering Server-side")]),t._v(" "),s("p",[t._v("Dynamic Form comes with support for S3 and local storage.")]),t._v(" "),s("p",[t._v("If you want to implement an additional backend you need to register it with the File component registry. The best place to register components is in a service provider's boot method.")]),t._v(" "),s("p",[t._v("Here is an example of creating and registering a layout component for tabs.")]),t._v(" "),s("div",{staticClass:"language-php extra-class"},[s("pre",{pre:!0,attrs:{class:"language-php"}},[s("code",[s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// app\\Providers\\AppServiceProvider.php")]),t._v("\n\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("namespace")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token package"}},[t._v("App"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("Providers")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("use")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token package"}},[t._v("App"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("DropboxDriver")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("use")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token package"}},[t._v("Illuminate"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("Support"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("ServiceProvider")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("use")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token package"}},[t._v("Northwestern"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("SysDev"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("DynamicForms"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("\\")]),t._v("FileComponentRegistry")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n"),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("class")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token class-name-definition class-name"}},[t._v("AppServiceProvider")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("extends")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token class-name"}},[t._v("ServiceProvider")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("{")]),t._v("\n    "),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// . . .")]),t._v("\n    \n    "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("public")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("function")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token function-definition function"}},[t._v("boot")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),t._v("\n    "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("{")]),t._v("\n        "),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("// . . .")]),t._v("\n        \n        "),s("span",{pre:!0,attrs:{class:"token comment"}},[t._v("/** @var ComponentRegistry $registry */")]),t._v("\n        "),s("span",{pre:!0,attrs:{class:"token variable"}},[t._v("$registry")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("=")]),t._v(" "),s("span",{pre:!0,attrs:{class:"token variable"}},[t._v("$this")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("->")]),s("span",{pre:!0,attrs:{class:"token property"}},[t._v("app")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("->")]),s("span",{pre:!0,attrs:{class:"token function"}},[t._v("make")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),s("span",{pre:!0,attrs:{class:"token class-name static-context"}},[t._v("FileComponentRegistry")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("::")]),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("class")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n\n        "),s("span",{pre:!0,attrs:{class:"token variable"}},[t._v("$registry")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("->")]),s("span",{pre:!0,attrs:{class:"token function"}},[t._v("register")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("(")]),s("span",{pre:!0,attrs:{class:"token class-name static-context"}},[t._v("DropboxDriver")]),s("span",{pre:!0,attrs:{class:"token operator"}},[t._v("::")]),s("span",{pre:!0,attrs:{class:"token keyword"}},[t._v("class")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(")")]),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v(";")]),t._v("\n    "),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("}")]),t._v("\n"),s("span",{pre:!0,attrs:{class:"token punctuation"}},[t._v("}")]),t._v("\n")])])])])}),[],!1,null,null,null);s.default=n.exports}}]);