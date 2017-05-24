<?php
	for($i = 0;$i<count($_FILES['file']['name']);$i++){
		if($_FILES['file']['name'][$i] != ""){
			$picture_tmp_name = $_FILES['file']['tmp_name'][$i];
			move_uploaded_file($picture_tmp_name,"../PIC/tmp_photo/".$_POST['video_select'][$i].".jpg");
		}
	}
?>