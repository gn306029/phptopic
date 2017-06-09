<?php
	include './Page_View_Set.php';
	if(!isset($_GET['action'])){
		include "./Index_View.php";
	}else{
		include "./".$_GET['action'].".php";
	}
	
?>