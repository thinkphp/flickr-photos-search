<?php

//start time
$oldtime = microtime(true);

//start the URL by defining the API endpoint and encoding the query
$endpoint = 'http://query.yahooapis.com/v1/public/yql?q=';

//user FLickr
$user = 'ydn';

//your YQL query
$yql = 'select * from flickr.photos.search(20) where user_id in (select value from html where url = "http://www.flickr.com/photos/'.$user.'" and xpath="//input[@name=\'w\']")';

//format (XML or JSON)
$format = '&format=json';

//diagnostics - remove it if you dont't need them
$diagnostics = '&diagnostics=true';

$url = $endpoint . urlencode($yql). $format.$diagnostics;

//this variable gives you access to the community tables
$env = '&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';

$output = get($url);

$json = json_decode($output);

//for debug
echo'<!-- ';
print_r($json);
echo' -->';

$result = build_photos($json->query->results->photo);

function build_photos($photos) {

     $output = '<ul>';

     if(count($photos) > 0) {

        foreach($photos as $photo) {

                $output .="<li><a href='http://www.flickr.com/photos/{$photo->owner}/{$photo->id}' target='_blank'><img src='http://farm{$photo->farm}.static.flickr.com/{$photo->server}/{$photo->id}_{$photo->secret}.jpg' alt='{$photo->title}' width='75' height='75'/></a></li>";
        }

     } else {

       $output .= '</li>No Photos found.</li>';
     }

       $output .= '</ul>';      

  return $output;
}

function get($url) {
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);
    $data = curl_exec($ch);
    curl_close($ch); 
    if(empty($data)) {return 'server timeout';}
                 else {return $data;}
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>get Photos</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
   <style type="text/css">
    html,body{font-family: helvetica,arial,sans-serif,verdana;color: #000}
    h1{background:none repeat scroll 0 0 #447E40;color:#FFFFFF;padding:14px;text-align:center;font-size: 40px;-moz-border-radius:5px;-border-radius:5px;-webkit-border-radius:5px;}
    h2{background: url(http://l.yimg.com/g/images/en-us/flickr-yahoo-logo.png.v2) no-repeat left;height: 40px}
    h2 span{;font-size: 20px;font-weight: bold;color: #393;padding-left: 200px}
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
        <h2><span>Get Photos from 'ydn' User Flickr</span></h2>
        <?php echo$result; ?>  
	</div>
	</div>
   <div id="ft" role="contentinfo"><p>Created by <a href="http://twitter.com/thinkphp">thinkphp</a> | <a href="getOwnPhotos-with-yql.phps">source</a> | <?php echo'Time spent: ';echo microtime(true) - $oldtime;echo' seconds';?></p></div>
</div>
</body>
</html>
