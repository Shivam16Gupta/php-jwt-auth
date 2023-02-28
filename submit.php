<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization,App-Version, X-Requested-With");

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

if ($_SERVER["REQUEST_METHOD"] != "POST") :

    $returnData = msg(0, 404, 'Page Not Found!');

elseif (
    !isset($data->quizid)
    || !isset($data->email)
    || !isset($data->response)
    || empty(trim($data->email))
) :
    echo($data->email);
    //echo(json_encode($data->response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    $response = json_encode($data->response);
    //$escaped_response=$conn->quote($response,PDO::PARAM_STR);
    echo(var_dump($response));
    //echo(var_dump($escaped_response));
    echo($response);
    //echo($escaped_response);
    $fields = ['fields' => ['quizid','email', 'response']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else :
    $quizid=trim($data->quizid);
    $email=trim($data->email);
    //$response=array_map('utf8_encode',$data->response);
    $response = json_encode($data->response);
    //$escaped_response=$conn->quote($response,PDO::PARAM_STR);
    
        try {
                $insert_query = "INSERT INTO `score`(`quizid`,`email`,`data`,`date`,`time`,`ip`) VALUES(:quizid,:email,:data,:date,:time,:ip)";

                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bindValue(':quizid', $quizid, PDO::PARAM_STR);
                $insert_stmt->bindValue(':email', $email, PDO::PARAM_STR);
                $insert_stmt->bindValue(':data', $response,PDO::PARAM_STR);
                $insert_stmt->bindValue(':date', $date, PDO::PARAM_STR);
                $insert_stmt->bindValue(':time', $time, PDO::PARAM_STR);
                $insert_stmt->bindValue(':ip', $ip_address,PDO::PARAM_STR);
                
                $insert_stmt->execute();
                $returnData = msg(1, 201, 'You have successfully submitted the test.');

            
        } catch (PDOException $e) {
            $returnData = msg(0, 500, $e->getMessage());
        }
    endif;


echo json_encode($returnData);