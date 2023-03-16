<?php
require('appHeaders.php');
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


    public function isValid()
    {   $db = parse_ini_file(dirname(__DIR__) . "\php-auth-api\DbProperties.ini");

        if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {
            
            if(array_key_exists('AdminApp', $this->headers)){

                forEach($this->headers as $name=>$value)
                {
                    if (strtoupper($name) == "ADMINAPP") {
                        $this->ver =  $value;
                      }
                }
            }

            $data = $this->jwtDecodeData($matches[1]);
            //echo(json_encode($data));
            if (
                isset($data['data']->user_id) &&
                $db['admin_app_version']== $this->ver &&
                $user = $this->fetchUser($data['data']->user_id)
                
            ) {
                
                return [
                    "success" => 1,
                    "user" => $user,
                    "version"=>$this->ver
                ];}
            else {
                //echo($this->ver+" "+$db['app_version']+" "+$data['data']->user_id+" "+$db['app_version']== $this->ver+" "+$this->fetchUser($data['data']->user_id));
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
            $fetch_user_by_id = "SELECT * FROM `admin` WHERE email='".$user_id."'";
            $query_stmt = mysqli_query($this->db,($fetch_user_by_id));
           // $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
            //$query_stmt->execute();

            if ($query_stmt && mysqli_num_rows($query_stmt)) :
                return mysqli_fetch_assoc($query_stmt);
            else :
                return false;
            endif;
        } catch (Exception $e) {
            return $e;
        }
    }
}
