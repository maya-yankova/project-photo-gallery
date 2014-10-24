<?php 
	session_start();
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
		
		<section id="category">
			<h1 class="section_title">Categories</h1>
			<div class="list">
				<article>
					<div class="user_name"><a href="categories.php?id_c=1">Nature</a></div>
					<div ><img class="user_photo" src="pictures/nature.jpg" alt="User Photo"></div>
				</article>
				<article>
					<div class="user_name"><a href="categories.php?id_c=2">Animals</a></div>
					<div ><img class="user_photo" src="pictures/animals.jpg" alt="User Photo"></div>
				</article>
				<article>
					<div class="user_name"><a href="categories.php?id_c=3">The City</a></div>
					<div ><img class="user_photo" src="pictures/city.jpg" alt="User Photo"></div>
				</article>
				<article>
					<div class="user_name"><a href="categories.php?id_c=4">People</a></div>
					<div ><img class="user_photo" src="pictures/people.jpg" alt="User Photo"></div>
				</article>
				<article>
					<div class="user_name"><a href="categories.php?id_c=5">Sport</a></div>
					<div ><img class="user_photo" src="pictures/sport.jpg" alt="User Photo"></div>
				</article>
		</section>
		<section id="latest">
			<h1 class="section_title">Latest Pictures</h1>
			<div class="list">
				
			<?php
				require 'config.php';
				
				$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
				$stmt2 = $conn->prepare("SELECT id_pic FROM pictures ORDER BY date_uploaded DESC LIMIT 5");
					
				$stmt2->execute();
				$res = $stmt2->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($res as $value){ ?>
					<article>
						<div class="pic"><a href="picture.php?id_pic=<?php echo $value['id_pic']; ?>"><img class="picture" src="pictures/thumbnails/<?php echo $value['id_pic']; ?>.jpg" alt="photo"></a></div>
					</article>
			<?php } ?>
			</div>
		</section>
		<section id="users">
			<h1 class="section_title">Users</h1>
			<div class="list">
			<?php
				
				
				
				$stmt = $conn->prepare("SELECT id, username, user_photo FROM users ORDER BY last_upload DESC LIMIT 5");
					
				$stmt->execute();
				$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($res as $value){ ?>
					<article>
						<div class="user_name"><a href="profile.php?id=<?php echo $value['id'];?>"><?php echo $value['username'];?></a></div>
						<div ><img class="user_photo" src="pictures/users/<?php echo $value['user_photo']; ?>.jpg" alt="User Photo"></div>
					</article>
			<?php } ?>
			</div>
		</section>

		<footer>
			<div id="left_footer">Проект по WWW Технологии</div>
			<div id="right_footer">ФМИ 2014</div>
		</footer>
	</div>
</body>
<html>