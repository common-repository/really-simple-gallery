<?php
/**
* GRAPHICS
*/
class Graphics
{
   private $image, $width,$height;
   private $font = "fonts/arial.ttf";
   private $threshold = 10;
   private $header = "png";
   /**
     CONSTRUCTOR
   */
   public function __construct($width,$height,$imageURL = "")
   {
      $this->width =  $width;
	  $this->height = $height;
      if($imageURL != ""){
          $this->image = Graphics::createFrom($imageURL);
      }
      else
        $this->image = imagecreatetruecolor($width,$height);
	  $this->setImageColour(255,255,255);
   }
 
   /**
     CREATE A SIMPLE CIRCLE
   */
   public function createCircle($x,$y,$width,$height,$colour)
   {
       $start = 0;
	   $end = 360;	   
       imagearc ($this->image,$x,$y,$width,$height,$start,$end,$colour);
   }

   /**
    * Creates a Graphics object from a given URL
    * @param <String> $imageURL
    * @return <Graphics>
    */
   public static function createToObject($imageURL)
   {
      $size = Graphics::getSize($imageURL);
      $graphic = new Graphics($size[0],$size[1],$imageURL);
      return $graphic;
   }
   
   /**
    CREATE A RECTANGLE
   */
   public function createRectangle($x1,$y1,$x2,$y2,$colour)
   {
      imagefilledrectangle($this->image,$x1,$y1,$x2,$y2,$colour);
   }
   
   /**
     PLOT A LINE (SIMPLE LINE ALGORITHM)
   */
   public function plotLine($start_x,$start_y,$end_x,$end_y)
   {
      $dx = $start_x - $end_x;
	  $dy = $start_y - $end_y;
	  
	  for($x = $start_x; $x < $end_x; $x++){
	    $y = $start_y + ($dy) * ($x - $start_x)/($dx);
		$this->addPixel($x,$y,$this->setColour(255,255,255));
	  }
   }

    /**
    *
    * @param <array> $size
    * @param <int> $newWidth
    * @return <array>
    */
   public static function getNewImageRatios($size,$newWidth)
   {
      $ratios = array();
      $ratio = $newWidth / $size[0];
      $height = $size[1] * $ratio;
      $ratios[0] = $newWidth;
      $ratios[1] = $height;
      return $ratios;
   }
   
   /**
    SET THE CURRENT FONT
   */
   public function setFont($font)
   {
     $this->font = $font;
   }
   
   /**
    CREATE A FILLED ARCH
   */
   public function createFilledArc($x,$y,$width,$height,$colour,$start,$end)
   {
      imagefilledarc($this->image,$x,$y,$width,$height,$start,$end,$colour,IMG_ARC_PIE);
   }
   
   /**
     CREATE A FILLED ELIPSE
   */
   public function createfilledElipse($x,$y,$height,$width,$colour)
   {
     imagefilledellipse($this->image,$x,$y,$width,$height,$colour);
   }
   
   /**
    SET THE ALPHA COLOUR
   */
   public function setAlphaColour($red,$green,$blue,$trans)
   {
     return imagecolorallocatealpha($this->image,$red,$green,$blue,$trans);
   }
   
   /**
     CREATE A FILLED CIRCLE
   */
   public function createFilledCircle($x,$y,$width,$height,$colour)
   {
      $start = 0;
	  $end = 360;
      imagefilledarc($this->image,$x,$y,$width,$height,$start,$end,$colour,IMG_ARC_PIE);
   }
   
   /**
     ADD A IMAGE RESOURCE TO CURRENT IMAGE
   */
   public function addImage($image,$sx,$sy,$width,$height,$trans)
   {
       imagecopymerge($this->image,$image,0,0,$sx,$sy,$width,$height,$trans);
   }
   
   /**
     CREATE A SIMPLE PIE CHART
   */
   public function drawPieChart($array)
   {
      $start = 0;
	  $total = array_sum($array);
	  
      for($i = 0; $i < count($array); $i++){
	    $currentPer = round($array[$i] / $total * 360,0);
		$end = $start + $currentPer;
		$r = rand(0,255);
		$g = rand(0,255);
		$b= rand(0,255);
		
		$this->createFilledArc($this->width/2,$this->height/2,$this->width,$this->height,$this->setColour($r,$g,$b),$start,$end);
		$start = $end;
		//$this->addString(10,10,$currentPer,5,$this->setColour(0,0,0));
	  }
   }
   
   /**
      GET RANDOM FONT
	  RETURN FONT
   */
   public function randomFont()
   {
      $fonts = File::getFiles("fonts");
	  $rand = rand(0,count($fonts)-1);
	  return $fonts[$rand];
   }
   
   /**
    CROP A GIVEN IMAGE
	RETURN NEW CROPPED IMAGE
   */
   public function cropImage($image)
   {
   
   }
   
   /**
    RESIZE IMAGE WIDTH
	RETURN NEW RESIZED IMAGE
   */
   public function resizeImageWidth($image, $newWidth)
   {
      // $ratio = $newWidth / $this->getSize($image)[0];
      // $height = $this->getSize($image)[1]; * $ratio;
   }
   
   /**
    SAVE CURRENT IMAGE TO A GIVEN LOCATION
   */
   public function save($type,$location)
   {
     if($type == "png")
       imagepng($this->image,$location . ".{$type}",100);
	 if($type == "jpg");
	   imagejpeg($this->image,$location . ".{$type}",100);
	 if($type == "gif")
	   imagegif($this->image,$location . ".{$type}",100);
   }
   
   /**
    SAVE IMAGE THAT IS PASSED IN TO A GIVEN LOCATION
   */
   public static function saveImage($type,$location,$image)
   {
     if($type == "png")
       imagepng($image,$location . ".{$type}",100);
	 if($type == "jpg");
	   imagejpeg($image,$location . ".{$type}",100);
	 if($type == "gif")
	   imagegif($image,$location . ".{$type}",100);
   }
   
   /**
     GET SIZE OF AN IMAGE
	 RETURN SIZE
   */ 
   public static function getSize($image)
   {
     return getimagesize($image);
   }
   
   /**
     INVERT COLOURS OF IMAGE
   */
   public function invertColours()
   {
      for($x = 0; $x < $this->width; $x++){
	    for($y = 0; $y < $this->height; $y++){
		   $this->addPixel($x,$y,$this->getInvertColour($x,$y));
		}
	  }
   }
   
   /**
    GET THE INVERTED COLOUR VALUE OF A GIVEN PIXEL LOCATION
	RETURN COLOUR VALUE
   */
   public function getInvertColour($x,$y,$colour = 0)
   {
     $pos = imagecolorat($this->image, $x, $y);
	 $f = imagecolorsforindex($this->image, $pos);
	 if($colour == true)
		$col = imagecolorresolve($this->image, 255-$f['red'], 255-$f['green'], 255-$f['blue']);
	 else{
		 $gst = $f['red']*0.15 + $f['green']*0.5 + $f['blue']*0.35;
		 $col = imagecolorclosesthwb($this->image, 255-$gst, 255-$gst, 255-$gst);
	 }
	 return $col;
   }
   
   /**
     DRAW A INVERTED RECTANGLE
   */
   public function drawRectangleInvert($start_x,$start_y,$width,$height,$colour)
   {
        $x = $width + $start_x;
		$y =  $height + $start_y;
		
        for($i=$start_y; $i<$y; $i++)
		{
			 for($j=$start_x; $j<$x; $j++)
			{
				 $col = $this->getInvertColour($j,$i,$colour);
				 imagesetpixel($this->image, $j, $i, $col);
			 }
		 }
   }
   
   /**
     TURN IMAGE TO BLACK AND WHITE (ONLY)
   */
   public function toBlackWhite()
   {
      imagefilter($this->image, IMG_FILTER_CONTRAST, -1000);
   }
   
   /**
     GET THE COLOUR OF A GIVEN PIXEL LOCATION
	 RETURN COLOUR VALUE
   */
   public function getColour($x,$y)
   {
      $rgb = imagecolorat($this->image,$x,$y);
      $r = ($rgb >> 16) & 0xFF;
      $g = ($rgb >> 8) & 0xFF;
	  $b = $rgb & 0xFF;
	  return ($r + $g + $b) / 3;
   }
   
   /**
     RETURN THE FULL COLOUR VALUE AT GIVEN LOCATION
   */
   public function colour($x,$y)
   {
     return imagecolorat($this->image,$x,$y);
   } 
   
   /**
     GET THE COLOUR VALUE OF A GIVEN LOCATION OF A GIVEN IMAGE
	 RETURN COLOUR VALUE
   */
   public static function getImageColourAt($image,$x,$y)
   {
      $rgb = imagecolorat($image,$x,$y);
      $r = ($rgb >> 16) & 0xFF;
      $g = ($rgb >> 8) & 0xFF;
	  $b = $rgb & 0xFF;
	  return ($r + $g + $b) / 3;
   }
   
   
   /**
    DISPLAY THE CURRENT GRAPHIC OBJECT IMAGE
   */
   public function show($type = "png")
   {
     if($type == "png")
       imagepng($this->image);
	 if($type == "jpg");
	   imagejpeg($this->image);
	 if($type == "gif")
	   imagegif($this->image);
   }
   
   /**
      SHOW A GIVEN IMAGE
   */
   public function showImage($type,$image)
   {
     if($type == "png")
       imagepng($image);
	 if($type == "jpg");
	   imagejpeg($image);
	 if($type == "gif")
	   imagegif($image);
   }
   
   /**
    CREATE AN IMAGE FROM A FILE
	RETURN NEW IMAGE OBJECT
	POSSIBLE TYPES: PNG,JPEG,GIF
   */
   public static function createFrom($location)
   {
     $type = explode(".",$location);
	 
     if($type[1] == "png")
       return imagecreatefrompng($location);
	 else if($type[1] == "jpg")
	   return imagecreatefromjpeg($location);
	 else if($type[1] == "gif")
	   return imagecreatefromgif($location);
	 else
	   return imagecreatefromjpeg($location);
   }
   
   /**
     DRAW LINES ON THE IMAGE AROUND POSSIBLE TEXT CHARACTERS
   */
   public function segmentation($colour)
   {
     $columns = $this->vertSeg($colour);
	 $rows = $this->horzSeg($colour);
	 
	 $rowsMarked = array();
	 $colsMarked = array();
	 
	 for($i = 0; $i < $this->width; $i++){
	   if($columns[$i] > 0){
	      if($columns[$i+1] < 1 || $columns[$i-1] < 1){
		     $this->drawLine($i,0,$i,$this->height,$this->setColour(255,0,0));
			 $rowsMarked[$i] = $i;
		  }
	   }
	 }
	 
	 for($j = 0; $j < $this->height; $j++){
	   if($rows[$j] > 0){
	      if($rows[$j+1] < 1 && $rows[$j+1] < $this->threshold || $rows[$j-1] < 1 && $rows[$j-1] < $this->threshold){
		     $this->drawLine(0,$j,$this->width,$j,$this->setColour(255,0,0));
			 $colsMarked[$i] = $i;
		  }
	   }
	 }
   }
    
   /**
     SEGMENT THE IMAGE VERTICIALLY
	 RETURN NUMBER OF GIVEN COLOUR FOUND
   */
   public function vertSeg($colours)
   {
      //Find the characters
      $whiteCount = array();
	  
      for($i = 0; $i < $this->width; $i++){
	    for($j = 0; $j < $this->height; $j++){
		   $colour = $this->getColour($i,$j);
		   if($colour == $colours){
		     $whiteCount[$i]++;
		   }
		}
	  }
	  return $whiteCount;
   }
   
   /**
    SEGMENT THE IMAGE HORZITANTLY
	RETURN NUMBER OF GIVEN COLOUR FOUND
   */
   public function horzSeg($colours)
   {
      $whiteCount = array();
	  
      for($i = 0; $i < $this->height; $i++){
	    for($j = 0; $j < $this->width; $j++){
		   $colour = $this->getColour($j,$i);
		   if($colour == $colours){
		     $whiteCount[$i]++;
		   }
		}
	  }
	  return $whiteCount;
   }
   
   /**
    ROTATE CURRENT IMAGE
   */
   public function rotate($angle)
   {
      $this->image = imagerotate($this->image,$angle,0);
   }
   
   /**
     ROTATE A IMAGE RESOURCE
   */
   public static function rotateImage($image,$angle)
   {
      return imagerotate($image,$angle,0);
   }
   
   /**
    PLOT A HISTOGRAM ON CURRENT IMAGE
   */
   public function plotHistogram()
   {
      $hist = array();
	  for($i = 0; $i < $this->width; $i++){
	    for($j = 0; $j < $this->height; $j++){
		   $colour = $this->getColour($i,$j);
		   $hist[$colour]++;
		}
	  }
	  $this->drawBarGraph($hist);
   }
   
   /**
     PLOT A BAR GRAPH
   */
   public function drawBarGraph($array,$barWidth)
   {
      sort($array);
      $maxValue = max($array);
	  $maxHeight = $this->height - 10;
	  $maxWidth = $this->width - 10;
	  
	  $x = 0;
	  $y = $this->height;
	  $width = $x + $barWidth;
	  
	  foreach($array as $value){
	  
	     $colour = $this->setColour(rand(0,255),rand(0,255),rand(0,255));
	     $h = $this->height - ($value / $maxValue) * $maxHeight;
		 $this->createRectangle($x,$h,$width,$y,$colour);
		 $x+=$barWidth+5;
		 $width = $x + $barWidth;
	  }
   }
   
   /**
      RESIZE CURRENT IMAGE - KEEPING RATIO
   */
   public function resize($width)
   {
     $ratio = $width / $this->width;
     $height = $this->height * $ratio;
	 
     $new_image = imagecreatetruecolor($width, $height);
     imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
     $this->image = $new_image;
   }
   
   /**
      RESIZE A GIVEN IMAGE RESOURCE
   */
   public function resizeImage($image,$width)
   {
     $ratio = $width / imagesx($image);
     $height = imagesy($image) * $ratio;
	 
     $new_image = imagecreatetruecolor($width, $height);
     imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
     return $new_image;
   }
   
   /**
      CONVERT CURRENT IMAGE TO GRAYSCALE
   */
   public function toGrayScale()
   { 
        for ($i=0; $i<$this->width; $i++)
		{
			for ($j=0; $j<$this->height; $j++)
			{
				$g = $this->getColour($i,$j);
				$val = imagecolorallocate($this->image, $g, $g, $g);
				imagesetpixel ($this->image, $i, $j, $val);
			}
		}
   }
   
   /**
    CONVERT A GIVEN IMAGE TO GRAY SCALE
   */
   public static function imageToGrayScale($image)
   { 
        for ($i=0; $i<(imagesx($image)); $i++)
		{
				for ($j=0; $j<(imagesy($image)); $j++)
				{
				
						// get the rgb value for current pixel
						
						$rgb = ImageColorAt($image, $i, $j); 
						
						// extract each value for r, g, b
						
						$rr = ($rgb >> 16) & 0xFF;
						$gg = ($rgb >> 8) & 0xFF;
						$bb = $rgb & 0xFF;
						
						// get the Value from the RGB value
						
						$g = round(($rr + $gg + $bb) / 3);
						
						// grayscale values have r=g=b=g
						
						$val = imagecolorallocate($image, $g, $g, $g);
						
						// set the gray value
						
						imagesetpixel ($image, $i, $j, $val);
				}
		}
   }
   
   /**
      GET PIXEL VALUEM (0-255)
   */
   public function getPixelValue($x,$y)
   {
	  $rgb = ImageColorAt($this->image, $x, $y); 
	  $rr = ($rgb >> 16) & 0xFF;
	  $gg = ($rgb >> 8) & 0xFF;
	  $bb = $rgb & 0xFF;  
	  $g = round(($rr + $gg + $bb) / 3);
	  return $g;
   }
   
   /**
     GET RED VALUE
   */
   public function getRed($rgb)
   {
      return ($rgb >> 16) & 0xFF;
   }
   
   /**
     GET GREEN VALUE
   */
   public function getGreen($rgb)
   {
      return ($rgb >> 8) & 0xFF;
   }
   
   /**
     GET BLUE VALUE
   */
   public function getBlue($rgb)
   {
      return $rgb & 0xFF;
   }
   
   /**
    SET HEADER
   */   
   public static function setHeader($header)
   {
     header("Content-type: ".$header."" );
   } 
   
   /**
    DRAW A LINE ON CURRENT IMAGE
   */
   public function drawLine($start_x,$start_y,$end_x,$end_y,$colour){imageline($this->image,$start_x,$start_y,$end_x, $end_y, $colour);}
   
   /**
     RETURN PIXEL COLOUR
   */
   public function getPixelColour($x,$y){$rgb = imagecolorat($this->image, $x, $y);return imagecolorsforindex($this->image, $rgb);}
   
   /**
      SET BRUSH THICKNESS
   */
   public function setThickness($thickness){imagesetthickness($this->image,$thickness);}
   
   /**
    * Add text to a image
    * @param <string> $text
    * @param <int> $fontsize
    * @param <int> $angle
    * @param <int> $x
    * @param <int> $y
    * @param <colour> $colour
    */
   public function addText($text = "Default",$fontsize = 10,$angle = 0,$x = 0,$y = 20,$colour = null)
   {
     $colour = ($colour == null) ? $this->setColour(255,255,255) : $colour;
     imagefttext ($this->image,$fontsize,$angle,$x,$y,$colour,$this->font,$text);
   }
   
   /**
     SET BRUSH
   */
   public function setBrush($image){imagesetbrush($this->image,$image);}
   
   /**
     GET HEIGHT OF CURRENT IMAGE
   */
   public function getHeight(){return $this->height;}
   
   /**
     GET WIDTH OF CURRENT IMAGE
   */
   public function getWidth(){return $this->width;}
   
   /**
      ADD DASHED LINE TO IMAGE
   */
   public function addDashedLine($start_x,$start_y,$end_x,$end_y,$colour){imagedashedline ($this->image ,$start_x,$start_y,$end_x,$end_y,$colour);}
   
   /*
     DRAW PIXEL ON CURRENT IMAGE
   */
   public function addPixel($x,$y,$colour){imagesetpixel ($this->image,$x,$y,$colour);}
   
   /**
      ADD STRING TO CURRENT IMAGE
   */
   public function addString($x = 0 ,$y = 0,$string = "Default",$fontsize = 5,$colour = null)
   {
       $colour = ($colour == null) ? $this->setColour(255,255,255) : $colour;
       imagestring($this->image, $fontsize, $x, $y,$string,$colour );
   }
   
   /**
     SET CURRENT IMAGE BACKGROUND COLOUR
   */
   public function setImageColour($red,$green,$blue){imagecolorallocate($this->image, $red, $green, $blue);}
   
   /**
     RETURN A COLOUR RESOURCE
   */
   public function setColour($red,$green,$blue){return imagecolorallocate($this->image, $red, $green, $blue);}
   
   /**
     RETURN CURRENT IMAGE
   */
   public function getImage(){return $this->image;}
   
   /**
     DESTROY CURRENT IMAGE
   */
   public function destroy($image){imagedestroy($image);}
   
   /**
     BLUR CURRENT IMAGE
   */
   public function blur($strength = 0){imagefilter($this->image, IMG_FILTER_GAUSSIAN_BLUR, $strength);}
   
   /**
     PIXELATE AND IMAGE
	 CODE FROM http://www.talkphp.com/script-giveaway/3620-pixelate-algorithm-using-gd.html
   */
   public function pixelize($blocksize = 1, $advance = true)
   {  
        $im = $this->image;
        $blocksize = (integer) $blocksize;

        $sx = imagesx($im);
        $sy = imagesy($im);

        if($advanced)
        {
            for($x = 0; $x < $sx; $x += $blocksize)
            {
                for($y = 0; $y < $sy; $y += $blocksize)
                {
                    $colors = Array('alpha' => 0, 'red' => 0, 'green' => 0,'blue' => 0,'total' => 0);

                    for($cx = 0; $cx < $blocksize; ++$cx)
                    {
                        for($cy = 0; $cy < $blocksize; ++$cy)
                        {
                            if($x + $cx >= $sx || $y + $cy >= $sy)
                            {
                                continue;
                            }

                            $color = imagecolorat($im, $x + $cx, $y + $cy);
                            imagecolordeallocate($im, $color);

                            $colors['alpha']     += ($color >> 24) & 0xFF;
                            $colors['red']        += ($color >> 16) & 0xFF;
                            $colors['green']    += ($color >> 8) & 0xFF;
                            $colors['blue']        += $color & 0xFF;

                            ++$colors['total'];
                        }
                    }

                    $color = imagecolorallocatealpha($im, 
                                        $colors['red'] / $colors['total'], 
                                        $colors['green'] / $colors['total'], 
                                        $colors['blue'] / $colors['total'], 
                                        $colors['alpha'] / $colors['total']
                                        );

                    if(!@imagefilledrectangle($im, $x, $y, ($x + $blocksize - 1), ($y + $blocksize - 1), $color))
                    {
                        return(false);
                    }
                }
            }
        }
        else
        {
            for($x = 0; $x < $sx; $x += $blocksize)
            {
                for($y = 0; $y < $sy; $y += $blocksize)
                {
                    if(!@imagefilledrectangle($im, $x, $y, ($x + $blocksize - 1), ($y + $blocksize - 1), imagecolorat($im, $x, $y)))
                    {
                        return(false);
                    }
                }
            }
        }
        return(true);
   }
   
   /**
     PIXELIZE A GIVEN IMAGE
   */
   public static function imagePixelize($strength = 10,$image){imagefilter($image, IMG_FIetReR_PIXELATE, $strength, true);}
   
   /**
      EMBOSS CURRENT IMAGE
   */
   public function emboss(){imagefilter($this->image, IMG_FILTER_EMBOSS);}
   
   /**
      HIGHLIGHT EDGES ON CURRENT IMAGE
   */
   public function edgeDetect(){imagefilter($this->image, IMG_FILTER_EDGEDETECT);}
   
   /**
      CREATE RANDOM CIRCLES ON IMAGE
   */
   public function randomCircles()
   {
	   $rand = rand(1,3);
	   for($i=0; $i<$rand;$i++){
		  $x = rand(0,$this->getWidth());
		  $y = rand(0,$this->getHeight());
		  $width = rand(30,$this->getWidth());
		  $height = rand(30,$this->getHeight());
		  $colour = $this->setColour(rand(0,255),rand(0,255),rand(0,255));
		  
		  $this->createCircle($x,$y,$width,$height,$colour);   
	   }
   }
   
    /**
      CREATE RANDOM FILLED CIRCLES ON CURRENT IMAGE
   */
   public function randomFilledCircles()
   {
	   $rand = rand(1,5);
	   for($i=0; $i<$rand;$i++){
		  $x = rand(0,$this->getWidth());
		  $y = rand(0,$this->getHeight());
		  $width = rand(30,$this->getWidth());
		  $height = rand(30,$this->getHeight());
		  $colour = $this->setAlphaColour(rand(0,255),rand(0,255),rand(0,255),75);
		  
		  $this->createFilledElipse($x,$y,$width,$height,$colour);
	   }
   }
   
   /**
      DISTORT CURRENT IMAGE
   */
   public function wave($amplitude = 3,$period = 5)
   { 
   		$x = 0;
		$y = 0;
	    $width = $this->getWidth();
		$height = $this->getHeight();
		
		// Make a copy of the image twice the size 
		$height2 = $height * 2; 
		$width2 = $width * 2; 
		
		$img2 = imagecreatetruecolor($width2, $height2); 
		
		imagecopyresampled($img2, $this->image, 0, 0, $x, $y, $width2, $height2, $width, $height); 
		if($period == 0) $period = 1; 
		// Wave it 
		for($i = 0; $i < ($width2); $i += 2) 
			imagecopy($img2, $img2, $x + $i - 2, $y + sin($i / $period) * $amplitude, $x + $i, $y, 2, $height2); 
		// Resample it down again 
		imagecopyresampled($this->image, $img2, $x, $y, 0, 0, $width, $height, $width2, $height2); 
		imagedestroy($img2); 
    } 
	
	/**
	   PERFORM MEDIAN FILTER (REMOVE NOISE)
	*/
    public function medianFilter($max = 1)
	{
	   for($times = 0; $times < $max; $times++){
		for($i = 0; $i < $this->getWidth(); $i++){
		   for($j = 0; $j < $this->getHeight(); $j++){
			   
			   $values = array();
			   for($x = $i-1; $x < $i+2; $x++){
				 for($y = $j-1; $y < $j+2; $y++){
				    $values[] = $this->colour($x,$y); 
			     }    
			   }
			   $median = $this->median($values);
			   $this->addPixel($i,$j,$this->setColour($this->getRed($median),$this->getGreen($median),$this->getBlue($median)));
		   }
		}
	  }
	}
	
	/**
	  PERFORM MONODITHER ON CURRENT IMAGE
	*/
	public function monoDither()
	{
	    $errors = array();
        $threshold = 8;

        for($y = 0; $y < $this->getHeight()-1; $y++){
            for($x = 0; $x < $this->getWidth()-1; $x++){
               
                $oldPixel =  $this->getPixelValue($x,$y) + $errors[$x+1];
                $newPixel = $this->findBlackOrWhite($oldPixel);
				
				//echo "<script> alert('{$oldPixel}'); </script>";
               
                $this->addPixel($x,$y,$this->setColour($newPixel,$newPixel,$newPixel));
                $error = $oldPixel - $newPixel;
                
                if($x > 0){
                    $errors[$x-1] = $errors[$x-1] + $error/$threshold;
                }
                $errors[$x] = $errors[$x] + 3 * $error/$threshold;
                $errors[$x+1] = $error/$threshold;
                $errors[$x+2] = $errors[$x+2] + 3*$error/$threshold;
            }
        }
	}
	
	/**
	  CHANGE IMAGE COLOUR TO RED,GREEN OR BLUE
	*/
	public function changeColourTo($colour)
	{
	    for($y = 0; $y < $this->getHeight(); $y++){
            for($x = 0; $x < $this->getWidth(); $x++){
			
			   if($colour == "red"){
			     $value = $this->getRed($this->getPixelValue($x,$y));
				 $this->addPixel($x,$y,$this->setColour($value,0,0));
				 echo "<script> alert('{$value}') </script>";
			   }
			   else if($colour == "green"){
			     $value = $this->getGreen($this->getPixelValue($x,$y));
				 $this->addPixel($x,$y,$this->setColour(0,$value,0));
			   }
			   else if($colour == "blue"){
			     $value = $this->getBlue($this->getPixelValue($x,$y));
				 $this->addPixel($x,$y,$this->setColour(0,0,$value));
			   }
			   else return;
			}
		}
	}
	
	/**
     * FIND CLOSEST COLOUR - BLACK OR WHITE
     */
    private function findBlackOrWhite($pixel)
    {
       if($pixel > 127) return 255;
       return 0;
    }
	
	/**
	  RETURN ARRAY OF IMAGE WIDTH AND HEIGHT
	*/
	public static function getImageSize($image)
	{
	   return getimagesize($image);
	}
	
	/**
	  GET MEDIAN VALUE OF A GIVEN ARRAY
	*/
	public function median($array)
	{
	   sort($array);
	   return $array[count($array)/2];	
	}
   
   /**
     CREATE RANDOM LINES ON IMAGE
   */
   public function randomLines()
   {
	   $rand = rand(2,10);
	   for($i=0; $i<$rand;$i++){
		   
		  $x1 = rand(0,$this->getWidth());
		  $y1 = rand(0,$this->getHeight());
		  $x2 = rand(0,$this->getWidth());
		  $y2 = rand(0,$this->getHeight());
		  $colour = $this->setColour(rand(0,255),rand(0,255),rand(0,255));
		  
		  $this->drawLine($x1,$y1,$x2,$y2,$colour); 
	   }
   }
   
   /**
    ADD RANDOM NOISE ON IMAGE
   */
   public function randomNoise($strength){
      for($i = 0; $i < $strength*20; $i++){
	    $x = rand(0,$this->getWidth());
		$y = rand(0,$this->getHeight());
		$colour = $this->getInvertColour($x,$y,false);
		$this->addPixel($x,$y,$colour);
	  }
   }
}
?>