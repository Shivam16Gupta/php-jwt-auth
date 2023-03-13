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


//echo(base64_decode($data->banner));
if ($_SERVER["REQUEST_METHOD"] != "POST") :

    $returnData = msg(0, 404, 'Page Not Found!');

elseif (
    !isset($data->quizid)
    || !isset($data->desc)
    || !isset($data->banner)
    || !isset($data->totalQ)
    || !isset($data->nmarks)
    || !isset($data->pmarks)
    || !isset($data->mmarks)
    || !isset($data->duration)
    || !isset($data->res)
    || !isset($data->host)
    || !isset($data->author)
) :
    // echo($data->quizid);
    // echo($data->desc);
    // echo($data->banner);
    // echo($data->totalQ);
    // echo($data->nmarks);
    // echo($data->pmarks);
    // echo($data->mmarks);
    // echo($data->duration);
    // echo($data->res);
    // echo($data->host);
    // echo($data->author);

    $fields = ['fields' => ['quizid', 'description', 'banner', 'totalquestions', 'negativemarks', 'positivemarks', 'maxmarks', 'duration_hrs', 'showresult', 'host', 'author']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else :


    $quizid = trim($data->quizid);
    $desc = trim($data->desc);
    $banner=trim($data->banner);
    $totalQ = trim($data->totalQ);
    $nmarks = trim($data->nmarks);
    $pmarks = trim($data->pmarks);
    $mmarks = trim($data->mmarks);
    $duration = trim($data->duration);
    $res = trim($data->res);
    $host = trim($data->host);
    $author = trim($data->author);
    // $fileinfo=pathinfo($banner,PATHINFO_EXTENSION);
    // echo($fileinfo);
    //$banner=addslashes($banner);

    try {
        

               
                $insert_query = "INSERT INTO `quizinfo`(`quizid`,`description`,`banner`,`totalquestions`,`negativemarks`,`positivemarks`,`maxmarks`,`duration_hrs`,`showresult`,`host`,`author`) VALUES('$quizid','$desc','$banner','$totalQ','$nmarks','$pmarks','$mmarks','$duration','$res','$host','$author')";

                $insert_stmt = $conn->prepare($insert_query);

                $insert_stmt->execute();
                $returnData = msg(1, 201, 'Information Recorded');
            
        
    } catch (mysqli_sql_exception $e) {
        $returnData = msg(0, 500, $e->getMessage());
    }
endif;


echo json_encode($returnData);
