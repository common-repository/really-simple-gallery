<?php  
    /* 
    Plugin Name: My Really Simple Gallery
    Plugin URI: http://updates.dalemooney.co.uk/simplegallery
    Description: Allows you to upload and view image files a gallery.
    Author: Dale Mooney
    Version: 1.4
    Author URI: http://www.dalemooney.co.uk 
    */  

require("includes/graphics.class.php");	
	
add_action('admin_menu', 'my_plugin_menu');
register_activation_hook(__FILE__,'hello_world_install'); 
register_deactivation_hook( __FILE__, 'hello_world_remove' );

function really_simple_gallery_widget($args) 
{
   echo $args['before_widget'];
   echo $args['before_title'] ." Random Image ". $args['after_title'];
   getRandomImage();
   echo $args['after_widget'];
}

register_sidebar_widget("My Really Simple Gallery", "really_simple_gallery_widget");

function hello_world_install() {
	/* Creates new database field */
	add_option("simple_gallery_uploads",'../wp-content/plugins/really-simple-gallery/uploads/', '', 'yes');
	add_option("simple_gallery_thumbnail",'150', '', 'yes');
}

function getRandomImage()
{
   $dir = explode("../",get_option("simple_gallery_uploads"));
   $images = getDirectoryList($dir[1]);
   $size = count($images);
   $image = $images[rand(0,$size-1)];
   
   echo "<center><img src=\"wp-content/plugins/really-simple-gallery/image.php?image=uploads/".$image."&width=".get_option("simple_gallery_thumbnail")."\" />";
   if(is_admin())
     echo "<a href=\"wp-admin/options-general.php?page=my-unique-identifier\"> Upload image </a>";
   echo "</center>";
}

function hello_world_remove() {
	/* Deletes the database field */
	delete_option('simple_gallery_uploads');
	delete_option("simple_gallery_thumbnail");
}

function my_plugin_menu() {
	add_options_page('My Really Simple Gallery Options', 'My Really Simple Gallery', 'manage_options', 'my-unique-identifier', 'my_plugin_options');
}

function getDirectoryList ($directory) 
{
    $results = array();
    $handler = opendir($directory);
	
    while ($file = readdir($handler)) {
      if ($file != "." && $file != "..") {
        $results[] = $file;
      }
    }
    closedir($handler);
    return $results;
}

function my_plugin_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}else{

		echo '<div class="postbox">
             <div class="inside" style="padding:10px">';
			 
		echo "<h1> Simple Gallery Options </h1>";
		echo '<p>You can upload new images to the gallery via the form below.</p>';
		echo '<form enctype="multipart/form-data" action="" method="POST">
			  <b>Select image:</b> <input name="uploadedfile" type="file" />
			  <input type="submit" value="Upload File" name="addImage" />
			  </form>';
		echo "<p> Or you can add via a URL (e.g facebook) </p>";		
	    echo '<form action="" method="POST">
			  <b>Image URL:</b> <input name="imageurl" type="text" size="70" value="http://" disabled=disabled />
			  <input type="submit" value="Add image" name="add" />
			  </form>';
			  
		if(isset($_POST["addImage"])){
			$target_path = get_option("simple_gallery_uploads");
			$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 
			
			echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>';

			if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
			   echo "Your image has been uploaded!";
			} 
			else
			  echo "There was an error uploading the file, please try again!";
			  
			echo "</strong></p></div>";

		}
        if(isset($_POST["add"])){
			
			$url = $_POST["imageurl"];
			$break = explode("/",$url);
			$image_name = $break[count($break)-1];

			echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>';
			echo "<i>Currently not working...</i>";
		    echo "</strong></p></div>";

		}			
		
		echo "<p>";
		echo "<h2> Settings </h2>";
		echo "<p>";
		echo "<p>Please make sure the details below are correct. You can give the location to where to upload images</p>";
		if(isset($_POST["updateSimple"])){
		  if(is_dir($_POST["upload_location"])){
		    update_option('simple_gallery_uploads', $_POST["upload_location"]);
			update_option('simple_gallery_thumbnail', $_POST["thumbnail"]);
		  }
		  else
		    echo "Directory not found!";
		}
		echo '<form method="post" action="">';
		echo "<b>Upload location:</b><br /> <input type=\"text\" name=\"upload_location\" value=\"".get_option("simple_gallery_uploads")."\" size=\"70\"/><br /><br />";
		echo "<b>Thumbnail Size (Ratio kept):<br /> </b> <input type=\"text\" name=\"thumbnail\" value=\"".get_option("simple_gallery_thumbnail")."\" size=\"3\"/><br />";
	    echo '<br /><input type="submit" value="Save Changes" name="updateSimple" />';
		echo "</form>";
		echo "</p>";
		echo "<p><i>Really simple gallery uses the <a href=\"../wp-content/plugins/really-simple-gallery/doc.php\" target=\"_blank\"> graphics library </a> developed by Dale Mooney </i></p>";

		echo "<h2> Current Images </h2>";
		
		echo "<p>";
		$files = getDirectoryList(get_option("simple_gallery_uploads"));
		
		if(isset($_POST["deleteIm"])){
		   $value = str_replace(" ","%",$_POST["delete"]);
		   unlink(get_option("simple_gallery_uploads").$value);
		   echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>';
		   echo "<i>$value has been deleted, refresh the page</i>";
		   echo "</strong></p></div>";
		}
		
		echo "<form name=\"deleteImage\" action=\"\" method=post>";
		echo "<select name=delete>";
		foreach($files as $file){
          $file = str_replace(" ","%",$file);
		  echo "<option value={$file}>{$file}</option>";
		}
		echo "</select>";
		echo "<input type=submit name=deleteIm value=\"Delete Image\" />";
		echo "</form>";
		
		echo "<p> Or apply an effect </p>";
		
		if(isset($_POST["applyEffect"])){
		   $value = $_POST["image"];
		   $effect = $_POST["effect"];
		   
		   echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>';
		   echo "Effect: $effect Value: $value";
		   echo "</strong></p></div>";
		}
		
		if(isset($_POST["preview"])){
		   $value = $_POST["image"];
		   $effect = $_POST["effect"];
		   echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>';
		   echo "Preview:</br />";
		   echo "<img src=\"../wp-content/plugins/reallysimplegallery/image.php?image=uploads/".$value."&effects={$effect}\" />";
		   echo "</strong></p></div>";
		}

		echo "<form name=\"deleteImage\" action=\"\" method=post>";
		echo "<select name=image>";
		foreach($files as $file){
           $file = str_replace(" ","%",$file);
		  echo "<option value={$file}>{$file}</option>";
		}
		echo "</select>";
		echo "<select name=effect>";
		echo "<option value=mono> Mono Dither </option>";
		echo "<option value=edge> Edge Detect </option>";
		echo "<option value=pixel> Pixelize</option>";
		echo "<option value=gray> Grayscale</option>";
		echo "</select>";
		echo "<input type=submit name=applyEffect value=\"Apply Effect\" />";
		echo "<input type=submit name=preview value=\"Preview\" />";
		echo "</form>";

		// ------------------
		echo '</div></div>';
	}
}

function really_simple_gallery_display()
{
   $dir = explode("../",get_option("simple_gallery_uploads"));
   $files = getDirectoryList($dir[1]);

   echo "<h1> Gallery </h1>";
   echo "<p> There are ".count($files)." images in the gallery </p>";
   
   
  // DISPLAY CURRENT IMAGES
	$perRow = 3; // IMAGES PER ROW
	$count = 1;

	echo "<table border=1 padding=5 width=100%>";
		foreach($files as $file){
		  
		  $file = urlencode($file);
		   
		  if($count == 1){
			echo "<tr><td><center><img src=\"../wp-content/plugins/really-simple-gallery/image.php?image=uploads/".$file."&width=".(get_option("simple_gallery_thumbnail")) ."\" />
			      <a href=".get_option("simple_gallery_uploads").$file." target=_blank> Full Version </a></center></td>";
			$count++;
		  }
		  else if($count > 1 && $count < $perRow){
			echo "<td><center><img src=\"../wp-content/plugins/really-simple-gallery/image.php?image=uploads/".$file."&width=".get_option("simple_gallery_thumbnail")."\" />
			<a href=".get_option("simple_gallery_uploads").$file." target=_blank> Full Version </a></center></td>";
			$count++;
		  }
		  else{
			echo "<td><center><img src=\"../wp-content/plugins/really-simple-gallery/image.php?image=uploads/".$file."&width=".get_option("simple_gallery_thumbnail")."\" />
			<a href=".get_option("simple_gallery_uploads").$file." target=_blank> Full Version </a></center></td></tr>";
			$count=1;
		  }
		}
	echo "</table>";

}


?>
