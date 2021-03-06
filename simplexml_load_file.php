<?php

$oldtime = microtime(true);

$endpoint = 'http://query.yahooapis.com/v1/public/yql?q=';

$yql = 'select * from flickr.photos.search where has_geo="true" and text="beach"';

$url_xml = $endpoint . urlencode($yql). '&format=xml';

$photos = simplexml_load_file($url_xml);

$result = build_photos($photos->results->photo);

function build_photos($photos) {

     $output = '<ul>';

     if(count($photos) > 0) {

        foreach($photos as $photo) {

              $output .="<li><a href='http://www.flickr.com/photos/{$photo['owner']}/{$photo['id']}' target='_blank'><img src='http://farm{$photo['farm']}.static.flickr.com/{$photo['server']}/{$photo['id']}_{$photo['secret']}.jpg' alt='{$photo['title']}' width='75' height='75'/></a></li>";
        }

     } else {

       $output .= '</li>No Photos found.</li>';
     }

       $output .= '</ul>';      

  return $output;
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>flickr.photos.search</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <style type="text/css">
    ul{width: 400px;}
    ul li{display: inline;}
    h1{font-size: 30px;margin: 10px}
   </style>
</head>
<body>
<div id="doc" class="yui-t7">
   <div id="hd" role="banner"><h1><?php echo$yql; ?></h1></div>
   <div id="bd" role="main">
	<div class="yui-g">
        <?php echo$result; ?>  
	</div>
	</div>
   <div id="ft" role="contentinfo"><p>written by Adrian Statescu | <a href="1.phps">source</a> | <?php echo'Time spent: ';echo microtime(true) - $oldtime;echo' seconds';?></p></div>
</div>
</body>
</html>
