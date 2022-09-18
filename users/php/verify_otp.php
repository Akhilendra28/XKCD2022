<?php

require_once __DIR__.'/database-connection.php';
require_once '../config/config.php';

class verify_otp
{
    private $db;
    private $query;
    private $user_mail;
    private $otp;
    private $header;
    private $message;
    private $config;

    public function send_mail_fun($user_mail){
        $this->config = new config();
        $this->user_mail = $user_mail;
        $this->message = '
            
        ';
       
        mail($this->user_mail,'Email Subscription',$this->message,$this->config->header);
    }

    public function __construct()
    {
        if(isset($_POST['email']) & isset($_POST['otp'])){
            $this->user_mail = $_POST['email'];
            $this->otp = $_POST['otp'];
        }
        else{
            echo 'Please try Again 🥺';
            exit();
        }
        if (!filter_var($this->user_mail, FILTER_VALIDATE_EMAIL)) {
            echo 'Please try Again 🥺';
            exit();
        }

        $this->db = new db();
        $this->db = $this->db->database();
        $this->user_mail = trim($this->user_mail);
        $this->user_mail = htmlspecialchars($this->user_mail,ENT_QUOTES);
        $this->user_mail = mysqli_real_escape_string($this->db,$this->user_mail);
        $this->otp = trim($this->otp);
        $this->otp = htmlspecialchars($this->otp,ENT_QUOTES);
        $this->otp = mysqli_real_escape_string($this->db,$this->otp);
        $this->query = $this->db->prepare('SELECT * FROM user_data WHERE email=? AND otp=?');
        $this->query->bind_param('si',$this->user_mail,$this->otp);
        $this->query->execute();
        $this->query->store_result();
        if ($this->query->num_rows != 0) {
            $this->query = $this->db->prepare('UPDATE user_data SET otp=1 WHERE email=?');
            $this->query->bind_param('s',$this->user_mail);
            $this->query->execute();
            if($this->query->affected_rows!=0){
                $this->send_mail_fun($this->user_mail);
                echo 'Email Verified';
            }
            else{
                echo 'Try Again 🥺';
            }
        } 
        else{
            echo 'Invalid OTP 🥺';
        }
        $this->db->close();
    }

    public function __destruct()
    {
        unset($this->db);
        unset($this->query);
        unset($this->user_mail);
        unset($this->otp);
        unset($this->header);
        unset($this->message);
        unset($this->config);
    }
}
// Creator:- "Akhilendra prtap Singh"
// akhilofficial11@gmail.com
new verify_otp();

?>

