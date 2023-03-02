(window.webpackJsonpGraphQLAPISchemaConfigurationAdditionalDocumentationPRO=window.webpackJsonpGraphQLAPISchemaConfigurationAdditionalDocumentationPRO||[]).push([[27],{72:function(s,e){s.exports='<h1 id="remove-output-directive">Remove Output Directive</h1> <p>Add directive <code>@remove</code> to remove the output of a field from the response.</p> <h2 id="description">Description</h2> <p>The GraphQL spec indicates that the GraphQL response needs to match exactly the shape of the query. However, in certain circumstances we&#39;d rather avoid sending back the response of the field, because:</p> <ul> <li>It is of no value</li> <li>We already know what it is</li> <li>An empty field can be distinguished from a <code>null</code> value</li> </ul> <p>For instance, let&#39;s say we want to retrieve some specific data from an external REST API endpoint, and we don&#39;t need the rest of the data. Then, we can use field <code>_requestJSONObjectItem</code> (from the <strong>HTTP Request Fields</strong> module) to connect to the REST API, process this data to extract the needed piece of information (via <strong>Field to Input</strong> and the <code>_objectProperty</code> field from <strong>Function Fields</strong>), and finally <code>@remove</code> the original data from the REST endpoint, which is of no use to us:</p> <pre><code class="hljs language-graphql"><span class="hljs"><span class="hljs-punctuation">{</span>\n  <span class="hljs-symbol">postData</span><span class="hljs-punctuation">:</span> _requestJSONObjectItem<span class="hljs-punctuation">(</span>\n    <span class="hljs-symbol">url</span><span class="hljs-punctuation">:</span> <span class="hljs-string">&quot;https://newapi.getpop.org/wp-json/wp/v2/posts/1&quot;</span>\n  <span class="hljs-punctuation">)</span> <span class="hljs-meta">@remove</span>\n  <span class="hljs-symbol">renderedTitle</span><span class="hljs-punctuation">:</span> _objectProperty<span class="hljs-punctuation">(</span>\n    <span class="hljs-symbol">object</span><span class="hljs-punctuation">:</span> <span class="hljs-variable">$__postData</span>,\n    <span class="hljs-symbol">by</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">{</span>\n      <span class="hljs-symbol">path</span><span class="hljs-punctuation">:</span> <span class="hljs-string">&quot;title.rendered&quot;</span>\n    <span class="hljs-punctuation">}</span>\n  <span class="hljs-punctuation">)</span>\n<span class="hljs-punctuation">}</span></span></code></pre> <p>In the response to this query, field <code>postData</code> has been removed:</p> <pre><code class="hljs language-json"><span class="hljs"><span class="hljs-punctuation">{</span>\n  <span class="hljs-attr">&quot;data&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">{</span>\n    <span class="hljs-attr">&quot;renderedTitle&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-string">&quot;Hello world!&quot;</span>\n  <span class="hljs-punctuation">}</span>\n<span class="hljs-punctuation">}</span></span></code></pre> <p><strong>Please notice:</strong> <code>@remove</code> takes place at the very end of the resolution of all the fields under the same node. That&#39;s why, in the query above, the field <code>renderedTitle</code> is processed before field <code>postData</code> is <code>@remove</code>d.</p> <h2 id="how-to-use">How to use</h2> <p>Directive <code>@remove</code> has argument <code>condition</code>, with which we can specify under what condition to remove the field. It has 3 possible values:</p> <ul> <li><code>ALWAYS</code> (default value): Remove it always</li> <li><code>IS_NULL</code>: Remove it whenever the value is <code>null</code></li> <li><code>IS_EMPTY</code>: Remove it whenever the value is empty</li> </ul> <p>For instance, in the query below, when a post does not have a featured image, field <code>featuredImage</code> will have value <code>null</code>. By adding <code>@remove(condition: IS_NULL)</code>, this value will not be added to the response:</p> <pre><code class="hljs language-graphql"><span class="hljs"><span class="hljs-keyword">query</span> <span class="hljs-punctuation">{</span>\n  posts <span class="hljs-punctuation">{</span>\n    title\n    featuredImage <span class="hljs-meta">@remove</span><span class="hljs-punctuation">(</span><span class="hljs-symbol">condition</span><span class="hljs-punctuation">:</span> IS_NULL<span class="hljs-punctuation">)</span> <span class="hljs-punctuation">{</span>\n      src\n    <span class="hljs-punctuation">}</span>\n  <span class="hljs-punctuation">}</span>\n<span class="hljs-punctuation">}</span></span></code></pre> <h2 id="graphql-spec">GraphQL spec</h2> <p>This functionality is currently not part of the GraphQL spec, but it has been requested:</p> <ul> <li><a href="https://github.com/graphql/graphql-spec/issues/275#issuecomment-338538911" target="_blank">Issue #275 - @include(unless null) ?</a></li> <li><a href="https://github.com/graphql/graphql-spec/issues/766" target="_blank">Issue #766 - GraphQL query: skip value field if null</a></li> </ul> '}}]);