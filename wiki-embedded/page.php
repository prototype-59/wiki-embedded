<?php
/**
 * manage wiki files

 * @author Aleksandar Radovanovic <aleksandar@radovanovic.com>
 * @version 2017-05-08

 * markdown: https://en.wikipedia.org/wiki/Markdown
 * parser: https://github.com/erusev/parsedown
*/

/*
input:
    db: database folder
    a: action ->  c(reate), f(ind), l(ist), o(get original) s(how), r(emove), u(pdate) (t)ransfer file
    p: page name
    content: new page content / optional for update and new page
*/

// security check is performed via session
if (session_status() == PHP_SESSION_NONE) { session_start(); }
$editable = isset($_SESSION["wiki-editable"]) ? $_SESSION["wiki-editable"] : false;

include_once  "Parsedown.php";
$input = &$_POST;

$Parsedown = new Parsedown();
$pagefile = array_key_exists("p", $input) ?  $input["p"] . ".md" : ""; //some options does not requre pagefile

switch ($input["a"])
{
    case "c":   // create page
        if ( !$editable ) { break; }
        $file = $input["db"] . $pagefile;
        if ( !file_exists( $file ) ) {
            file_put_contents( $file, "* [Wiki home](main)\n\n---\n##" . pathinfo( $file, PATHINFO_FILENAME) );
        }
        break;

    case "f":   // find
        $search = $input["search"];
        if ( strlen( $search ) <= 2 ) { break; }
        $result = "[wiki home](main)\n##Search result:\n\n";
        
        foreach (glob($input["db"] ."*.md") as $filename) 
        {
            $lines = file($filename);
            $found = "";
            foreach($lines as $line)
            {
                if(stripos($line, $search) !== false)
                {	// make found text bold and replace some markdown tags
						$line = preg_replace("/\p{L}*?".preg_quote($search)."\p{L}*/ui", "<b>$0</b>", $line);
						$patterns = array('/#/', '/\[(.*?)\][\[\(].*?[\]\)]/', '/\|/', '/\*/');
						$line = preg_replace($patterns, "$1", $line);
						$found .= "  * " . $line . "\n";
                }
            }
            if ( strlen( $found ) > 0 ) {
                $result .= "* [" . pathinfo($filename, PATHINFO_FILENAME) . "](" .pathinfo($filename, PATHINFO_FILENAME) . "):\n\n" . $found ."\n";
            }
        }
        print $Parsedown->text( $result );
        break;

    case "l":   // create pages list
        $result = "[wiki home](main)\n##Wiki pages:\n\n";
        foreach (glob($input["db"] ."*.md") as $filename) 
        {
            $file = pathinfo($filename, PATHINFO_FILENAME);
            $result .= "* [$file]($file)\n";
        }
        $result .= "\n##Files uploaded:\n";
        foreach (glob("uploads/*") as $filename) 
        {
            $file = "[```" . basename($filename) . "```](" . basename($filename) . ")";
            $result .= "* $file\n";
        }
        print $Parsedown->text( $result );
        break;

    case "o":   // get original - no parsing
        $file = $input["db"] . $pagefile;
        if ( !file_exists( $file ) ) {
            print "error";
            break;
        }
        print file_get_contents( $file );
        break;

    case "r":   // remove page 
        if ( !$editable ) { break; }
        unlink( $input["db"] .$pagefile );
        break;

    case "s":   // show page
        $file = $input["db"] . $pagefile;
        if ( !file_exists( $file ) ) {
            print $Parsedown->text("Page does not exists! Create one.\n\n[wiki home](main)");
            break;
        }
        $page = file_get_contents( $file );
        print $Parsedown->text( $page );
        break;

    case "u":   // update page
        if ( !$editable ) { break; }
        $file = $input["db"] . $pagefile;
        if ( !file_exists( $file ) ) {
            print "Page does not exists!";
            break;
        }
        $content = $input["content"];
        file_put_contents( $file, $content );
        break;

    case "t":   // ftransfer - file ie. upload
        move_uploaded_file ($_FILES['file'] ['tmp_name'],"uploads/{$_FILES['file'] ['name']}");
        break;

    default:
}

?>