<?php declare(strict_types=1);

if (!file_exists('/app/index.php')) {
    echo "Entrypoint file (index.php) not found. It should be on the root directory. Is it there?\n";
    exit(1);
}

use Swoole\Process;
use Swoole\Timer;
use Swoole\Event;

$process = new Process(function (Process $process) {
    echo "ECHO POINT\n";
    $process->exec('/usr/local/bin/php', ['/app/index.php']);
}, true);

echo "Starting process.\n";
$process->start();

if (false === $process->pid) {
    echo swoole_strerror(swoole_errno())."\n";
    exit(1);
}

Event::add($process->pipe, function ($pipe) use ($process) {
    echo $process->read();
});

$files = php_files();
$watch = array_combine($files, array_map('file_hash', $files));

$count = count($watch);
echo "Watching $count files...\n";

Timer::tick(2000, function () use ($watch, $process) {
    foreach ($watch as $pathname => $currrent_hash) {
        $new_hash = file_hash($pathname);

        if ($new_hash != $currrent_hash) {
            echo "Change detected ($pathname). Restarting process...\n";
            $process->exit();

            $watch[$pathname] = $new_hash;

            $pid = $process->start();
            echo "::: 🚀 :::\n";

            continue;
        }
    }
});

function file_hash(string $pathname): string
{
    return md5(file_get_contents($pathname));
}

function php_files(): array
{
    $directory = new RecursiveDirectoryIterator('/app');
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

        return preg_match('/\.php$/', $this->current()->getFilename());
    }
}
