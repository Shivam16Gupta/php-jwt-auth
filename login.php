<?php
require('appHeaders.php');
require __DIR__.'/classes/Database.php';
require __DIR__.'/classes/JwtHandler.php';

function msg($success, $status, $message, $extra = []) {
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra);
}

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$data = json_decode(file_get_contents("php://input"));
$returnData = [];

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $returnData = msg(0, 404, 'Page Not Found!');
} elseif (!isset($data->email) || !isset($data->password) || empty(trim($data->email)) || empty(trim($data->password))) {
    $fields = ['fields' => ['email', 'password']];
    $returnData = msg(0, 422, 'Please Fill in all Required Fields!', $fields);
} else {
    $email = trim($data->email);
    $password = trim($data->password);
    $fetch_user_by_email = "SELECT * FROM `users` JOIN `profile` ON users.email = profile.email WHERE users.email = '$email'";
    $result = mysqli_query($conn, $fetch_user_by_email);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($password == $row['password']) {
            $jwt = new JwtHandler();
            $token = $jwt->jwtEncodeData(
                'http://localhost/php-auth-api/',
                array("user_id"=> $row['email'])
            );
            $returnData = [
                'success' => 1,
                'message' => 'You have successfully logged in.',
                'token' => $token
            ];
        } else {
            $returnData = msg(0, 422, 'Invalid Password!');
        }
    } else {
        $returnData = msg(0, 422, 'Invalid Email Address!');
    }
}

echo json_encode($returnData);

