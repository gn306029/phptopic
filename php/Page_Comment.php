<?php
	/*
	 * 該檔案為撈出該電影的所有評論
	 *
	 */
	$db_host = 'db.mis.kuas.edu.tw';
    $db_name = 's1104137130';
    $db_user = 's1104137130';
    $db_password = '1314520';
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn,$db_user,$db_password);

    $video_id = $_POST['videoid'];
    $comment = $_POST['comment'];
    $member_id = $_POST['userid'];
	/*
     * 設定時區及時間格式
     *
     */
    date_default_timezone_set('Asia/Taipei');
    $comment_time = date("Y-m-d H:i:s");

    $sql = "INSERT INTO `commentary`(`VIDEO_ID`,`MEMBER_ID`,`COMMENT_TIME`,`COMMENTARY`) Values ('".$video_id."','".$member_id."','".$comment_time."','".nl2br($comment)."')";
    echo $sql;
    $result = $conn -> exec($sql);
	/*
     * 重新導向該電影
     *
     */
    header("Location: ./Page_Video.php?VIDEO_ID=".$video_id);
?>