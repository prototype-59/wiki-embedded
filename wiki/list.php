<?php
/**
 * list wiki pages
 * note: pages directory access: chmod  777 -R pages/
 

 * @author Aleksandar Radovanovic <aleksandar@radovanovic.com>
 * @version 2017-05-01
*/

$files = scandir( "pages/" );
$content = "##Wiki pages:\n\n";

foreach ($files as $idx => $file)
{
	$filename = pathinfo($file, PATHINFO_FILENAME);
	if ($filename != "" && $filename != "." && $filename != "list") 
	{
		$content .= "* [$filename]($filename)\n";
	}
}

file_put_contents( "pages/list.md", $content );

?>