# GraphQL API for WordPress

Transform your WordPress site into a GraphQL server.

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

### Synchronizing the repo

Whenever pulling changes from this repo, install again the dependencies:

```bash
composer install
```

## Credits

- [Leonardo Losoviz][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-author]: https://github.com/leoloso
