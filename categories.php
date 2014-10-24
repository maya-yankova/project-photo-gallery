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
		
		<?php if(!isset($_GET['id_c'])){ ?>
		<section id="categories">
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
			</div>
		</section>
		<?php } else { ?>
		<section id="categories">
			<h1 class="section_title"><?php switch($_GET['id_c']){ case 1: echo "Nature"; break; case 2: echo "Animals"; break; case 3: echo "The City"; break; case 4: echo "People"; break; case 5: echo "Sport"; break;} ?></h1>
			<div class="list"><?php
				require 'config.php';
				$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
				$stmt2 = $conn->prepare("SELECT id_pic FROM pictures WHERE id_c=? ORDER BY date_uploaded DESC ");
				
					switch($_GET['id_c']){
						case 1: $category = "Nature"; break;
						case 2: $category = "Animals"; break;
						case 3: $category = "The city"; break;
						case 4: $category = "People"; break;
						case 5: $category = "Sport"; break;
					}
				
				$stmt2->execute(array($category));
				$res = $stmt2->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($res as $value){ ?>
					<article>
						<div class="pic"><a href="picture.php?id_pic=<?php echo $value['id_pic']; ?>&id_c=<?php echo $_GET['id_c'];?>"><img class="picture" src="pictures/thumbnails/<?php echo $value['id_pic']; ?>.jpg" alt="photo"></a></div>
					</article>
			<?php } ?>
			</div>
		</section>
		<?php } ?>
		
		<footer>
			<div id="left_footer">Проект по WWW Технологии</div>
			<div id="right_footer">ФМИ 2014</div>
		</footer>
	</div>
</body>
<html>