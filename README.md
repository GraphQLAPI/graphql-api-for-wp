# GraphQL API for WordPress

Transform your WordPress site into a GraphQL server.

## Requirements

WordPress 5.4 or above, PHP 7.1 or above.

## Install

Download the plugin as a .zip file:

https://github.com/GraphQLAPI/graphql-api/releases/download/0.1.0/graphql-api.zip 

In the WordPress admin:

- Go to `Plugins => Add New`
- Click on `Upload Plugin`
- Select the .zip file
- Click on `Install Now` (it may take a few minutes)
- Once installed, click on `Activate`

## Development

Clone repo, and then install Composer dependencies, by running:

```bash
git clone https://github.com/GraphQLAPI/graphql-api.git
cd graphql-api
composer install
```

### Using `wp-env`

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

### Pulling code

Whenever pulling changes from this repo, install again the dependencies:

```bash
composer install
```

### Pushing code

Compiled JavaScript code (such as all files under a block's `build/` folder) is added to the repo, but only as compiled for production, i.e. after running `npm run build`.

Code compiled for development, i.e. after running `npm start`, is not allowed in the repo.

### Clone own dependencies

GraphQL API is not a monorepo. Instead, its code is distributed across packages (living in repos from [PoP](https://github.com/getpop), [GraphQLByPoP](https://github.com/GraphQLByPoP) and [PoPSchema](https://github.com/PoPSchema)), and managed through Composer.

File [`dev-helpers/scripts/clone-all-dependencies-from-github.sh`](https://github.com/GraphQLAPI/graphql-api/blob/master/dev-helpers/scripts/clone-all-dependencies-from-github.sh) contains the list of all own dependencies, ready to be cloned.

For development, the GraphQL API plugin can use these local projects by overriding Composer's autoload `PSR-4` sources. To do so:

- Duplicate file [`composer.local-sample.json`](https://github.com/GraphQLAPI/graphql-api/blob/master/composer.local-sample.json) as `composer.local.json`
- Customize it with the paths to the folders

This file will override any corresponding entry defined in `composer.json`.

## Credits

- [Leonardo Losoviz][link-author]

## License

GPLv2 or later. Please see [License File](LICENSE.md) for more information.

[link-author]: https://github.com/leoloso
