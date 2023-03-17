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

// DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

if ($_SERVER["REQUEST_METHOD"] != "POST") :
    $returnData = msg(0, 404, 'Page Not Found!');
elseif (
    !isset($data->quizid)
    ||!isset($data->email)
    ||!isset($data->unattempted)
    || !isset($data->review)
    || !isset($data->answered)
    //|| !isset($data->score)
    ||empty($data->email)
) :
    $fields = ['fields' => ['quizid','email','unattempted','review', 'answered']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);
else :
    $quizid = trim($data->quizid);
    $email = trim($data->email);
    $unattempted = trim($data->unattempted);
    $review = trim($data->review);
    $answered = trim($data->answered);
    //$score = trim($data->score);
    
    $insert_query = "UPDATE `performance` SET `answered`='$answered',`review`='$review',`unattempted`='$unattempted' WHERE `quizid`='$quizid' AND `email`='$email'";
    
    if(mysqli_query($conn, $insert_query)){
        $returnData = msg(1, 201, 'Performace Recorded');
    } else{
        $returnData = msg(0, 500, mysqli_error($conn));
    }
endif;

echo json_encode($returnData);
