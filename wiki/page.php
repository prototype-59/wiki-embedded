<?php
/**
 * display a wiki page
 * markdown: https://en.wikipedia.org/wiki/Markdown
 * parser: https://github.com/erusev/parsedown

 * @author Aleksandar Radovanovic <aleksandar@radovanovic.com>
 * @version 2017-05-01
*/

$pg = empty($_GET["p"]) ? "main.md" : $_GET["p"];
include_once  "Parsedown.php";
$page = file_get_contents("pages/" . $pg);
$Parsedown = new Parsedown();
print $Parsedown->text( $page ); 
?>