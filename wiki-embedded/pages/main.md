![ wiki](logo.jpg) wiki-embedded
=======
No more 1990's wiki look. wiki-embedded is a simple wiki that lives inside your webpage. It is made to seamlessly integrate to your personal or company website. Great for help/support pages or for a knowledgebase.

<div id="toc"></div>             

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
* enable rw access to pages subdirectory: `chmod  777 -R wiki/pages/` If you wish to have multiple databases create subdirectory for each of them.
* Inside your webpage ( look at the `example.php` ) include:
```html
<script src="jquery.min.js"></script>
...
<?php 
$wikisettings = array("path"=>"wiki/", "wdb"=>"pages", "editable"=>1);
include_once $wikisettings["path"] . "wiki.php" 
?>
```
$wikisettings variable:
  * path: a relative path to your wiki directory ending with '/'
  * wdb: wiki database name (subdirectory under wiki direcotry). You can have wiki embedded into various webpages each pointing to a different database (e.g. for customers, staff, admin ...).
  * editable: 1 or 0. Since wiki does not have any users database it is up to your application to controll read/write access by setting this value.
* wiki inherits your page stylesheet, but you can also define additional one as shown in `wikistyles.css`

Markdown cheatsheet
-----
```
# H1
## H2
### H3
#### H4
##### H5
###### H6
---------------------------------------
*italics* or _italics_
**bold** or __bold__
_combined **bold** italic_
~~Strikethrough~~
---------------------------------------
* Unordered list 
 * Unordered sub-list
- Unordered list
+ Unordered list

1. Ordered list
2. Ordered list item
 1. Ordered list sub item
2. Ordered list item
---------------------------------------
> Blockquote
<hr> --- or *** or ___
---------------------------------------
![my image](logo.jpg)
---------------------------------------
http://www.google.com or <http://www.google.com> 
[Google](http://www.google.com)
[link with title](https://www.google.com "Google")
[relative link to a webpage](../pages/LICENSE)
---------------------------------------
code starts with 4 spaces or fenced by lines with 3 back-ticks 
---------------------------------------
| Table header 1 | Table header 2 | Table header 3 |
| --- | :---: | ---: |
| left | centered  | right | 
---------------------------------------

Autogenerate table of contents: <div id="toc"></div> 
Content is generated from h2,h3 (must have at least 1 h2 on the page before the first h3)

---------------------------------------
[boomark](#bookmark)
...
<div id="bookmark">bookmark</div>

```

License
-----
* wiki-embedded is licensed under [MIT](https://github.com/Fabianlindfors/multi.js/blob/master/LICENSE).
* markdown parsing class by Emanuil Rusev [Parsedown](https://github.com/erusev/parsedown)
