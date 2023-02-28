<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/classes/Database.php';

//CONNECTION SETUP
$db_connection= new Database();
$conn=$db_connection->dbConnection();

//CAPTURE QUIZ ID FROM USER INPUT
$req_quiz=json_decode(file_get_contents("php://input"));
//$quizid=intval($req_quiz);
$quizid=trim($req_quiz->num);
//$quizid=json_encode($req_quiz->num);

//FETCH QUIZ DATA
$fetchQuery="SELECT * FROM `quizbank` WHERE `quizid`='".$quizid."'";
$query_stmt=$conn->prepare($fetchQuery);
//$query_stmt->bindValue(':quizid',1,PDO::PARAM_INT);
$query_stmt->execute();

//CONVERT QUIZ DATA
$returnData=json_encode($query_stmt->fetchAll(PDO::FETCH_ASSOC));
echo $returnData;

//CREATE JSON FILE
$fp=fopen('questions.json','w');
fwrite($fp, $returnData);
fclose($fp);
?>