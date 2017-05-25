<?php
	for($i = 0;$i<count();$i++){
		if($_FILES['file']['name'][$i] != ""){
			$picture_tmp_name = $_FILES['file']['banner'][$i];
			move_uploaded_file($picture_tmp_name,"../PIC/banner/".$_POST['video_select'][$i].".jpg");
		}
	}
?>