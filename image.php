<?php
include("includes/graphics.class.php");

if(!$_GET["image"])
  echo "PLEASE PROVIDE A IMAGE IN URL";
else{

    $size = Graphics::getSize($_GET["image"]); // RETURNS ARRAY - 0 -> width 1 -> height
    $effects = $_GET["effects"];

    // IMPROVE PERFORMANCE NOT NEEDING TO RESIZE
    if(isset($_GET["width"])){
        $newSize = Graphics::getNewImageRatios($size, $_GET["width"]);
    }
    else
       $newSize = $size;

    $image = Graphics::createToObject($_GET["image"]); //CREATE GRAPHICS OBJECT USING A IMAGE URL
	$image->setHeader("png");
	$image->resize($newSize[0]);
    $image->setFont("arial.ttf");

    if(isset($_GET["text"])){
        $textOptions = explode("|",$_GET["text"]);
        $image->addText($textOptions[0],30,0,$textOptions[1],$textOptions[2]);
    }

	// PERFORM EFFECTS HERE
	// $image->resize($width);
	if(isset($effects)){
	   $effect = explode(",",$effects);
	   foreach($effect as $apply){
		  switch ($apply) {
			case "mono":
                $image->monoDither();
				break;
            case "wave":
                $image->wave(10);
                break;
			case "edge":
				$image->edgeDetect();
				break;
			case "pixel":
				$image->pixelize(5);
				break;
			case "gray":
				$image->toGrayScale();
				break;
		  } 
	   }
	}
	
    $image->show("png");
}
?>