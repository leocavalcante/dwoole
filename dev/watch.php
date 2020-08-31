<?php declare(strict_types=1);

use Swoole\Process;

define('PHP_BIN', getenv('PHP_BIN') ?: '/usr/local/bin/php');
define('WATCH_DIR', getenv('WATCH_DIR') ?: '/app');
define('ENTRY_POINT_FILE', getenv('ENTRY_POINT_FILE') ?: '/app/index.php');
define('WATCH_LIST', getenv('WATCH_LIST') ?: 'php,phtml,twig');
define('DEBUG', getenv('DEBUG') ?: false);
define('WATCH_INTERVAL', getenv('WATCH_INTERVAL') ?: 2000);

if (!file_exists(ENTRY_POINT_FILE)) {
    echo "Entry-point file (" . ENTRY_POINT_FILE . ") not found. It should be on the root directory. Is it there?\n";
    exit(1);
}

$hashes = [];
/** @var Process|null $serve */
$serve = null;
$changes_counter = 0;

state();
start();

while (true) {
    watch();
    usleep((int)WATCH_INTERVAL * 1000);
}

function start()
{
    global $serve;

    $serve = new Process('serve', false, 0, false);
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
        if ($new_hash !== $current_hash) {
            change();
            break;
        }
    }
}

function change()
{
    global $serve, $changes_counter;

    $changes_counter++;
    echo "🔄 Changes detected ($changes_counter)\n";

    Process::kill($serve->pid);
    Process::wait();

    state();
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
