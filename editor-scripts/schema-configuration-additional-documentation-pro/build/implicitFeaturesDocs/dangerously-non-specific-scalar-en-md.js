(window.webpackJsonpGraphQLAPISchemaConfigurationAdditionalDocumentationPRO=window.webpackJsonpGraphQLAPISchemaConfigurationAdditionalDocumentationPRO||[]).push([[1],{46:function(s,a){s.exports='<h1 id="dangerouslynonspecificscalar-type">DangerouslyNonSpecificScalar Type</h1> <p>Scalar type <code>DangerouslyNonSpecificScalar</code> represents any scalar type (including both built-in and custom scalar types), and in addition it can be a single value, a List, or a List of Lists.</p> <p>In other words, considering a hypothetical scalar type <code>AnyScalar</code> that handles all scalar types, <code>DangerouslyNonSpecificScalar</code> represents all of these, at the same time:</p> <ul> <li><code>AnyScalar</code></li> <li><code>[AnyScalar]</code></li> <li><code>[[AnyScalar]]</code></li> </ul> <h2 id="description">Description</h2> <p>Fields cannot be defined to return all potential combinations of types and their modifiers: a single value, a list of values, or a list of list of values.</p> <p>For instance, field <code>optionValue</code> returns type <code>AnyBuiltInScalar</code> (i.e. it can handle any of <a href="https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars">GraphQL&#39;s built-in scalar types</a>), but it can only retrieve a single value, and not a list of values. If we need to retrieve a list of values, then we need to use field <code>optionValues</code> instead, which returns <code>[AnyBuiltInScalar]</code>.</p> <p>However, being able to return either a single value, a list of values, or a list of lists of values, always from the same field, is useful for the <strong>Function Fields</strong>, as they provide functionalities which, in many cases, are independent of the type or cardinality of the value.</p> <p>An example is the field <code>_echo</code> which, whatever input it gets:</p> <pre><code class="hljs language-graphql"><span class="hljs"><span class="hljs-punctuation">{</span>\n  <span class="hljs-symbol">single</span><span class="hljs-punctuation">:</span> _echo<span class="hljs-punctuation">(</span><span class="hljs-symbol">value</span><span class="hljs-punctuation">:</span> <span class="hljs-string">&quot;hello&quot;</span><span class="hljs-punctuation">)</span>\n  <span class="hljs-symbol">list</span><span class="hljs-punctuation">:</span> _echo<span class="hljs-punctuation">(</span><span class="hljs-symbol">value</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">[</span><span class="hljs-number">1</span>, <span class="hljs-number">2</span>, <span class="hljs-number">3</span><span class="hljs-punctuation">]</span><span class="hljs-punctuation">)</span>\n  <span class="hljs-symbol">listOfLists</span><span class="hljs-punctuation">:</span> _echo<span class="hljs-punctuation">(</span><span class="hljs-symbol">value</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">[</span><span class="hljs-punctuation">[</span><span class="hljs-string">&quot;a&quot;</span>, <span class="hljs-string">&quot;b&quot;</span>, <span class="hljs-string">&quot;c&quot;</span><span class="hljs-punctuation">]</span>, <span class="hljs-punctuation">[</span><span class="hljs-string">&quot;d&quot;</span>, <span class="hljs-string">&quot;e&quot;</span><span class="hljs-punctuation">]</span>, <span class="hljs-punctuation">[</span><span class="hljs-string">&quot;f&quot;</span><span class="hljs-punctuation">]</span><span class="hljs-punctuation">]</span><span class="hljs-punctuation">)</span>\n<span class="hljs-punctuation">}</span></span></code></pre> <p>...it will print it back:</p> <pre><code class="hljs language-json"><span class="hljs"><span class="hljs-punctuation">{</span>\n  <span class="hljs-attr">&quot;data&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">{</span>\n    <span class="hljs-attr">&quot;single&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-string">&quot;hello&quot;</span><span class="hljs-punctuation">,</span>\n    <span class="hljs-attr">&quot;list&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">[</span><span class="hljs-number">1</span><span class="hljs-punctuation">,</span> <span class="hljs-number">2</span><span class="hljs-punctuation">,</span> <span class="hljs-number">3</span><span class="hljs-punctuation">]</span><span class="hljs-punctuation">,</span>\n    <span class="hljs-attr">&quot;listOfLists&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">[</span><span class="hljs-punctuation">[</span><span class="hljs-string">&quot;a&quot;</span><span class="hljs-punctuation">,</span> <span class="hljs-string">&quot;b&quot;</span><span class="hljs-punctuation">,</span> <span class="hljs-string">&quot;c&quot;</span><span class="hljs-punctuation">]</span><span class="hljs-punctuation">,</span> <span class="hljs-punctuation">[</span><span class="hljs-string">&quot;d&quot;</span><span class="hljs-punctuation">,</span> <span class="hljs-string">&quot;e&quot;</span><span class="hljs-punctuation">]</span><span class="hljs-punctuation">,</span> <span class="hljs-punctuation">[</span><span class="hljs-string">&quot;f&quot;</span><span class="hljs-punctuation">]</span><span class="hljs-punctuation">]</span>\n  <span class="hljs-punctuation">}</span>\n<span class="hljs-punctuation">}</span></span></code></pre> <p>Another example is field <code>_arrayItem</code> which, given an array an a position, retrieves the item at that position from the array. This field does not care if the array contains single values, lists, lists of lists, or what not; whatever item is contained in the array at that position will be retrieved.</p> <p>For instance, in this query, the posts&#39; categories are exported to a dynamic variable via the <strong>Field to Input</strong> feature, and then the first item is retrieved:</p> <pre><code class="hljs language-graphql"><span class="hljs"><span class="hljs-punctuation">{</span>\n  posts<span class="hljs-punctuation">(</span><span class="hljs-symbol">pagination</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">{</span> <span class="hljs-symbol">limit</span><span class="hljs-punctuation">:</span> <span class="hljs-number">3</span> <span class="hljs-punctuation">}</span><span class="hljs-punctuation">)</span> <span class="hljs-punctuation">{</span>\n    categoryNames\n    <span class="hljs-symbol">mainCategory</span><span class="hljs-punctuation">:</span> _arrayItem<span class="hljs-punctuation">(</span><span class="hljs-symbol">array</span><span class="hljs-punctuation">:</span> <span class="hljs-variable">$__categoryNames</span>, <span class="hljs-symbol">position</span><span class="hljs-punctuation">:</span> <span class="hljs-number">0</span><span class="hljs-punctuation">)</span>\n  <span class="hljs-punctuation">}</span>\n<span class="hljs-punctuation">}</span></span></code></pre> <p>...producing:</p> <pre><code class="hljs language-json"><span class="hljs"><span class="hljs-punctuation">{</span>\n  <span class="hljs-attr">&quot;data&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">{</span>\n    <span class="hljs-attr">&quot;posts&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">[</span>\n      <span class="hljs-punctuation">{</span>\n        <span class="hljs-attr">&quot;categoryNames&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">[</span>\n          <span class="hljs-string">&quot;Uncategorized&quot;</span>\n        <span class="hljs-punctuation">]</span><span class="hljs-punctuation">,</span>\n        <span class="hljs-attr">&quot;mainCategory&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-string">&quot;Uncategorized&quot;</span>\n      <span class="hljs-punctuation">}</span><span class="hljs-punctuation">,</span>\n      <span class="hljs-punctuation">{</span>\n        <span class="hljs-attr">&quot;categoryNames&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">[</span>\n          <span class="hljs-string">&quot;Advanced&quot;</span>\n        <span class="hljs-punctuation">]</span><span class="hljs-punctuation">,</span>\n        <span class="hljs-attr">&quot;mainCategory&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-string">&quot;Advanced&quot;</span>\n      <span class="hljs-punctuation">}</span><span class="hljs-punctuation">,</span>\n      <span class="hljs-punctuation">{</span>\n        <span class="hljs-attr">&quot;categoryNames&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-punctuation">[</span>\n          <span class="hljs-string">&quot;Resource&quot;</span><span class="hljs-punctuation">,</span>\n          <span class="hljs-string">&quot;Blog&quot;</span><span class="hljs-punctuation">,</span>\n          <span class="hljs-string">&quot;Advanced&quot;</span>\n        <span class="hljs-punctuation">]</span><span class="hljs-punctuation">,</span>\n        <span class="hljs-attr">&quot;mainCategory&quot;</span><span class="hljs-punctuation">:</span> <span class="hljs-string">&quot;Resource&quot;</span>\n      <span class="hljs-punctuation">}</span>\n    <span class="hljs-punctuation">]</span>\n  <span class="hljs-punctuation">}</span>\n<span class="hljs-punctuation">}</span></span></code></pre> <p>As <code>categoryNames</code> returns <code>[String]</code>, then <code>_arrayItem</code> will produce <code>String</code>. If the input were instead <code>[[String]]</code>, then <code>_arrayItem</code> will produce <code>[String]</code>. And so on.</p> '}}]);