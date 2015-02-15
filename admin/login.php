<?php
if(isset($_POST['login']))
	{
		try {
			if (empty($_POST['username'])) {
				throw new Exception('Username is Empty');
			}
			if (empty($_POST['password'])) {
				throw new Exception('password is Empty');
			}
			
			$username = $_POST['username'];
			
			$password = $_POST['password'];
			
			$password = md5($password);
			
			include('../config.php');
			
			$num=0;
			
			$statement = $db->prepare("select * from tbl_login where username=? and password=?");
			$statement->execute(array($username,$password));
			
			$num = $statement->rowCount();
			
			if ($num > 0)
				{
					session_start();
					$_SESSION['name'] = "correct";
					header("location: index.php");
				}
			else {
				throw new Exception('invalid Username or password');
			}
		}
		catch(Exception $e) {
			$error_msg = $e->getMessage();
		}
	}
?>
<!DOCTYPE HTML>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="../style-admin.css"/>
		<title>Login</title>
	</head>
	<body>
		<div class="login fix">
			<h2 class="login_hea">Admin Login Area</h2>
			<?php 
				if (isset($error_msg))
					{
						echo "<div class='error'>".$error_msg."</div>";
					}
			?>
			<form method="POST" action="">
				<table>
					<tr>
						<td>Username:</td>
						<td><input type="text" name="username" id="" /></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" name="password" id="" /></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value="Login" name="login" /></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>