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
if (isset($_POST['category'])) {
	
	try {
		if (empty($_POST['cat_name'])) {
			throw new Exception('Category Name can not be empty');
		}
		$statement = $db->prepare("select * from tbl_cat where cat_name=?");
		$statement->execute(array($_POST['cat_name']));
		$num = $statement->rowCount();
		if ($num > 0) {
			throw new Exception('This Category name is already exists');
		}
		$statement = $db->prepare("insert into tbl_cat (cat_name) values (?)");
		$statement->execute(array($_POST['cat_name']));
		
		$succ_msg = "Category Name has been Inserted Successfully";
	}
	catch(Exception $e) {
		$error_msg = $e->getMessage();
	}
}

?>

<?php
if (isset($_POST['update_category'])) {
	
	try {
		if (empty($_POST['cat_name'])) {
			throw new Exception('Category Name can not be empty');
		}
		$statement = $db->prepare("select * from tbl_cat where cat_name=?");
		$statement->execute(array($_POST['cat_name']));
		$num = $statement->rowCount();
		if ($num > 0) {
			throw new Exception('This Category name is already exists');
		}
		$statement = $db->prepare("update tbl_cat set cat_name=? where cat_id=?");
		$statement->execute(array($_POST['cat_name'],$_POST['cat_id']));
		
		$succ_msg1 = "Category Name has been Updated Successfully";
	}
	catch(Exception $e) {
		$error_msg1 = $e->getMessage();
	}
}

?>

<?php
if(isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
	
	$statement = $db->prepare("DELETE from tbl_cat where cat_id=?");
	$statement->execute(array($id));
	
	$succ_msg2 = "Category Name has been Deleted Successfully";
}
?>

<?php include('header.php'); ?>

<div id="content">
	<h2>Manage your category</h2>
	<form action="" method="POST">
		<table>
			<tr>
				<td>Add New Category</td>
			</tr>
			<tr>
				<td><input class="short" type="text" name="cat_name" value="" id="" /></td>
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
				<td><input type="submit" value="Add Category" name="category" /></td>
			</tr>
		</table>
	</form>
	<h2>View All Categories</h2>

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
				<th>Category Name</th>
				<th>Action</th>
			</tr>
			<?php 
				$i=0;
				$statement = $db->prepare("select * from tbl_cat order by cat_name ASC");
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach($result as $row)
					{
						$i++;
						?>
						<tr>
							<td><?php echo $i ; ?></td>
							<td><?php echo $row['cat_name'] ; ?></td>
							<td><a class="fancybox" href="#inline<?php echo $i ;?>">Edit</a>
							<div id="inline<?php echo $i ;?>" style="width:400px;display: none;">
								<h3>Manage Category</h3>
								<form action="" method="POST">
								
									<input type="hidden" name="cat_id" value="<?php echo $row['cat_id'] ; ?>"/>
									
									<table>
										<tr>
											<td>Edit Category Name</td>
										</tr>
										<tr>
											<td><input type="text" name="cat_name" value="<?php echo $row['cat_name'] ; ?>" id="" /></td>
										</tr>
										<tr>
											<td><input type="submit" value="Update Category" name="update_category"/></td>
										</tr>
									</table>
								</form>
							</div>
							| <a onClick='return confirmDelete();' href="manage-category.php?id=<?php echo $row['cat_id'] ; ?>">Delete</a></td>
						</tr>
						<?php
					}
			?>
		</table>
</div>
<?php include('footer.php'); ?>			