> ⚠️ First, I'd like to invite you to take a look at the official Swoole image: https://hub.docker.com/r/phpswoole/swoole

# Dwoole

[Docker](https://www.docker.com/) image for [Swoole](https://www.swoole.co.uk/) apps with [Composer](https://getcomposer.org/), auto-restart on development and a production-ready version.

## Usage

### Requirements

- `composer.json`
- `index.php` (this will be your entry-point)

Entry-point file can be overridden with the environment variable `ENTRY_POINT_FILE`. See [this](https://github.com/leocavalcante/siler/blob/master/examples/swoole-chat/docker-compose.yml) for an example.

#### Exposed port is 9501

#### Using Docker Compose?

A `docker-compose.yml` file would look like:

```yaml
version: "3"
services:
  web:
    container_name: my_app
    image: leocavalcante/dwoole:dev
    volumes:
      - ./:/app
    ports:
      - "9501:9501"
```

Yeah! Simple like that.

### What is inside?

Options enabled:

- `--enable-openssl`
- `--enable-sockets`
- `--enable-http2`
- `--enable-mysqlnd`

#### Development

Comes bundled with:

- [sdebug](https://github.com/swoole/sdebug)
- Hot-restart
- PDO MySQL & MySQLi
- MongoDB
- Redis

Watch interval can be overridden with the environment variable `WATCH_INTERVAL`.

#### Production

Comes bundled with:

- _Nothing_

It is Swoole only. That is because only you knows what your project really needs.
The recommended way to use the production variant is as a base image for you project image.

```Dockerfile
FROM leocavalcante/dwoole:prod
# Add only what your project really needs
COPY . /app
```

Then you can add whatever extensions you would like.

##### Adding PHP extensions

```Dockerfile
RUN apk add --no-cache freetype-dev libjpeg-turbo-dev libpng-dev libzip-dev \
 && docker-php-ext-configure gd && docker-php-ext-install -j$(nproc) gd zip
```

##### Adding PECL extensions

```Dockerfile
RUN pecl install mongodb \
  && docker-php-ext-enable mongodb
```

```Dockerfile
RUN pecl install redis \
  && docker-php-ext-enable redis
```

###### [You can always take a look at the development Dockerfile to see how it installs extensions that you might have used.](https://github.com/leocavalcante/dwoole/blob/master/development/Dockerfile)

#### Why not inotify?

- https://github.com/docker/for-win/issues/56
- > Linux containers only receive file change events (“inotify events”) if the original files are stored in the Linux filesystem. - https://docs.docker.com/docker-for-windows/wsl/

⚠ Dwoole will always commit to the latest PHP and Swoole versions.
