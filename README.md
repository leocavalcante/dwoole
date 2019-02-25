# Dwoole
[Docker](https://www.docker.com/) image for [Swoole](https://www.swoole.co.uk/) apps with [Composer](https://getcomposer.org/), auto-restart on development and a production-ready version.

## Usage

### Requirements
- `composer.json`
- `index.php` (this will be your entry-point)

Entry-point file can be overridden with the environment variable `ENTRY_POINT_FILE`. See [docker-compose.yml](https://github.com/leocavalcante/siler/blob/master/examples/swoole-chat/docker-compose.yml) for an example.

### Development
Comes bundled with:
- Hot-restart
- PDO (MySQL)
- MongoDB

### Production
Comes bundled with:
- *Nothing*

It is Swoole only. That is because only you knows what your project really needs.
The recommended way to use the production variant is as a base image for you project image.

```Dockerfile
FROM leocavalcante/dwoole:<version>-production
```

Then you can add whatever extensions you would like.

#### Adding PECL extensions
TODO
