<?php 
	session_start();
	require 'config.php';
	
	if(!isset($_POST['submit'])){
		if(!isset($_GET['id_pic'])){
			header("Location: index.php");
			exit();
		}
		
		$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$stmt = $conn->prepare("SELECT name_pic, id_a, id_c, date_uploaded FROM pictures WHERE id_pic=? ");
		$stmt2 = $conn->prepare("SELECT author, comment, date FROM comments WHERE pic=? ORDER BY date DESC ");
			
		$stmt->execute(array($_GET['id_pic']));
		$stmt2->execute(array($_GET['id_pic']));
		
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		
		
		$picture = $res['name_pic'];
		$author = $res['id_a'];
		$date = date_create($res['date_uploaded']);
		$category = $res['id_c'];
	}
	if(isset($_POST['comment'])){
		$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$stmt = $conn->prepare("SELECT name_pic, id_a, id_c, date_uploaded FROM pictures WHERE id_pic=? ");
		$stmt2 = $conn->prepare("SELECT author, comment, date FROM comments WHERE pic=? ORDER BY date DESC ");
		
			
		$stmt->execute(array($_POST['id_pic']));
		$stmt2->execute(array($_POST['picid']));
		
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		
		
		$picture = $res['name_pic'];
		$author = $res['id_a'];
		$date = date_create($res['date_uploaded']);
		$category = $res['id_c'];
		
		$stmt3 = $conn->prepare("INSERT INTO comments (author, pic, comment, date) VALUES (?, ?, ?, ?) ");
		$stmt3->execute(array($_SESSION['username'], $_POST['picid'], $_POST['comment'], date("Y-m-d H:i:s")));
		
		header("Location: picture.php?id_pic=".$_POST['picid']);
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
		
		<section>
		<section id="showpic">
			<div ><img src="pictures/<?php echo $_GET['id_pic'];?>.jpg"></div>
			<?php if(isset($_SESSION['username']) and $author == $_SESSION['username']){ ?><div><a href="editpic.php?id_pic=<?php echo $_GET['id_pic'];?>&user=<?php echo $author;?>">Edit picture</a></div><?php } ?>
		</section>
		<section id="info">
			<div><h1>Title:</h1> <?php echo $picture;?></div>
			<div><h1>Author:</h1> <?php echo $author;?></div>
			<div><h1>Category:</h1> <?php echo $category;?></div>
			<div><h1>Date uploaded:</h1> <?php echo date_format($date, "F j, Y, g:i a");?></div>
			<div><h1>Comments:</h1></div>
			<?php 
					if(isset($_SESSION['username'])){
			?>
				<form id="edit" action="picture.php" method="post" >
				<div >
				<textarea rows="4" cols="30" name="comment" placeholder="Leave a comment..."></textarea>
				<button name="submit" id="up_button">Comment</button> </div>
				<input type="hidden" name="picid" value="<?php echo $_GET['id_pic']?>">
				</form>
			<?php } ?>
			
			<?php 
					if($stmt2->rowCount() < 1){ ?>
						No comments found!
			<?php
					} else{ 
						$com = $stmt2->fetchAll(PDO::FETCH_ASSOC);
					
						foreach($com as $value){
							?><div class="comment">
									<div>by <b><?php echo $value['author']?></b></div>
									<div>" <?php echo $value['comment']; ?> "</div>
									<div><?php echo date_format(date_create($value['date']), "F j, Y, g:i a"); ?></div>
							  </div><?php
						}
					}
			?>
		</section>
		</section>
		<footer>
			<div id="left_footer">Проект по WWW Технологии</div>
			<div id="right_footer">ФМИ 2014</div>
		</footer>
	</div>
</body>
<html>