<?php
include("includes/file.class.php");
include("includes/graphics.class.php");
include("includes/documentation.class.php");
?>
<html>
<head>
<style>
body {
  font-family: arial;
  font-size: 16px;
}
</style>
</head>
<body>
<?php
$class = (isset($_GET["class"])) ? $_GET["class"] : "Graphics";
$documentation = new Documentation($class);
?>
</body>
</html>