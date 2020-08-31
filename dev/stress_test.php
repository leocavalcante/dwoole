<?php declare(strict_types=1);

while (true) {
    $handle = fopen(__DIR__ . '/example/stress.php', b'w');
    fwrite($handle, uniqid());
    fclose($handle);
    usleep(1);
}
