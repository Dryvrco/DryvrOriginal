<?php

//MySqli details for saving user details
$mysql_host = 'localhost';
$mysql_username = 'dryvrc5_ef';
$mysql_password = 'iT0ZvTrT3NGM';
$mysql_db_name = 'dryvrc5_cars';


$id = $_GET['id'];
$name = $_GET['name'];
$email = $_GET['email'];
$birthday = $_GET['birthday'];

echo 'My Name is ' . $name . '. My id is ' . $id . '. My email is ' . $email . ' and my birthday is on ' . $birthday . '.';

###### connect to user table ########
/*$mysqli = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_db_name);
if ($mysqli->connect_error) {
    die('Error : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}*/


/* Demo review
if ($session){ //if we have the FB session
	
	######## Fetch user Info ###########
	$user_profile = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className());
    $user_id =  $user_profile->getId(); 
    $user_name = $user_profile->getName(); 
	$user_email =  $user_profile->getEmail();
	$location =  $user_profile->getLocation();


    ######## Check User Permission called 'publish_actions' ##########
    $user_permissions = (new FacebookRequest($session, 'GET', '/me/permissions'))->execute()->getGraphObject(GraphUser::className())->asArray();
    $found_permission = false;
    foreach($user_permissions as $key => $val){         
        if($val->permission == 'publish_actions'){
            $found_permission = true;
        }
    }
    
	###### post image if 'publish_actions' permission available ########
    if($found_permission){
		$image = "/home/images/image_name.jpg"; //server path to image
		$post_data = array('source' => '@'.$image, 'message' => 'This is test Message');
		$response = (new FacebookRequest( $session, 'POST', '/me/photos', $post_data ))->execute()->getGraphObject();
    }


	###### Save info in database ########
	$mysqli = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_db_name);
	if ($mysqli->connect_error) {
		die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}
	$results = $mysqli->query("SELECT COUNT(*) FROM usertable WHERE id=".$user_id);
	$get_total_rows = $results->fetch_row();
	
	if(!$get_total_rows[0]){
		$insert_row = $mysqli->query("INSERT INTO usertable (fbid, fullname, email) VALUES(".$user_id.", '".$user_name."', '".$user_email."')");
		if($insert_row){
			print 'Success! ID of last inserted record is : ' .$mysqli->insert_id .'<br />'; 
		}
	}
}else{ 

	//display login url 
	$login_url = $helper->getLoginUrl( array( 'scope' => $required_scope ) );
	echo '<a href="'.$login_url.'">Login with Facebook</a>'; 
}
*/