<?php /*
<!doctype html>
<html lang="en">
<head><title></title></head>
<body style="padding: 0; margin: 0;">
<iframe src="<?php echo str_replace("~", ".", urldecode($_GET['url'])) ?>"
        style="position: relative; z-index: 0; width: 100%; height: <?php echo $_GET['height'] ?>px;" id="spy-iframe-lvl2" scrolling="no"
        frameborder="0" noresize="noresize"></iframe>
</body>
</html>
 */
include( "includes/aws_sdk/aws-autoloader.php" );

use Guzzle\Http\Client;
$client   = new Client( preg_replace("%~%", ".", urldecode($_GET['url'])) );
$request  = $client->get();
$response = $request->send();


//$request->setAuth('user', 'pass');
$html = $response->getBody();
if ( isset( $_GET['base'] ) ) {
	$html = preg_replace( "%<head>%", "<head><base href='". urldecode($_GET['base']) . "'>", $html );
}
echo $html;


