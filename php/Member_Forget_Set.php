<?php
    /*
     * 引入寄信的 Php 檔案
     *
     */
	include './Member_SendMail.php';
	$result = search_account_result($_POST['username'],$_POST['email']);
    /*
     * 依使用者輸入的帳號與密碼來做查詢
     * 若是有撈到資料 代表輸入正確
     * 否則回傳資料不符
     *
     */
	function search_account_result($account,$email){
        $db_host = 'db.mis.kuas.edu.tw';
        $db_name = 's1104137130';
        $db_user = 's1104137130';
        $db_password = '1314520';
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
        $conn = new PDO($dsn,$db_user,$db_password);
        $sql = "Select `MEMBER_NAME`,`MEMBER_EMAIL`,`MEMBER_PASSWORD` From `member` Where `MEMBER_ACCOUNT` = '".$account."' And `MEMBER_EMAIL` = '".$email."'";
        $result = $conn -> query($sql);
        return $result;
    }
    
    $data = $result -> fetchAll();
    if(count($data) == 1){
    	$a = send_email($data[0]['MEMBER_NAME'],$data[0]['MEMBER_EMAIL'],"您的密碼","密碼是 ".$data[0]['MEMBER_PASSWORD']);
    	echo $a;
    }else{
    	echo "資料不符";
    }

?>