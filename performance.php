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
    !isset($data->quizid)
    ||!isset($data->email)
    ||!isset($data->unattempted)
    || !isset($data->review)
    || !isset($data->answered)
    || !isset($data->score)
    ||empty($data->email)
) :
    echo($data->quizid);
    echo($data->email);
    echo($data->unattempted);
    echo($data->review);
    echo($data->answered);
    echo($data->score);
    
    $fields = ['fields' => ['quizid','email','unattempted','review', 'answered','score']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else :
    $quizid=trim($data->quizid);
    $email=trim($data->email);
    $unattempted=trim($data->unattempted);
    $review=trim($data->review);
    $answered=trim($data->answered);
    $score=trim($data->score);
    
        try {
                $insert_query = "INSERT INTO `performance`(`quizid`,`email`,`answered`,`review`,`unattempted`,`score`) VALUES(:quizid,:email,:answered,:review,:unattempted,:score)";

                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bindValue(':quizid', $quizid, PDO::PARAM_STR);
                $insert_stmt->bindValue(':email', $email, PDO::PARAM_STR);
                $insert_stmt->bindValue(':answered', $answered,PDO::PARAM_STR);
                $insert_stmt->bindValue(':review', $review, PDO::PARAM_STR);
                $insert_stmt->bindValue(':unattempted', $unattempted, PDO::PARAM_STR);
                $insert_stmt->bindValue(':score', $score,PDO::PARAM_STR);
                
                $insert_stmt->execute();
                $returnData = msg(1, 201, 'Performace Recorded');

            
        } catch (PDOException $e) {
            $returnData = msg(0, 500, $e->getMessage());
        }
    endif;


echo json_encode($returnData);