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
if(isset($_POST['post_add'])) {
	
	try {
		if(empty($_POST['post_title'])) {
			throw new Exception("Post Title can not be empty");
		}
		if(empty($_POST['post_des'])) {
			throw new Exception("Post Description can not be empty");
		}
		if(empty($_POST['post_cat'])) {
			throw new Exception("Post Category can not be empty");
		}
		if(empty($_POST['post_tag'])) {
			throw new Exception("Post Tags can not be empty");
		}
		
		//Finding auto increament value
		$statement = $db->prepare("SHOW TABLE STATUS LIKE 'tbl_post'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach ($result as $row)
			{
				$post_id = $row[10];
			}
		//		
		$up_filename = $_FILES["post_image"]["name"];
		$filebasename = substr($up_filename,0,strripos($up_filename,'.'));  //strip Extention
		$file_ext = substr($up_filename,strripos($up_filename,'.')); //strip name
		$f1 = $post_id.$file_ext;
		
		if(($file_ext != '.jpg') && ($file_ext != '.jpeg') && ($file_ext != '.png') && ($file_ext != '.gif')){
			throw new Exception("Only jpg,jpeg,png,gif format imgage are allowed");
		}
		move_uploaded_file($_FILES["post_image"]["tmp_name"],"../uploads/" .$f1);
		
		$title = $_POST['post_title'];
		$post_des = $_POST['post_des'];
		$post_cat_id = $_POST['post_cat'];
		$post_tag_id = $_POST['post_tag'];
		$post_image = $f1;
		$i=0;
		if (is_array($post_tag_id)) {
			foreach($post_tag_id as $key)
				{
					$arr[$i] = $key;
					$i++;					
				}
		}
		
		$tag_ids = implode(",",$arr);
		
		$post_date = date('d-m-Y');
		$post_month = date('m');
		$post_year = date('Y');
		$post_timestamp = strtotime($post_date);
		
		$statement = $db->prepare("INSERT INTO tbl_post (post_title,post_des,post_image,post_cat,post_tag,post_date,post_month,post_year,post_timestamp) VALUES(?,?,?,?,?,?,?,?,?)");
		$statement->execute(array($title,$post_des,$post_image,$post_cat_id,$tag_ids,$post_date,$post_month,$post_year,$post_timestamp));
		
		$succ_msg = "Post has been inserted Successfully";
	}	
	catch (Exception $e) {
		$error_msg = $e->getMessage();
	}

}

?>



<?php include('header.php'); ?>
<div id="content">
	<h2>Add New Post</h2>
	<?php
				if(isset($error_msg)) {
					echo "<div class='error'>".$error_msg."</div>";
					}
				if(isset($succ_msg)) {
					echo "<div class='success'>".$succ_msg."</div>";
					}
			?>
	<form action="" method="POST" enctype="multipart/form-data">
		<table>
			<tr>
				<td><b>Post Title</b></td>
			</tr>
			<tr>
				<td><input class="long" type="text" name="post_title" value="" id="" /></td>
			</tr>
			<tr>
				<td><b>Post Contents</b></td>
			</tr>
			<tr>
				<td>
					<textarea name="post_des" id="" cols="50" rows="10"></textarea>
					<script type="text/javascript">
						if ( typeof CKEDITOR == 'undefined' )
						{
							document.write(
								'<strong><span style="color: #ff0000">Error</span>: CKEditor not found</strong>.' +
								'This sample assumes that CKEditor (not included with CKFinder) is installed in' +
								'the "/ckeditor/" path. If you have it installed in a different place, just edit' +
								'this file, changing the wrong paths in the &lt;head&gt; (line 5) and the "BasePath"' +
								'value (line 32).' ) ;
						}
						else
						{
							var editor = CKEDITOR.replace( 'post_des' );
							//editor.setData( '<p>Just click the <b>Image</b> or <b>Link</b> button, and then <b>&quot;Browse Server&quot;</b>.</p>' );
						}

					</script>
				</td>
			</tr>
			<tr>
				<td><b>Add Featured Image</b></td>
			</tr>
			<tr>
				<td><input type="file" name="post_image" id="" /></td>
			</tr>
			<tr>
				<td><b>Select Category</b></td>
			</tr>
			<tr>
				<td>
					<select name="post_cat">
					
						<option value="">Select Category</option>
						<?php 
							
							$statement = $db->prepare("select * from tbl_cat order by cat_name ASC");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach($result as $row)
								{	
									?>
										<option value="<?php echo $row['cat_id'] ; ?>"><?php echo $row['cat_name'] ;?></option>
									<?php
								}					
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td><b>Select Tag</b></td>
			</tr>
			<tr>
				<td>
					<?php 
							
							$statement = $db->prepare("select * from tbl_tag order by tag_name ASC");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach($result as $row)
								{	
									?>
										<input type="checkbox" name="post_tag[]" value="<?php echo $row['tag_id'] ; ?>" />&nbsp;<?php echo $row['tag_name'] ;?><br>
									<?php
								}					
						?>
				</td>
			</tr>
			<tr>
				<td><input type="submit" value="Publish Post" name="post_add"/></td>
			</tr>
		</table>
	</form>
</div>
<?php include('footer.php'); ?>			