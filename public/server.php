<?php
$publicPath = getcwd();
$uri = urldecode(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) ?? "");
// Handle storage via direct PHP response to avoid 403 on Windows symlink junction
if (str_starts_with($uri, "/storage/")) {
    $target = realpath(dirname($publicPath) . "/storage/app/public/" . substr($uri, 9));
    if ($target && file_exists($target) && is_file($target)) {
        $mime = mime_content_type($target) ?: "application/octet-stream";
        header("Content-Type: " . $mime);
        header("Content-Length: " . filesize($target));
        readfile($target);
        exit;
    }
}
if ($uri !== "/" && file_exists($publicPath.$uri)) {
    return false;
}
require_once $publicPath."/index.php";
