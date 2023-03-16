<?php
require('appHeaders.php');
header('Content-Type:multipart/form-data');
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
$returnData = [];

// DATA FORM REQUEST
//$data = json_decode(file_get_contents("php://input"));
//echo(json_encode($_POST));
if ($_FILES["banner"]["error"] == UPLOAD_ERR_OK) {
    $temp_name = $_FILES["banner"]["tmp_name"];
    $name = $_FILES["banner"]["name"];
    move_uploaded_file($temp_name, "uploads/$name");
    echo "File uploaded successfully";
  } else {
    echo "Error uploading file";
  }
//$name=mysqli_real_escape_string($conn,$name);
$imagePath=mysqli_real_escape_string($conn,"/uploads/".basename($name));
$quizid=$_POST['quizid'];
$title=$_POST['title'];
$desc=$_POST['desc'];
$totalQ=$_POST['totalQ'];
$nmarks=$_POST['nmarks'];
$pmarks=$_POST['pmarks'];
$mmarks=$_POST['mmarks'];
$dur=$_POST['duration'];
$res=$_POST['res'];
$host=$_POST['host'];
$paid=$_POST['paid'];
$tags=$_POST['tags'];
$author=$_POST['author'];

$insert_query="INSERT INTO `quizinfo`(`quizid`,`title`,`description`,`banner`,`totalquestions`,`negativemarks`,`positivemarks`,`maxmarks`,`duration_hrs`,`showresult`,`host`,`paid`,`tags`,`author`) VALUES ('$quizid','$title','$desc','$imagePath','$totalQ','$nmarks','$pmarks','$mmarks','$dur','$res','$host','$paid','$tags','$author')";

$insert_result = mysqli_query($conn,$insert_query);

    if ($insert_result) {
        $returnData = msg(1, 201, 'You have successfully created a record!');
    } else {
       
        $returnData = msg(0,  mysqli_errno($conn), mysqli_error($conn));
    }

echo json_encode($returnData);
