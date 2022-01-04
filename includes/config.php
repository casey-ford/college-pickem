<?php
//modify vars below
// Database
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '409wHUR0N!');
define('DB_DATABASE', 'pickem');
define('DB_PREFIX', '');

define('SITE_URL', 'https://www.collegepickem2021.online:8443/');
define('ALLOW_SIGNUP', true);
define('SHOW_SIGNUP_LINK', true);
define('USER_NAMES_DISPLAY', 1); // 1 = real names, 2 = usernames, 3 = usernames w/ real names on hover
define('COMMENTS_SYSTEM', 'basic'); // basic, disqus, or disabled
define('DISQUS_SHORTNAME', ''); // only needed if using Disqus for comments

define('SEASON_YEAR', '2021');
define('SERVER_TIMEZONE', 'America/New_York'); // Your SERVER's timezone. NOTE: Game times will always be displayed in Eastern time, as they are on NFL.com. This setting makes sure cutoff times work properly.

// ***DO NOT EDIT ANYTHING BELOW THIS LINE***
error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);

//automatically set timezone offset (hours difference between your server's timezone and eastern time)
date_default_timezone_set(SERVER_TIMEZONE);
/*$timeZoneCurrent = @date_default_timezone_get();
if (ini_get('date.timezone')) {
	$timeZoneCurrent = ini_get('date.timezone');
}*/
$dateTimeZoneCurrent = new DateTimeZone(SERVER_TIMEZONE);
$dateTimeZoneEastern = new DateTimeZone("America/New_York");
$dateTimeCurrent = new DateTime("now", $dateTimeZoneCurrent);
$dateTimeEastern = new DateTime("now", $dateTimeZoneEastern);
$offsetCurrent = $dateTimeCurrent->getOffset();
$offsetEastern = $dateTimeEastern->getOffset();
$offsetHours = ($offsetEastern - $offsetCurrent) / 3600;
define('SERVER_TIMEZONE_OFFSET', $offsetHours);
