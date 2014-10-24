<?php 
	session_start();
	require 'config.php';
	
	if(!isset($_POST['change']) and !isset($_POST['delete']) and !isset($_POST['cancel'])){
		if(!isset($_GET['id_pic'])){
			header("Location: index.php");
			exit();
		}
	
	
		$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$stmt = $conn->prepare("SELECT name_pic, id_a, id_c FROM pictures WHERE id_pic=? ");
			
		$stmt->execute(array($_GET['id_pic']));
		
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$picture = $res['name_pic'];
		$author = $res['id_a'];
		$category = $res['id_c'];
		
		if($_SESSION['username'] != $author){
			header("Location: index.php");
			exit();
		}
	}
	
	if(isset($_POST['cancel'])){
	 header("Location: picture.php?id_pic=".$_POST['picid']);
	}
	
	if(isset($_POST['delete'])){
		unlink("pictures/".$_POST['picid'].".jpg");
		unlink("pictures/thumbnails/".$_POST['picid'].".jpg");
		
		$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$stmt = $conn->prepare("DELETE FROM pictures WHERE id_pic=?");
			
		$stmt->execute(array($_POST['picid']));
		
		header("Location: profile.php");
	}
	
	if(isset($_POST['change'])){
		$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$stmt = $conn->prepare("UPDATE pictures SET name_pic=?, id_c=? WHERE id_pic=?");
			
		$stmt->execute(array($_POST['newtitle'], $_POST['newcategory'], $_POST['picid']));
		
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
		</section>
		<section id="editpic">
			<h1 class="section_title">Edit picture</h1>
			 	<form id="edit" action="editpic.php" method="post" >
					<div><label for="newtitle">Change title:</label><br>
					<input type="text" name="newtitle" value="<?php echo $picture;?>"> </div>
					
					<div><label for="newcategory">Change category:</label><br>
					<select name="newcategory" >
					  <option value="Nature" <?php if($category == "Nature"){ echo "selected";}?>>Nature</option>
					  <option value="Animals" <?php if($category == "Animals"){ echo "selected";}?>>Animals</option>
					  <option value="The city" <?php if($category == "The city"){ echo "selected";}?>>The city</option>
					  <option value="People" <?php if($category == "People"){ echo "selected";}?>>People</option>
					  <option value="Sport" <?php if($category == "Sport"){ echo "selected";}?>>Sport</option>
					</select> </div>
					
					<div><button name="change" id="up_button">Change</button>
					<button name="delete" id="up_button">Delete picture</button>
					<button name="cancel" id="up_button">Cancel</button></div>
					<input type="hidden" name="picid" value="<?php echo $_GET['id_pic']?>">
				</form>
		</section>
		</section>
		<footer>
			<div id="left_footer">Проект по WWW Технологии</div>
			<div id="right_footer">ФМИ 2014</div>
		</footer>
	</div>
</body>
<html>