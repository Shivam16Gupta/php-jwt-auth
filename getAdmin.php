<?php
require('appHeaders.php');
require __DIR__.'/classes/Database.php';
require __DIR__.'/adminAuthMiddleware.php';

$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$auth = new Auth($conn, $allHeaders);
//echo(json_encode($allHeaders));
echo json_encode($auth->isValid());