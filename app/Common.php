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

if (! function_exists('app_format_quantity')) {
    /**
     * Quantity fields are stored in DECIMAL columns, but most workflows expect
     * whole-number display unless there is a real fractional value to show.
     */
    function app_format_quantity(
        mixed $value,
        string $empty = '0',
        int $precision = 3,
        bool $useGrouping = true
    ): string {
        if ($value === null) {
            return $empty;
        }

        if (is_string($value)) {
            $value = trim($value);

            if ($value === '') {
                return $empty;
            }
        }

        if (! is_numeric($value)) {
            return (string) $value;
        }

        $number = (float) $value;
        $thousandsSeparator = $useGrouping ? ',' : '';

        if (abs($number - round($number)) <= 0.00001) {
            return number_format((float) round($number), 0, '.', $thousandsSeparator);
        }

        return rtrim(
            rtrim(number_format($number, $precision, '.', $thousandsSeparator), '0'),
            '.'
        );
    }
}
