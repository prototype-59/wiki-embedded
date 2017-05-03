##wiki-embedded


wiki-embedded is a simple wiki that lives inside `<div>` on your webpage. No more 1990's wiki look - it is made to seamlessly integrates to to your personal or company website. Great for an help/support pages or for a knowledgebase.

###Features

* php/jquery based wiki
* non-intrusive, two-lines setup
* add/update/delete **Markdown** markup language compatible pages
* no database required
* no page reload when following the wiki links
* use the same style sheet as your webage, or define your own
* user control is done by your webpage: you decide who can read and who can edit
* pages parsing by Emanuil Rusev [Parsedown](http://parsedown.org)

###Setup

* copy or clone the code to your wiki/ subdirectory
* enable access to wikipages directory by: `chmod  777 -R wiki/pages/`
* Inside your webpage include 
  *  jQuery library, 
  * `wiki/wiki.php` file and 
  * `<div id="wiki" data-editable="false"></div>`
* Control the access by `data-editable="false"` or `data-editable="true"` attribute.

note: It is up to your webpage to control user logins and to manipulate `data-editable` attribute. Wiki can also be public for reading and only logged in users can edit. 



####Example:

```html
<script src="jquery.min.js"></script>
...
<?php include_once "wiki/wiki.php" ?>
<div id="wiki" data-editable="false"></div>
```

In case you have some complicated directory structure and wish to embed wiki somewhere deep you need to edit `wiki.php` by changing:
```html
var wikiurl = "wiki/"; 
```
to point to your path, e.g.
```html
var wikiurl = "../../downTheTree/evenDeeper/wiki/";
```


