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
//$data = json_decode(file_get_contents("php://input"));
$returnData=[];
//echo($data->email);
//
$email = trim($_POST['email']);
$par = trim($_POST['search']);
if ($_SERVER["REQUEST_METHOD"] != "POST") :

    $returnData = msg(0, 404, 'Page Not Found!');

elseif (
    !isset($email)
    || empty($email)
) :
    echo (json_encode($email));

    $fields = ['fields' => ['email']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);

else :

    
    
    //FETCH QUIZ INFO
    try {
        $fetchQuery = "SELECT * FROM `quizinfo` WHERE host='1' AND tags LIKE '$par' AND (quizid NOT IN (SELECT quizid FROM `score` WHERE email='" . $email . "'))";
        $query_result = mysqli_query($conn, $fetchQuery);
        $returnData = msg(1, 201, 'You have successfully submitted the test.');
    } catch (Exception $e) {
        $returnData = msg(0, 500, $e->getMessage());
    }

    //CONVERT QUIZ DATA
    $quiz_data = array();
    while ($row = mysqli_fetch_assoc($query_result)) {
        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/php-auth-api'.$row['banner'])){
            $row['banner'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/php-auth-api'.$row['banner']));
            //echo json_encode(base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/php-auth-api'.$row['banner'])));
        }
        $quiz_data[] = $row;
    }
    $returnData = json_encode($quiz_data);
    echo $returnData;
endif;
//CREATE JSON FILE
$fp = fopen('quizInfo.json', 'w');
fwrite($fp, $returnData);
fclose($fp);
