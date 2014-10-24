<?php 
	session_start();
	
	require 'config.php';
	
	if(!isset($_SESSION['username'])){
		header("Location: index.php");
		exit();
	}

	if(isset($_POST['submit'])){
			  
			$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
			$stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=? WHERE id=".$_SESSION['id']);
			
			$stmt->execute(array($_POST['newfirstname'], $_POST['newlastname'], $_POST['newemail']));
			
			$_SESSION['first_name'] = $_POST['newfirstname'];
			$_SESSION['last_name'] = $_POST['newlastname'];
			$_SESSION['email'] = $_POST['newemail'];
			
			header("Location: profile.php");
	}
	
	if(isset($_POST['cancel'])){
	 header("Location: profile.php");
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title> Photo Gallery </title>
	<link href="style.css" rel="stylesheet">
</head>
<body>
	<div id="wrapper">
		<header>
			<div id="login-profile">
				<?php 
					if(isset($_SESSION['username'])){
				?>
				<nav class="sec">
					<h1><?php echo $_SESSION['username']; ?></h1>
					<h1><a href="profile.php?id=<?php echo $_SESSION['id']; ?>">Profile</a></h1>
					<h1><a href="logout.php">Exit</a></h1>
				</nav> 
				<?php }
					else{
				?>
				<nav class="sec">
					<h1><a href="login.php">Login</a></h1>
					<h1><a href="register.php">Register</a></h1>
				</nav>
				<?php }?>
			</div>
			<div id="main-head">
				<h1 class="logo"><a href="index.php"> Photo Gallery </a></h1>
				<nav class="main">
					<h1><a href="index.php">Home</a></h1>
					<h1><a href="categories.php">Categories</a>
							<ul>
								<a href="categories.php?id_c=1"><li>Nature</li></a>
								<a href="categories.php?id_c=2"><li>Animals</li></a>
								<a href="categories.php?id_c=3"><li>The city</li></a>
								<a href="categories.php?id_c=4"><li>People</li></a>
								<a href="categories.php?id_c=5"><li>Sport</li></a>
							</ul>
					</h1>
					<h1><a href="users.php">Users</a></h1>
				</nav>
			</div>
		</header>
		
		<section id="editprof">
			<h1 class="section_title">Edit profile</h1>
				<div ><img class="user_photo" src="pictures/users/<?php echo $_SESSION['user_photo']; ?>.jpg" alt="User Photo"></div>
			 	<form id="edit" action="editprofile.php" method="post" >
					<div><label for="newfirstname">First name:</label><br>
					<input type="text" name="newfirstname" value="<?php echo $_SESSION['first_name'];?>"> </div>
					
					<div><label for="newlastname">Last name:</label><br>
					<input type="text" name="newlastname" value="<?php echo $_SESSION['last_name'];?>"> </div>
					
					<div><label for="newemail">Email:</label><br>
					<input type="email" name="newemail" value="<?php echo $_SESSION['email'];?>"> </div>
					
					<div><button name="submit" id="up_button">Submit</button>
					<button name="cancel" id="up_button">Cancel</button></div>
				</form> 
		</section>
		
		<footer>
			<div id="left_footer">Проект по WWW Технологии</div>
			<div id="right_footer">ФМИ 2014</div>
		</footer>
	</div>
</body>
<html>