<?php
/**
 * manage wiki pages

 * @author Aleksandar Radovanovic <aleksandar@radovanovic.com>
 * @version 2017-05-03

 * markdown: https://en.wikipedia.org/wiki/Markdown
 * parser: https://github.com/erusev/parsedown
*/

/*
input:
    a: action -> s(how), u(pdate), l(ist), r(emove)
    p: page name
    content: new page content / optional for update and new page
*/

include_once  "Parsedown.php";
$input = &$_GET;

switch ($input["a"])
{
    case "l":   // create pages list
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
        break;

    case "r":   // remove page 
        unlink( "pages/" . $input["p"] );
        break;

    case "s":   // show page 
        $page = file_get_contents("pages/" . $input["p"]);
        $Parsedown = new Parsedown();
        print $Parsedown->text( $page );
        break;

    case "u":   // update/create page 
        $page = "pages/" . $input["p"];
        $content = $input["content"];
        file_put_contents( $page, $content );
        break;

    default:
}

?>