<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__ . '/classes/Database.php';

//CONNECTION SETUP
$db_connection = new Database();
$conn = $db_connection->dbConnection();

//MSG
function msg($success, $status, $message, $extra = [])
{
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra);
}

// FETCH INPUT FROM CLIENT

$returnData=[];
//echo($data->email);
//
if ($_SERVER["REQUEST_METHOD"] != "POST") :

    $returnData = msg(0, 404, 'Page Not Found!');

else:
    
    //FETCH QUIZ INFO
    try {
        $fetchQuery = "SELECT * FROM `quizinfo` WHERE host=1 ";
        $query_stmt = $conn->prepare($fetchQuery);
        $query_stmt->execute();
        $returnData = msg(1, 201, 'You have successfully submitted the test.');
    } catch (PDOException $e) {
        $returnData = msg(0, 500, $e->getMessage());
    }
    //CONVERT QUIZ DATA
    $returnData = json_encode($query_stmt->fetchAll(PDO::FETCH_ASSOC));
    echo $returnData;
endif;
//CREATE JSON FILE
$fp = fopen('gallery.json', 'w');
fwrite($fp, $returnData);
fclose($fp);
