<p align="center"><img src="assets/img/graphql-api-logo.png" width="150" /></p>

# GraphQL API for WordPress

[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)
<!-- [![GitHub release][ico-release]][link-release] -->
<!-- [![Github all releases][ico-downloads]][link-downloads] -->

Transform your WordPress site into a GraphQL server.

This plugin is the implementation for WordPress of [GraphQL by PoP](https://graphql-by-pop.com/), a CMS-agnostic GraphQL server in PHP.

## Why

Please read the author's [introduction to the GraphQL API for WordPress](https://leoloso.com/posts/introducing-the-graphql-api-for-wordpress/), which describes:

- How does it compare with the existing solutions: WP REST API and WPGraphQL
- Its most important features
- An overview of all its features
- Q&A

## Requirements

WordPress 5.4 or above, PHP 7.4+ for development, PHP 7.1+ for production.

## Install

Download [the latest release of the plugin](https://github.com/GraphQLAPI/graphql-api/releases/download/v0.5.0/graphql-api.zip) as a .zip file.

Then, in the WordPress admin:

- Go to `Plugins => Add New`
- Click on `Upload Plugin`
- Select the .zip file
- Click on `Install Now` (it may take a few minutes)
- Once installed, click on `Activate`

After installed, there will be a new "GraphQL API" section on the menu:

![The interactive schema visualizer](docs/images/interactive-schema.png)

### Ready for production?

This plugin requires 3rd party dependencies, but they have not been scoped yet (see [issue #9](https://github.com/GraphQLAPI/graphql-api/issues/9)). So please install this plugin in a development environment first, to make sure there are no conflicts with the other plugins installed in the site.

If any problem arises, either installing or running the plugin, please [create a new issue](https://github.com/GraphQLAPI/graphql-api/issues/new).

## Modules

GraphQL API is extensible, and ships with the following modules (organized by category):

<table>
<thead>
<tr><th>Module</th><th>Description</th></tr>
</thead>
<tbody>

<tr><th colspan="2"><br/>Endpoint</th></tr>
<tr><td><a href="docs/en/modules/single-endpoint.md">Single Endpoint</a></td><td>Expose a single GraphQL endpoint under <code>/graphql/</code>, with unrestricted access</td></tr>
<tr><td><a href="docs/en/modules/persisted-queries.md">Persisted Queries</a></td><td>Expose predefined responses through a custom URL, akin to using GraphQL queries to publish REST endpoints</td></tr>
<tr><td><a href="docs/en/modules/custom-endpoints.md">Custom Endpoints</a></td><td>Expose different subsets of the schema for different targets, such as users (clients, employees, etc), applications (website, mobile app, etc), context (weekday, weekend, etc), and others</td></tr>
<tr><td><a href="docs/en/modules/api-hierarchy.md">API Hierarchy</a></td><td>Create a hierarchy of API endpoints extending from other endpoints, and inheriting their properties</td></tr>
<tr><th colspan="2"><br/>Schema Configuration</th></tr>
<tr><td><a href="docs/en/modules/schema-configuration.md">Schema Configuration</a></td><td>Customize the schema accessible to different Custom Endpoints and Persisted Queries, by applying a custom configuration (involving namespacing, access control, cache control, and others) to the grand schema</td></tr>
<tr><td><a href="docs/en/modules/schema-namespacing.md">Schema Namespacing</a></td><td>Automatically namespace types and interfaces with a vendor/project name, to avoid naming collisions</td></tr>
<tr><td><a href="docs/en/modules/public-private-schema.md">Public/Private Schema</a></td><td>Enable to communicate the existence of some field from the schema to certain users only (private mode) or to everyone (public mode). If disabled, fields are always available to everyone (public mode)</td></tr>
<tr><th colspan="2"><br/>Access Control</th></tr>
<tr><td><a href="docs/en/modules/access-control.md">Access Control</a></td><td>Set-up rules to define who can access the different fields and directives from a schema</td></tr>
<tr><td>Access Control Rule: Disable Access</td><td>Remove access to the fields and directives</td></tr>
<tr><td>Access Control Rule: User State</td><td>Allow or reject access to the fields and directives based on the user being logged-in or not</td></tr>
<tr><td>Access Control Rule: User Roles</td><td>Allow or reject access to the fields and directives based on the user having a certain role</td></tr>
<tr><td>Access Control Rule: User Capabilities</td><td>Allow or reject access to the fields and directives based on the user having a certain capability</td></tr>
<tr><th colspan="2"><br/>Versioning</th></tr>
<tr><td><a href="docs/en/modules/field-deprecation.md">Field Deprecation</a></td><td>Deprecate fields, and explain how to replace them, through a user interface</td></tr>
<tr><th colspan="2"><br/>User Interface</th></tr>
<tr><td><a href="docs/en/modules/low-level-persisted-query-editing.md">Low-Level Persisted Query Editing</a></td><td>Have access to schema-configuration low-level directives when editing GraphQL queries in the admin</td></tr>
<tr><td><a href="docs/en/modules/excerpt-as-description.md">Excerpt as Description</a></td><td>Provide a description of the different entities (Custom Endpoints, Persisted Queries, and others) through their excerpt</td></tr>
<tr><th colspan="2"><br/>Performance</th></tr>
<tr><td><a href="docs/en/modules/cache-control.md">Cache Control</a></td><td>Provide HTTP Caching for Persisted Queries, sending the Cache-Control header with a max-age value calculated from all fields in the query</td></tr>
<tr><td><a href="docs/en/modules/configuration-cache.md">Configuration Cache</a></td><td>Cache the generated application configuration to disk</td></tr>
<tr><td><a href="docs/en/modules/schema-cache.md">Schema Cache</a></td><td>Cache the generated schema to disk</td></tr>
<tr><th colspan="2"><br/>Operational</th></tr>
<tr><td><a href="docs/en/modules/multiple-query-execution.md">Multiple Query Execution</a></td><td>Execute multiple GraphQL queries in a single operation</td></tr>
<tr><td><a href="docs/en/modules/remove-if-null-directive.md">Remove if Null</a></td><td>Addition of `@removeIfNull` directive, to remove an output from the response if it is `null`</td></tr>
<tr><td><a href="docs/en/modules/proactive-feedback.md">Proactive Feedback</a></td><td>Usage of the top-level entry `extensions` to send deprecations, warnings, logs, notices and traces in the response to the query</td></tr>
<tr><th colspan="2"><br/>Plugin Management</th></tr>
<tr><td><a href="docs/en/modules/schema-editing-access.md">Schema Editing Access</a></td><td>Grant access to users other than admins to edit the GraphQL schema</td></tr>
<tr><th colspan="2"><br/>Clients</th></tr>
<tr><td><a href="docs/en/modules/graphiql-for-single-endpoint.md">GraphiQL for Single Endpoint</a></td><td>Make a public GraphiQL client available under <code>/graphiql/</code>, to execute queries against the single endpoint. It requires pretty permalinks enabled</td></tr>
<tr><td><a href="docs/en/modules/interactive-schema-for-single-endpoint.md">Interactive Schema for Single Endpoint</a></td><td>Make a public Interactive Schema client available under <code>/schema/</code>, to visualize the schema accessible through the single endpoint. It requires pretty permalinks enabled</td></tr>
<tr><td><a href="docs/en/modules/graphiql-for-custom-endpoints.md">GraphiQL for Custom Endpoints</a></td><td>Enable custom endpoints to be attached their own GraphiQL client, to execute queries against them</td></tr>
<tr><td><a href="docs/en/modules/interactive-schema-for-custom-endpoints.md">Interactive Schema for Custom Endpoints</a></td><td>Enable custom endpoints to be attached their own Interactive schema client, to visualize the custom schema subset</td></tr>
<tr><td><a href="docs/en/modules/graphiql-explorer.md">GraphiQL Explorer</a></td><td>Add the Explorer widget to the GraphiQL client when creating Persisted Queries, to simplify coding the query (by point-and-clicking on the fields)</td></tr>
<tr><th colspan="2"><br/>Schema Type</th></tr>
<tr><td><a href="docs/en/modules/schema-customposts.md">Schema Custom Posts</a></td><td>Base functionality for all custom posts</td></tr>
<tr><td><a href="docs/en/modules/schema-generic-customposts.md">Schema Generic Custom Posts</a></td><td>Query any custom post type (added to the schema or not), through a generic type <code>GenericCustomPost</code></td></tr>
<tr><td>Schema Posts</td><td>Query posts, through type <code>Post</code> added to the schema</td></tr>
<tr><td>Schema Pages</td><td>Query pages, through type <code>Page</code> added to the schema</td></tr>
<tr><td>Schema Users</td><td>Query users, through type <code>User</code> added to the schema</td></tr>
<tr><td>Schema User Roles</td><td>Query user roles, through type <code>UserRole</code> added to the schema</td></tr>
<tr><td>Schema Comments</td><td>Query comments, through type <code>Comment</code> added to the schema</td></tr>
<tr><td>Schema Tags</td><td>Base functionality for all tags</td></tr>
<tr><td>Schema Post Tags</td><td>Query post tags, through type <code>PostTag</code> added to the schema</td></tr>
<tr><td>Schema Media</td><td>Query media elements, through type <code>Media</code> added to the schema</td></tr>
</tbody>
</table>

## Development

Clone repo, and then install Composer dependencies, by running:

```bash
$ git clone https://github.com/GraphQLAPI/graphql-api.git
$ cd graphql-api
$ composer install
```

### Launch a development environment with `wp-env`

Launch a WordPress environment with the GraphQL API plugin activated through [`wp-env`](https://www.npmjs.com/package/@wordpress/env).

[Prerequisites](https://www.npmjs.com/package/@wordpress/env#prerequisites):

- Node.js
- npm
- Docker

To [install `wp-env`](https://www.npmjs.com/package/@wordpress/env#installation) globally, run in the terminal:

```bash
npm -g i @wordpress/env
```

To start a new WordPress instance with the GraphQL API plugin already installed and activated, execute in the root folder of the plugin (make sure Docker is running):

```bash
wp-env start
```

> Please notice: The first time using `wp-env`, this process may take a long time (half an hour or even more). To see what is happening, execute with the `--debug` option: `wp-env start --debug`

<!-- The first time, change the permalink structure to use pretty permalinks:

```bash
wp-env run cli wp rewrite structure '/%postname%/'
``` -->

The site will be available under `http://localhost:6666`.

To access the wp-admin, under `http://localhost:6666/wp-admin/`:

- User: `admin`
- Password: `password`

To enable pretty permalinks, run:

```bash
wp-env run cli wp rewrite structure '/%postname%/'
```

### Pulling code

Whenever pulling changes from this repo, install again the dependencies:

```bash
composer install
```

### Pushing code

Compiled JavaScript code (such as all files under a block's `build/` folder) is added to the repo, but only as compiled for production, i.e. after running `npm run build`.

Code compiled for development, i.e. after running `npm start`, is not allowed in the repo.

### Clone own dependencies

GraphQL API is not a monorepo. Instead, every package lives under its own repo, and everything is managed and assembled together through Composer.

File [`dev-helpers/scripts/clone-all-dependencies-from-github.sh`](https://github.com/GraphQLAPI/graphql-api/blob/master/dev-helpers/scripts/clone-all-dependencies-from-github.sh) contains the list of all own dependencies, ready to be cloned.

For development, the GraphQL API plugin can use these local projects by overriding Composer's autoload `PSR-4` sources. To do so:

- Duplicate file [`composer.local-sample.json`](https://github.com/GraphQLAPI/graphql-api/blob/master/composer.local-sample.json) as `composer.local.json`
- Customize it with the paths to the folders

This file will override any corresponding entry defined in `composer.json`.

### PSR-4 Namespaces

The package owner for this plugin is `GraphQLAPI`. In addition, there are 3 other package owners for all the required components, each as an organization in GitHub:

- [GraphQLByPoP](https://github.com/GraphQLByPoP): components belonging to "GraphQL by PoP", the CMS-agnostic GraphQL server which powers the plugin
- [PoPSchema](https://github.com/PoPSchema): components to add entities to the schema (types, field resolvers, directives)
- [PoP](https://github.com/getpop): the core server-side component architecture, used by the server to load the graph data

### CMS-agnosticism

Even though this plugin is already the implementation for WordPress, it is recommended to develop components following the [CMS-agnostic method employed by GraphQL by PoP](https://graphql-by-pop.com/docs/architecture/cms-agnosticism.html), so that they can benefit from architectural optimizations and future developments.

In particular, support for serverless PHP (a feature which is [on the roadmap](https://graphql-by-pop.com/docs/roadmap/serverless-wordpress.html)) may require to decouple the codebase from WordPress.

This method requires the code for the component to be divided into 2 separate packages:

- A CMS-agnostic package, containing the business code and generic contracts, but without using any WordPress code (eg: [posts](https://github.com/PoPSchema/posts))
- A CMS-specific package, containing the implementation of the contracts for WordPress (eg: [posts-wp](https://github.com/PoPSchema/posts-wp))

## PHP versions

Requirements:

- PHP 7.4+ for development
- PHP 7.1+ for production (through release [`graphql-api.zip`](https://github.com/GraphQLAPI/graphql-api/releases/download/v0.5.0/graphql-api.zip))

Allowed PHP code, in this package and dependencies:

| PHP Version | Features | Supported? | 
| --- | --- | --- |
| 7.1 | Everything | ✅ |
| 7.2 | `object` type | ✅ |
| 7.4 | Typed properties | ✅ |
| 8.0 | **Interfaces:**<br/>`Stringable` | ✅ |
| 8.0 | **Classes:**<br/>`ValueError`<br/>`UnhandledMatchError` | ✅ |
| 8.0 | **Constants:**<br/>`FILTER_VALIDATE_BOOL` | ✅ |
| 8.0 | **Functions:**<br/>[`fdiv`](https://php.net/fdiv)<br/>[`get_debug_type`](https://php.net/get_debug_type)<br/>[`preg_last_error_msg`](https://php.net/preg_last_error_msg)<br/>[`str_contains`](https://php.net/str_contains)<br/>[`str_starts_with`](https://php.net/str_starts_with)<br/>[`str_ends_with`](https://php.net/str_ends_with)<br/>[`get_resource_id`](https://php.net/get_resource_id) | ✅ |
| 7.4 | Arrow functions | ⏳[#4125](https://github.com/rectorphp/rector/issues/4125) |
| 7.4 | Null coalescing assignment operator `??=` | ⏳[#4124](https://github.com/rectorphp/rector/issues/4124) |
| 8.0 | `mixed` type | ⏳[#4122](https://github.com/rectorphp/rector/issues/4122) |
| 8.0 | `static` return type | ⏳[#4123](https://github.com/rectorphp/rector/issues/4123) |
| 8.0 | Type unions | ⏳[#4062](https://github.com/rectorphp/rector/issues/4062) |

### Downgrading PHP code from v7.4 to v7.1

Via [Rector](https://github.com/rectorphp/rector) (dry-run mode):

```bash
composer downgrade-code
```

## Resources

The following videos show several features:

- [Persisted queries](https://vimeo.com/413503547)
- [Custom endpoints](https://vimeo.com/413503485)
- [Access control](https://vimeo.com/413503383)
- [Public/private API](https://vimeo.com/413503284)
- [HTTP caching](https://vimeo.com/413503188)
- [Field deprecation](https://vimeo.com/413503110)
- [Query inheritance](https://vimeo.com/413503010)

For technical information on how the GraphQL server works, check out [GraphQL by PoP's documentation](https://graphql-by-pop.com/docs/getting-started/intro.html) and [resources](https://graphql-by-pop.com/resources/) (these are still a work in progress).

## Standards

[PSR-1](https://www.php-fig.org/psr/psr-1), [PSR-4](https://www.php-fig.org/psr/psr-4) and [PSR-12](https://www.php-fig.org/psr/psr-12).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

## Static Analysis

Execute [phpstan](https://github.com/phpstan/phpstan) with level 8:

``` bash
composer analyse
```

To run checks for level 0 (or any level from 0 to 8):

``` bash
./vendor/bin/phpstan analyse -l 0 src tests
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]

## License

GPLv2 or later. Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-GPL%20(%3E%3D%202)-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/GraphQLAPI/graphql-api-for-wp/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/GraphQLAPI/graphql-api-for-wp.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/GraphQLAPI/graphql-api-for-wp.svg?style=flat-square
[ico-release]: https://img.shields.io/github/release/GraphQLAPI/graphql-api-for-wp.svg
[ico-downloads]: https://img.shields.io/github/downloads/GraphQLAPI/graphql-api-for-wp/total.svg

[link-travis]: https://travis-ci.com/github/GraphQLAPI/graphql-api-for-wp
[link-scrutinizer]: https://scrutinizer-ci.com/g/GraphQLAPI/graphql-api-for-wp/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/GraphQLAPI/graphql-api-for-wp
[link-downloads]: https://GitHub.com/GraphQLAPI/graphql-api-for-wp/releases/
[link-release]: https://GitHub.com/GraphQLAPI/graphql-api-for-wp/releases/
[link-downloads]: https://GitHub.com/GraphQLAPI/graphql-api-for-wp/releases/
[link-contributors]: ../../contributors
[link-author]: https://github.com/leoloso
