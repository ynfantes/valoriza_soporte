<?php 
require_once __DIR__.'/vendor/autoload.php';

$app_id = "242011383598796"; //change this
$app_secret = "151a51441d76a82b33a9e6ac8501a896"; //change this
$redirect_url = "https://soporte.administracion-condominio.com.ve/hybridauth.php"; //change this

$code = $_REQUEST["code"];
session_start();

if(empty($code)) 
{
    header( 'Location: https://soporte.administracion-condominio.com.ve/administracion' ) ; //change this
    exit(0);
}

$access_token_details = getAccessTokenDetails($app_id,$app_secret,$redirect_url,$code);

if($access_token_details == null)
{
            echo "No se obtiene el token de acceso";
            exit(0);
}   

if($_SESSION['state'] == null || ($_SESSION['state'] != $_REQUEST['state'])) 
{
            die("May be CSRF attack");
}

$_SESSION['access_token'] = $access_token_details['access_token']; //save token is session 

$fb = new \Facebook\Facebook([
  'app_id' => $app_id,           //Replace {your-app-id} with your app ID
  'app_secret' => $app_secret,   //Replace {your-app-secret} with your app secret
  'graph_api_version' => 'v6.0',
]);

try {
   
// Get your UserNode object, replace {access-token} with your token
  $response = $fb->get('/me?fields=name,email,first_name,last_name,picture', 
          $_SESSION['access_token']);

} catch(\Facebook\Exceptions\FacebookResponseException $e) {
        // Returns Graph API errors when they occur
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
    // Returns SDK errors when validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$me = $response->getGraphUser();

//All that is returned in the response
echo 'All the data returned from the Facebook server:'.$me;
echo '<br>';
//Print out my name
echo 'Mi nombre es '.$me->getName();
echo '<br>';
$picture = $me->getPicture();
//var_dump($picture['url']);
//echo 'Mi picture '.$me->getPicture();
echo '<img src="'.$picture['url'].'">';
//   $user = getUserDetails($access_token_details['access_token']);
//   
//   if($user)
//   {
//		echo "Facebook OAuth is OK<br>";
//		echo "<h3>User Details</h3><br>";
//		echo "<b>ID: </b>".$user->id."<br>";
//		echo "<b>Name: </b>".$user->name."<br>";
//		echo "<b>First Name: </b>".$user->first_name."<br>";
//		echo "<b>Last Name: </b>".$user->last_name."<br>";
//		echo "<b>Username: </b>".$user->username."<br>";
//		echo "<b>Profile Link: </b>".$user->link."<br>";
//		echo "<b>email: </b>".$user->email."<br>";
//                var_dump($user);
//		
//   }
//	
//	
function getAccessTokenDetails($app_id,$app_secret,$redirect_url,$code)
{

	$token_url = "https://graph.facebook.com/oauth/access_token?"
	  . "client_id=".$app_id."&redirect_uri=".urlencode($redirect_url)
	  . "&client_secret=".$app_secret."&code=".$code;

	$response = file_get_contents($token_url);
        $params = null;
        $params = json_decode($response,true);
        return $params;

}
//
//function getUserDetails($access_token)
//{       
//	$graph_url = "https://graph.facebook.com/me?access_token=".$access_token;
//        $user = json_decode(file_get_contents($graph_url));
//	
//        if($user != null && isset($user->name)) {
//            return $user;
//        } else {
//            return null;
//        }
//}
