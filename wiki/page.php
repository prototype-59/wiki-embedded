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
    db: database folder
    a: action ->  c(reate), f(ind), l(ist), o(get original) s(how), r(emove), u(pdate)
    p: page name
    content: new page content / optional for update and new page
*/

// security check is performed via session
if (session_status() == PHP_SESSION_NONE) { session_start(); }
$editable = isset($_SESSION["wiki-editable"]) ? $_SESSION["wiki-editable"] : false;

include_once  "Parsedown.php";
$input = &$_POST;

switch ($input["a"])
{
    case "c":   // create page
        if ( !$editable ) { break; }
        $file = $input["db"] . $input["p"];
        if ( !file_exists( $file ) ) {
            file_put_contents( $file, "* [Wiki home](main)\n\n---\n##" . pathinfo( $file, PATHINFO_FILENAME) );
        }
        break;

    case "f":   // find
        $search = $input["search"];
        if ( strlen( $search ) <= 2 ) { break; }
        $result = "##Search result:\n\n";
        $Parsedown = new Parsedown();
        
        foreach (glob($input["db"] ."*.md") as $filename) 
        {
            $lines = file($filename);
            $found = "";
            foreach($lines as $line)
            {
                if(strpos($line, $search) !== false)
                {
                    $found .= "  * " . $line . "\n";
                }
            }
            if ( strlen( $found ) > 0 ) {
                $result .= "* [" . pathinfo($filename, PATHINFO_FILENAME) . "](" .pathinfo($filename, PATHINFO_FILENAME) . ") page:\n\n" . $found ."\n";
            }
        }
        $result .= "\n* [wiki home](main)";
        $Parsedown = new Parsedown();
        print $Parsedown->text( $result );
        break;

    case "l":   // create pages list
        $files = scandir( $input["db"] );
        $list = " ##Wiki pages:\n\n";
        foreach ($files as $idx => $file)
        {
	        $filename = pathinfo($file, PATHINFO_FILENAME);
	        if ($filename != "" && $filename != ".") 
	        {
		        $list .= "* [$filename]($filename)\n";
	        }
        }
        $Parsedown = new Parsedown();
        print $Parsedown->text( $list );
        break;
    
    case "o":   // get original - no parsing
        $file = $input["db"] . $input["p"];
        if ( !file_exists( $file ) ) {
            print "error";
            break;
        }
        print file_get_contents( $file );
        break;

    case "r":   // remove page 
        if ( !$editable ) { break; }
        unlink( $input["db"] . $input["p"] );
        break;

    case "s":   // show page
        $file = $input["db"] . $input["p"];
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
        $file = $input["db"] . $input["p"];
        if ( !file_exists( $file ) ) {
            print "Page does not exists!";
            break;
        }
        $content = $input["content"];
        file_put_contents( $file, $content );
        break;

    default:
}

?>