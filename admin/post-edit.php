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
if(!isset($_REQUEST['id'])) {
	header("location: all-post.php");
}
else {
	$id = $_REQUEST['id'];
}
?>

<?php 
if(isset($_POST['edit_post'])) {
	
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
		/*
		//Finding auto increament value
		$statement = $db->prepare("SHOW TABLE STATUS LIKE 'tbl_post'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach ($result as $row)
			{
				$post_id = $row[10];
			}
		//
*/
		$title = $_POST['post_title'];
		$post_des = $_POST['post_des'];
		$post_cat_id = $_POST['post_cat'];
		$post_tag_id = $_POST['post_tag'];
		
		
		$i=0;
		if (is_array($post_tag_id)) {
			foreach($post_tag_id as $key)
				{
					$arr[$i] = $key;
					$i++;					
				}
		}
		
		$tag_ids = implode(",",$arr);
		
		if(empty($_FILES['post_image']['name'])) {

			$statement = $db->prepare("UPDATE 	tbl_post SET post_title=?,post_des=?,post_cat=?,post_tag=? WHERE post_id=?");
			$statement->execute(array($title,$post_des,$post_cat_id,$tag_ids,$id));
		}
		else {
			
			$up_filename = $_FILES["post_image"]["name"];
			$filebasename = substr($up_filename,0,strripos($up_filename,'.'));  //strip Extention
			$file_ext = substr($up_filename,strripos($up_filename,'.')); //strip name
			$f1 = $id.$file_ext;
			
			if(($file_ext != '.jpg') && ($file_ext != '.jpeg') && ($file_ext != '.png') && ($file_ext != '.gif')){
				throw new Exception("Only jpg,jpeg,png,gif format imgage are allowed");
			}
			
			$statement1 = $db->prepare("select * from tbl_post where post_id=?");
			$statement1->execute(array($id));
			$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
			foreach($result1 as $row1)
				{
					$old_image_path = "../uploads/".$row1['post_image'];
					unlink($old_image_path);
				}
			move_uploaded_file($_FILES["post_image"]["tmp_name"],"../uploads/" .$f1);
			
			$statement = $db->prepare("UPDATE 	tbl_post SET post_title=?,post_des=?,post_image=?,post_cat=?,post_tag=? WHERE post_id=?");
			$statement->execute(array($title,$post_des,$f1,$post_cat_id,$tag_ids,$id));

		}
		
		
		
		
		$succ_msg = "Post has been Updated Successfully";
	}	
	catch (Exception $e) {
		$error_msg = $e->getMessage();
	}

}

?>

<?php
	$statement = $db->prepare("select * from tbl_post where post_id=?");
	$statement->execute(array($id));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $row)
		{
			$old_post_title = $row['post_title'];
			$old_post_des = $row['post_des'];
			$old_post_cat = $row['post_cat'];
			$old_post_tag = $row['post_tag'];
			$old_post_image = $row['post_image'];	
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
		<input type="hidden" name="id" value="<?php echo $id ;?>" />
		<table>
			<tr>
				<td><b>Post Title</b></td>
			</tr>
			<tr>
				<td><input class="long" type="text" name="post_title" value="<?php echo $old_post_title ;?>" id="" /></td>
			</tr>
			<tr>
				<td><b>Post Contents</b></td>
			</tr>
			
			<tr>
				<td>
					<textarea name="post_des" id="" cols="50" rows="10"><?php echo $old_post_des ;?></textarea>
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
				<td><b>Old Featured Image preview</b></td>
			</tr>
			<tr>
				<td><img src="../uploads/<?php echo $old_post_image ;?>" alt="" width="250px;" /></td>
			</tr>
			<tr>
				<td><b>Edit Featured Image</b></td>
			</tr>
			<tr>
				<td><input type="file" name="post_image" id="" /></td>
			</tr>
			<tr>
				<td><b>Edit Category</b></td>
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
									if ($row['cat_id'] == $old_post_cat)
										{
											?><option value="<?php echo $row['cat_id'] ; ?>" selected><?php echo $row['cat_name'] ;?></option><?php
										}
									else
										{
											?><option value="<?php echo $row['cat_id'] ; ?>"><?php echo $row['cat_name'] ;?></option><?php
										}
								}					
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td><b>Edit Tag</b></td>
			</tr>
			<tr>
				<td>
					<?php 
							
					$statement = $db->prepare("select * from tbl_tag order by tag_name ASC");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					foreach($result as $row)
						{	
							$post_tags = explode(",",$old_post_tag);
							$post_tags_lenght = count($post_tags);
							$k=0;
							for($j=0;$j<$post_tags_lenght;$j++)
								
								{
									if($post_tags[$j] == $row['tag_id'])
										{
											$k=1;
											break;
										}
								}
								if($k == 1)
									{
										?><input type="checkbox" name="post_tag[]" value="<?php echo $row['tag_id'] ; ?>" checked />&nbsp;<?php echo $row['tag_name'] ;?><br><?php
									}
								else
									{
										?><input type="checkbox" name="post_tag[]" value="<?php echo $row['tag_id'] ; ?>" />&nbsp;<?php echo $row['tag_name'] ;?><br><?php
						
									}
									
						}					
					?>
				</td>
			</tr>
			<tr>
				<td><input type="submit" value="Update Post" name="edit_post"/></td>
			</tr>
		</table>
	</form>
</div>
<?php include('footer.php'); ?>			