<?php
$schema          = str_replace( array( "http:", "https:" ), array( "", "" ), $this->PLUGIN_URL );
$cleaned_project = str_replace( '%20', '~', $project );
$src             = "\"{$schema}?hmtrackerjs={$cleaned_project}";
if ( is_agency() ) {
	$src .= "&uid={$user->user_key}&purl=\"+hmt_purl;";
} else {
	$src .= "\";";
}
?>
&lt;script type="text/javascript"&gt;if (typeof hmtracker == 'undefined') {var hmt_script = document.createElement('script'),hmt_purl = encodeURIComponent(location.href).replace('.', '~');hmt_script.type = "text/javascript";hmt_script.src = <?php echo $src; ?>document.getElementsByTagName('head')[0].appendChild(hmt_script);}&lt;/script&gt;