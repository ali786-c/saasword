<?php
// Dev audit helper: scan every vendor + platform source file for outbound HTTP
// to any botble.com host (license / marketplace / update phone-home).
$roots = ['vendor', 'platform', 'app', 'config', 'routes'];
$needles = ['botble.com'];

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
        if (str_contains($f->getPathname(), 'find-phone-home') || str_contains($f->getPathname(), 'audit-phone-home')) {
            continue;
        }
        $content = @file_get_contents($f->getPathname());
        if ($content === false) {
            continue;
        }
        foreach ($needles as $needle) {
            if (stripos($content, $needle) !== false) {
                echo $f->getPathname() . PHP_EOL;
            }
        }
    }
}
echo 'AUDIT_DONE' . PHP_EOL;
