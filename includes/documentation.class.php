<?php
class Documentation
{
   /**
     CONSTRUCTOR
   */
   public function __construct($class)
   {
      echo "<h1>Documentation for class ".$class."</h1>";
      $graphics = new ReflectionClass($class); 
	  $props = $graphics->getProperties();
	  echo "<table width=\"100%\">";
	  echo "<tr><td valign=\"top\" width=\"150px\">";
	  echo "<b>Classes</b>";
	  $this->listClasses();
	  echo "<b>Methods</b>";
	  $this->listMethods($graphics->getMethods());
	  echo "</td><td>";
	  $this->showProperties($props);
	  $this->showStaticProperties($graphics->getStaticProperties());
	  $this->showMethods($graphics->getMethods());
	  echo "</td></tr>";
	  echo "</table>";
   }
   
   /**
     LIST ALL CLASSES FOUND
   */
   public function listClasses()
   {
       $files = File::getFiles("includes");
	   echo "<ul>";
	   foreach($files as $file){
	      $break = explode(".",$file);
		  echo "<li><a href=\"?class=".$break[0]."\"> ".$break[0]." </a></li>";
	   }
	   echo "</ul>";
   }
   
   /**
     SHOW STATIC PROPERTIES
   */
   public function showStaticProperties($props)
   {
      echo "<h2> Static Field Information </h2>";
	  if(count($props) > 0){
		   echo "<ul>";
		   foreach($props as $value => $item1){
		     if(is_array($item1)){
			   echo "<ul>";
				foreach($item1 as $key => $item)
				  echo "<li><b>".$key."</b> = ".$item."</li>";
				echo "</ul>";
			 }
			 else
			   echo "<li><b>".$value."</b> = ".$item1."</li>";
			 
		   }
		   echo "</ul>";
	  }
	  else
	    echo "None";
   }
   
   /**
     SHOW PROPERTIES OF A CLASS
   */
   public function showProperties($props)
   {
      echo "<h2> Class Fields </h2>";
	  if(count($props) > 0){
		  echo "<ul>";
		  foreach($props as $prop){
			echo "<li>".$prop->name. " (".$prop->class.")</li>";
		  }
		  echo "</ul>";
	  }
	  else
	    echo "None";
	  
   }
   
   /**
     LIST ALL METHODS (JUST NAMES)
   */
   public function listMethods($methods)
   {
     sort($methods); //SORT METHOD NAMES
     echo "<ul>";
     foreach($methods as $method){
	   echo "<li><a href=\"#".$method->name."\"> ".$method->name."</a></li>";
	 }
	 echo "</ul>";
   }

   /**
     LIST ALL THE METHODS AND THEIR INFORMATION
   */
   public function showMethods($methods)
   {
     sort($methods);
     foreach($methods as $method){
	    $commentHeader = $method->getDocComment();
		$filter = array("/","*");
		$comment = str_replace($filter,"",$commentHeader);
		
		echo "<table border=\"1\" width=\"100%\" cellpadding=\"10\"><tr><td>";
        $params = $method->getParameters();
	    echo "<a name=\"".$method->name."\"><h1>".$method->name."</h1>";
	    echo "<pre>".$comment."</pre>";
		echo "<h3>Parameters</h3>";
		
		if(count($params) > 0){
		    $count = 1;
			foreach($params as $param){
			  if($count == count($params)){
			     echo "<i>".$param->name."</i> ";
			  }
			  else{
			    echo "<i>".$param->name.",</i> ";
				$count++;
			  }
			}
	    }
		
		else echo "<i>No Parameters</i>";
		echo "</td></tr></table></a> <br />";
	 }
   }
}
?>