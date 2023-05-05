(window.webpackJsonpGraphQLAPISchemaConfigurationAdditionalDocumentation=window.webpackJsonpGraphQLAPISchemaConfigurationAdditionalDocumentation||[]).push([[33],{79:function(e,t){e.exports='<h1 id="schema-editing-access">Schema Editing Access</h1> <p>Grant access to users other than admins to edit the GraphQL schema</p> <h2 id="description">Description</h2> <p>By default, only admin users (those with the <code>manage_options</code> capability) have access to the different screens of plugin GraphQL API for WordPress in the admin.</p> <p>This module <code>Schema Editing Access</code> enables to grant non-admin users access to the GraphiQL and Interactive schema clients in the admin, and to read and/or write the different Custom Post Types from this plugin:</p> <ul> <li>Persisted Queries</li> <li>Custom Endpoints</li> <li>Schema Configurations</li> <li>Access Control Lists</li> <li>Cache Control Lists</li> <li>Others</li> </ul> <p>This is achieved via two different methods:</p> <ol> <li>By assigning the custom capability <code>manage_graphql_schema</code> to the user</li> <li>By selecting the user roles that can edit the schema (down to the &quot;Author&quot; level)</li> </ol> <h2 id="how-to-use">How to use</h2> <p>Assign capability <code>manage_graphql_schema</code> to any user role or any specific user that must be able to edit the schema.</p> <p>(You can use a 3rd-party plugin to do this, such as <a href="https://wordpress.org/plugins/search/role/">User Role Editor</a>.)</p> <p>You can also select a group of user roles which can edit the GraphQL schema.</p> <p>The appropriate configuration must be selected from the dropdown in the &quot;Plugin Configuration &gt; Schema Editing Access&quot; tab on the Settings page:</p> <ul> <li><code>Users with capability: &quot;manage_graphql_schema&quot;</code></li> <li><code>Users with role: &quot;administrator&quot;</code></li> <li><code>Users with any role: &quot;administrator&quot;, &quot;editor&quot;</code></li> <li><code>Users with any role: &quot;administrator&quot;, &quot;editor&quot;, &quot;author&quot;</code></li> </ul> <p><img src="https://raw.githubusercontent.com/leoloso/PoP/master/layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/docs/implicit-features//../../images/settings-schema-editing-access.png" alt="Configuring the schema editing access in the Settings" title="Configuring the schema editing access in the Settings"></p> '}}]);