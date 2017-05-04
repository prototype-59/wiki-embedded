<!DOCTYPE html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link href='wikistyles.css' rel='stylesheet' type='text/css'>
</head>

<body>
<!-- some page content .. -->
<div>
<?php 
$wikisettings = array("path"=>"wiki/", "wdb"=>"pages", "editable"=>"true");
include_once $wikisettings["path"] . "wiki.php" 
?>
</div>
<!-- more page content ... -->

</body>
</html>