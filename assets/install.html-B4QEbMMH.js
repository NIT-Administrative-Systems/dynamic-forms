import{_ as o,r as i,o as l,c as r,a as n,b as e,d as t,e as s}from"./app-DYzx3xIL.js";const c={},p=s('<h1 id="installation" tabindex="-1"><a class="header-anchor" href="#installation"><span>Installation</span></a></h1><p>Dynamic Forms for Laravel is available via composer.</p><h2 id="prerequisites" tabindex="-1"><a class="header-anchor" href="#prerequisites"><span>Prerequisites</span></a></h2><p>You will need the following:</p><ul><li>PHP 8.2+</li><li>Laravel 11+</li><li>Bootstrap 5</li><li>FontAwesome 6</li><li>(optional) An Amazon S3 bucket &amp; access token, if you are going to handle file uploads via S3 for your dynamic forms</li></ul>',5),u={href:"https://laravel.com/docs/10.x/vite",target:"_blank",rel:"noopener noreferrer"},d=n("code",null,"resources/js",-1),h=s(`<p>The guide assumes you have switched from the Laravel default <code>resources/css/app.css</code> to <code>resources/sass/app.scss</code> and updated the <code>vite.config.js</code> file, plus any <code>@vite(&#39;resources/css/app.css&#39;)</code> references in the layout. This is necessary to bundle Bootstrap.</p><p>There is no Tailwind version at this time. This is driven by Formiojs&#39; support for different CSS frameworks. Bootstrap v3 through v5 and Semantic UI are the available options.</p><h2 id="installation-1" tabindex="-1"><a class="header-anchor" href="#installation-1"><span>Installation</span></a></h2><p>Install the package, run the installation command, and build your frontend assets:</p><div class="language-bash line-numbers-mode" data-ext="sh" data-title="sh"><pre class="language-bash"><code><span class="token function">composer</span> require northwestern-sysdev/dynamic-forms
php artisan dynamic-forms:install
<span class="token function">yarn</span> <span class="token function">install</span>
<span class="token function">yarn</span> run prod
</code></pre><div class="line-numbers" aria-hidden="true"><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div></div></div><p>If you are going to use S3 for file uploads, you will want to ensure you have configured your Laravel app with a bucket name and credentials. If you are deploying to Laravel Vapor, no additional config is needed for file uploads.</p><h2 id="post-installation-tasks" tabindex="-1"><a class="header-anchor" href="#post-installation-tasks"><span>Post-Installation Tasks</span></a></h2><h3 id="storage" tabindex="-1"><a class="header-anchor" href="#storage"><span>Storage</span></a></h3><p>The installation command creates <code>App\\Http\\Controllers\\DynamicFormsStorageController</code>. This controller is responsible for interactions from the form to backend storage providers such as Amazon S3 to upload &amp; download files.</p><p>Out of the box, this controller will deny all requests. You need to implement the <code>authorizeFileAction</code> method to check a gate or perform some other authorization check.</p><p>Depending on who will be uploading, you may also want to add the <code>auth</code> middleware to verify a user is logged in.</p><p>For file uploads, S3 and direct server uploads are both options available in the builder. You can set the env variable <code>VITE_STORAGE_DEFAULT_VALUE</code> to <code>s3</code> or <code>url</code> if you do not need to give people a choice.</p><h3 id="resources" tabindex="-1"><a class="header-anchor" href="#resources"><span>Resources</span></a></h3><p>The installation command creates <code>App\\Http\\Controllers\\DynamicFormsResourceController</code>. This controller is responsible for handling Resource Requests for Select components that utilize the Resource Source.</p><p>This controller presents Resources for any php files in <code>App\\Http\\Controllers\\Resources</code> that implements ResourceInterface.</p>`,15),m=n("code",null,"$context",-1),f=n("code",null,"ResourceInterface::submissions",-1),v={href:"https://help.form.io/developers/fetch-plugin-api#prerequest-requestargs",target:"_blank",rel:"noopener noreferrer"},k=n("code",null,"preRequest",-1),g=s(`<div class="language-javascript line-numbers-mode" data-ext="js" data-title="js"><pre class="language-javascript"><code>Formio<span class="token punctuation">.</span><span class="token function">registerPlugin</span><span class="token punctuation">(</span><span class="token punctuation">{</span>
    <span class="token function-variable function">preRequest</span><span class="token operator">:</span> <span class="token punctuation">(</span><span class="token parameter">requestArgs</span><span class="token punctuation">)</span> <span class="token operator">=&gt;</span> <span class="token punctuation">{</span>
        <span class="token keyword">const</span> exampleElement <span class="token operator">=</span> document<span class="token punctuation">.</span><span class="token function">querySelector</span><span class="token punctuation">(</span><span class="token string">&#39;#exampleElement&#39;</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
        <span class="token keyword">if</span> <span class="token punctuation">(</span>exampleElement<span class="token punctuation">)</span> <span class="token punctuation">{</span>
            requestArgs<span class="token punctuation">.</span>opts<span class="token punctuation">.</span>header<span class="token punctuation">.</span><span class="token function">set</span><span class="token punctuation">(</span><span class="token string">&#39;X-Foo-Bar&#39;</span><span class="token punctuation">,</span> exampleElement<span class="token punctuation">.</span>dataset<span class="token punctuation">.</span>fooBar<span class="token punctuation">)</span><span class="token punctuation">;</span>
        <span class="token punctuation">}</span>
    <span class="token punctuation">}</span>
<span class="token punctuation">}</span><span class="token punctuation">,</span> <span class="token string">&#39;exampleContext&#39;</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
</code></pre><div class="line-numbers" aria-hidden="true"><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div><div class="line-number"></div></div></div>`,1);function b(y,_){const a=i("ExternalLinkIcon");return l(),r("div",null,[p,n("p",null,[e("Dynamic Forms assumes that you are using "),n("a",u,[e("Laravel Vite"),t(a)]),e(". If you are not, you will need to transpile/minify the JavaScript that is installed into your "),d,e(" folder using your own build system.")]),h,n("p",null,[e("Request headers are made available through the "),m,e(" parameter in "),f,e(" if additional information is required to fetch the resources. To include information in the request header, a "),n("a",v,[e("Formio "),k,e(" plugin hook"),t(a)]),e(" can be configured. Provided below is an example of what that may look like.")]),g])}const x=o(c,[["render",b],["__file","install.html.vue"]]),S=JSON.parse('{"path":"/install.html","title":"Installation","lang":"en-US","frontmatter":{},"headers":[{"level":2,"title":"Prerequisites","slug":"prerequisites","link":"#prerequisites","children":[]},{"level":2,"title":"Installation","slug":"installation-1","link":"#installation-1","children":[]},{"level":2,"title":"Post-Installation Tasks","slug":"post-installation-tasks","link":"#post-installation-tasks","children":[{"level":3,"title":"Storage","slug":"storage","link":"#storage","children":[]},{"level":3,"title":"Resources","slug":"resources","link":"#resources","children":[]}]}],"git":{"updatedTime":1710856712000,"contributors":[{"name":"Nick Evans","email":"nick.evans@northwestern.edu","commits":1}]},"filePathRelative":"install.md","excerpt":"\\n<p>Dynamic Forms for Laravel is available via composer.</p>\\n<h2>Prerequisites</h2>\\n<p>You will need the following:</p>\\n<ul>\\n<li>PHP 8.2+</li>\\n<li>Laravel 11+</li>\\n<li>Bootstrap 5</li>\\n<li>FontAwesome 6</li>\\n<li>(optional) An Amazon S3 bucket &amp; access token, if you are going to handle file uploads via S3 for your dynamic forms</li>\\n</ul>"}');export{x as comp,S as data};
