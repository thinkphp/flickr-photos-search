<?php

//get time
$oldtime = microtime(true);

//your YQL statement
$yql = "use 'http://thinkphp.ro/apps/YQL/flickr.own.xml' as flickr.own.photos; select * from flickr.own.photos where username='ydn' and amount=20 and size='s'";

//start the URL by defining the API endpoint and encoding the query
$endpoint = 'http://query.yahooapis.com/v1/public/yql?q=';
$url = $endpoint . urlencode($yql);

//diagnostics - remove it if you don't need them
//$url .= '&diagnostics=true';

//format - (xml or JSON)
$url .= '&format=xml';

//environment this gives you access to the community tables
//$env .= '&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';

$data = get($url);

//echo$data;

function get($url) {
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);
    $data = curl_exec($ch);
    $data = preg_replace('/<\?.*\?>/','',$data);
    $data = preg_replace('/<\!--.*-->/','',$data);
    $data = preg_replace('/.*<ul>/','<ul>',$data);
    $data = preg_replace('/<\/ul>.*/','</ul>',$data);
    curl_close($ch); 
    if(empty($data)) {return 'Server Timeout. Try agai later!';}
                 else {return $data;}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>Getting a list of Flickr photos by User with a YQL Open Table</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
   <style type="text/css">
    html,body{font-family: helvetica,arial,sans-serif,verdana;color: #000}
    h1{background:none repeat scroll 0 0 #447E40;color:#FFFFFF;padding:14px;text-align:center;font-size: 40px;-moz-border-radius:5px;-border-radius:5px;-webkit-border-radius:5px;}
    h2{font-size: 20px;font-weight: bold;color: #393;}
    ul{width: 400px;}
    ul li{display: inline;}
    #ft a{color: #393}
    
   </style>
</head>
<body>
<div id="doc2" class="yui-t7">
   <div id="hd" role="banner"><h1><?php echo$yql; ?></h1></div>
   <div id="bd" role="main">
	<div class="yui-g">
        <h2>Getting a list of Flickr photos by User with a YQL Open Table</h2>
        <?php echo$data; ?>  
	</div>
	</div>
   <div id="ft" role="contentinfo"><p>Created by @<a href="http://twitter.com/thinkphp">thinkphp</a> | <a href="getOwnPhotos-with-yql-table.phps">source</a> | <a href="http://thinkphp.ro/apps/YQL/flickr.own.xml">Open Data Table</a> | <?php echo'Time spent: ';echo microtime(true) - $oldtime;echo' seconds';?></p></div></div>
</body>
</html>