<?php
class loginHelper extends Database {
	
	var $session;
	function __construct()
	{
		parent::__construct();
		$this->session = new Session;

	}

	function goLogin()
	{
		$username = _p('username');
		$password = _p('password');
		
		
		$sql = "SELECT * FROM {$this->preftable}_admin_member WHERE username = '{$username}' and n_status = '1' LIMIT 1";
		$res = $this->fetch($sql);
		if ($res){
			$salt = sha1($password.$res['salt']);
			if ($res['password'] == $salt){
				
				$this->session->set_session($res);
				return true;
			}
		}		
		
		return false;
	}
	
	function createUser($data)
	{
		$query = "INSERT INTO {$this->preftable}_admin_member (name,nickname,email,register_date,username,salt,password,n_status,usertype)
					VALUES ('{$data['name']}','{$data['name']}','{$data['email']}','".date('Y-m-d H:i:s')."',
						'{$data['email']}','{$data['salt']}','{$data['password']}',0,1)";
		
		$result = $this->query($query);
		
		return $result;
	}
	
	function user_check($user){
		$query = "SELECT count(username) as count FROM {$this->preftable}_user_member WHERE username LIKE '{$user}'";
		
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
}
?>