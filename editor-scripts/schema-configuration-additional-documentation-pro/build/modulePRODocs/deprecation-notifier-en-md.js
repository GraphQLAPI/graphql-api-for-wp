(window.webpackJsonpGraphQLAPISchemaConfigurationAdditionalDocumentationPRO=window.webpackJsonpGraphQLAPISchemaConfigurationAdditionalDocumentationPRO||[]).push([[13],{58:function(s,n){s.exports='<h1 id="deprecation-notifier">Deprecation Notifier</h1> <p>Send deprecations in the response to the query (and not only when doing introspection), under the top-level entry <code>extensions</code></p> <h2 id="how-it-works">How it works</h2> <p>Deprecations are returned in the same query involving deprecated fields, and not only when doing introspection.</p> <p>For instance, running this query, where field <code>isPublished</code> is deprecated:</p> <pre><code class="hljs language-graphql"><span class="hljs"><span class="hljs-keyword">query</span> <span class="hljs-punctuation">{</span>\n  posts <span class="hljs-punctuation">{</span>\n    title\n    isPublished\n  <span class="hljs-punctuation">}</span>\n<span class="hljs-punctuation">}</span></span></code></pre> <p>...will produce this response:</p> <pre><code class="hljs language-json"><span class="hljs"><span class="hljs-punctuation">{</span>\n  <span class="hljs-attr">&quot;extensions&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">{</span>\n    <span class="hljs-attr">&quot;deprecations&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">[</span>\n      <span class="hljs-punctuation">{</span>\n        <span class="hljs-attr">&quot;message&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-string">&quot;Use &#x27;isStatus(status:published)&#x27; instead of &#x27;isPublished&#x27;&quot;</span><span class="hljs-punctuation">,</span>\n        <span class="hljs-attr">&quot;extensions&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">{</span>\n          ...\n        <span class="hljs-punctuation">}</span>\n      <span class="hljs-punctuation">}</span>\n    <span class="hljs-punctuation">]</span>\n  <span class="hljs-punctuation">}</span><span class="hljs-punctuation">,</span>\n  <span class="hljs-attr">&quot;data&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">{</span>\n    <span class="hljs-attr">&quot;posts&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">[</span>\n      ...\n    <span class="hljs-punctuation">]</span>\n  <span class="hljs-punctuation">}</span>\n<span class="hljs-punctuation">}</span></span></code></pre> '}}]);