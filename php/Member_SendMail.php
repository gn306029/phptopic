<?php
require("../phpmailer/class.phpmailer.php");
header('Content-type:text/html;charset=utf-8');

function send_email($user_name,$user_email,$email_title,$email_content){
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true; // turn on SMTP authentication
	//這幾行是必須的

	$mail->Username = "1104137130@gm.kuas.edu.tw";
	$mail->Password = "L125071747";
	//這邊是你的gmail帳號和密碼

	$mail->FromName = "=?UTF-8?B?".base64_encode("IMDB")."?=";
	// 寄件者名稱(你自己要顯示的名稱)
	$webmaster_email = "1104137130@gm.kuas.edu.tw"; 
	//回覆信件至此信箱
	$email=$user_email;
	// 收件者信箱
	$name=$user_name;
	// 收件者的名稱or暱稱
	$mail->From = $webmaster_email;


	$mail->AddAddress($email,$name);
	$mail->AddReplyTo($webmaster_email,"Squall.f");
	//這不用改

	$mail->WordWrap = 50;
	//每50行斷一次行

	//$mail->AddAttachment("/XXX.rar");
	// 附加檔案可以用這種語法(記得把上一行的//去掉)

	$mail->IsHTML(true); // send as HTML

	$mail->Subject = "=?UTF-8?B?".base64_encode($email_title)."?=";
	// 信件標題 標題一定要加 UTF8 否則會亂碼
	$mail->Body = $email_content;
	//信件內容(html版，就是可以有html標籤的如粗體、斜體之類)

	if(!$mail->Send()){
		return "寄信發生錯誤：" . $mail->ErrorInfo;
		//如果有錯誤會印出原因
	}else{ 
		return "寄信成功";

	}
}

?>