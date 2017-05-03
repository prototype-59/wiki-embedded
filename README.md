wiki-embedded
=======


wiki-embedded is a simple wiki that lives inside `<div>` on your webpage. No more 1990's wiki look - it is made to seamlessly integrate to to your personal or company website. Great for an help/support pages or for a knowledgebase.

Features
-----

* php/jquery based wiki
* non-intrusive, two-lines setup
* **Markdown** markup language compatible pages
* no database required
* simple add/update/delete 
* no page reload when following the wiki links
* use the same style sheet as your webage
* user control is done by your webpage: you decide who can read and who can edit
* pages parsing by Emanuil Rusev [Parsedown](https://github.com/erusev/parsedown)

Setup
-----

* copy or clone the code from the wiki/ subdirectory
* enable access to wikipages directory: `chmod  777 -R wiki/pages/`
* Inside your webpage ( look at the `index.php` example ) include the following: 
  *  jQuery library
  * `wiki/wiki.php` file
  * `<div id="wiki" data-editable="false"></div>`
* Control users access by `data-editable="false"` / `data-editable="true"` attribute.

note: It is up to your webpage to control user logins and to manipulate `data-editable` attribute.

Example:
-----

```html
<script src="jquery.min.js"></script>
...
<?php include_once "wiki/wiki.php" ?>
<div id="wiki" data-editable="false"></div>
```

In case you have some complicated directory structure and wish to embed wiki somewhere deep you'll need to edit `wiki.php` by setting:
```html
var wikiurl = "wiki/";  to point to your path, e.g. var wikiurl = "../../downTheTree/evenDeeper/wiki/";
```

License
-----
wiki-embedded is licensed under [MIT](https://github.com/Fabianlindfors/multi.js/blob/master/LICENSE).


