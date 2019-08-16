<?php declare(strict_types=1);

const RESTART_CMD = '@restart';

$env_vars = [
    'PHP_BIN' => '/usr/local/bin/php',
    'WATCH_DIR' => '/app',
    'ENTRY_POINT_FILE' => '/app/index.php',
    'WATCH_LIST' => 'php,phtml,twig,env'
];

foreach ($env_vars as $var => $default) {
    $val = getenv($var);
    define($var, $val === false ? $default : $val);
}

if (!file_exists(ENTRY_POINT_FILE)) {
    echo "Entry-point file (".ENTRY_POINT_FILE.") not found. It should be on the root directory. Is it there?\n";
    exit(1);
}

use Swoole\Process;
use Swoole\Timer;
use Swoole\Event;

swoole_async_set(['enable_coroutine' => false]);

$hashes = [];
$serve = null;

start();
state();
Timer::tick(2000, 'watch');

function start()
{
    global $serve;

    echo "🚀 Start\n";

    $serve = new Process('serve', true);
    $serve->start();

    if (false === $serve->pid) {
        echo swoole_strerror(swoole_errno())."\n";
        exit(1);
    }

    Event::add($serve->pipe, function ($pipe) use (&$serve) {
        $message = $serve->read();

        if (!empty($message)) {
            echo $message;
        }
    });
}

function watch()
{
    global $hashes;

    foreach ($hashes as $pathname => $current_hash) {
        if (!file_exists($pathname)) {
            unset($hashes[$pathname]);
            continue;
        }

        $new_hash = file_hash($pathname);
        if ($new_hash != $current_hash) {
            change();
            state();
            break;
        }
    }
}

function state()
{
    global $hashes;

    $files = php_files(WATCH_DIR);
    $hashes = array_combine($files, array_map('file_hash', $files));
    $count = count($hashes);

    echo "📡 Watching $count files...\n";
}

function change()
{
    global $serve;

    echo "🔄 Change detected!\n";

    Process::kill($serve->pid);
    start();
}

function serve(Process $serve)
{
    $serve->exec(PHP_BIN, [ENTRY_POINT_FILE]);
}

function file_hash(string $pathname): string
{
    $contents = file_get_contents($pathname);

    // File may be deleted on the fly
    if (false === $contents) {
        return 'deleted';
    }

    return md5($contents);
}

function php_files(string $dirname): array
{
    $directory = new RecursiveDirectoryIterator($dirname);
    $filter = new Filter($directory);
    $iterator = new RecursiveIteratorIterator($filter);

    return array_map(function ($fileInfo) {
        return $fileInfo->getPathname();
    }, iterator_to_array($iterator));
}

class Filter extends RecursiveFilterIterator
{
    public function accept()
    {
        if ($this->current()->isDir()) {
            if (preg_match('/^\./', $this->current()->getFilename())) {
                return false;
            }

            return !in_array($this->current()->getFilename(), ['vendor']);
        }

        $list = array_map(function (string $item): string {
            return "\.$item";
        }, explode(',', WATCH_LIST));

        $list = implode('|', $list);

        return preg_match("/($list)$/", $this->current()->getFilename());
    }
}
