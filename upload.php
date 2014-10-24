<?php 
	session_start();
	
	require 'config.php';
	
	if(!isset($_SESSION['username'])){
		header("Location: index.php");
		exit();
	}
	 $msg="";
	if(isset($_POST['submit'])){
	 
		if(isset($_FILES['uploadfile'])){
			$uploaddir = './pictures/'; 
			
			$picid = md5(date('Y-m-d H:i:s:u'));
			$filedir = $uploaddir . $picid.".jpg";   
			
			
			
			if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $filedir)) {  
					$imagepath = $picid; 
				  $save = "pictures/" . $imagepath.".jpg"; //This is the new file you saving 
				  $file = "pictures/" . $imagepath.".jpg"; //This is the original file 
	  
				  list($width, $height) = getimagesize($file) ;  
	  
				  $modwidth=0;  
				  $modheight=0;
					if($width > 500){
						$modwidth = 500;
						
						$diff = $width / $modwidth; 
	  
						$modheight = $height / $diff;
					} else{
						$modwidth = $width;
						$modheight = $height;
					}
				    
				  $tn = imagecreatetruecolor($modwidth, $modheight) ;  
				  $image = imagecreatefromjpeg($file) ;  
				  imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ;  
	  
				  imagejpeg($tn, $save, 100) ;
								//make a thumbnail
				  $thumbnail = "pictures/thumbnails/" . $imagepath.".jpg";
				  $thumbwidth = 150;
				  $thumbheight = 150;
				  
				  $tn2 = imagecreatetruecolor($thumbwidth, $thumbheight) ;  
				  
				  if($width > $height){  
					imagecopyresampled($tn2, $image, 0, 0, ($width-$height)/2, 0, $thumbwidth, $thumbheight, $width - ($width - $height), $height) ;  
				  } else{ if($width < $height){
					imagecopyresampled($tn2, $image, 0, 0, 0, ($height-$width)/2, $thumbwidth, $thumbheight, $width, $height - ($height-$width)) ;
				  } else{
					imagecopyresampled($tn2, $image, 0, 0, 0, 0, $thumbwidth, $thumbheight, $width, $height) ;
				  }}
	  
				  imagejpeg($tn2, $thumbnail, 100) ;
					
					$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
				$stmt = $conn->prepare("INSERT INTO pictures (id_pic, name_pic, id_a, id_c, date_uploaded) VALUES (?, ?, ?, ?, ?)");
				$stmt2 = $conn->prepare("UPDATE users SET last_upload=? WHERE id=".$_SESSION['id']);
			
				$stmt->execute(array($picid, $_POST['picname'], $_SESSION['username'], $_POST['category'], date("Y-m-d H:i:s")));
				$stmt2->execute(array(date("Y-m-d H:i:s")));
				  header("Location: profile.php");
				  
			} else {  
				$msg = "The image should be less than 2MB!";  
			}  
		}
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
		
		<section id="upload">
			<h1 class="section_title">Upload picture</h1>
			 	<form id="upload" action="upload.php" method="post" enctype="multipart/form-data">
					<div><label for="file">New picture:</label><br>
					<input type="file" name="uploadfile" id="uploadfile" /> 
					<span id="status" ></span> </div>
					<div><label for="picname">Name:</label><br>
					<input type="text" name="picname" > </div>
					<div><label for="category">Category:</label><br>
					<select name="category" required>
					  <option value="">Please select</option>
					  <option value="Nature" >Nature</option>
					  <option value="Animals">Animals</option>
					  <option value="The city">The city</option>
					  <option value="People">People</option>
					  <option value="Sport">Sport</option>
					</select></div>
					
					<div><button name="submit" id="up_button">Upload</button>
					<button name="cancel" id="up_button">Cancel</button></div>
				</form> 
				<span><?php echo $msg; ?></span>

		</section>
		
		<footer>
			<div id="left_footer">Проект по WWW Технологии</div>
			<div id="right_footer">ФМИ 2014</div>
		</footer>
	</div>
</body>
<html>