![ wiki](logo.jpg) wiki-embedded
=======
 No more 1990's wiki look. wiki-embedded is a simple wiki that lives inside your webpage. It is made to seamlessly integrate to your personal or company website. The same installation supports multiple databases so it is great for help/support pages or for knowledgebases.

Features
-----

* php/jquery based wiki
* non-intrusive, self-containing code
* one-line setup
* supports multiple wikies
* **Markdown** markup language compatible pages
* no SQL/NoSQL database required
* simple add/update/delete 
* no page reload when following the wiki links
* uses the same stylesheet as your webage
* user control is done outside wiki: your application controls who can read and who can edit

Setup
-----

* copy or clone this code
* enable rw access to pages subdirectory: `chmod  777 -R wiki/pages/` If you wish to have multiple databases create subdirectory for each of them
* enable rw access to uploads subdirectory: `chmod  777 -R wiki/uploads/`
* Inside your webpage ( look at the `example.php` ) include:
```html
<script src="jquery.min.js"></script>
...
<?php 
$wikisettings = array("path"=>"wiki-embedded/", "wdb"=>"pages", "editable"=>"true");
include_once $wikisettings["path"] . "wiki.php" 
?>
```
$wikisettings variable:
  * path: a relative path to your wiki directory
  * wdb: wiki database name (subdirectory under wiki-embedded direcotry). You can embed wiki into various webpages each pointing to a different database (e.g. for customers, staff, admin ...).
  * editable: true/false. Since wiki does not have any users database it is up to your application to controll read/write access by setting this value.
* wiki-embedded inherits your page stylesheet, but you can also define additional one as shown in `wikistyles.css`

License
-----
* wiki-embedded is licensed under [MIT](https://github.com/Fabianlindfors/multi.js/blob/master/LICENSE).
* markdown parsing class by Emanuil Rusev [Parsedown](https://github.com/erusev/parsedown)
