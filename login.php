<?php 
	session_start();
	require 'config.php';
	
	function check_user_login($user,$pass){
		$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$stmt = $conn->prepare("SELECT id, username, user_photo,first_name, last_name, email FROM users WHERE username=? AND  password=MD5(?) LIMIT 1");
		
		$stmt->execute(array($user, $pass));
		
		if($stmt->rowCount() > 0){
			$res = $stmt->fetch(PDO::FETCH_ASSOC);
			return $res;
		} else{
			return false;
		}
	}
	$msg="";
	if(isset($_POST["submit"])){
		$row = check_user_login($_POST['username'], $_POST['password']);
		if($row){
			$_SESSION['username'] = $row['username'];
			$_SESSION['id'] = $row['id'];
			$_SESSION['user_photo'] = $row['user_photo'];
			$_SESSION['first_name'] = $row['first_name'];
			$_SESSION['last_name'] = $row['last_name'];
			$_SESSION['email'] = $row['email'];
			
			header("Location: index.php");
			exit();
		} else{
			$msg = "Invalid username or password!";
		}
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
		
		<section id="login">
				<?php if(!isset($_SESSION['username'])) { ?>
                <h1 class="section_title">Login</h1>
				<div class="reg_log">
					<form action="login.php" method="post">
						<label for="username">Username</label>
						<input type="text" name="username" required>
						
						<label for="password">Password</label>
						<input type="password" name="password" required>
					
						<button name="submit" id="login_button">Login</button>
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
