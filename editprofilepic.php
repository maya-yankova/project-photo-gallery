<?php 
	session_start();
	
	require 'config.php';
	
	if(!isset($_SESSION['username'])){
		header("Location: index.php");
		exit();
	}
	$msg="";
	$picid = $_SESSION['user_photo'];
	if(isset($_POST['submit'])){
		if(isset($_FILES['uploadfile'])){
			$uploaddir = './pictures/users/'; 
			
			$picid = md5(date('Y-m-d H:i:s:u'));
			$filedir = $uploaddir . $picid.".jpg";   
			
			if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $filedir)) {  
				//resize the original image
			  $imagepath = $picid; 
              $save = "pictures/users/" . $imagepath.".jpg"; //This is the new file you saving 
              $file = "pictures/users/" . $imagepath.".jpg"; //This is the original file 
  
              list($width, $height) = getimagesize($file) ;  
  
              $modwidth = 500;  
  
              $diff = $width / $modwidth; 
  
              $modheight = $height / $diff;  
              $tn = imagecreatetruecolor($modwidth, $modheight) ;  
              $image = imagecreatefromjpeg($file) ;  
              imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ;  
  
              imagejpeg($tn, $save, 100) ;
			  $_SESSION['user_photo'] = $picid;
			} else{
					$msg = "The image should be less than 2MB!"; 					
			}
		} 
	}
	
	if(isset($_POST['croppic'])){
		$x1      = $_POST['x1'];
		$y1      = $_POST['y1'];
		$width   = $_POST['width'];
		$height  = $_POST['height'];
		 
		$srcImg  = imagecreatefromjpeg('pictures/users/'.$picid.'.jpg');
		$newImg  = imagecreatetruecolor($width, $height);
		 
		imagecopyresampled($newImg, $srcImg, 0, 0, $x1, $y1, $width, $height, $width, $height);
		 
		imagejpeg($newImg, 'pictures/users/'.$picid.'.jpg');
			
				
		$conn = new PDO('mysql:host=localhost;dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$stmt2 = $conn->prepare("SELECT user_photo FROM users WHERE id=".$_SESSION['id']);
		$stmt = $conn->prepare("UPDATE users SET user_photo=? WHERE id=".$_SESSION['id']);
		
		$stmt2->execute();
		
		$res = $stmt2->fetch(PDO::FETCH_ASSOC);
	
		$delpic = $res['user_photo'];
		
		unlink("pictures/users/".$delpic.".jpg");
		
		$stmt->execute(array($picid));
				
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
	<script src="scripts/prototype.js" type="text/javascript"></script>
	<script src="scripts/scriptaculous.js?load=effects,builder,dragdrop" type="text/javascript"></script>
	<script src="scripts/cropper.js" type="text/javascript"></script>
	
	<script type="text/javascript" charset="utf-8">
		Event.observe (
			window,
			'load',
			function() {
				new Cropper.Img (
					'profpic',
					{
						minWidth: 150,
						minHeight: 150,
						ratioDim: { x: 150, y: 150 },
						displayOnInit: true,
						onEndCrop: saveCoords,
						onloadCoords: { x1: 0, y1: 0, x2: 200, y2: 100 },
					}
				)
			}
		);
		 
		function saveCoords (coords, dimensions)
		{
			$( 'x1' ).value = coords.x1;
			$( 'y1' ).value = coords.y1;
			$( 'width' ).value = dimensions.width;
			$( 'height' ).value = dimensions.height;
		}
	</script>
	
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
		<section id="editprofpic">
			<h1 class="section_title">Change profile picture</h1>
				<div ><img class="user_photo" src="pictures/users/<?php echo $picid; ?>.jpg" alt="User Photo"></div>
			 	<form id="edit" action="editprofilepic.php" method="post" enctype="multipart/form-data">
					<div><label for="file">Change profile picture:</label><br>
					<input type="file" name="uploadfile" id="uploadfile" /> </div>
					
					<div><button name="submit" id="up_button">Submit</button>
					<button name="cancel" id="up_button">Cancel</button></div>
				</form> 
				<span><?php echo $msg; ?></span>
		</section>
		
		<?php if(isset($_POST['submit'])){ ?>
		<section id="crop">
			<form action="editprofilepic.php" method="post">
				<div >
					<div >
						<img src="pictures/users/<?php echo $picid?>.jpg" id="profpic" />
					</div>
					
					<div style="clear:both"></div>
					<input type="hidden" name="x1" id="x1" value="">
					<input type="hidden" name="y1" id="y1" value="">
					<input type="hidden" name="width" id="width" value="">
					<input type="hidden" name="height" id="height" value="">
				</div><br>
				<button name="croppic" id="up_button">Done cropping</button>
			</form>
		</section>
		<?php } ?>
		</section>
		<footer>
			<div id="left_footer">Проект по WWW Технологии</div>
			<div id="right_footer">ФМИ 2014</div>
		</footer>
	</div>
</body>
<html>