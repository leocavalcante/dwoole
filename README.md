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
    image: leocavalcante/dwoole:1.0-development
    volumes:
      - ./:/app
    ports:
      - "9501:9501"
```

Yeah! Simple like that.

### What is inside?

All Swoole options enabled:
* `--enable-openssl`
* `--enable-sockets`
* `--enable-http2`
* `--enable-mysqlnd`
* `--enable-async-redis`

#### Development

Comes bundled with:
- Hot-restart
- PDO (MySQL)
- MongoDB
- Redis


#### Production

Comes bundled with:
- *Nothing*

It is Swoole only. That is because only you knows what your project really needs.
The recommended way to use the production variant is as a base image for you project image.

```Dockerfile
FROM leocavalcante/dwoole:<version>-production
COPY . /app
```

Then you can add whatever extensions you would like.

##### Adding PECL extensions

```Dockerfile
RUN pecl install mongodb \
  && docker-php-ext-enable mongodb
```

```Dockerfile
RUN pecl install redis \
  && docker-php-ext-enable redis
  ```

#### Why not inotify?

https://github.com/docker/for-win/issues/56
