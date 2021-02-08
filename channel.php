<title>Ghostvideo - Display Yourself</title>
<html>
<head>
    <title><?php echo $title; ?></title>
</head>
<body>

 <!-- end header -->

<!-- begin footer -->
</body>
</html>
<?php
session_start();
$title = "Ghostvideo - Display Yourself"; // (1) Set the title
include "header.php";                     // (2) Include the header
?>

<style>
	#popup {
		background-color: rgb(220, 220, 220);
		position: fixed;
		top: 50%; left: 50%;
		transform: translate(-50%, -50%);
		width: 320px; height: 220px;
		display: none;
	}
	#popup_edit_avatar {
		background-color: rgb(220, 220, 220);
		position: fixed;
		top: 50%; left: 50%;
		transform: translate(-50%, -50%);
		width: 480px; height: 360px;
		display: none;
	}
	#popup_edit_channel {
		background-color: rgb(220, 220, 220);
		position: fixed;
		top: 50%; left: 50%;
		transform: translate(-50%, -50%);
		width: 480px; height: 360px;
		display: none;
	}
</style>

<?php
    include("db_credentials.php");
    error_reporting(0);

    $userId = $_GET['user'];
    
    if (isset($_POST['update_avatar'])) {
		//upload the file to the server:
		$avatarPath = $_FILES["user_avatar"]["name"];
		$avatarPathParts = pathinfo($avatarPath);

        $uniqid = uniqid();

		$avatar_dir = "images/avatars/";
		$target_avatar_file = $avatar_dir . $uniqid . "_" . basename($_FILES["user_avatar"]["name"]);
		$upload_ok = 1;

		$check = getimagesize($_FILES["user_avatar"]["tmp_name"]);
		if($check !== false) {
			$upload_ok = 1;
		} 
		else {
			$upload_ok = 0;
		}
		
		if ($upload_ok != 0) {
			if (move_uploaded_file($_FILES["user_avatar"]["tmp_name"], $target_avatar_file)) {
				/*echo "The avatar ". basename( $_FILES["user_avatar"]["name"]). " has been uploaded.";*/
			}
		}
		
		$update_avatar = "UPDATE Users SET avatar='". $uniqid . "_" . basename( $_FILES["user_avatar"]["name"]). "' WHERE user_id=".$_SESSION["userId"];
		$update = $conn->query($update_avatar);
	}

    $get_user_info = "SELECT * FROM Users WHERE user_id=".$userId;
	$result = $conn->query($get_user_info);

	if ($result->num_rows === 1) {
		while($row = $result->fetch_assoc()) {
			$username = $row['username'];
			$channel_email = $row['email'];
			$join_date = $row['join_date'];
			$avatar = $row['avatar'];
			$country = $row['country'];
			$website = $row['website'];
		}
	}
	else {
		die ("<p style='padding: 10px;'>This user does not exist.<p>");
	}
	
	$get_channel = "SELECT * FROM Channels WHERE channel_id='$userId'";
	$result = $conn->query($get_channel);

	if ($result->num_rows === 1) {
		while($row = $result->fetch_assoc()) {
			$channel_description = $row['channel_description'];
			$featured_video = $row['featured_video'];
			$background_image = $row['background_image'];
		}
	}
?>

<!-- begin page content -->
<div class="pr_box_left" style='display: inline-block; width: 360px;'>
    <div class="pr_box_hd highlight_hd">
        <div class="box_hd_l" style="width: 200px">
            <div style="position:width:210px;line-height:24px;font-size:15px">
                                                                                 </div>
        </div>
        <div class="box_hd_r" style="position: absolute; top: 50%; right: 7px">
            <div class="vcenter">
                <a href="javascript:void(0)" class="sub" onclick="alert('You must be logged in to subscribe!')">Subscribe</a>
            </div>
        </div>
    </div>
    <div class="pr_box_in highlight_in">
        <div class="high_hd">
            <div class="high_av">
                <div style="padding:2px;border:1px solid gray;background-color:white">
                    <a href="/web/20160714204944/http://www.vidbit.co/user/VidBit"><img class="avatar" style="border: 1px double #999" src="images/avatars/<?php echo ($avatar); ?>" width="96" height="96"></a>                        
                </div>
                <br />
            </div>
            <div class="high_info">
                <div class="info_user">

                    <?php
                        echo ($username);
                    ?>

                </div>
            </div>
        </div>
        <div class="high_pro">
            Age: <strong>
            <?php 
                if ($age != "")
                    echo ($age);
                else
                    echo ("Not specified");
            ?> 
            </strong>
        </div>
                        
        <div class="high_dscr">
            <?php 
                if ($channel_description != "")
                    echo ($channel_description);
                else
                    echo ("No description");
            ?>
        </div>
        <div class="high_pro">
            Country: 
            <strong>
            <?php 
                if ($country != "")
                    echo ($country);
                else
                    echo ("Not specified");
            ?>
            </strong>
        </div>
        <div class="high_pro">
            Website: <strong>
            <?php 
                if ($website != "")
                    echo ('<a href="$website">'.$website.'</a>');
                else
                    echo ("Not specified");
            ?>
            </strong>
        </div>
    </div>
    
    <?php
		if ($userId == $_SESSION["userId"]) {
			echo '<br />
			<button style=
				"font-size: 16px; 
				font-weight: bold;"
				onclick="show_popup(1)">Change your avatar</button>
			<br />
			<br />
			';
		}
	?>
</div>

<div class="pr_box_right" style='display: inline-block;'>
	<div style="padding: 1rem;">
	<?php
		if ($featured_video != "") {
			echo "<video class='vid' src='videos/uploaded/$featured_video_file' controls width='100%' height='auto' style='width: 640px;'></video>";
		}
		else {
			echo "<img src='images/no_featured_video.png' style='width: 640px;'>";
		}
		
		echo "<br />";
		
		if ($featured_video != "") {
		    echo "<div style='font-weight: bold; font-size: 18px;'><a href='watch_video.php?id=1' 
			    class='feat_video_link'>featured video</a></div>";
		}
	?>
	</div>
</div>
<!-- end page content -->

<!-- POPUPS AND FUNCTIONS THAT SHOW/HIDE THEM -->
<!-- 'unsubscribe from?' popup -->
<div id="popup">
	<h2 style="text-align: center; padding-top: 20px;">Unsubscribe from <?php echo ($channel_user); ?>?</h2>
	<table style="padding-top: 50px; padding-left: 78px;"><tr>
	<td><button onclick="cancel()">Cancel</button></td>
	<td><button onclick="unsubscribe(
		<?php echo ($_SESSION["userId"]); ?>, 
		<?php echo ($_GET['user']); ?>)
	">Unsubscribe</button></td>
	</tr></table>
</div>
<!-- end popup -->
<!-- 'edit avatar' popup -->
<div id="popup_edit_avatar">
	<form method="POST" enctype="multipart/form-data">
		<input name="user" value="<?php echo ($_SESSION["userId"]); ?>" hidden />
		<h2 style="text-align: center; padding-top: 20px; padding-bottom: 20px;">Change your avatar</h2>
		<tr>
			<td><label><strong style="padding: 5px;">Avatar</strong></label></td>
		</tr>		
		<tr>
			<td><input id="avatar_input" class="form-control" type="file" name="user_avatar" accept="image/*" /></td>					
		</tr>
		<tr>
			<td><p style="padding: 5px; font-size: 12px; font-style: italic;">Avatars should have a 1:1 aspect ratio,
			as any and all avatars uploaded to Ghostvideo will be stretched to fit a 1:1 ratio and some 
			avatars may end up looking awkward as a result.</p></td>
		</tr>
		<table style="padding-top: 50px; padding-left: 30px;"><tr>
		<td><input type="submit" name="update_avatar" value="Update avatar" /></td>
		</tr></table>
	</form>
	<div style="padding-left: 33px;"><button onclick="hide_popup(1)">Cancel</button></div>
</div>
<!-- 'edit channel' popup -->
<div id="popup_edit_channel">
	<form method="POST" enctype="multipart/form-data">
		<input name="user" value="<?php echo ($_SESSION["userId"]); ?>" hidden />
		<h2 style="text-align: center; padding-top: 20px; padding-bottom: 20px;">Customize your channel</h2>
		<table style="padding-left: 20px;">
		<tr>
			<td><label style="padding-bottom: 30px;"><strong style="padding: 5px;">Channel background</strong></label></td>
		</tr>		
		<tr>
			<td><input id="avatar_input" class="form-control" type="file" name="user_avatar" accept="image/*" /></td>					
		</tr>
		<tr>
			<td><label><strong style="padding: 5px;">Country</strong></label></td>
		</tr>
		<tr>
		
		<td><input type="submit" name="update_channel" value="Save changes" /></td>
		</tr></table>
	</form>
	<div style="padding-left: 33px;"><button onclick="hide_popup(2)">Cancel</button></div>
</div>
<!-- end popup -->	

<script>
	function show_popup(popup) {
		//show popup
		var p;
		if (popup == 1)
			var p = document.getElementById("popup_edit_avatar");
		else if (popup == 2)
			var p = document.getElementById("popup_edit_channel");
		p.style.display = "block";
	}
	function hide_popup(popup) {
		//show popup
		var p;
		if (popup == 1)
			var p = document.getElementById("popup_edit_avatar");
		else if (popup == 2)
			var p = document.getElementById("popup_edit_channel");
		p.style.display = "none";
	}
</script>

<?php
include "footer.php";                 // (3) Include the footer
?>
