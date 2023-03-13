<?php
require('appHeaders.php');
require __DIR__.'/classes/Database.php';

//CONNECTION SETUP
$db_connection= new Database();
$conn=$db_connection->dbConnection();

//CAPTURE QUIZ ID FROM USER INPUT
$req_quiz=json_decode(file_get_contents("php://input"));

//$quizid=intval($req_quiz);
$email=trim($req_quiz->email);

//FETCH QUIZ DATA
$fetchQuery="SELECT * FROM `quizinfo` LEFT JOIN `score` on score.quizid=quizinfo.quizid LEFT JOIN `performance` on score.email=performance.email WHERE score.email='".$email."' and quizinfo.showresult='1'";
$query_result = mysqli_query($conn, $fetchQuery);
$result_array = array();
while($row = mysqli_fetch_assoc($query_result)) {
    $result_array[] = $row;
}

//CONVERT QUIZ DATA
$returnData=json_encode($result_array);
echo $returnData;

//CREATE JSON FILE
$fp=fopen('score.json','w');
fwrite($fp, $returnData);
fclose($fp);
