<?php
require('appHeaders.php');
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
if ($_SERVER["REQUEST_METHOD"] != "GET") :

    $returnData = msg(0, 404, 'Page Not Found!');

else:
    
    //FETCH QUIZ INFO
    try {
        $fetchQuery = "SELECT * FROM `quizinfo` WHERE host=1 ";
        $query_result = mysqli_query($conn, $fetchQuery);
        
        if ($query_result) {
            $returnData = msg(1, 201, 'Browsed all quizes.');
            $rows = array();
            while ($row = mysqli_fetch_assoc($query_result)) {
                $rows[] = $row;
            }
            $returnData = $rows;
        } else {
            $returnData = msg(0, 500, mysqli_error($conn));
        }

    } catch (Exception $e) {
        $returnData = msg(0, 500, $e->getMessage());
    }

endif;

echo json_encode($returnData);

//CREATE JSON FILE
$fp = fopen('gallery.json', 'w');
fwrite($fp, json_encode($returnData));
fclose($fp);
