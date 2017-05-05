<?php 
/**
 * wiki-embedded 

 * @author Aleksandar Radovanovic <aleksandar@radovanovic.com>
 * @version 2017-05-03
*/
if (session_status() == PHP_SESSION_NONE) { session_start(); }
$_SESSION['wiki-editable'] = $wikisettings["editable"];
?>
<script>
$(function() {
	// set the relative path to wiki, database name(subdirectory) and default page name
	var wikiurl = "<?php print $wikisettings["path"] . "page.php" ?>"; 
	var wdb	= "<?php print $wikisettings["wdb"] . "/" ?>";
	var pagename = "main.md";	// <- set start page

	$( "#wiki" ).load(wikiurl, {a:"s",db:wdb,p:pagename});
	
	// make pages editable id data-editable is set to true
	if ($("#wiki").data("editable")===true) {
		$("#wiki-edit-menu").show();
	}

	// attach click on links inside wikipage
	$("#wiki").on("click", "a", function(event) {
		event.preventDefault();	
		$( "#wiki" ).empty();
		pagename = $(this).attr("href");
		if (pagename.indexOf("http://") >= 0 || pagename.indexOf("https://") >= 0) {
			window.location.href = pagename;
		} else {
			pagename += ".md";
			$( "#wiki" ).load(wikiurl, {a:"s",db:wdb,p:pagename});
		}
	});

	// create a new page
	$("#create-page").on("click", function(e){
		e.preventDefault();
		$( "form" ).hide(); $("#wiki-searchform").show();
		$("#wiki-createform").show();
		return false;
	});
	$( "#wiki-createform" ).submit(function( event ) {
		event.preventDefault();
		var regex = new RegExp("^[a-zA-Z0-9\-\_\]+$");
		if (!regex.test($("#newpage-name").val())) {
			alert("Please use alphanumeric names including '-' & '_' only!");
			return false;
		}
		pagename = $("#newpage-name").val()+".md";
		$.post(wikiurl,{a:"c",db:wdb, p:pagename}, function(){
			$( "#wiki" ).empty();
			$( "#wiki" ).load(wikiurl, {a:"s",db:wdb,p:pagename});
			$( "form" ).hide(); $("#wiki-searchform").show();
			$('#wiki-createform')[0].reset();
		});
		return false;
	});

	// search
	$( "#wiki-searchform" ).submit(function( event ) {
		event.preventDefault();
		var searchfor = $("#wiki-searchfor").val();
		if ( searchfor.length <= 2) {
			alert("type at least 3 letter word!");
			return false;
		}
		$.post(wikiurl,{a:"f",db:wdb, search:searchfor}, function(page){
			$( "#wiki" ).empty();
			$( "#wiki" ).html(page);
		});
		$( "form" ).hide(); $("#wiki-searchform").show();
		return false;
	});

	// edit existing
	$("#edit-page").on("click", function(e){
		e.preventDefault();
		$( "form" ).hide(); $("#wiki-searchform").show();
		$.post({url:wikiurl, cache:false, data:{a:"o",db:wdb,p:pagename}}, function(page){
			if (page != "error") {
				$("#wikipage-text").val(page);
				$("#wiki-editform").show();
			}
		});
		return false;
	});
	$( "#wiki-editform" ).submit(function( e ) {
		e.preventDefault();
		var content = $("#wikipage-text").val();
		$.post(wikiurl,{a:"u",db:wdb,p:pagename,content:content}, function(){
			$( "#wiki" ).empty();
			$( "#wiki" ).load(wikiurl,{a:"s",db:wdb,p:pagename});
			$('#wiki-editform')[0].reset();
			$( "form" ).hide(); $("#wiki-searchform").show();
		});		
		return false;
	});
	// remove this page
	$("#remove-page").on("click", function(e){
		e.preventDefault();
		$( "form" ).hide(); $("#wiki-searchform").show();
		$.post(wikiurl,{a:"r",db:wdb,p:pagename}, function(){
			$( "#wiki" ).empty();
			pagename = "main.md";
			$( "#wiki" ).load(wikiurl, {a:"s",db:wdb,p:pagename});
		});
		return false;
	});

	// list all pages
	$("#list-pages").on("click", function(e){
		e.preventDefault();
		$.post(wikiurl, {a:"l",db:wdb}, function(list){
			$( "#wiki" ).empty();
			pagename = "nopage.txt"; // non-existing page in case user clicks on "remove this page"
			$( "#wiki" ).html(list);
		});
		$("form").hide;$("#wiki-searchform").show();
		return false;
	});

	// File upload
	$("#file-upload").on("click", function(e){
		e.preventDefault();
		$('#fileinfo').show();
		return false;
	});
	$( "#fileinfo" ).submit(function( e ) {
		e.preventDefault();
		var regex = new RegExp("^[a-zA-Z0-9\-\.\_\]+$");
		if (!regex.test($("#file").val())) {
			alert("Please use alphanumeric names including '-' & '_' only!");
			return false;
		}
		var fd = new FormData($("#fileinfo")[0]);
        fd.append("a", "t"); // action: (t)ransfer file
        $.ajax({
            url: wikiurl,  
            type: 'POST',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function(){
  			//console.log("Success: Files sent!");
		}).fail(function(){
  			alert("An error occurred, the files couldn't be sent!");
		});
		$( "form" ).hide(); $("#wiki-searchform").show();
		return false;
	});
});
</script>
<div id="wiki-edit-menu" style="display:none;">
	<a href="#" id="edit-page">Edit page</a>&nbsp;
	<a href="#"  id="create-page">Create a new page</a>&nbsp;
	<a href="#"  id="remove-page">Remove this page</a>
	<a href="#"  id="file-upload">File upload</a>
	<a href="#"  id="list-pages">List pages & files</a>&nbsp;
	
</div>

<div id="wiki-forms">
<form name="wiki-createform" id="wiki-createform" method="get" style="display:none;">
	<input type="text" name="newpage-name" id="newpage-name" value="" placeholder="new page name">
	<input type="submit"  value="Submit" />
	<input type="reset" value="Cancel" onclick="$('#wiki-createform').hide();"/>
</form>

<form name="wiki-editform" id="wiki-editform" method="get" style="display:none;">
	<textarea id="wikipage-text" rows="25" style="width:100%"></textarea>
	<input type="submit" value="Submit" />
	<input type="reset" value="Cancel" onclick="$('#wiki-editform').hide();"/>
</form>
</div>

<form name="fileinfo" id="fileinfo" method="post" enctype="multipart/form-data" style="display:none;">
    <input type="file" name="file" id="file" required>
    <input type="submit" value="File upload">
</form>

<form name="wiki-searchform" id="wiki-searchform">
	<input type="text" id="wiki-searchfor">
	<input type="submit" value="Search" />
</form>

<div id="wiki" data-editable="<?php print $wikisettings["editable"]; ?>"></div>
