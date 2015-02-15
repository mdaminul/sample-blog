<?php
ob_start();
session_start();
if ($_SESSION['name'] != 'correct')
	{
		header("location: login.php");
	}
include('../config.php');

$id=1;

?>



<?php
if(isset($_POST['update_pass'])) {
	try {
		if(empty($_POST['old_pass'])) {
			throw new Exception("Old password Can not be empty");
		}
		if(empty($_POST['new_pass'])) {
			throw new Exception("New password field Can not be empty");
		}
		if(empty($_POST['confirm_pass'])) {
			throw new Exception("Confirm password field Can not be empty");
		}
		if($_POST['new_pass'] != $_POST['confirm_pass']) {
			throw new Exception("Your New password & Confirm password does not match!!");
		}
		$i=1;
		$statement = $db->prepare("select * from tbl_login where id=?");
		$statement->execute(array($i));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row)
			{
				 
			}
			
		if(md5($_POST['old_pass']) != $row['password']) {
			throw new Exception("Your old password does not match your existing password!!");
		}
		
		$new_pass = md5($_POST['new_pass']);
		
		$statement = $db->prepare("UPDATE tbl_login set password=? where id=?");
		$statement->execute(array($new_pass,$id));
		
		$succ_msg = "Your password has been changed successfully";
	}
	catch(Exception $e) {
		$error_msg = $e->getMessage();
	}
}
?>

<?php
	
	$statement = $db->prepare("SELECT * FROM tbl_footer where id=?");
	$statement->execute(array($id));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $row)
		{
			$old_text = $row['footer_text'];
		}
?>


<?php include('header.php'); ?>

<div id="content">
	<h2>Change Admin Password</h2>
	
	<form action="" method="POST">
		<table>
			<tr>
				<td>Old Password :</td>
			</tr>
			<tr>
				<td><input class="short" type="password" name="old_pass" placeholder="Old password" id="" /></td>
			</tr>
			<tr>
				<td>New Password :</td>
			</tr>
			<tr>
				<td><input class="short" type="password" name="new_pass" placeholder="New password" id="" /></td>
			</tr>
			<tr>
				<td>Confirm New Password :</td>
			</tr>
			<tr>
				<td><input class="short" type="password" name="confirm_pass" placeholder="Confirm New password" id="" /></td>
			</tr>
			<tr>
				<td><input type="submit" value="Update" name="update_pass" /></td>
			</tr>
		</table>
	</form>
	<?php
		if(isset($error_msg)) {
			echo "<div class='error'>".$error_msg."</div>";
			}
		if(isset($succ_msg)) {
			echo "<div class='success'>".$succ_msg."</div>";
			}
	?>
</div>
<?php include('footer.php'); ?>			