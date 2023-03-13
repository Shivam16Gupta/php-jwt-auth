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

if ($_SERVER["REQUEST_METHOD"] != "POST") :

    $returnData = msg(0, 404, 'Page Not Found!');

elseif (
    !isset($data->name)
    || !isset($data->email)
    || !isset($data->phone)
    || !isset($data->gender)
    || !isset($data->dob)
    || !isset($data->add)
    || !isset($data->city)
    || !isset($data->country)
    || !isset($data->qualification)
    || !isset($data->tags)
    || empty($data->email)
) :
    echo ($data->name);
    echo ($data->email);
    echo ($data->phone);
    echo ($data->gender);
    echo ($data->dob);
    echo ($data->add);
    echo ($data->city);
    echo ($data->country);
    echo ($data->qualification);
    echo ($data->tags);

    $fields = ['fields' => ['quizid', 'email', 'unattempted', 'review', 'answered', 'score']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else :
    $name = trim($data->name);
    $email = trim($data->email);
    $phone = trim($data->phone);
    $gender = trim($data->gender);
    $dob = trim($data->dob);
    $add = trim($data->add);
    $city = trim($data->city);
    $country = trim($data->country);
    $qualification = trim($data->qualification);
    $tags = trim($data->tags);
    
    try {
        $insert_query = "UPDATE `profile` SET `name`='$name',`phone`='$phone',`gender`='$gender',`dob`='$dob',`address`='$add',`city`='$city',`country`='$country',`qualification`='$qualification',`tags`='$tags' WHERE `email`='$email'";

        $stmt = $conn->query($insert_query);
        if ($stmt) {
            $returnData = msg(1, 201, 'Profile Recorded');
        } else {
            $returnData = msg(0, 500, 'Unable to update profile');
        }
    } catch (Exception $e) {
        $returnData = msg(0, 500, $e->getMessage());
    }
endif;


echo json_encode($returnData);
