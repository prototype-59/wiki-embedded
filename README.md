wiki-embedded
=======


No more 1990's wiki look. wiki-embedded is a simple wiki that lives inside your webpage. It is made to seamlessly integrate to to your personal or company website. Great for help/support pages or for a knowledgebase.

Features
-----

* php/jquery based wiki
* non-intrusive, self-containing code
* one-line setup
* **Markdown** markup language compatible pages
* no database required
* simple add/update/delete 
* no page reload when following the wiki links
* uses the same stylesheet as your webage
* user control is done outside wiki: your application controls who can read and who can edit

Setup
-----

* copy or clone this code
* enable access to pages subdirectory: `chmod  777 -R wiki/pages/`
* Inside your webpage ( look at the `example.php` ) include:
```html
<script src="jquery.min.js"></script>
...
<?php 
$wikisettings = array("path"=>"wiki/", "editable"=>"true");
include_once $wikisettings["path"] . "wiki.php" 
?>
```
$wikisettings variable:
  * path: a relative path to your wiki directory
  * editable: true/false. Wiki does not have any users database. It is up to your application to controll an access by setting this value.
* wiki inherits your page stylesheet, but you can also define additional one as shown in `wikistyles.css`

License
-----
* wiki-embedded is licensed under [MIT](https://github.com/Fabianlindfors/multi.js/blob/master/LICENSE).
* markdown parsing class by Emanuil Rusev [Parsedown](https://github.com/erusev/parsedown)


