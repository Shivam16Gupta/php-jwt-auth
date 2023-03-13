<?php
require('appHeaders.php');
require __DIR__.'/classes/Database.php';

//CONNECTION SETUP
$db_connection = new Database();
$conn = $db_connection->dbConnection();

//CAPTURE QUIZ ID FROM USER INPUT
$req_quiz = json_decode(file_get_contents("php://input"));
$quizid = trim($req_quiz->num);

//FETCH QUIZ DATA
$fetchQuery = "SELECT * FROM `quizbank` WHERE `quizid`='" . $quizid . "'";
$query_result = mysqli_query($conn, $fetchQuery);

if ($query_result) {
    //CONVERT QUIZ DATA
    $returnData = json_encode(mysqli_fetch_all($query_result, MYSQLI_ASSOC));
    echo $returnData;

    //CREATE JSON FILE
    $fp = fopen('questions.json', 'w');
    fwrite($fp, $returnData);
    fclose($fp);
} else {
    echo "Error: " . mysqli_error($conn);
}
