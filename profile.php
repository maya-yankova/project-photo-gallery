<?php 
	session_start();
	require 'config.php';
	
	if(!isset($_GET['id'])){
		header("Location: profile.php?id=".$_SESSION['id']);
	}
	
	$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
	$stmt = $conn->prepare("SELECT id, username, first_name, last_name, user_photo FROM users WHERE id=?");
		
	$stmt->execute(array($_GET['id']));
	$res = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$id = $res['id'];
	$user = $res['username'];
	$firstname = $res['first_name'];
	$lastname = $res['last_name'];
	$userphoto = $res['user_photo'];
	
	
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
		
		<section id="pro">
		<section id="profile">
			<h1 class="section_title"><?php echo $user; ?></h1>
			<div ><img class="user_photo" src="pictures/users/<?php echo $userphoto; ?>.jpg" alt="User Photo"></div>
			<nav id="profnav">
				<h1 id="name"><?php echo $firstname." ".$lastname; ?></h1>
				<?php if(isset($_SESSION['id']) && $id === $_SESSION['id']) { ?>
				<h1><a href="editprofilepic.php">Change profile picture</a></h1>
				<h1><a href="editprofile.php">Edit profile</a></h1>
				<h1><a href="upload.php">Upload</a></h1>
				<?php } ?>
			</nav>
		</section>
		<section id="user_pictures">
			<h1 class="section_title">Pictures</h1>
			<div class="list">
		<?php
			$stmt2 = $conn->prepare("SELECT id_pic FROM pictures WHERE id_a=? ORDER BY date_uploaded DESC");
				
			$stmt2->execute(array($user));
			if($stmt2->rowCount() < 1){ ?>
			<h1>No pictures found!</h1>
		<?php
			} else{ 
			$res = $stmt2->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($res as $value){ ?>
				<article>
					<div class="pic"><a href="picture.php?id_pic=<?php echo $value['id_pic']; ?>&id=<?php echo $_GET['id'];?>"><img class="picture" src="pictures/thumbnails/<?php echo $value['id_pic']; ?>.jpg" alt="photo"></a></div>
				</article>
		<?php } } ?>
		</div>
		</section>
		</section>
		
		<footer>
			<div id="left_footer">Проект по WWW Технологии</div>
			<div id="right_footer">ФМИ 2014</div>
		</footer>
	</div>
</body>
<html>