<?php

include('config.php');

	if(!isset($_REQUEST['id'])) {
		header("location: index.php");
	}
	else {
		$id = $_REQUEST['id'];
	}
?>

<?php
if(isset($_POST['c_email'])) {
	try{
		if(empty($_POST['c_message'])) {
			throw new Exception("Message field can not be empty");	
		}
		if(empty($_POST['c_email'])) {
			throw new Exception("Please enter your E-mail Address");	
		}
		if(!(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $_POST['c_email']))) {
			throw new Exception("Please enter a valid email address.");
		}
		if(empty($_POST['c_user'])) {
			throw new Exception("Please enter your name");	
		}
		
		$c_date =date('d-m-Y');
		$comments_status = 0;
		$statement = $db->prepare("INSERT INTO tbl_comments (c_user,c_email,c_url,c_message,c_date,post_id,comments_status) VALUES(?,?,?,?,?,?,?)");
		$statement->execute(array($_POST['c_user'],$_POST['c_email'],$_POST['c_url'],$_POST['c_message'],$c_date,$id,$comments_status));
		
		$succ_msg = "Thanks..Your comments will be published after  the admin Approval";
		
	}
	catch(Exception $e) {
		$error_msg = $e->getMessage();
	}
}
?>

<?php include('header.php') ;?>

<?php

	$statement = $db->prepare("select * from tbl_post where post_id=?"); 
	$statement->execute(array($id));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $row)
		{
			$post_title = $row['post_title'];
			$post_des = $row['post_des'];
			$post_image = $row['post_image'];
			?>
			<div class="post">
				<h2><?php echo $post_title ;?></h2>
				<div>
					<span class="date">
						<?php
							$post_date = $row['post_date'];
							$day = substr($post_date,0,2);
							$month = substr($post_date,3,2);
							$year = substr($post_date,6,4);
							if($month =='01') {$month = 'Jan';}
							if($month =='02') {$month = 'Feb';}
							if($month =='03') {$month = 'Mar';}
							if($month =='04') {$month = 'Apr';}
							if($month =='05') {$month = 'May';}
							if($month =='06') {$month = 'Jun';}
							if($month =='07') {$month = 'Jul';}
							if($month =='08') {$month = 'Aug';}
							if($month =='09') {$month = 'Sep';}
							if($month =='10') {$month = 'Oct';}
							if($month =='11') {$month = 'Nov';}
							if($month =='12') {$month = 'Dec';}
							
							echo $month." ".$day." ".$year;
						?>
					</span>
					<span class="categories">
					Tags:&nbsp;
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
								$post_tags = implode(", ",$arr1);
											
							echo $post_tags;

						?>
					</span>
				</div>
				<div class="description">
					<p><img src="uploads/<?php echo $post_image ;?>" alt="" width="239" height="232" />
					<?php echo $post_des ;?></p>
				</div>
			</div>
			<?php
		}
		?>
			
			<div id="comments">
				<img src="img/title3.gif" alt="" width="216" height="39" /><br />
				<?php
				$status = 1;
				$statement = $db->prepare("SELECT * FROM tbl_comments WHERE post_id=? AND comments_status=?");
				$statement->execute(array($id,$status));
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach($result as $row)
					{	
						$comments_date = $row['c_date'];
						$day = substr($comments_date,0,2);
						$month = substr($comments_date,3,2);
						$year = substr($comments_date,6,4);
						if($month =='01') {$month = 'Jan';}
						if($month =='02') {$month = 'Feb';}
						if($month =='03') {$month = 'Mar';}
						if($month =='04') {$month = 'Apr';}
						if($month =='05') {$month = 'May';}
						if($month =='06') {$month = 'Jun';}
						if($month =='07') {$month = 'Jul';}
						if($month =='08') {$month = 'Aug';}
						if($month =='09') {$month = 'Sep';}
						if($month =='10') {$month = 'Oct';}
						if($month =='11') {$month = 'Nov';}
						if($month =='12') {$month = 'Dec';}
						
						
						?>
							<div class="comment">
								<div class="avatar">
								
								<?php
									$gravarmd5 = md5($row['c_email']);
									if ($gravarmd5 != 0)
										{
											?>
											<img src="http://www.gravatar.com/avatar/<?php echo $gravarmd5; ?>" alt="" width="80" height="80"> <br>
											<?php
										}
									else {
											?>
											<img src="img/avatar1.jpg" alt="" width="80" height="80" /><br />
											<?php
										}
								?>
								
									<span><a href="<?php echo "http://".$row['c_url'] ?>"><?php echo $row['c_user'] ?></a></span><br />
									<?php echo $day." ".$month." ".$year; ?>
								</div>
								<p><?php echo $row['c_message'] ?></p>
							</div>
						<?php
					}
				?>
				<div id="add">
					
					<img src="img/title4.gif" alt="" width="216" height="47" class="title" /><br />
					<?php
						if(isset($error_msg)) {
							echo "<div class='error'>".$error_msg."</div>";
							}
						if(isset($succ_msg)) {
							echo "<div class='success'>".$succ_msg."</div>";
							}
					?>
					<div class="avatar">
						<img src="img/avatar2.gif" alt="" width="80" height="80" /><br />
						<span>Name User</span><br />
						<?php
							$date = date('d-m-Y');
							$day = substr($date,0,2);
							$month = substr($date,3,2);
							$year = substr($date,6,4);
							if($month =='01') {$month = 'Jan';}
							if($month =='02') {$month = 'Feb';}
							if($month =='03') {$month = 'Mar';}
							if($month =='04') {$month = 'Apr';}
							if($month =='05') {$month = 'May';}
							if($month =='06') {$month = 'Jun';}
							if($month =='07') {$month = 'Jul';}
							if($month =='08') {$month = 'Aug';}
							if($month =='09') {$month = 'Sep';}
							if($month =='10') {$month = 'Oct';}
							if($month =='11') {$month = 'Nov';}
							if($month =='12') {$month = 'Dec';}
							echo $day." ".$month." ".$year;
						?>
					</div>
					<div class="form">
						<form action="" method="POST">
							<textarea placeholder="Your Message..." name="c_message"></textarea><br />
							<input type="text" placeholder="Your Name" / name="c_user"><br />
							<input type="text" placeholder="Your E-mail" name="c_email"/><br />
							<input type="text" placeholder="Please Enter your URL except http:// (optional)" name="c_url"/><br />
							<input type="image" src="img/button.gif" alt="" />
						</form>
					</div>
				</div>
			</div>
	<?php include('footer.php') ;?>	