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
if(isset($_REQUEST['post_id'])) {
	$id = $_REQUEST['post_id'];
	
	$statement = $db->prepare("select * from tbl_post where post_id=?");
	$statement->execute(array($id));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $row)
		{
			$delete_post_image = "../uploads/".$row['post_image'];
			unlink($delete_post_image);
		}

	$statement = $db->prepare("DELETE from tbl_post where post_id=?");
	$statement->execute(array($id));
	
	$succ_msg = "This post Name has been Deleted Successfully";
}
?>

<?php include('header.php'); ?>
<div id="content">
	<h2>View All Posts</h2>
		<?php
		
			if(isset($succ_msg)) {
				echo "<div class='success'>".$succ_msg."</div>";
			}
		?>
	<form action="">
		<table class="tbl2">
			<tr>
				<th>Serial</th>
				<th>Post Title</th>
				<th>Action</th>
			</tr>
			<?php
				$i=0;
				$statement = $db->prepare("select * from tbl_post order by post_id DESC");
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach($result as $row)
					{
						$i++;
						?>
						
							<tr>
								<td><?php echo $i ;?></td>
								<td><?php echo $row['post_title'] ;?></td>
								<td><a class="fancybox" href="#inline<?php echo $i ;?>">View</a>
								<div id="inline<?php echo $i ;?>" style="width:850px;display: none;">
									<h3>Post details</h3>
										<table>
											<tr>
												<td><b>Title</b></td>
											</tr>
											<tr>
												<td><?php echo $row['post_title'] ;?></td>
											</tr>
											<tr>
												<td><b>Post Description</b></td>
											</tr>
											<tr>
												<td><?php echo $row['post_des'] ;?></td>
											</tr>
											<tr>
												<td><b>Post Image</b></td>
											</tr>
											<tr>
												<td><img src="../uploads/<?php echo $row['post_image'] ;?>" style="width:300px;height:250px;" alt="" /></td>
											</tr>
											<tr>
												<td><b>Post Category</b></td>
											</tr>
											<tr>	
												<td>
													<?php
													$statement1 = $db->prepare("select * from tbl_cat where cat_id=?");
													$statement1->execute(array($row['post_cat']));
													$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
													foreach($result1 as $row1)
														{
															echo $row1['cat_name'] ;
														}
													?>
												</td>
											</tr>
											<tr>
												<td><b>Post Tags</b></td>
											</tr>
											<tr>
												<td>
													<?php 
														$arr = explode(",",$row['post_tag']);
														$arr_lenght = count($arr);
														$k=0;
														for($j=0;$j<$arr_lenght;$j++)
															{
																$statement1 = $db->prepare("select * from tbl_tag where tag_id=?");
																$statement1->execute(array($arr[$j]));
																$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
																foreach($result1 as $row1)
																	{
																		$arr1[$k] = $row1['tag_name'];
																	}
																$k++;
															}
														$post_tags = implode(",",$arr1);
														
														echo $post_tags;

													?>
												</td>
											</tr>
										</table>
								</div>
								| <a href="post-edit.php?id=<?php echo $row['post_id'];?>">Edit</a> | <a onClick='return confirmDelete();' href="all-post.php?post_id=<?php echo $row['post_id'];?>">Delete</a></td>
							</tr>
						
						<?php
					}
			?>
		</table>
	</form>
</div>
<?php include('footer.php'); ?>			