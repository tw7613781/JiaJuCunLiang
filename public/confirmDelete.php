<?php

	if(!isset($_SESSION)){
		session_start();
	}
 	

	if(isset($_POST['item_id']) && isset($_SESSION['user'])){
		$id=(int)$_POST['item_id'];
 	}
	else{
		header("Location: ./");
		exit;
	}

 	include_once '../sys/core/init.inc.php';

 	$board = new Board($dbo);
 	$markup = $board->confirmDelete($id);

	$page_title = "删除确认";
	$css_files = array("style.css","admin.css");
	include_once 'assets/comm/header.inc.php';
?>


<div id="content">
	<?php echo $markup; ?> 
</div>
<?php
include_once 'assets/comm/footer.inc.php';
?>