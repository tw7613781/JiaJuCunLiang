<?php

	if(isset($_GET['location_id'])){
		$id=preg_replace('/[^0-9]/','', $_GET['location_id']);
		if(empty($id)){
			header("Location: ./");
			exit;
		}
	}else{
			header("Location: ./");
			exit;
	}

	
	include_once '../sys/core/init.inc.php';

	$board = new Board($dbo);
	$page_title = "存放地点";
	$css_files = array('style.css');

	include_once 'assets/comm/header.inc.php';
?>

<div id="content">
<?php
	echo $board->showLocation($id);
?>
<br>
<a href="./">&laquo; 返回粮仓</a>

</div>

<?php

	include_once 'assets/comm/footer.inc.php';

?>

