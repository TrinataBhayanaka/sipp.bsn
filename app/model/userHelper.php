<?php
class userHelper extends Database {
	
    function __construct()
    {   
        global $CONFIG;
        $loadSession = new Session();
        $getUserData = $loadSession->get_session();
        $this->user = $getUserData[0];
        $this->salt = $CONFIG['default']['salt'];
        $this->prefix = "";
        $this->date = date('Y-m-d H:i:s');
        $this->token = str_shuffle('1q2w3e4r5t6y7u8i9o0pazsxdcfvgbhnjmkl');
    }
    
    
    
    
    /**
     * @todo get data user/person
     * 
     * @param $data = 
     * @param $field =  field name
     */
    function getUserData($field,$data){

        // pr($this->user);
        if($data==false) return false;
        $sql = "SELECT * FROM `ck_user_member` WHERE `$field` = '".$data."' ";
        $res = $this->fetch($sql,0);  
        if(empty($res)){return false;}
        return $res; 
    }
    
    

    function validateEmail($email, $debug=false)
    {

        $sql = array(
                'table'=>'ck_user_member',
                'field'=>"COUNT(email) AS total",
                'condition' => "email = '{$email}'",
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res[0]['total']>0) return true;
        return false;

    } 

    function createAccount($data,$debug=false)
    {

        if ($data['password'] !== $data['repassword']) return false;
        
        $field = array('name','email','username','tempatlahir','tanggallahir','pendidikan','institusi','jenispekerjaan','hp','alamat'); 

        foreach ($data as $key => $value) {
            
            if (in_array($key, $field)){
                $tmpF[] = $key;
                $tmpV[] = "'".$value."'";
            }
        }

        $tmpF[] = "register_date";
        $tmpF[] = "type";
        $tmpF[] = "email_token";
        $tmpF[] = "salt";
        $tmpF[] = "password";


        $pass = sha1($this->salt.$data['password'].$this->salt);
        $tmpV[] = "'".$this->date."'";
        $tmpV[] = 2;
        $tmpV[] = "'".$this->token."'";
        $tmpV[] = "'".$this->salt."'";
        $tmpV[] = "'{$pass}'";


        // pr($tmpV);
        $impField = implode(',', $tmpF);
        $impData = implode(',', $tmpV);

        $sql = "INSERT IGNORE INTO ck_user_member ({$impField}) VALUES ({$impData})";
        // pr($sql);
        // exit;
        /*
        $sql = array(
                'table'=>'user',
                'field'=>"{$impField}",
                'value' => "{$impData}",
                );

        $res = $this->lazyQuery($sql,$debug,1);*/
        $res = $this->query($sql);

        if ($res){

            
            $dataencode = array('email'=>$data['email'], 'token'=>$this->token);
            $dataArr['encode'] = encode(serialize($dataencode));
            $dataArr['email'] = $data['email'];
            $dataArr['name'] = $data['name'];
            
            logFile($dataArr['encode']);
            // $html = "klik link berikut ini {$basedomain}register/validate/?ref={$msg}";
            // $send = sendGlobalMail($email,'noreply@pindai.co.id',$html);
            return $dataArr;
        } 

        
        return false;

    }

    function logoutUser()
    {


        $sql = array(
                'table'=>"ck_user_member",
                'field'=>"is_online = 0",
                'condition'=>"idUser = '{$this->user['idUser']}'",
                );

        $result = $this->lazyQuery($sql,$debug,2);
        if ($result) return true;
        return false;
    }

    function getEmailToken($email=false, $all=false)
    {

        $filter = "";

        if($email==false) return false;
        
        if($all) $filter = " * ";
        else $filter = " email_token ";

        $sql = "SELECT {$filter} FROM ck_user_member WHERE `email` = '".$email."' LIMIT 1";
        // logFile($sql);
        $res = $this->fetch($sql);
        if ($res) return $res;
        return false;
    }

    function updateStatusUser($email=false)
    {

        $sql = array(
                'table'=>'ck_user_member',
                'field'=>"n_status = 1",
                'condition' => "email = '{$email}'",
                );

        $res = $this->lazyQuery($sql,$debug,2);
        if ($res) return true;
        return false;

    }
}
?>