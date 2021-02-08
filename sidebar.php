<?php
	session_start();
?>

<!-- sidebar reused on many of the website's pages -->
<aside class="sidebar">

<?php
//if logged in...
if ($_SESSION['userName'] != null) {
    
	include("db_credentials.php");

	$lifetime_views = 0;
	$get_all_videos = "SELECT * FROM Videos WHERE uploader=".$_SESSION['userId'];
	$result = $conn->query($get_all_videos);
	$total_videos = $result->num_rows;

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$lifetime_views += $row['views'];
		}
	}
	
	$subscribers = 0;
	$get_all_subscribers = "SELECT * FROM Subscriptions WHERE subscribed_to_id=".$_SESSION['userId'];
	$result = $conn->query($get_all_subscribers);
	$subscribers = $result->num_rows;
		
	$get_user_info = "SELECT * FROM Users WHERE user_id=".$_SESSION['userId'];
	$result = $conn->query($get_user_info);		

	if ($result->num_rows === 1) {
		while($row = $result->fetch_assoc()) {
			$avatar = $row['avatar'];
		}
	}
				
		
	echo '<div>';
	  echo '<div class="userinfo">';
		echo '<div class="sidebar_index">';
		  echo '<a class="username" href="channel.php?user='.$_SESSION['userId'].'">'.$_SESSION['userName'].'</a>';
		echo '</div>';
		echo '<div class="usericon" >';
		  if ($avatar != '') {
			echo '<img src="images/avatars/'.$avatar.'" style="width: 100%;">';
		  }
		  else {
			echo '<img src="images/791224_man_512x512.png" style="width: 100%;">';
		  }
		echo '</div>';
		echo '<div class="sidebar_footer" >';
		  echo 'Subscribers: '.$subscribers.'';
		  echo '<br>';
		  echo 'Views: '.$lifetime_views.'';
		  echo '<br>';
		  echo 'Videos: '.$total_videos.'';
		echo '</div>';
	  echo '</div>';
	  echo '<b>Manage content</b>';
	  echo '<hr />';
	  echo '<a href="https://vidsurf.glitch.me/index.html#">My stats</a><br />';
	  echo '<a href="video_manager.php">My videos</a><br />';
	  echo '<a href="https://vidsurf.glitch.me/index.html#">My info</a><br />';
	  echo '<br />';
	  echo '<b>Subscriptions</b><br />';
	  echo '<hr>';
	  
	  $get_all_subscriptions = "SELECT * FROM Subscriptions WHERE subscriber_id=".$_SESSION['userId']."";
	  $result = $conn->query($get_all_subscriptions);
	  if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$get_user = "SELECT * FROM Users WHERE user_id=".$row['subscribed_to_id'];
			$result2 = $conn->query($get_user);
			if ($result2->num_rows === 1) {
				while($row = $result2->fetch_assoc()) {
					echo "<a href='channel.php?user=".$row['user_id']."'>".$row['username']."</a><br />";
				}
			}
		}
	  }

	 echo '</div>';
}
?>

<!-- ALWAYS show... -->
<div>
	<b></b>
</div>
  
</aside>
