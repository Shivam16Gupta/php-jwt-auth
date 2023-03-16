<?php
require('appHeaders.php');
//header("Content-Type: application/json; charset=UTF-8");
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
//echo(var_dump($_POST));
$par=($_POST['search']);
if ($_SERVER["REQUEST_METHOD"] != "POST") :

    $returnData = msg(0, 404, 'Page Not Found!');

else:
    
    //FETCH QUIZ INFO
    try {
        $fetchQuery = "SELECT * FROM `quizinfo` WHERE host=1 and tags like '$par'";
        //echo($fetchQuery);
        $query_result = mysqli_query($conn, $fetchQuery);
        
        if ($query_result) {
            $returnData = msg(1, 201, 'Browsed all quizes.');
            $rows = array();
            while ($row = mysqli_fetch_assoc($query_result)) {
                if(file_exists($_SERVER['DOCUMENT_ROOT'].'/php-auth-api'.$row['banner'])){
                    $row['banner'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/php-auth-api'.$row['banner']));
                    //echo json_encode(base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/php-auth-api'.$row['banner'])));
                }
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
//header("Content-Type: image/*");
echo json_encode($returnData);

//CREATE JSON FILE
$fp = fopen('gallery.json', 'w');
fwrite($fp, json_encode($returnData));
fclose($fp);
