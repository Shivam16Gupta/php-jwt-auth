<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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
    !isset($data->email)
    || !isset($data->response)
    || empty(trim($data->email))
) :
    echo($data->email);
    echo($data->response);
    $fields = ['fields' => ['email', 'response']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else :
    $email=trim($data->email);
    $response = json_encode($data->response);
        try {
                $insert_query = "INSERT INTO `score`(`email`,`data`) VALUES('".$email."','".$response."')";

                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->execute();
                $returnData = msg(1, 201, 'You have successfully registered.');

            
        } catch (PDOException $e) {
            $returnData = msg(0, 500, $e->getMessage());
        }
    endif;


echo json_encode($returnData);