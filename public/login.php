<?php
	include_once '../sys/core/init.inc.php';

	$page_title = "Please Log In";
	$css_files = array("style.css","admin.css");
	include_once 'assets/comm/header.inc.php';
?>

<div id="content">
	<form action="assets/inc/process.inc.php" method="post">
		<fieldset>
			<legend>自己人请登录</legend>
			<label for="uname">用户名</label>
			<input type="text" name="uname" id="uname" value="">
			<label for="pword">密码</label>
			<input type="password" name="pword" id="pword" value="">
			<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
			<input type="hidden" name="action" value="user_login">
			<input type="submit" name="login_submit" value="登录">
			or <a href="./">cancel</a>
		</fieldset>
	</form>

</div>

<?php
	include_once 'assets/comm/footer.inc.php';
?>