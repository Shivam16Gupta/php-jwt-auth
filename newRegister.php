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
//$data = json_decode(file_get_contents("php://input"));
$returnData = [];
$name = trim($_POST['Name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
if ($_SERVER["REQUEST_METHOD"] != "POST") :
    $returnData = msg(0, 404, 'Page Not Found!');
elseif (
    !isset($name)
    || !isset($email)
    || !isset($password)
    || empty(trim($name))
    || empty(trim($email))
    || empty(trim($password))
) :
    $fields = ['fields' => ['name', 'email', 'password']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);
else :
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
        $returnData = msg(0, 422, 'Invalid Email Address!');
    elseif (strlen($password) < 8) :
        $returnData = msg(0, 422, 'Your password must be at least 8 characters long!');
    elseif (strlen($name) < 3) :
        $returnData = msg(0, 422, 'Your name must be at least 3 characters long!');
    else :
        $check_email = "SELECT `email` FROM `users` WHERE `email`='" . $email . "'";
        $check_email_stmt = $conn->query($check_email);
        if (mysqli_num_rows($check_email_stmt)) :
            $returnData = msg(0, 422, 'This E-mail already in use!');
        else :
            $insert_query = "INSERT INTO `users`(`name`,`email`,`password`) VALUES('" . htmlspecialchars(strip_tags($name)) . "','" . $email . "','" . $password . "')";
            $insert_profile = "INSERT INTO `profile`(`name`,`email`) VALUES('" . htmlspecialchars(strip_tags($name)) . "','" . $email . "')";
            $conn->query($insert_query);
            $conn->query($insert_profile);
            $returnData = msg(1, 201, 'You have successfully registered.');
        endif;
    endif;
endif;

echo json_encode($returnData);
