<?php
/*
* HTML - JPG -parser
*
* Authors:
* Ville-Pekka Lahti <ville-pekka.lahti@hotmail.com>
*/

function base64_to_jpg_file($b64, $jpgfile) {
    $f = fopen($jpgfile, "wb"); 
    $d = explode(",", $b64);
    fwrite($f, base64_decode($d[1]));
    fclose($f); 
    return $jpgfile; 
}

function htmlImagesToFilesAndResize($html, $max_width, $max_height) {
	$doc = new DOMDocument();
	@$doc->loadHTML($html);
	$tags = $doc->getElementsByTagName("img");
	$c=0;
	foreach ($tags as $tag) {
		$c++;
		$imagesrc = $tag->getAttribute("src");
		$imgpath = "dl/";
		$imgname = $c.".jpg";
		$tag->setAttribute("src", $imgpath.$imgname);
		@mkdir($imgpath);
		base64_to_jpg_file($imagesrc, $imgpath.$imgname);

		// Get new dimensions
		list($width, $height) = getimagesize($imgpath.$imgname);
		
		$new_width = min($width, $max_width);
		$new_height = ($new_width / $width) * $height; // save the ratio but no max height yet...
		if ($new_height > $max_height) {
			// we have to force width to be smaller than max width, which is common if the image is vertical
			// we need to scale image down again:
			$new_width = ($max_height / $new_height) * $new_width;
			$new_height = $max_height;
		}
		
		// Resample
		$image_p[$c] = imagecreatetruecolor($new_width, $new_height);
		$image_t[$c] = imagecreatetruecolor(100, 100);
		$image[$c] = imagecreatefromjpeg($imgpath.$imgname);
		imagecopyresampled($image_p[$c], $image[$c], 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagecopyresampled($image_t[$c], $image[$c], 0, 0, 0, 0, 100, 100, $width, $height);

		// Output
		@mkdir($imgpath."thumbnails");
		imagejpeg($image_p[$c], $imgpath.$imgname.".resized.jpg", 100);
		imagejpeg($image_t[$c], $imgpath."thumbnails/".$imgname, 100);
		// delete the original file and replace it with resized one
		rename($imgpath.$imgname.".resized.jpg", $imgpath.$imgname); 
	}
	return $doc->saveHTML();
}