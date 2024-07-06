<?php
	error_reporting(E_ALL ^ E_NOTICE);
	date_default_timezone_set ('Asia/Calcutta');
	session_start();

	$mode=false;
	if(!$_GET && $_POST){
	    $params = json_encode($_POST);
	    $mode = $_POST['mode'];
	}
	else if(!$_POST && $_GET){
	    $params = json_encode($_GET);
	    $mode = $_GET['mode'];
	}

	$basepath = "data/";
	if(!is_dir($basepath))
		mkdir($basepath, 0777 , true );

	if($mode!="verifyuser" && $mode!="createuser" && !isset($_SESSION['userid']))
		die("Not authenticated.");


	//default db creation start

	$db = new SQLite3($basepath."/teachers.db", SQLITE3_OPEN_READWRITE|SQLITE3_OPEN_CREATE);
	$teachcreate  = "CREATE TABLE IF NOT EXISTS teachers(tid INTEGER PRIMARY KEY AUTOINCREMENT , name TEXT, username TEXT,password TEXT);";
	$stdcreate = "CREATE TABLE IF NOT EXISTS students(sid INTEGER PRIMARY KEY AUTOINCREMENT,name TEXT,subject TEXT,marks INTEGER,tid INTEGER);";

	$teachtable = $db->query($teachcreate);
	if (!$teachtable)
		die($db->lastErrorMsg()."1".$teachcreate);

	$stdtable = $db->query($stdcreate);
	if (!$stdtable)
		die($db->lastErrorMsg()."2".$stdcreate);

	 //default db creation end

	$res = array();
	if($mode=="verifyuser")
	{

		$username = $_POST['username'];
		$password = $_POST['password'];

		$mkqry="select * from teachers where password ='".$password."' AND username='".$username."';";
        $results = $db->query($mkqry);

		while ($row = $results->fetchArray(SQLITE3_ASSOC)) 
		{
			$res['userdata'] = $row;
		}


		if(!isset($res['userdata'])){
			$res['err'] = "No user found";
		}else
		{
			$_SESSION['userid'] = $res['userdata']['tid'];
			$_SESSION['username'] = $res['userdata']['name'];
		}
		
	}else if($mode=="createuser")
{

		$data = json_decode($_POST['data'],true);


		$mkqry="select * from teachers where username ='".$data['teacher_username']."';";
        $results = $db->query($mkqry);

		$isexist = $results->fetchArray(SQLITE3_ASSOC);
		if(isset($isexist['tid'])){
			$res['err'] = "Username already in use";
		}else{

			$data = json_decode($_POST['data'],true);
			$mkqry="insert into teachers (name,username,password) VALUES('".$data['teacher_name']."','".$data['teacher_username']."','".$data['teacher_pass']."');";
	        $results = $db->query($mkqry);

			if (!$results){
				$res['errmsg'] = $db->lastErrorMsg()." on ".$mkqry;
				$res['err'] = "Not Created";
			}
	    	else{
	    		$res['msg'] = "Created  successfully";
	    	}	
		}


		
	}else if($mode=="logout")
	{
		if(session_destroy())
			$res['data'] = "success";
		else
			$res['err'] = "Something went wrong";
		
		
	}else if($mode=="getstdlist")
	{
		$tid = $_SESSION['userid'];
		$res['data']=array();

		$mkqry="select * from students where tid ='".$tid."';";
        $results = $db->query($mkqry);

		while ($row = $results->fetchArray(SQLITE3_ASSOC)) 
		{
			$res['data'][$row['sid']] = $row;
		}

	}
	else if($mode=="createstd")
	{
		$stddata = json_decode($_POST['stddata'],true);
		$updateid = false;
		if(isset($_POST['sid']) && $_POST['sid'] && $_POST['sid']!="false" && $_POST['sid']!=false){
			$updateid = $_POST['sid'];
		}else{
			$entrystd = strtoupper(trim($stddata['name']))."-".strtoupper(trim($stddata['subject']));

			$tid = $_SESSION['userid'];
			$mkqry="select * from students where tid ='".$tid."';";
	        $results = $db->query($mkqry);

			while ($row = $results->fetchArray(SQLITE3_ASSOC)) 
			{
				if(strtoupper(trim($row['name']))."-".strtoupper(trim($row['subject']))==$entrystd){
					$updateid = $row['sid'];
					break;
				}
			}
		}
		
		if($updateid)
		{
			$mkqry="update students set marks=".$stddata['marks']." where sid=".$updateid.";";
	        $results = $db->query($mkqry);

	        if (!$results){
				$res['errmsg'] = $db->lastErrorMsg()." on ".$mkqry;
				$res['err'] = "Not Updated";
			}
	    	else{
	    		$res['msg'] = "Updated  successfully";
	    	}
			
		}else
		{

			$mkqry="insert into students (name,subject,marks,tid) VALUES('".$stddata['name']."','".$stddata['subject']."',".$stddata['marks'].",".$_SESSION['userid'].");";
	        $results = $db->query($mkqry);

			if (!$results){
				$res['errmsg'] = $db->lastErrorMsg()." on ".$mkqry;
				$res['err'] = "Not Created";
			}
	    	else{
	    		$res['msg'] = "Created  successfully";
	    	}
		}

	}else if($mode=="deletestd"){
		$mkqry="delete from students where sid=".$_POST['sid'].";";
        $results = $db->query($mkqry);

		if (!$results){
			$res['errmsg'] = $db->lastErrorMsg()." on ".$mkqry;
			$res['err'] = "Not deleted";
		}
    	else{
    		$res['msg'] = "Deleted  successfully";
    	}
	}
	echo json_encode($res);

?>