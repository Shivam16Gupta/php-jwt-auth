<?php
require('appHeaders.php');
require __DIR__ . '/classes/Database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

function msg($success, $status, $message, $extra = [])
{
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra);
}

// VISITING USER SYSTEM INFO
$ip_address = $_SERVER["REMOTE_ADDR"]; 
date_default_timezone_set('Asia/Kolkata');
$date = date("Y-m-d");
$time = date("H:i:s");

// DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];
//echo(json_encode($data));
// if ($_SERVER["REQUEST_METHOD"] != "POST" || $_SERVER["REQUEST_METHOD"] != "OPTIONS") :
//     $returnData = msg(0, 404, 'Page Not Found!');
// else 
if (
    !isset($data->quizid)
    || !isset($data->email)
    || !isset($data->response)
    || empty(trim($data->email))
) :
    $fields = ['fields' => ['quizid','email', 'response']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);
else :
    $quizid = trim($data->quizid);
    $email = trim($data->email);
    $response = json_encode($data->response);
    $response=addslashes($response);
    //echo(json_encode($data));
    $insert_query = "INSERT INTO `score` (`quizid`,`email`,`data`,`date`,`time`,`ip`) VALUES('$quizid','$email','$response','$date','$time','$ip_address')";
    $insert_result = mysqli_query($conn,$insert_query);

    if ($insert_result) {
        $returnData = msg(1, 201, 'You have successfully submitted the test.');
    } else {
       
        $returnData = msg(0,  mysqli_errno($conn), mysqli_error($conn));
    }
    //echo(json_encode($data));
endif;

echo json_encode($returnData);
