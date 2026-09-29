<?php
// Dev helper: recursively find which files reference Botble license/marketplace URLs.
$roots = ['vendor/botble', 'platform/core', 'platform/packages', 'platform/plugins', 'app'];
$needles = ['license.botble.com', 'marketplace.botble.com'];

foreach ($roots as $root) {
    if (! is_dir($root)) {
        continue;
    }
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $f) {
        if (! $f->isFile() || ! preg_match('/\.(php|json|js|env|blade\.php)$/', $f->getPathname())) {
            continue;
        }
        $content = @file_get_contents($f->getPathname());
        if ($content === false) {
            continue;
        }
        foreach ($needles as $needle) {
            if (strpos($content, $needle) !== false) {
                echo $f->getPathname() . ' => ' . $needle . PHP_EOL;
            }
        }
    }
}
echo 'SEARCH_DONE' . PHP_EOL;
