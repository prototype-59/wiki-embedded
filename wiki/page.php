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
    a: action -> c(reate), l(ist), s(how), r(emove), u(pdate)
    p: page name
    content: new page content / optional for update and new page
*/

// security check is performed via session
if (session_status() == PHP_SESSION_NONE) { session_start(); }
$editable = $_SESSION['wiki-editable'];

include_once  "Parsedown.php";
$input = &$_GET;

switch ($input["a"])
{
    case "c":   // create page
        if ( !$editable ) { break; }
        $file = "pages/" . $input["p"];
        if ( !file_exists( $file ) ) {
            file_put_contents( $file, "* [Wiki home](main)\n\n---\n##" . pathinfo( $file, PATHINFO_FILENAME) );
        }
        break;

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
        if ( !$editable ) { break; }
        unlink( "pages/" . $input["p"] );
        break;

    case "s":   // show page
        $file = "pages/" . $input["p"];
        if ( !file_exists( $file ) ) {
            print "Page does not exists! Create one.";
            break;
        }
        $page = file_get_contents( $file );
        $Parsedown = new Parsedown();
        print $Parsedown->text( $page );
        break;

    case "u":   // update page
        if ( !$editable ) { break; }
        $page = "pages/" . $input["p"];
        $content = $input["content"];
        file_put_contents( $page, $content );
        break;

    default:
}

?>