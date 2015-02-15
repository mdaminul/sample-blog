<?php
ob_start();
session_start();
if ($_SESSION['name'] != 'correct')
	{
		header("location: login.php");
	}
include('../config.php');
?>

<?php
if (isset($_POST['tag'])) {
	
	try {
		if (empty($_POST['tag_name'])) {
			throw new Exception('Tag Name can not be empty');
		}
		$statement = $db->prepare("select * from tbl_tag where tag_name=?");
		$statement->execute(array($_POST['tag_name']));
		$num = $statement->rowCount();
		if ($num > 0) {
			throw new Exception('This Tag name is already exists');
		}
		$statement = $db->prepare("insert into tbl_tag (tag_name) values (?)");
		$statement->execute(array($_POST['tag_name']));
		
		$succ_msg = "Tag Name has been Inserted Successfully";
	}
	catch(Exception $e) {
		$error_msg = $e->getMessage();
	}
}

?>

<?php
if (isset($_POST['update_tag'])) {
	
	try {
		if (empty($_POST['tag_name'])) {
			throw new Exception('Tag Name can not be empty');
		}
		$statement = $db->prepare("select * from tbl_tag where tag_name=?");
		$statement->execute(array($_POST['tag_name']));
		$num = $statement->rowCount();
		if ($num > 0) {
			throw new Exception('This Tag name is already exists');
		}
		$statement = $db->prepare("update tbl_tag set tag_name=? where tag_id=?");
		$statement->execute(array($_POST['tag_name'],$_POST['tag_id']));
		
		$succ_msg1 = "Tag Name has been Updated Successfully";
	}
	catch(Exception $e) {
		$error_msg1 = $e->getMessage();
	}
}

?>

<?php
if(isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
	
	$statement = $db->prepare("DELETE from tbl_tag where tag_id=?");
	$statement->execute(array($id));
	
	$succ_msg2 = "Tag Name has been Deleted Successfully";
}
?>

<?php include('header.php'); ?>

<div id="content">
	<h2>Manage your Tags</h2>
	<form action="" method="POST">
		<table>
			<tr>
				<td>Add New Tag</td>
			</tr>
			<tr>
				<td><input class="short" type="text" name="tag_name" value="" id="" /></td>
			</tr>
			<tr>
				<td>
					<?php
						if(isset($error_msg)) {
							echo "<div class='error'>".$error_msg."</div>";
						}
						if(isset($succ_msg)) {
							echo "<div class='success'>".$succ_msg."</div>";
						}
					?>
				</td>
			</tr>
			<tr>
				<td><input type="submit" value="Add Tag" name="tag" /></td>
			</tr>
		</table>
	</form>
	<h2>View All Tags</h2>

		<?php
			if(isset($error_msg1)) {
				echo "<div class='error'>".$error_msg1."</div>";
				}
			if(isset($succ_msg1)) {
				echo "<div class='success'>".$succ_msg1."</div>";
				}
			if(isset($succ_msg2)) {
				echo "<div class='success'>".$succ_msg2."</div>";
				}
		?>
											
		<table class="tbl2">
			<tr>
				<th>Serial</th>
				<th>Tag Name</th>
				<th>Action</th>
			</tr>
			<?php 
				$i=0;
				$statement = $db->prepare("select * from tbl_tag order by tag_name ASC");
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach($result as $row)
					{
						$i++;
						?>
						<tr>
							<td><?php echo $i ; ?></td>
							<td><?php echo $row['tag_name'] ; ?></td>
							<td><a class="fancybox" href="#inline<?php echo $i ;?>">Edit</a>
							<div id="inline<?php echo $i ;?>" style="width:400px;display: none;">
								<h3>Manage Tag</h3>
								<form action="" method="POST">
								
									<input type="hidden" name="tag_id" value="<?php echo $row['tag_id'] ; ?>"/>
									
									<table>
										<tr>
											<td>Edit Tag Name</td>
										</tr>
										<tr>
											<td><input type="text" name="tag_name" value="<?php echo $row['tag_name'] ; ?>" id="" /></td>
										</tr>
										<tr>
											<td><input type="submit" value="Update Tag" name="update_tag"/></td>
										</tr>
									</table>
								</form>
							</div>
							| <a onClick='return confirmDelete();' href="manage-tags.php?id=<?php echo $row['tag_id'] ; ?>">Delete</a></td>
						</tr>
						<?php
					}
			?>
		</table>
</div>
<?php include('footer.php'); ?>			