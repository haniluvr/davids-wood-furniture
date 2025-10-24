<?php

/**
 * Script to update all admin route references to use environment-aware routing
 */

$basePath = __DIR__ . '/../';
$files = [
    'app/Http/Controllers/Admin/',
    'resources/views/admin/',
    'app/Http/Middleware/',
];

$replacements = [
    // Controllers - redirect()->route() patterns
    "redirect()->route('admin." => "redirect()->to(admin_route('",
    "redirect()->intended(route('admin." => "redirect()->intended(admin_route('",
    
    // Views - route() patterns
    "route('admin." => "admin_route('",
    "route(\"admin." => "admin_route(\"",
    
    // Middleware patterns
    "return redirect()->route('admin." => "return redirect()->to(admin_route('",
];

function updateFile($filePath, $replacements) {
    if (!file_exists($filePath)) {
        return false;
    }
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        echo "Updated: $filePath\n";
        return true;
    }
    
    return false;
}

function scanDirectory($dir, $replacements) {
    $files = glob($dir . '*.php');
    $updated = 0;
    
    foreach ($files as $file) {
        if (updateFile($file, $replacements)) {
            $updated++;
        }
    }
    
    // Recursively scan subdirectories
    $subdirs = glob($dir . '*', GLOB_ONLYDIR);
    foreach ($subdirs as $subdir) {
        $updated += scanDirectory($subdir . '/', $replacements);
    }
    
    return $updated;
}

echo "Starting admin route updates...\n";

$totalUpdated = 0;
foreach ($files as $dir) {
    $fullPath = $basePath . $dir;
    if (is_dir($fullPath)) {
        echo "Scanning: $fullPath\n";
        $totalUpdated += scanDirectory($fullPath, $replacements);
    }
}

echo "Total files updated: $totalUpdated\n";
echo "Done!\n";
