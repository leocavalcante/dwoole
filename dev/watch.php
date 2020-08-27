<?php declare(strict_types=1);

$env_vars = [
    'PHP_BIN' => '/usr/local/bin/php',
    'WATCH_DIR' => '/app',
    'ENTRY_POINT_FILE' => '/app/index.php',
    'WATCH_LIST' => 'php,phtml,twig',
    'DEBUG' => false,
    'WATCH_INTERVAL' => 2000,
];

foreach ($env_vars as $var => $default) {
    $val = getenv($var);
    define($var, $val === false ? $default : $val);
}

if (!file_exists(ENTRY_POINT_FILE)) {
    echo "Entry-point file (" . ENTRY_POINT_FILE . ") not found. It should be on the root directory. Is it there?\n";
    exit(1);
}

use Swoole\Process;

$hashes = [];
/** @var Process|null $serve */
$serve = null;

state();
start();
watch();

function start()
{
    global $serve;

    $serve = new Process('serve');
    $serve->start();

    echo "🚀 Ready\n";
}

function state()
{
    global $hashes;

    $files = php_files(WATCH_DIR);
    $hashes = array_combine($files, array_map('file_hash', $files));
    $count = count($hashes);

    echo "📡 Watching $count files (interval " . WATCH_INTERVAL . "ms)\n";
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
            break;
        }
    }

    usleep((int)WATCH_INTERVAL * 1000);
    watch();
}

function change()
{
    global $serve;

    echo "🔄 Change detected\n";

    Process::kill($serve->pid);
    Process::wait();

    state();
    start();
    watch();
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
