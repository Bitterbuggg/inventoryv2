<?php

use CodeIgniter\HTTP\ResponseInterface;

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (! function_exists('app_forbidden_response')) {
    function app_forbidden_response(
        ResponseInterface $response,
        string $message = 'Your account does not have permission to access this page.'
    ): ResponseInterface {
        return $response
            ->setStatusCode(403, 'Forbidden')
            ->setHeader('Content-Type', 'text/html; charset=UTF-8')
            ->setBody((string) view('errors/html/error_403', ['message' => $message]));
    }
}
