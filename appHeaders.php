<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PATCH, OPTIONS');
header('Access-Control-Expose-Headers: APP-VERSION, ADMIN-APP-VERSION');

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, Authentication,Admin-App-Version,App-Version, X-Requested-With");

$db = parse_ini_file(dirname(__DIR__) . "\php-auth-api\DbProperties.ini");
header("APP_VERSION: " . $db['app_version'] . "");
header("ADMIN_APP_VERSION: " . $db['admin_app_version'] . "");

// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     exit(0);
// }