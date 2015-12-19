<?php

include_once '../sys/core/init.inc.php';

if(!isset($_SESSION['user'])){
	header("Location: ./");
	exit;
}

$page_title = "添加/编辑 物件";
$css_files = array("style.css","admin.css","jquery-ui.css");
include_once 'assets/comm/header.inc.php';

$board = new Board($dbo);

?>

<div id = "content">
<?php echo $board->displayItem(); ?>

</div>

<?php
	include_once 'assets/comm/footer_jsFunc.inc.php';
?>