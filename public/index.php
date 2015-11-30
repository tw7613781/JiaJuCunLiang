<?php
	
	include_once '../sys/core/init.inc.php';

	$board = new Board($dbo);
	$page_title = "家居存粮";
	$css_files = array('style.css','admin.css');

	include_once 'assets/comm/header_index.inc.php';
?>

<div id="content">
<?php
	echo $board->buildBoard();
?>
</div>

<?php

	include_once 'assets/comm/footer.inc.php';

?>

