<?php
// setting time zone
$timezone = 'Asia/Singapore';

if (PHP_OS_FAMILY === 'Windows' && function_exists('exec')) {
	$output = [];
	$exitCode = 1;
	@exec('tzutil /g 2>NUL', $output, $exitCode);

	if ($exitCode === 0 && isset($output[0])) {
		$windowsTimezone = trim($output[0]);

		if (class_exists('IntlTimeZone') && method_exists('IntlTimeZone', 'getIDForWindowsID')) {
			$ianaTimezone = \IntlTimeZone::getIDForWindowsID($windowsTimezone, '001');
			if (is_string($ianaTimezone) && $ianaTimezone !== '') {
				$timezone = $ianaTimezone;
			}
		} elseif ($windowsTimezone === 'Singapore Standard Time') {
			$timezone = 'Asia/Singapore';
		}
	}
}

date_default_timezone_set($timezone);

// define the site root
define('SITE_ROOT', 'http://localhost/ample/');



// Database Information
// Database Hostname
define('DATABASE_HOST','localhost');
// Database Username
define('DATABASE_USER','root');
// Database Name
define('DATABASE_NAME', 'ample');
// Database DB_PASS
define('DATABASE_PASS','');


 ?>
