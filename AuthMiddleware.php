<?php
require __DIR__ . '/classes/JwtHandler.php';

class Auth extends JwtHandler
{
    protected $db;
    protected $headers;
    protected $token;
    protected $ver;

    public function __construct($db, $headers)
    {
        parent::__construct();
        $this->db = $db;
        $this->headers = $headers;
    }

    // public function verifyVersion()
    // {
    //     $db = parse_ini_file(dirname(__DIR__) . "/DbProperties.ini");
    //     $versionMatched = false;
    //     echo($db);
    //     if ($role == 'ADMIN') {
    //         if ($db['admin_app_version'] == $admin_app_version) {
    //             $versionMatched = true;
    //         }
    //     } else {
    //     if ($db['app_version'] == 1.0) {
    //         $versionMatched = true;
    //     }
    //     }
    //     if (!$versionMatched) {
    //         return false;
    //     } else {
    //         return true;
    //     }
    // }

    public function isValid()
    {   $db = parse_ini_file(dirname(__DIR__) . "\php-auth-api\DbProperties.ini");

        if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

            if(array_key_exists('App-Version', $this->headers)){

                forEach($this->headers as $name=>$value)
                {
                    if (strtoupper($name) == "APP-VERSION") {
                        $this->ver =  $value;
                      }
                }
            }

            $data = $this->jwtDecodeData($matches[1]);

            if (
                isset($data['data']->user_id) &&
                $db['app_version']== $this->ver &&
                $user = $this->fetchUser($data['data']->user_id)
                
            ) {
                
                return [
                    "success" => 1,
                    "user" => $user,
                    "version"=>$this->ver
                ];}
            else {
                echo($this->ver+$db['app_version']);
                return [
                    "success" => 0,
                    "message" => "Decode Failed",
                    "version" => false
                ];}
            
        } else {
            return [
                "success" => 0,
                "message" => "Token not found in request"
            ];
        }
    }

    

    protected function fetchUser($user_id)
    {
        try {
            $fetch_user_by_id = "SELECT `name`,`email` FROM `users` WHERE `id`=:id";
            $query_stmt = $this->db->prepare($fetch_user_by_id);
            $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
            $query_stmt->execute();

            if ($query_stmt->rowCount()) :
                return $query_stmt->fetch(PDO::FETCH_ASSOC);
            else :
                return false;
            endif;
        } catch (PDOException $e) {
            return null;
        }
    }
}
