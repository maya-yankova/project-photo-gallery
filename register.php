<?php 
	session_start();
	require 'config.php';

	function check_user_exist($user, $email){
		$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$stmt1 = $conn->prepare("SELECT * FROM users WHERE username=?");
		$stmt2 = $conn->prepare("SELECT * FROM users WHERE email=?");
		
		$stmt1->execute(array($user));
		$stmt2->execute(array($email));
		
		if($stmt1->rowCount() > 0){
			$msg = "This username has already been used!";
			return $msg;
		} else{ if($stmt2->rowCount() > 0){
					$msg = "This email has already been used!";
					return $msg;
				} else{
					return false;
				}
		}
	}
	
	$msg="";
	if(isset($_POST['submit'])){
		if(!check_user_exist($_POST['username'], $_POST['email'])){
			$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
			$stmt = $conn->prepare("INSERT INTO users (username, password, first_name, last_name, email) VALUES (?, ?, ?, ?, ?)");
			
			$stmt->execute(array($_POST['username'], MD5($_POST['password']), $_POST['firstname'], $_POST['lastname'], $_POST['email']));
			
			$stmt2 = $conn->prepare("SELECT id, username, user_photo FROM users WHERE username=?");
			$stmt2->execute(array($_POST['username']));
			
			$res = $stmt2->fetch(PDO::FETCH_ASSOC);
			$_SESSION['username'] = $res['username'];
			$_SESSION['id'] = $res['id'];
			$_SESSION['user_photo'] = $row['user_photo'];
			
			header("Location: profile.php?id=".$_SESSION['id']);
			exit();
		} else {
			$msg = check_user_exist($_POST['username'], $_POST['email']);
		}
	}

	$dbh = null;
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
		
		<section id="register">
				<?php if(!isset($_SESSION['username'])) { ?>
                <h1 class="section_title">Registration</h1>
				<div class="reg_log">
					<form action="register.php" method="post">
						<label for="username">Username</label>
						<input type="text" name="username" required>
						
						<label for="password">Password</label>
						<input type="password" name="password" required>
						
						<label for="firstname">First name</label>
						<input type="text" name="firstname" required>
						
						<label for="lastname">Last name</label>
						<input type="text" name="lastname" required>
						
						<label for="email">Email</label>
						<input type="email" name="email" required>
					
						<button name="submit" id="registration_button">Register me!</button>
					</form>
				<div>
				<?php } else { ?>
				<h1> You are already logged in! </h1>
				<?php } ?>
            </section>
			<div class="msg"> <?php echo $msg; ?> </div>
		
		<footer>
			<div id="left_footer">Проект по WWW Технологии</div>
			<div id="right_footer">ФМИ 2014</div>
		</footer>
	</div>
</body>
<html>
