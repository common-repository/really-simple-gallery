<?php
class File
{
  /**
    Save a file with a given text as contentn
  */
  public static function saveFile($text,$location)
  {
	 $fh = fopen("{$location}/{$text}.genetics", 'w') or die("can't open file");
	 fwrite($fh, $text);
	 fclose($fh);
  }
  
  /**
    Save a file with content provided
  */
  public static function save($name,$text)
  {
     $fh = fopen("{$name}", 'w') or die("can't open file");
	 fwrite($fh, $text);
	 fclose($fh);
  }
  
  /**
    Append to a file
  */
  public static function append($name,$text)
  {
     $fh = fopen("{$name}", 'a') or die("can't open file");
	 fwrite($fh, $text);
	 fclose($fh);
  }
  
  /**
    Remove words that dont have the given word length from the file.
  */
  public static function onlyWordsOfLength($wordLength)
  {
     $newList = "";
     $words = explode("\n",File::getContent("words.txt"));
	 foreach($words as $word){
	    if(strlen($word) -1  == $wordLength){
		  $newList .= "{$word}\n";
		}
	 }
	
	 File::save("words.txt",$newList);
  }
  
  /**
   DELETE A FILE
  */
  public static function delete($file)
  {
     unlink($file);
  }
  
  /**
   Return all files round in location as an array
    @param location
	@return array of file names
  */
  public static function getFiles($location)
  {
		 $files = array();
		 if ($handle = opendir($location)) {
			while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						$files[] = $file;
					}
				}
				closedir($handle);
		 }
		 return $files;
  }
 
  /**
   Get the contents in the file
  */
  public static function getContent($file)
  {
     if(is_file($file))
       return file_get_contents($file);
  }
}
?>