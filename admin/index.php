<?php
ob_start();
session_start();
if ($_SESSION['name'] != 'correct')
	{
		header("location: login.php");
	}
?>
<?php include('header.php'); ?>
<div id="content">
	<h2>Admin panel</h2>
	<p>Welcome to admin panel.</p>
</div>
<?php include('footer.php'); ?>			