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
if(isset($_POST['update_footer'])) {
	try {
		if(empty($_POST['footer_text'])) {
			throw new Exception("Footer Text Can not be empty");
		}
		$statement = $db->prepare("	UPDATE tbl_footer set footer_text=? where id=?");
		$statement->execute(array($_POST['footer_text'],$id));
		
		$succ_msg = "Footer Text has been changed successfully";
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
	<h2>Change footer Text</h2>
	
	<form action="" method="POST">
		<table>
			<tr>
				<td>Update Footer text :</td>
			</tr>
			<tr>
				<td><input class="long" type="text" name="footer_text" value="<?php echo $old_text ;?>" id="" /></td>
			</tr>
			<tr>
				<td><input type="submit" value="Update" name="update_footer" /></td>
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