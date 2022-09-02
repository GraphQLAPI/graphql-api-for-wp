(window.webpackJsonpGraphQLAPICustomEndpointOptions=window.webpackJsonpGraphQLAPICustomEndpointOptions||[]).push([[1],{46:function(t,e){t.exports='<h1 id="custom-endpoints">Custom Endpoints</h1> <p>Create custom schemas, with custom access rules for different users, each available under its own endpoint.</p> <h2 id="description">Description</h2> <p>A GraphQL server normally exposes a single endpoint for retrieving and posting data.</p> <p>In addition to supporting the single endpoint, the GraphQL API also makes it possible to create custom endpoints, providing different schema configurations to deal with the needs from different targets, such as:</p> <ul> <li>Some specific client or user</li> <li>A group of users with more access to features (such as PRO users)</li> <li>One of the several applications, like mobile app or website</li> <li>3rd-party APIs</li> <li>Any other</li> </ul> <p>The custom endpoint is a Custom Post Type, and its permalink is the endpoint. An endpoint with title <code>&quot;My endpoint&quot;</code> and slug <code>my-endpoint</code> will be accessible under <code>/graphql/my-endpoint/</code>.</p> <p><img src="https://raw.githubusercontent.com/GraphQLAPI/graphql-api-for-wp/master/docs/modules/custom-endpoints/../../images/custom-endpoint.png" alt="Creating a custom endpoint" title="Creating a custom endpoint"></p> <h2 id="clients">Clients</h2> <p>Each custom endpoint has its own set of clients to interact with:</p> <p>✅ A <strong>GraphiQL client</strong>, available under the endpoint + <code>?view=graphiql</code> (eg: <code>/graphql/my-endpoint/?view=graphiql</code>).</p> <p>Module <code>GraphiQL for Custom Endpoints</code> must be enabled.</p> <p><img src="https://raw.githubusercontent.com/GraphQLAPI/graphql-api-for-wp/master/docs/modules/custom-endpoints/../../images/custom-endpoint-graphiql.png" alt="Custom endpoint&#39;s GraphiQL client" title="Custom endpoint&#39;s GraphiQL client"></p> <p>✅ An <strong>Interactive schema client</strong>, available under the endpoint + <code>?view=schema</code> (eg: <code>/graphql/my-endpoint/?view=schema</code>).</p> <p>Module <code>Interactive Schema for Custom Endpoints</code> must be enabled.</p> <p><img src="https://raw.githubusercontent.com/GraphQLAPI/graphql-api-for-wp/master/docs/modules/custom-endpoints/../../images/custom-endpoint-interactive-schema.png" alt="Custom endpoint&#39;s Interactive schema" title="Custom endpoint&#39;s Interactive schema"></p> <h2 id="how-to-use">How to use</h2> <p>Clicking on the Custom Endpoints link in the menu, it displays the list of all the created custom endpoints:</p> <p><img src="https://raw.githubusercontent.com/GraphQLAPI/graphql-api-for-wp/master/docs/modules/custom-endpoints/../../images/custom-endpoints-page.png" alt="Custom Endpoints in the admin"></p> <p>A custom endpoint is a custom post type (CPT). To create a new custom endpoint, click on button &quot;Add New GraphQL endpoint&quot;, which will open the WordPress editor:</p> <p><img src="https://raw.githubusercontent.com/GraphQLAPI/graphql-api-for-wp/master/docs/modules/custom-endpoints/../../images/new-custom-endpoint.png" alt="Creating a new Custom Endpoint"></p> <p>When the endpoint is ready, publish it, and its permalink becomes its endpoint:</p> <p><img src="https://raw.githubusercontent.com/GraphQLAPI/graphql-api-for-wp/master/docs/modules/custom-endpoints/../../images/publishing-custom-endpoint.gif" alt="Publishing the custom endpoint"></p> <p>Appending <code>?view=source</code> to the permalink, it will show the endpoint&#39;s configuration (as long as the user has access to it):</p> <p><img src="https://raw.githubusercontent.com/GraphQLAPI/graphql-api-for-wp/master/docs/modules/custom-endpoints/../../images/custom-endpoint-source.png" alt="Custom endpoint source"></p> <p>By default, the custom endpoint has path <code>/graphql/</code>, and this value is configurable through the Settings:</p> <p><img src="https://raw.githubusercontent.com/GraphQLAPI/graphql-api-for-wp/master/docs/modules/custom-endpoints/../../images/settings-custom-endpoints.png" alt="Custom endpoint Settings"></p> <h2 id="editor-inputs">Editor Inputs</h2> <p>These inputs in the body of the editor are shipped with the plugin (more inputs can be added by extensions):</p> <table> <thead> <tr> <th>Input</th> <th>Description</th> </tr> </thead> <tbody> <tr> <td><strong>Title</strong></td> <td>Custom endpoint\'s title</td> </tr> <tr> <td><strong>Schema configuration</strong></td> <td>From the dropdown, select the schema configuration that applies to the custom endpoint, or one of these options: <ul><li><code>"Default"</code>: the schema configuration is the one selected on the plugin\'s Settings</li><li><code>"None"</code>: the custom endpoint will be unconstrained</li><li><code>"Inherit from parent"</code>: Use the same schema configuration as the parent custom endpoint.<br/>This option is available when module <code>"API Hierarchy"</code> is enabled, and the custom endpoint has a parent query (selected on the Document settings)</li></ul></td> </tr> <tr> <td><strong>Options</strong></td> <td>Select if the custom endpoint is enabled.<br/>It\'s useful to disable a custom endpoint it\'s a parent query in an API hierarchy</td> </tr> <tr> <td><strong>GraphiQL</strong></td> <td>Enable/disable attaching a GraphiQL client to the endpoint, accessible under <code>?view=graphiql</code></td> </tr> <tr> <td><strong>Interactive Schema</strong></td> <td>Enable/disable attaching an Interactive schema client to the endpoint, accessible under <code>?view=schema</code></td> </tr> <tr> <td><strong>API Hierarchy</strong></td> <td>Use the same query as the parent custom endpoint.<br/>This section is enabled when the custom endpoint has a parent query (selected on the Document settings)</td> </tr> </tbody> </table> <p>These are the inputs in the Document settings:</p> <table> <thead> <tr> <th>Input</th> <th>Description</th> </tr> </thead> <tbody><tr> <td><strong>Permalink</strong></td> <td>The endpoint under which the custom endpoint will be available</td> </tr> <tr> <td><strong>Categories</strong></td> <td>Can categorize the custom endpoint.<br/>Eg: <code>mobile</code>, <code>app</code>, etc</td> </tr> <tr> <td><strong>Excerpt</strong></td> <td>Provide a description for the custom endpoint.<br/>This input is available when module <code>&quot;Excerpt as Description&quot;</code> is enabled</td> </tr> <tr> <td><strong>Page attributes</strong></td> <td>Select a parent custom endpoint.<br/>This input is available when module <code>&quot;API Hierarchy&quot;</code> is enabled</td> </tr> </tbody></table> \x3c!-- ## Settings\n\n| Option | Description | \n| --- | --- |\n| **Base path** | The base path for the custom endpoint URL. It defaults to `graphql` | --\x3e <h2 id="resources">Resources</h2> <p>Video showing how to create a custom endpoint: <a href="https://vimeo.com/413503485" target="_blank">vimeo.com/413503485</a>.</p> '}}]);