<?php
/**
 * update/create a new wiki page
 * note: pages directory access: chmod  777 -R pages/
 

 * @author Aleksandar Radovanovic <aleksandar@radovanovic.com>
 * @version 2017-05-01
*/
$page = "pages/" . $_GET["name"];
$content = $_GET["content"];
file_put_contents( $page, $content );

?>