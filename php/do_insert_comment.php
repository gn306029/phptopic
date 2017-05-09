<?php
	
	$db_host = 'db.mis.kuas.edu.tw';
    $db_name = 's1104137130';
    $db_user = 's1104137130';
    $db_password = '1314520';
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn,$db_user,$db_password);

    $video_id = $_POST['videoid'];
    $comment = $_POST['comment'];
    $member_id = $_POST['userid'];
    date_default_timezone_set('Asia/Taipei');
    $comment_time = date("Y-m-d H:i:s");

    $sql = "INSERT INTO `commentary`(`VIDEO_ID`,`MEMBER_ID`,`COMMENT_TIME`,`COMMENTARY`) Values ('".$video_id."','".$member_id."','".$comment_time."','".$comment."')";
    echo $sql;
    $result = $conn -> exec($sql);
    header("Location: ./video.php?VIDEO_ID=".$video_id);
?>