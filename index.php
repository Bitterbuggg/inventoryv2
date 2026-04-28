<?php

declare(strict_types=1);

/*
 * Root front controller for XAMPP/shared-host installs where Apache points at
 * the project folder instead of the `public/` directory.
 */
require __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'index.php';
