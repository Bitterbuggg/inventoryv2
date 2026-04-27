<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.2'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Support legacy URLs like /inventoryv2/public/* when running with `php spark serve`.
if (PHP_SAPI === 'cli-server' && isset($_SERVER['REQUEST_URI']) && is_string($_SERVER['REQUEST_URI'])) {
    $requestUri   = $_SERVER['REQUEST_URI'];
    $requestPath  = parse_url($requestUri, PHP_URL_PATH);
    $legacyPrefix = '/' . basename(dirname(__DIR__)) . '/public';

    if (is_string($requestPath) && ($requestPath === $legacyPrefix || str_starts_with($requestPath, $legacyPrefix . '/'))) {
        $query       = parse_url($requestUri, PHP_URL_QUERY);
        $trimmedPath = substr($requestPath, strlen($legacyPrefix));
        $trimmedPath = $trimmedPath === '' ? '/' : $trimmedPath;

        $_SERVER['REQUEST_URI'] = is_string($query) && $query !== ''
            ? $trimmedPath . '?' . $query
            : $trimmedPath;
    }
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// LOAD OUR PATHS CONFIG FILE
// This is the line that might need to be changed, depending on your folder structure.
require FCPATH . '../app/Config/Paths.php';
// ^^^ Change this line if you move your application folder

$paths = new Paths();

// LOAD THE FRAMEWORK BOOTSTRAP FILE
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
