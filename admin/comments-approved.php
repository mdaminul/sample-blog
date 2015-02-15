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
if (isset($_REQUEST['id'])) {
	try {
		$id = $_REQUEST['id'];
		$k=1;
		$statement = $db->prepare("UPDATE tbl_comments set comments_status=? where c_id=?");
		$statement->execute(array($k,$id));
		
		$succ_msg = "Comments Approved Successfully.....";
	}
	catch (Exception $e) {
		$error_msg = $e->getMessage();
	}
}
?>

<?php include('header.php'); ?>

<div id="content">
	<h2>All Unapproved Comments</h2>
		<?php
		
			if(isset($succ_msg)) {
				echo "<div class='success'>".$succ_msg."</div>";
			}
		?>
		<table class="tbl2">
			<tr>
				<th>Serial</th>
				<th>Name</th>
				<th>E-mail</th>
				<th>URL</th>
				<th>Comments</th>
				<th>Post ID</th>
				<th>Action</th>
			</tr>
			<?php
				$i=0;
				$statement = $db->prepare("select * from tbl_comments where comments_status=? order by c_id DESC");
				$statement->execute(array($i));
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach($result as $row)
					{
						$i++;
						
						
						?>
						
							<tr>
								<td><?php echo $i ;?></td>
								<td><?php echo $row['c_user'] ;?></td>
								<td><?php echo $row['c_email'] ;?></td>
								<td><?php echo $row['c_url'] ;?></td>
								<td><?php echo $row['c_message'] ;?></td>
								<td><a class="fancybox" href="#inline<?php echo $i ;?>"><?php echo $row['post_id'] ;?></a>
									<div id="inline<?php echo $i ;?>" style="width:850px;display: none;">
										<h3>Post details</h3>
											<?php
											$statement1 = $db->prepare("select * from tbl_post where post_id=?");
											$statement1->execute(array($row['post_id']));
											$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
											foreach($result1 as $row1)
												{
													?>
													<table>
														<tr>
															<td><b>Title</b></td>
														</tr>
														<tr>
															<td><?php echo $row1['post_title'] ;?></td>
														</tr>
														<tr>
															<td><b>Post Description</b></td>
														</tr>
														<tr>
															<td><?php echo $row1['post_des'] ;?></td>
														</tr>
														<tr>
															<td><b>Post Image</b></td>
														</tr>
														<tr>
															<td><img src="../uploads/<?php echo $row1['post_image'] ;?>" style="width:300px;height:250px;" alt="" /></td>
														</tr>
														<tr>
															<td><b>Post Category</b></td>
														</tr>
														<tr>	
															<td>
																<?php
																$statement2 = $db->prepare("select * from tbl_cat where cat_id=?");
																$statement2->execute(array($row1['post_cat']));
																$result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
																foreach($result2 as $row2)
																	{
																		echo $row2['cat_name'] ;
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
																	$arr = explode(",",$row1['post_tag']);
																	$arr_lenght = count($arr);
																	$k=0;
																	for($j=0;$j<$arr_lenght;$j++)
																		{
																			$statement2 = $db->prepare("select * from tbl_tag where tag_id=?");
																			$statement2->execute(array($arr[$j]));
																			$result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
																			foreach($result2 as $row2)
																				{
																					$arr1[$k] = $row2['tag_name'];
																				}
																			$k++;
																		}
																	$post_tags = implode(",",$arr1);
																	
																	echo $post_tags;

																?>
															</td>
														</tr>
													</table>
													<?php
												}
											?>
											
									</div>
								</td>
								<td><a href="comments-approved.php?id=<?php echo $row['c_id'];?>">Approved</a></td>
							</tr>
						
						<?php
					}
			?>
		</table>
		
		<h2>All Approved Comments</h2>
		
		<table class="tbl2">
			<tr>
				<th>Serial</th>
				<th>Name</th>
				<th>E-mail</th>
				<th>URL</th>
				<th>Comments</th>
				<th>Post ID</th>
			</tr>
			<?php
				$i=0;
				$k=1;
				$statement = $db->prepare("select * from tbl_comments where comments_status=? order by c_id DESC");
				$statement->execute(array($k));
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach($result as $row)
					{
						$i++;
						?>
						
							<tr>
								<td><?php echo $i ;?></td>
								<td><?php echo $row['c_user'] ;?></td>
								<td><?php echo $row['c_email'] ;?></td>
								<td><?php echo $row['c_url'] ;?></td>
								<td><?php echo $row['c_message'] ;?></td>
								<td><a class="fancybox" href="#inline<?php echo $i ;?>"><?php echo $row['post_id'] ;?></a>
									<div id="inline<?php echo $i ;?>" style="width:850px;display: none;">
										<h3>Post details</h3>
											<?php
											$statement1 = $db->prepare("select * from tbl_post where post_id=?");
											$statement1->execute(array($row['post_id']));
											$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
											foreach($result1 as $row1)
												{
													?>
													<table>
														<tr>
															<td><b>Title</b></td>
														</tr>
														<tr>
															<td><?php echo $row1['post_title'] ;?></td>
														</tr>
														<tr>
															<td><b>Post Description</b></td>
														</tr>
														<tr>
															<td><?php echo $row1['post_des'] ;?></td>
														</tr>
														<tr>
															<td><b>Post Image</b></td>
														</tr>
														<tr>
															<td><img src="../uploads/<?php echo $row1['post_image'] ;?>" style="width:300px;height:250px;" alt="" /></td>
														</tr>
														<tr>
															<td><b>Post Category</b></td>
														</tr>
														<tr>	
															<td>
																<?php
																$statement2 = $db->prepare("select * from tbl_cat where cat_id=?");
																$statement2->execute(array($row1['post_cat']));
																$result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
																foreach($result2 as $row2)
																	{
																		echo $row2['cat_name'] ;
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
																	$arr = explode(",",$row1['post_tag']);
																	$arr_lenght = count($arr);
																	$k=0;
																	for($j=0;$j<$arr_lenght;$j++)
																		{
																			$statement2 = $db->prepare("select * from tbl_tag where tag_id=?");
																			$statement2->execute(array($arr[$j]));
																			$result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
																			foreach($result2 as $row2)
																				{
																					$arr1[$k] = $row2['tag_name'];
																				}
																			$k++;
																		}
																	$post_tags = implode(",",$arr1);
																	
																	echo $post_tags;

																?>
															</td>
														</tr>
													</table>
													<?php
												}
											?>
											
									</div>
								</td>
							</tr>
						
						<?php
					}
			?>
		</table>
</div>
<?php include('footer.php'); ?>			